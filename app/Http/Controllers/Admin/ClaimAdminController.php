<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClaimAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Claim::withCount('referrals')->latest();
        if ($request->filled('benefit')) $q->where('benefit',$request->benefit);
        if ($request->filled('status'))  $q->where('status',$request->status);
        $claims = $q->paginate(20)->withQueryString();
        return view('admin.claims.index', compact('claims'));
    }

    public function export(Request $request)
    {
        $q = Claim::with('referrals')->latest();
        if ($request->filled('benefit')) $q->where('benefit',$request->benefit);
        if ($request->filled('status'))  $q->where('status',$request->status);
        $rows = $q->get();

        $sheet = (new Spreadsheet())->getActiveSheet();
        $headers = ['ID','Beneficio','Fecha tentativa','Nombre','Teléfono','Email','Código','Estado','#Referidos','Creado'];
        foreach ($headers as $i => $h) $sheet->setCellValueByColumnAndRow($i+1,1,$h);

        $r=2;
        foreach ($rows as $c) {
            $sheet->setCellValueByColumnAndRow(1,$r,$c->id);
            $sheet->setCellValueByColumnAndRow(2,$r,\App\Models\Claim::BENEFITS[$c->benefit] ?? $c->benefit);
            $sheet->setCellValueByColumnAndRow(3,$r,optional($c->tentative_date)->format('Y-m-d'));
            $sheet->setCellValueByColumnAndRow(4,$r,$c->name);
            $sheet->setCellValueByColumnAndRow(5,$r,$c->phone);
            $sheet->setCellValueByColumnAndRow(6,$r,$c->email);
            $sheet->setCellValueByColumnAndRow(7,$r,$c->code);
            $sheet->setCellValueByColumnAndRow(8,$r,$c->status);
            $sheet->setCellValueByColumnAndRow(9,$r,$c->referrals->count());
            $sheet->setCellValueByColumnAndRow(10,$r,$c->created_at->format('Y-m-d H:i'));
            $r++;
        }
        foreach (range('A','J') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        $filename = 'claims.xlsx';
        $tmp = storage_path('app/exports/'.$filename);
        @mkdir(dirname($tmp),0777,true);
        (new Xlsx($sheet->getParent()))->save($tmp);

        return response()->download($tmp,$filename)->deleteFileAfterSend(true);
    }
}
