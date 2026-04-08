<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SusQuestion;
use App\Models\SusResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SusAdminController extends Controller
{
    public function index()
    {
        $responses = SusResponse::with('user')->get();
        $questions = SusQuestion::orderBy('order')->get();
        
        $totalResponses = $responses->count();
        $meanScore = $totalResponses > 0 ? $responses->avg('total_score') : 0;
        
        $analysis = $this->interpretScore($meanScore);

        return view('admin.sus.index', compact('responses', 'questions', 'totalResponses', 'meanScore', 'analysis'));
    }

    public function manage()
    {
        $questions = SusQuestion::orderBy('order')->get();
        return view('admin.sus.manage', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required',
            'order' => 'required|integer',
        ]);

        SusQuestion::create($request->all());

        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required',
            'order' => 'required|integer',
        ]);

        $question = SusQuestion::findOrFail($id);
        $question->update($request->all());

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroyQuestion($id)
    {
        SusQuestion::findOrFail($id)->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    public function exportExcel()
    {
        $responses = SusResponse::with('user')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Skor SUS');

        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleData = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleCenter = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $redText = [
            'font' => ['color' => ['argb' => Color::COLOR_RED]]
        ];

        // Row 1
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Responden');
        $sheet->setCellValue('C1', 'Usia');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Skor Asli (Data Contoh)');
        $sheet->setCellValue('O1', 'Skor Hasil Hitung (Data Contoh)');
        $sheet->setCellValue('Y1', 'Jumlah');
        $sheet->setCellValue('Z1', 'Nilai');

        // Merging Headers
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:N1');
        $sheet->mergeCells('O1:X1');
        $sheet->mergeCells('Y1:Y2');
        $sheet->mergeCells('Z1:Z2');

        // Row 2
        $colAsli = ['E','F','G','H','I','J','K','L','M','N'];
        $colHitung = ['O','P','Q','R','S','T','U','V','W','X'];
        for ($i=0; $i<10; $i++) {
            $sheet->setCellValue($colAsli[$i].'2', 'Q'.($i+1));
            $sheet->setCellValue($colHitung[$i].'2', 'Q'.($i+1));
        }

        $sheet->getStyle('A1:Z2')->applyFromArray($styleHeader);
        $sheet->getStyle('E2:N2')->applyFromArray($redText);
        
        $sheet->getStyle('Z1')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('Z1', "Nilai\n(Jumlah x 2.5)");

        $row = 3;
        foreach ($responses as $index => $res) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $res->nama ?? ($res->user ? $res->user->name : 'Responden ' . ($index + 1)));
            $sheet->setCellValue('C'.$row, $res->usia ?? '-');
            $sheet->setCellValue('D'.$row, $res->jenis_kelamin ?? '-');
            
            $sheet->setCellValue('E'.$row, $res->q1);
            $sheet->setCellValue('F'.$row, $res->q2);
            $sheet->setCellValue('G'.$row, $res->q3);
            $sheet->setCellValue('H'.$row, $res->q4);
            $sheet->setCellValue('I'.$row, $res->q5);
            $sheet->setCellValue('J'.$row, $res->q6);
            $sheet->setCellValue('K'.$row, $res->q7);
            $sheet->setCellValue('L'.$row, $res->q8);
            $sheet->setCellValue('M'.$row, $res->q9);
            $sheet->setCellValue('N'.$row, $res->q10);
            
            $sheet->setCellValue('O'.$row, "=E$row-1");
            $sheet->setCellValue('P'.$row, "=5-F$row");
            $sheet->setCellValue('Q'.$row, "=G$row-1");
            $sheet->setCellValue('R'.$row, "=5-H$row");
            $sheet->setCellValue('S'.$row, "=I$row-1");
            $sheet->setCellValue('T'.$row, "=5-J$row");
            $sheet->setCellValue('U'.$row, "=K$row-1");
            $sheet->setCellValue('V'.$row, "=5-L$row");
            $sheet->setCellValue('W'.$row, "=M$row-1");
            $sheet->setCellValue('X'.$row, "=5-N$row");
            
            $sheet->setCellValue('Y'.$row, "=SUM(O$row:X$row)");
            $sheet->setCellValue('Z'.$row, "=Y$row*2.5");
            
            $row++;
        }

        if ($responses->count() > 0) {
            $sheet->getStyle('A3:Z'.($row-1))->applyFromArray($styleData);
            $sheet->getStyle('A3:A'.($row-1))->applyFromArray($styleCenter);
            $sheet->getStyle('C3:Z'.($row-1))->applyFromArray($styleCenter);
            $sheet->getStyle('E3:N'.($row-1))->applyFromArray($redText);
        }
        
        $lastRowData = $row - 1;
        $sheet->mergeCells('A'.$row.':Y'.$row);
        $sheet->setCellValue('A'.$row, 'Skor Rata-rata (Hasil Akhir)');
        
        if ($responses->count() > 0) {
            $sheet->setCellValue('Z'.$row, "=AVERAGE(Z3:Z$lastRowData)");
        }
        
        $sheet->getStyle('A'.$row.':Z'.$row)->applyFromArray($styleHeader);

        foreach (range('A', 'D') as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);

        $fileName = 'rekap_skor_sus_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ];

        return response()->stream(function() use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    private function interpretScore($score)
    {
        $interpretation = [
            'grade' => 'F',
            'adjective' => 'Worst Imaginable',
            'acceptability' => 'Unacceptable',
            'color' => 'danger'
        ];

        if ($score >= 80.3) {
            $interpretation = ['grade' => 'A', 'adjective' => 'Excellent', 'acceptability' => 'Acceptable', 'color' => 'success'];
        } elseif ($score >= 68) {
            $interpretation = ['grade' => 'B', 'adjective' => 'Good', 'acceptability' => 'Acceptable', 'color' => 'primary'];
        } elseif ($score >= 51) {
            $interpretation = ['grade' => 'C', 'adjective' => 'OK', 'acceptability' => 'Marginal', 'color' => 'warning'];
        } elseif ($score >= 38) {
            $interpretation = ['grade' => 'D', 'adjective' => 'Poor', 'acceptability' => 'Marginal', 'color' => 'info'];
        }

        return $interpretation;
    }
}
