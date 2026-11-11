<?php

namespace App\Exports\Student;

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

class StudentSheetExport implements FromView, WithStyles, ShouldAutoSize, WithColumnWidths, WithTitle
{
    protected string $sheet_name;
    protected array $rows;

    public function __construct(string $sheet_name, array $rows)
    {
        $this->sheet_name = $sheet_name;
        $this->rows = $rows;
    }

    /**
     * view blade for structure excel
     *
     * @return View
     */
    public function view(): View
    {
        return view('export-excels.school.export-student', [
            'title' => $this->sheet_name,
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

        $sheet->getRowDimension(1)->setRowHeight(15);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(20);

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

        $sheet->getStyle('A2:D2')->applyFromArray([
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

        $sheet->getStyle('A3:D3')->applyFromArray([
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
        
        $sheet->getStyle("A4:D{$highestRow}")->applyFromArray([
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

        for ($i = 4; $i <= $highestRow; $i++) {
            if ($i % 2 != 0) {
                $sheet->getStyle("A{$i}:D{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0F4FC'],
                    ],
                ]);
            }
        }

        $sheet->getStyle("A4:B{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle("D4:D{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }
}
