<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;

class ClaimController extends Controller
{
    /** Página inicial con la cuadrícula de beneficios. */
    public function landing()
    {
        return view('landing', ['benefits' => Claim::BENEFITS]);
    }

    /** Formulario para solicitar un bono según beneficio (acepta beneficio opcional). */
    public function form(?string $benefit = null)
    {
        if (!$benefit || !array_key_exists($benefit, Claim::BENEFITS)) {
            return redirect()->route('landing');
        }

        return view('claim.form', [
            'benefitKey'   => $benefit,
            'benefitLabel' => Claim::BENEFITS[$benefit],
        ]);
    }

    /**
     * Procesa el formulario, genera QR + bono y guarda registro.
     * Soporta flujo normal (redirect) y flujo AJAX (JSON) para modal.
     */
    public function store(StoreClaimRequest $request)
    {
        try {
            // 0) Campos validados (incluye los nuevos)
            $v = $request->validated();

            // Benefit (oculto en el form). Fallback si no llega.
            $benefit = (string)($v['benefit'] ?? '');
            if (!$benefit || !array_key_exists($benefit, Claim::BENEFITS)) {
                $benefit = array_key_first(Claim::BENEFITS);
            }

            // Código único del voucher
            $code = strtoupper(Str::random(8));

            // 1) Generar QR (PNG)
            $voucherUrl = route('voucher.show', $code);
            $renderer   = new GDLibRenderer(440);
            $writer     = new Writer($renderer);
            $qrPng      = $writer->writeString($voucherUrl);

            $qrPath = "vouchers/qr_{$code}.png";
            Storage::disk('public')->put($qrPath, $qrPng);

            // 2) Componer el bono PNG (ticket) con fecha + hora
            $fecha = $request->date('fecha_tentativa'); // Carbon|null
            $hora  = $request->string('hora_tentativa')->toString();
            $fechaTexto = $fecha ? $fecha->format('Y-m-d') : '';
            $fechaHora  = trim($fechaTexto . ($hora ? " {$hora}" : ''));

            $voucherPath = "vouchers/bono_{$code}.png";
            $this->makeVoucherPng(
                storage_path("app/public/{$qrPath}"),
                public_path('logo.png'), // opcional si existe
                Claim::BENEFITS[$benefit] ?? ucfirst($benefit),
                $code,
                $fechaHora, // mostramos fecha y hora en la tarjeta
                storage_path("app/public/{$voucherPath}"),
                (string) $request->string('nombre')
            );

            // 3) Guardar claim con NUEVOS campos + LEGACY para compatibilidad
            $claim = Claim::create([
                'benefit'         => $benefit,
                'code'            => $code,
                'qr_path'         => $qrPath,
                'voucher_path'    => $voucherPath,
                'meta'            => ['ip' => $request->ip(), 'ua' => $request->userAgent()],

                // === NUEVOS campos ===
                'nombre'          => (string) $request->string('nombre'),
                'cedula'          => (string) $request->string('cedula'),
                'telefono'        => (string) $request->string('telefono'),
                'direccion'       => (string) $request->string('direccion'),
                'email'           => (string) $request->string('email'),
                'placa'           => (string) $request->string('placa'),
                'marca_modelo'    => (string) $request->string('marca_modelo'),
                'fecha_tentativa' => $fecha,
                'hora_tentativa'  => (string) $request->string('hora_tentativa'),

                // === LEGACY (para satisfacer NOT NULL y compat con vistas antiguas) ===
                'tentative_date'  => $fecha,                                        // era NOT NULL en tu schema
                'name'            => (string) $request->string('nombre'),           // alias
                'phone'           => (string) $request->string('telefono'),         // alias
                // 'status' tiene default 'issued', no es necesario setearlo
            ]);

            // 4) Referidos (opcionales)
            $referrals = collect($request->input('referrals', []))->take(3)->values();
            foreach ($referrals as $i => $ref) {
                if (empty($ref['name']) && empty($ref['phone']) && empty($ref['email'])) continue;
                $claim->referrals()->create([
                    'name'     => $ref['name']  ?? null,
                    'phone'    => $ref['phone'] ?? null,
                    'email'    => $ref['email'] ?? null,
                    'position' => $i + 1,
                ]);
            }

            // 5) Respuesta según tipo: JSON (modal) o redirect (flujo normal)
            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => route('voucher.show', $claim->code),
                ], 201);
            }

            return redirect()->route('voucher.show', $claim->code);

        } catch (QueryException $e) {
            // Diferenciar UNIQUE vs NOT NULL (ambos usan SQLSTATE 23000 en SQLite)
            $msg = strtolower((string)($e->errorInfo[2] ?? $e->getMessage()));
            $isUnique  = str_contains($msg, 'unique');     // 'unique constraint failed'
            $isNotNull = str_contains($msg, 'not null');   // 'not null constraint failed'

            if ($isUnique) {
                $map = [
                    'email'    => 'Este correo ya tiene un bono registrado.',
                    'cedula'   => 'Esta cédula ya tiene un bono registrado.',
                    'telefono' => 'Este teléfono ya tiene un bono registrado.',
                    'nombre'   => 'Este nombre ya tiene un bono registrado.',
                ];
                $field = null;
                foreach (array_keys($map) as $k) {
                    // el mensaje típico incluye 'claims.k' (p. ej., claims.email)
                    if (str_contains($msg, ".{$k}")) { $field = $k; break; }
                }
                $payload = $field ? [ $field => [ $map[$field] ] ] : [ 'form' => ['Datos duplicados.'] ];

                return $request->expectsJson()
                    ? new JsonResponse(['message' => 'Validación falló.', 'errors' => $payload], 422)
                    : back()->withInput()->withErrors($payload);
            }

            if ($isNotNull) {
                // intenta extraer la columna: '... failed: claims.tentative_date'
                $field = null;
                if (preg_match('/failed:\s*claims\.(\w+)/', $msg, $m)) $field = $m[1];

                $payload = $field
                    ? [ $field => ['Este campo es obligatorio.'] ]
                    : [ 'form' => ['Faltan datos requeridos.'] ];

                return $request->expectsJson()
                    ? new JsonResponse(['message' => 'Validación falló.', 'errors' => $payload], 422)
                    : back()->withInput()->withErrors($payload);
            }

            // Otros errores de BD
            throw $e;

        } catch (\Throwable $e) {
            // Fallback general para el flujo AJAX
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Ocurrió un error inesperado. Intenta de nuevo.',
                ], 500);
            }
            throw $e;
        }
    }

    /** Vista del bono generado. */
    public function showVoucher(string $code)
    {
        $claim  = Claim::where('code', $code)->firstOrFail();
        $imgUrl = asset('storage/'.$claim->voucher_path);

        return view('claim.voucher', compact('claim','imgUrl'));
    }

    /** Descarga directa del bono en PNG. */
    public function downloadVoucher(string $code)
    {
        $claim = Claim::where('code', $code)->firstOrFail();

        return response()->download(
            storage_path('app/public/'.$claim->voucher_path)
        );
    }

    /**
     * Ticket PNG (horizontal): esquinas redondeadas, perforación central, QR centrado.
     * Render HiDPI 2× y reescala a 1400×800.
     */
    protected function makeVoucherPng(
        string $qrAbsPath,
        ?string $logoAbsPath,
        string $benefitLabel,
        string $code,
        string $date,            // puede traer "YYYY-MM-DD HH:mm"
        string $saveTo,
        ?string $customerName = null
    ) {
        $manager = new ImageManager(new Driver());

        // Final + HiDPI
        $FW = 1400; $FH = 800; $S = 2;
        $W = $FW * $S; $H = $FH * $S;

        // Paleta
        $outer='#f3f6fb'; $ticket='#ffffff'; $ink='#0f172a'; $muted='#475569';
        $brand='#10b981'; $line='#e5e7eb'; $soft='#f8fafc';

        // Lienzo
        $img = $manager->create($W, $H)->fill($outer);

        // Tarjeta
        $m=28*$S; $r=32*$S; $tw=$W-$m*2; $th=$H-$m*2; $tx=$m; $ty=$m;
        $card = $manager->create($tw,$th)->fill($ticket);
        $img->place($card,'top-left',$tx,$ty);
        $corner=$manager->create($r*2,$r*2)->fill($outer);
        $img->place($corner,'top-left',$tx-$r,$ty-$r);
        $img->place($corner,'top-left',$tx+$tw-$r,$ty-$r);
        $img->place($corner,'top-left',$tx-$r,$ty+$th-$r);
        $img->place($corner,'top-left',$tx+$tw-$r,$ty+$th-$r);

        // Perforación
        $midX = intval($tx + $tw * 0.60);
        for ($yy=$ty+$r; $yy<$ty+$th-$r; $yy+=26*$S) {
            $img->place($manager->create(4*$S,16*$S)->fill($line),'top-left',$midX,$yy);
        }
        $notchD=58*$S; $notch=$manager->create($notchD,$notchD)->fill($outer);
        $img->place($notch,'top-left',$midX-intval($notchD/2),$ty-intval($notchD/2)+12*$S);
        $img->place($notch,'top-left',$midX-intval($notchD/2),$ty+$th-intval($notchD/2)-12*$S);

        // Header
        if ($logoAbsPath && is_file($logoAbsPath)) {
            $logo=$manager->read($logoAbsPath);
            $logo->resize(240*$S,150*$S,function($c){$c->aspectRatio();$c->upsize();});
            $img->place($logo,'top-left',$tx+28*$S,$ty+24*$S);
        }
        $this->drawText($img,'BONO DIGITAL',$tx+$tw-32*$S,$ty+34*$S,[
            'size'=>22*$S,'color'=>$muted,'align'=>'right'
        ]);

        // Columna izquierda
        $lx = $tx + 56*$S;
        $leftTop = $ty + 150*$S;
        $leftBottomGuard = $ty + $th - 160*$S;
        $ly = $leftTop;
        $maxW = ($midX - $lx - 60*$S);

        // Título ajustado
        $fit = $this->drawHeadingWrapped($img, $benefitLabel, $lx, $ly, $maxW, 76*$S, 56*$S, 'bold', 1.14);
        $ly += $fit['height'] + 40*$S;

        // Fila 2 columnas: Cliente / Fecha y hora
        $colGap = 36*$S; $colW = intval(($maxW - $colGap)/2);
        $this->drawLabelValue($img, 'Cliente', $customerName ?: '—', $lx, $ly, $colW, 24*$S, 44*$S);
        $this->drawLabelValue($img, 'Fecha tentativa', $date ?: '—', $lx + $colW + $colGap, $ly, $colW, 24*$S, 40*$S);
        $ly += 44*$S + 48*$S;

        // Código (badge)
        $labelSize = 24*$S;
        $this->drawText($img, 'Código', $lx, $ly, ['size'=>$labelSize, 'color'=>'#6b7280']);
        $badgeTop = $ly + intval($labelSize * 1.6);

        $badgeW = min(620*$S, $maxW);
        $badgeH = 110*$S;

        if ($badgeTop + $badgeH + 24*$S > $leftBottomGuard) {
            $shift = ($badgeTop + $badgeH + 24*$S) - $leftBottomGuard;
            $badgeTop -= $shift;
        }

        $badge = $manager->create($badgeW, $badgeH)->fill('#ecfdf5');
        $img->place($badge, 'top-left', $lx, $badgeTop);
        $this->drawText($img, $code, $lx + 24*$S, $badgeTop + 20*$S, [
            'size'=>48*$S, 'color'=>$brand, 'weight'=>'extrabold'
        ]);

        // Columna derecha (QR)
        $rcPad = 56*$S;
        $rcX0  = $midX + $rcPad;
        $rcX1  = $tx + $tw - $rcPad;
        $rcY0  = $ty + 120*$S;
        $rcY1  = $ty + $th - 180*$S;
        $rcW   = $rcX1 - $rcX0;
        $rcH   = $rcY1 - $rcY0;

        $panelSize = min(520*$S, min($rcW, $rcH));
        $panel     = $manager->create($panelSize, $panelSize)->fill($soft);

        $qr       = $manager->read($qrAbsPath);
        $qrSize   = (int)($panelSize * 0.82);
        $qr->resize($qrSize, $qrSize, function ($c) { $c->aspectRatio(); $c->upsize(); });
        $panel->place($qr, 'center');

        $panelX = $rcX0 + (int)(($rcW - $panelSize) / 2);
        $panelY = $rcY0 + (int)(($rcH - $panelSize) / 2);
        $img->place($panel, 'top-left', $panelX, $panelY);

        $this->drawText(
            $img,
            'Escanea para validar',
            $panelX + $panelSize/2,
            $panelY + $panelSize + 36*$S,
            ['size'=>22*$S, 'color'=>'#475569', 'align'=>'center']
        );

        // Acento inferior
        $img->place($manager->create($tw,12*$S)->fill('#10b981'),'top-left',$tx,$ty+$th-12*$S);

        // Escala final
        $img->resize($FW,$FH);
        $img->save($saveTo, quality:95);
    }

    /* =========================
       Helpers de tipografía / layout
       ========================= */

    protected function drawLabelValue($image, string $label, string $value, int $x, int $y, int $w, int $labelSize, int $valueSize): void
    {
        $this->drawText($image, $label, $x, $y, ['size'=>$labelSize, 'color'=>'#6b7280']);
        $this->drawText($image, $value !== '' ? $value : '—', $x, $y + intval($labelSize*1.6), [
            'size'=>$valueSize, 'weight'=>'semibold'
        ]);
    }

    protected function drawHeadingWrapped($image, string $text, int $x, int $y, int $maxWidth, int $baseSize, int $minSize, string $weight='bold', float $lh=1.15): array
    {
        $font = $this->getFontFile($weight);

        if ($font) {
            $one = $this->measureTextWidth($text, $baseSize, $font);
            if ($one <= $maxWidth) {
                $this->drawText($image, $text, $x, $y, ['size'=>$baseSize, 'weight'=>$weight]);
                return ['size'=>$baseSize, 'height'=>intval($baseSize*$lh)];
            }
        }

        $size = $baseSize; $lines = [];
        do {
            $lines = $this->wrapToWidth($text, $size, $weight, $maxWidth);
            if (count($lines) <= 2 || $size <= $minSize) break;
            $size -= 4;
        } while (true);

        $dy = 0;
        foreach ($lines as $line) {
            $this->drawText($image, $line, $x, $y + $dy, ['size'=>$size, 'weight'=>$weight]);
            $dy += intval($size * $lh);
        }
        return ['size'=>$size, 'height'=>$dy ?: intval($size*$lh)];
    }

    protected function wrapToWidth(string $text, int $size, string $weight, int $maxWidth): array
    {
        $font = $this->getFontFile($weight);
        if (!$font) return [$text];

        $words = preg_split('/\s+/', trim($text));
        $lines = []; $line = '';

        foreach ($words as $w) {
            $try = trim($line === '' ? $w : ($line.' '.$w));
            $width = $this->measureTextWidth($try, $size, $font);
            if ($width <= $maxWidth) {
                $line = $try;
            } else {
                if ($line !== '') $lines[] = $line;
                $line = $w;
            }
        }
        if ($line !== '') $lines[] = $line;
        return $lines;
    }

    protected function measureTextWidth(string $text, int $size, string $fontFile): int
    {
        $box = imagettfbbox($size, 0, $fontFile, $text);
        if (!$box) return PHP_INT_MAX;
        return (int)(max($box[2], $box[4]) - min($box[0], $box[6]));
    }

    protected function getFontFile(string $weight = 'regular'): ?string
    {
        $candidates = [
            'extrabold' => [ resource_path('fonts/Inter-ExtraBold.ttf'), public_path('fonts/Inter-ExtraBold.ttf') ],
            'bold'      => [ resource_path('fonts/Inter-Bold.ttf'),      public_path('fonts/Inter-Bold.ttf') ],
            'semibold'  => [ resource_path('fonts/Inter-SemiBold.ttf'),  public_path('fonts/Inter-SemiBold.ttf'), resource_path('fonts/Inter-Medium.ttf') ],
            'regular'   => [ resource_path('fonts/Inter-Regular.ttf'),   public_path('fonts/Inter-Regular.ttf') ],
        ];

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidates['bold'][]     = 'C:\Windows\Fonts\arialbd.ttf';
            $candidates['semibold'][] = 'C:\Windows\Fonts\arialbd.ttf';
            $candidates['regular'][]  = 'C:\Windows\Fonts\arial.ttf';
            $candidates['extrabold'][]= 'C:\Windows\Fonts\arialbd.ttf';
        } else {
            $candidates['bold'][]     = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
            $candidates['semibold'][] = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
            $candidates['regular'][]  = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
            // macOS
            $candidates['regular'][]  = '/System/Library/Fonts/Supplemental/Arial.ttf';
            $candidates['bold'][]     = '/System/Library/Fonts/Supplemental/Arial Bold.ttf';
        }

        foreach ($candidates[$weight] ?? [] as $p) {
            if ($p && is_file($p)) return $p;
        }
        return null;
    }

    protected function drawText($image, string $text, int $x, int $y, array $opt = []): void
    {
        $size   = $opt['size']   ?? 28;
        $color  = $opt['color']  ?? '#0f172a';
        $align  = $opt['align']  ?? 'left';
        $valign = $opt['valign'] ?? 'top';
        $weight = $opt['weight'] ?? 'regular';

        $fontFile = $this->getFontFile($weight);
        if (!$fontFile) logger()->warning('Voucher: no TTF font found, text may render tiny.', ['weight'=>$weight]);

        $image->text($text, $x, $y, function ($font) use ($size, $color, $align, $valign, $fontFile) {
            if ($fontFile) $font->file($fontFile);
            $font->size($size);
            $font->color($color);
            $font->align($align);
            $font->valign($valign);
            if (method_exists($font, 'lineHeight')) $font->lineHeight(1.18);
        });
    }
}
