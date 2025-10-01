<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ClaimAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Claim::with(['referrals'])
            ->withCount('referrals')
            ->latest();

        if ($request->filled('benefit')) {
            $q->where('benefit', $request->benefit);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $claims = $q->paginate(20)->withQueryString();

        return view('admin.claims.index', compact('claims'));
    }

    public function show(string $code)
    {
        $claim = Claim::with('referrals')->where('code', $code)->firstOrFail();
        return view('admin.claims.show', compact('claim'));
    }

    public function export(Request $request)
    {
        // 1) Filtrado opcional
        $q = Claim::with('referrals')->latest();
        if ($request->filled('benefit')) $q->where('benefit', $request->benefit);
        if ($request->filled('status'))  $q->where('status',  $request->status);

        $rows = $q->get();

        // 2) Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados (incluye campos nuevos)
        $headers = [
            'ID',
            'Código',
            'Beneficio (key)',
            'Beneficio',
            'Estado',
            'Nombre',
            'Cédula',
            'Teléfono',
            'Correo',
            'Dirección',
            'Placa',
            'Marca/Modelo',
            'Fecha tentativa',
            'Hora tentativa',
            'Creado en',
            '#Referidos',
            'Referidos (detalle)',
        ];

        foreach ($headers as $i => $h) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col.'1', $h);
        }

        // 3) Cuerpo
        $data = [];
        foreach ($rows as $c) {
            $benefitKey   = $c->benefit;
            $benefitLabel = Claim::BENEFITS[$benefitKey] ?? $benefitKey;


            $refDetail = $c->referrals->map(function ($r) {
                $parts = array_filter([$r->name, $r->phone, $r->email]);
                return implode(' / ', $parts);
            })->implode('; ');

            $data[] = [
                $c->id,
                $c->code,
                $benefitKey,
                $benefitLabel,
                $c->status,
                $c->nombre,
                $c->cedula,
                $c->telefono,
                $c->email,
                $c->direccion,
                $c->placa,
                $c->marca_modelo,
                optional($c->fecha_tentativa)->format('Y-m-d'),
                $c->hora_tentativa,
                optional($c->created_at)->format('Y-m-d H:i'),
                $c->referrals->count(),
                $refDetail,
            ];
        }

        if (!empty($data)) {
            // Vuelca data a partir de A2
            $sheet->fromArray($data, null, 'A2', true);
        }

        // Autosize columnas
        for ($i = 1; $i <= count($headers); $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 4) Descargar
        $filename = 'claims_'.now()->format('Ymd_His').'.xlsx';
        $tmp = storage_path('app/exports/'.$filename);
        @mkdir(dirname($tmp), 0777, true);
        (new Xlsx($spreadsheet))->save($tmp);

        return response()->download($tmp, $filename)->deleteFileAfterSend(true);
    }
}
