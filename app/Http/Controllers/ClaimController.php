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
    /**
     * Página inicial con la cuadrícula de beneficios.
     */
    public function landing()
    {
        return view('landing', ['benefits' => Claim::BENEFITS]);
    }

    /**
     * Formulario para solicitar un bono según beneficio.
     */
    public function form(string $benefit)
    {
        abort_unless(array_key_exists($benefit, Claim::BENEFITS), 404);

        return view('claim.form', [
            'benefitKey'   => $benefit,
            'benefitLabel' => Claim::BENEFITS[$benefit],
        ]);
    }

    /**
     * Procesa el formulario, genera QR + bono y guarda registro.
     */
    public function store(StoreClaimRequest $request)
    {
        $benefit = $request->input('benefit');
        $code    = strtoupper(Str::random(8));

        // 1) QR (PNG) con BaconQrCode v3 usando GD (sin Imagick)
        $voucherUrl = route('voucher.show', $code);
        $renderer   = new GDLibRenderer(440); // tamaño del QR
        $writer     = new Writer($renderer);
        $qrPng      = $writer->writeString($voucherUrl); // binario PNG

        $qrPath = "vouchers/qr_{$code}.png";
        Storage::disk('public')->put($qrPath, $qrPng);

        // 2) Componer el bono PNG
        $voucherPath = "vouchers/bono_{$code}.png";
        $this->makeVoucherPng(
            storage_path("app/public/{$qrPath}"),
            public_path('logo.png'), // opcional
            Claim::BENEFITS[$benefit],
            $code,
            $request->date('tentative_date')->format('Y-m-d'),
            storage_path("app/public/{$voucherPath}")
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
            if (empty($ref['name']) && empty($ref['phone']) && empty($ref['email'])) {
                continue;
            }
            $claim->referrals()->create([
                'name'     => $ref['name']  ?? null,
                'phone'    => $ref['phone'] ?? null,
                'email'    => $ref['email'] ?? null,
                'position' => $i + 1,
            ]);
        }

        return redirect()->route('voucher.show', $claim->code);
    }

    /**
     * Vista del bono generado.
     */
    public function showVoucher(string $code)
    {
        $claim  = Claim::where('code', $code)->firstOrFail();
        $imgUrl = asset('storage/'.$claim->voucher_path);

        return view('claim.voucher', compact('claim','imgUrl'));
    }

    /**
     * Descarga directa del bono en PNG.
     */
    public function downloadVoucher(string $code)
    {
        $claim = Claim::where('code', $code)->firstOrFail();

        return response()->download(
            storage_path('app/public/'.$claim->voucher_path)
        );
    }

    /**
     * Genera el PNG del bono digital con Intervention Image v3 (GD).
     * Evita métodos removidos como ->rectangle(); usa lienzos de color y place().
     */
    protected function makeVoucherPng(
        string $qrAbsPath,
        ?string $logoAbsPath,
        string $benefitLabel,
        string $code,
        string $date,
        string $saveTo
    ) {
        $manager = new ImageManager(new Driver());

        $w = 1080;
        $h = 1350;
        $img = $manager->create($w, $h)->fill('#ffffff');

        // Layout
        $pad = 60;
        $gap = 30;
        $topGridY = 180;
        $gridBottomReserve = 520; // espacio para el bloque QR y textos inferiores
        $bw = intval(($w - $pad*2 - $gap) / 2);
        $gridHeight = $h - $topGridY - $gridBottomReserve;
        $bh = intval(($gridHeight - $gap) / 2);

        // Helper tarjeta color
        $makeCard = function (string $hex, int $cw, int $ch) use ($manager) {
            return $manager->create($cw, $ch)->fill($hex);
        };

        // 2x2
        $cardA = $makeCard('#1E90FF', $bw, $bh);
        $cardB = $makeCard('#22C55E', $bw, $bh);
        $cardC = $makeCard('#F9A8D4', $bw, $bh);
        $cardD = $makeCard('#A16207', $bw, $bh);

        $img->place($cardA, 'top-left', $pad, $topGridY);
        $img->place($cardB, 'top-left', $pad + $bw + $gap, $topGridY);
        $img->place($cardC, 'top-left', $pad, $topGridY + $bh + $gap);
        $img->place($cardD, 'top-left', $pad + $bw + $gap, $topGridY + $bh + $gap);

        // Fuentes (si no existen, se usa la default del sistema)
        $fontBold = resource_path('fonts/DejaVuSans-Bold.ttf');
        $fontReg  = resource_path('fonts/DejaVuSans.ttf');

        // Helper texto centrado
        $center = function($x, $y, $text, $size = 42, $color = '#ffffff') use ($img, $fontBold) {
            $img->text($text, $x, $y, function($f) use ($size, $color, $fontBold) {
                if (file_exists($fontBold)) $f->file($fontBold);
                $f->size($size);
                $f->color($color);
                $f->align('center');
                $f->valign('middle');
                $f->lineHeight(1.1);
            });
        };

        // Centros de cada tarjeta
        $ax = $pad + $bw/2;                      $ay = $topGridY + $bh/2;
        $bx = $pad + $bw + $gap + $bw/2;         $by = $topGridY + $bh/2 - 10;
        $cx = $pad + $bw/2;                      $cy = $topGridY + $bh + $gap + $bh/2;
        $dx = $pad + $bw + $gap + $bw/2;         $dy = $topGridY + $bh + $gap + $bh/2;

        $center($ax, $ay, "70% Mega Combo");
        $center($bx, $by, "Revisión\nGRATIS\nde batería");
        $center($cx, $cy, "10% cambio\nde aceite");
        $center($dx, $dy, "10% Trabajos\nautorizados");

        // Cabecera
        if ($logoAbsPath && file_exists($logoAbsPath)) {
            $logo = $manager->read($logoAbsPath);
            $logo->resize(220, 220, function($c){ $c->aspectRatio(); $c->upsize(); });
            $img->place($logo, 'top-left', $pad, $pad-10);
        }
        $img->text('BONO DIGITAL', $w - $pad, 120, function($f) use ($fontBold) {
            if (file_exists($fontBold)) $f->file($fontBold);
            $f->size(64);
            $f->color('#111111');
            $f->align('right');
            $f->valign('top');
        });

        // QR
        $qr = $manager->read($qrAbsPath);
        $qr->resize(380, 380, function($c){ $c->aspectRatio(); $c->upsize(); });

        // Caja del QR
        $qrBoxW = 440; $qrBoxH = 440;
        $qrBox = $manager->create($qrBoxW, $qrBoxH)->fill('#F1F5F9');
        $qrBoxX = intval(($w - $qrBoxW)/2);
        $qrBoxY = $h - 520 + 40;

        $img->place($qrBox, 'top-left', $qrBoxX, $qrBoxY);
        $img->place($qr, 'top-left', $qrBoxX + intval(($qrBoxW - 380)/2), $qrBoxY + intval(($qrBoxH - 380)/2));

        // Datos del bono
        $img->text("Beneficio: {$benefitLabel}", $pad, $h - 560, function($f) use ($fontReg) {
            if (file_exists($fontReg)) $f->file($fontReg);
            $f->size(40);
            $f->color('#111111');
            $f->align('left');
            $f->valign('top');
        });
        $img->text("Código: {$code}", $pad, $h - 500, function($f) use ($fontBold) {
            if (file_exists($fontBold)) $f->file($fontBold);
            $f->size(48);
            $f->color('#111111');
            $f->align('left');
            $f->valign('top');
        });
        $img->text("Fecha tentativa: {$date}", $pad, $h - 440, function($f) use ($fontReg) {
            if (file_exists($fontReg)) $f->file($fontReg);
            $f->size(40);
            $f->color('#111111');
            $f->align('left');
            $f->valign('top');
        });

        // Footer
        $img->text(
            "Muestra este código en el punto de servicio. Sujeto a términos.",
            intval($w/2), $h - 30,
            function($f) use ($fontReg) {
                if (file_exists($fontReg)) $f->file($fontReg);
                $f->size(28);
                $f->color('#64748B');
                $f->align('center');
                $f->valign('bottom');
            }
        );

        // Guardar
        $img->save($saveTo, quality: 95);
    }
}
