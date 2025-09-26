<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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

    /** Formulario para solicitar un bono según beneficio. */
    public function form(string $benefit)
    {
        abort_unless(array_key_exists($benefit, Claim::BENEFITS), 404);

        return view('claim.form', [
            'benefitKey'   => $benefit,
            'benefitLabel' => Claim::BENEFITS[$benefit],
        ]);
    }

    /** Procesa el formulario, genera QR + bono y guarda registro. */
    public function store(StoreClaimRequest $request)
    {
        $benefit = $request->input('benefit');
        $code    = strtoupper(Str::random(8));

        // 1) QR (PNG) con BaconQrCode (GD)
        $voucherUrl = route('voucher.show', $code);
        $renderer   = new GDLibRenderer(440);
        $writer     = new Writer($renderer);
        $qrPng      = $writer->writeString($voucherUrl);

        $qrPath = "vouchers/qr_{$code}.png";
        Storage::disk('public')->put($qrPath, $qrPng);

        // 2) Componer el bono PNG (ticket)
        $voucherPath = "vouchers/bono_{$code}.png";
        $this->makeVoucherPng(
            storage_path("app/public/{$qrPath}"),
            public_path('logo.png'), // opcional
            Claim::BENEFITS[$benefit],
            $code,
            $request->date('tentative_date')->format('Y-m-d'),
            storage_path("app/public/{$voucherPath}"),
            (string) $request->string('name')
        );

        // 3) Guardar claim
        $claim = Claim::create([
            'benefit'        => $benefit,
            'tentative_date' => $request->date('tentative_date'),
            'name'           => $request->string('name'),
            'phone'          => $request->string('phone'),
            'email'          => $request->string('email'),
            'code'           => $code,
            'qr_path'        => $qrPath,
            'voucher_path'   => $voucherPath,
            'meta'           => ['ip' => $request->ip(), 'ua' => $request->userAgent()],
        ]);

        // 4) Referidos (opcionales, hasta 3)
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

        return redirect()->route('voucher.show', $claim->code);
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
     * Título con auto-ajuste (wrap/scale). Render HiDPI 2× y reescala a 1400×800.
     */
    protected function makeVoucherPng(
        string $qrAbsPath,
        ?string $logoAbsPath,
        string $benefitLabel,
        string $code,
        string $date,
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
        $leftBottomGuard = $ty + $th - 160*$S; // margen inferior seguro
        $ly = $leftTop;
        $maxW = ($midX - $lx - 60*$S);

        // Título con auto-ajuste
        $fit = $this->drawHeadingWrapped($img, $benefitLabel, $lx, $ly, $maxW, 76*$S, 56*$S, 'bold', 1.14);
        $ly += $fit['height'] + 40*$S;

        // Fila 2 columnas: Cliente / Fecha tentativa
        $colGap = 36*$S; $colW = intval(($maxW - $colGap)/2);
        $this->drawLabelValue($img, 'Cliente', $customerName ?: '—', $lx, $ly, $colW, 24*$S, 44*$S);
        $this->drawLabelValue($img, 'Fecha tentativa', $date ?: '—', $lx + $colW + $colGap, $ly, $colW, 24*$S, 40*$S);
        $ly += 44*$S + 48*$S;

        // Código (badge) con separación real respecto al label y margen inferior
        $labelSize = 24*$S;
        $this->drawText($img, 'Código', $lx, $ly, ['size'=>$labelSize, 'color'=>$muted]);
        $badgeTop = $ly + intval($labelSize * 1.6); // renglón siguiente

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

        // ===== Columna derecha (QR más pequeño y centrado) =====
        $rcPad = 56*$S;                          // padding interno en la derecha
        $rcX0  = $midX + $rcPad;
        $rcX1  = $tx + $tw - $rcPad;
        $rcY0  = $ty + 120*$S;                   // margen superior
        $rcY1  = $ty + $th - 180*$S;             // margen inferior
        $rcW   = $rcX1 - $rcX0;
        $rcH   = $rcY1 - $rcY0;

        $panelSize = min(520*$S, min($rcW, $rcH)); // más pequeño que antes
        $panel     = $manager->create($panelSize, $panelSize)->fill($soft);

        $qr       = $manager->read($qrAbsPath);
        $qrSize   = (int)($panelSize * 0.82);      // reduce/ajusta el QR
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
            ['size'=>22*$S, 'color'=>$muted, 'align'=>'center']
        );

        // Acento inferior
        $img->place($manager->create($tw,12*$S)->fill($brand),'top-left',$tx,$ty+$th-12*$S);

        // Escala final
        $img->resize($FW,$FH);
        $img->save($saveTo, quality:95);
    }

    /* =========================
       Helpers de tipografía / layout
       ========================= */

    /** Dibuja un par Etiqueta/Valor dentro de un ancho. */
    protected function drawLabelValue($image, string $label, string $value, int $x, int $y, int $w, int $labelSize, int $valueSize): void
    {
        $this->drawText($image, $label, $x, $y, ['size'=>$labelSize, 'color'=>'#6b7280']);
        $this->drawText($image, $value !== '' ? $value : '—', $x, $y + intval($labelSize*1.6), [
            'size'=>$valueSize, 'weight'=>'semibold'
        ]);
    }

    /**
     * Título ajustado al ancho. Devuelve ['size'=>int,'height'=>int].
     */
    protected function drawHeadingWrapped($image, string $text, int $x, int $y, int $maxWidth, int $baseSize, int $minSize, string $weight='bold', float $lh=1.15): array
    {
        $font = $this->getFontFile($weight);

        // ¿Cabe en una línea?
        if ($font) {
            $one = $this->measureTextWidth($text, $baseSize, $font);
            if ($one <= $maxWidth) {
                $this->drawText($image, $text, $x, $y, ['size'=>$baseSize, 'weight'=>$weight]);
                return ['size'=>$baseSize, 'height'=>intval($baseSize*$lh)];
            }
        }

        // Wrap con reducción gradual
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

    /** Envuelve texto por palabras para no exceder $maxWidth. */
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

    /** Mide ancho de texto con GD (imagettfbbox). */
    protected function measureTextWidth(string $text, int $size, string $fontFile): int
    {
        $box = imagettfbbox($size, 0, $fontFile, $text);
        if (!$box) return PHP_INT_MAX;
        return (int)(max($box[2], $box[4]) - min($box[0], $box[6]));
    }

    /** Devuelve ruta a una TTF según peso (Inter > Arial/DejaVu). */
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

    /**
     * Escribe texto asegurando TTF real (si no, GD usa fuente minúscula).
     * options: size, color, align, valign, weight(regular|semibold|bold|extrabold)
     */
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
