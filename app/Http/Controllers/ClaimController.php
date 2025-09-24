<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClaimController extends Controller
{
    public function landing()
    {
        return view('landing', ['benefits' => Claim::BENEFITS]);
    }

    public function form(string $benefit)
    {
        abort_unless(array_key_exists($benefit, Claim::BENEFITS), 404);
        return view('claim.form', [
            'benefitKey'   => $benefit,
            'benefitLabel' => Claim::BENEFITS[$benefit],
        ]);
    }

    public function store(StoreClaimRequest $request)
    {
        $benefit = $request->input('benefit');
        $code    = strtoupper(Str::random(8));

        // 1) Generar QR → URL pública del bono
        $voucherUrl = route('voucher.show', $code);
        $qrPng      = QrCode::format('png')->size(440)->margin(1)->generate($voucherUrl);
        $qrPath     = "vouchers/qr_{$code}.png";
        Storage::disk('public')->put($qrPath, $qrPng);

        // 2) Componer imagen del bono
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

    public function showVoucher(string $code)
    {
        $claim = Claim::where('code',$code)->firstOrFail();
        $imgUrl = asset('storage/'.$claim->voucher_path);
        return view('claim.voucher', compact('claim','imgUrl'));
    }

    public function downloadVoucher(string $code)
    {
        $claim = Claim::where('code',$code)->firstOrFail();
        return response()->download(storage_path('app/public/'.$claim->voucher_path));
    }

    // ------- PNG del bono (Intervention) -------
    protected function makeVoucherPng(string $qrAbsPath, ?string $logoAbsPath, string $benefitLabel, string $code, string $date, string $saveTo)
    {
        $manager = new ImageManager(new Driver());
        $w=1080; $h=1350;

        $img = $manager->create($w, $h)->fill('#ffffff');

        // Cuadrícula 2x2 (colores del mock)
        $pad=60; $gap=30; $bw=intval(($w-$pad*2-$gap)/2); $bh=intval(($h-$pad*2-220-$gap)/2);
        $img->rectangle($pad,             180, $pad+$bw,        180+$bh, function($d){ $d->background('#1E90FF'); $d->rounded(40);});
        $img->rectangle($pad+$bw+$gap,    180, $pad+$bw*2+$gap, 180+$bh, function($d){ $d->background('#22C55E'); $d->rounded(40);});
        $img->rectangle($pad,          180+$bh+$gap, $pad+$bw,         180+$bh*2+$gap, function($d){ $d->background('#F9A8D4'); $d->rounded(40);});
        $img->rectangle($pad+$bw+$gap, 180+$bh+$gap, $pad+$bw*2+$gap,  180+$bh*2+$gap, function($d){ $d->background('#A16207'); $d->rounded(40);});

        // Títulos de cuadrantes
        $fontBold = resource_path('fonts/DejaVuSans-Bold.ttf');
        $fontReg  = resource_path('fonts/DejaVuSans.ttf');

        $img->text('70% Mega Combo', $pad+$bw/2, 180+$bh/2, function($f) use($fontBold){ $f->file($fontBold); $f->size(42); $f->color('#fff'); $f->align('center'); $f->valign('middle');});
        $img->text("Revisión\nGRATIS\nde batería", $pad+$bw+$gap+$bw/2, 180+$bh/2-20, function($f) use($fontBold){ $f->file($fontBold); $f->size(42); $f->color('#fff'); $f->align('center'); $f->valign('middle');});
        $img->text("10% cambio\nde aceite", $pad+$bw/2, 180+$bh+$gap+$bh/2, function($f) use($fontBold){ $f->file($fontBold); $f->size(42); $f->color('#fff'); $f->align('center'); $f->valign('middle');});
        $img->text("10% Trabajos\nautorizados", $pad+$bw+$gap+$bw/2, 180+$bh+$gap+$bh/2, function($f) use($fontBold){ $f->file($fontBold); $f->size(42); $f->color('#fff'); $f->align('center'); $f->valign('middle');});

        // Cabecera / Logo
        if ($logoAbsPath && file_exists($logoAbsPath)) {
            $logo = $manager->read($logoAbsPath)->scale(220,220, keepAspectRatio:true);
            $img->place($logo, 'top-left', $pad, $pad-10);
        }
        $img->text('BONO DIGITAL', $w-$pad, 120, function($f) use($fontBold){ $f->file($fontBold); $f->size(64); $f->color('#111'); $f->align('right');});

        // QR en recuadro
        $qr = $manager->read($qrAbsPath)->scale(380,380);
        $img->rectangle(($w/2)-220, $h-520, ($w/2)+220, $h-80, function($d){ $d->background('#F1F5F9'); $d->border(2, '#CBD5E1'); $d->rounded(28); });
        $img->place($qr, 'bottom', 0, 120);

        // Datos
        $img->text("Beneficio: {$benefitLabel}", $pad, $h-560, function($f) use($fontReg){ $f->file($fontReg); $f->size(40); $f->color('#111'); $f->align('left');});
        $img->text("Código: {$code}", $pad, $h-500, function($f) use($fontBold){ $f->file($fontBold); $f->size(48); $f->color('#111'); $f->align('left');});
        $img->text("Fecha tentativa: {$date}", $pad, $h-440, function($f) use($fontReg){ $f->file($fontReg); $f->size(40); $f->color('#111'); $f->align('left');});

        $img->text("Muestra este código en el punto de servicio. Sujeto a términos.", $w/2, $h-30, function($f) use($fontReg){ $f->file($fontReg); $f->size(28); $f->color('#64748B'); $f->align('center');});

        $img->save($saveTo, quality:95);
    }
}
