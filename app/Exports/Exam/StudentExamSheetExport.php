<?php

namespace App\Exports\Exam;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExamSheetExport implements FromView, WithStyles, ShouldAutoSize, WithColumnWidths, WithTitle
{
    protected string $sheet_name;
    protected string $title;
    protected array $rows;
    protected array $filtering;

    public function __construct(string $sheet_name, string $title, array $rows, array $filtering = [])
    {
        $this->sheet_name = $sheet_name;
        $this->title = $title;
        $this->rows = $rows;
        $this->filtering = $filtering;
    }

    /**
     * view blade for structure excel
     *
     * @return View
     */
    public function view(): View
    {
        return view('export-excels.school.export-student-exam', [
            'title' => $this->title,
            'filtering' => $this->filtering,
            'rows' => $this->rows,
        ]);
    }

    /**
     * title sheet
     *
     * @return string
     */
    public function title(): string
    {
        return $this->sheet_name;
    }

    /**
     * width column
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 35,
            'D' => 18,
            'E' => 10,
            'F' => 20,
        ];
    }

    /**
     * styling excel
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $startRow = empty($this->filtering) ? 0 : 1;

        $sheet->getRowDimension(1)->setRowHeight(15);
        if ($startRow) {
            $sheet->getRowDimension(2)->setRowHeight(20);
        }
        $sheet->getRowDimension(2 + $startRow)->setRowHeight(30);
        $sheet->getRowDimension(3 + $startRow)->setRowHeight(20);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        if ($startRow) {
            $sheet->getStyle('A2')->applyFromArray([
                'font' => [
                    'name' => 'Calibri',
                    'size' => 9,
                    'italic' => true,
                    'color' => ['rgb' => '333333'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        $sheet->getStyle('A' . (2 + $startRow) .':F' . (2 + $startRow))->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 15,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4A6FA5'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => 'FF4A6FA5'],
                ],
            ],
        ]);

        $sheet->getStyle('A' . (3 + $startRow) . ':F' . (3 + $startRow))->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '6B8CBC'],
                ],
            ],
        ]);
        
        $sheet->getStyle("A" . (4 + $startRow) . ":F{$highestRow}")->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '8EA9DB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'wrapText' => true,
            ],
        ]);

        for ($i = (4 + $startRow); $i <= $highestRow; $i++) {
            if ($i % 2 != 0) {
                $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0F4FC'],
                    ],
                ]);
            }
        }

        $sheet->getStyle("A" . (4 + $startRow) . ":B{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle("D" . (4 + $startRow) . ":F{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }
}
