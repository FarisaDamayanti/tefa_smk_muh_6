<?php

namespace App\Exports\Evaluation;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class TaskRecapEvaluationSheet implements FromView, ShouldAutoSize, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $collection)
    {
        if($this->collection->isEmpty()){
            throw new Exception("Tidak ada data yang tersedia di kriteria ini.");
        }
    }

    public function title(): string
    {
        $first = $this->collection->first();
        return strtoupper($first['subject']->name . ' - ' . $first['classroom']->name);
    }

    public function view(): View
    {
        return view('export-excels.teacher.export-all-task-recap', [
            'data' => $this->collection
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No column
            'B' => 30,   // Name column
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get total columns dynamically
        $maxColumn = $sheet->getHighestColumn();
        $maxColumnIndex = Coordinate::columnIndexFromString($maxColumn);

        // Blue header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4A6FA5'], // Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        // Light blue header style for topic rows
        $topicHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        // Data row style
        $dataStyle = [
            'font' => [
                'size' => 10,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '6B8CBC'],
                ],
            ],
        ];

        // Center alignment for number columns
        $centerStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        // Main title row (row 1)
        $sheet->mergeCells('A1:' . $maxColumn . '1');
        $sheet->getStyle('A1')->applyFromArray($headerStyle);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Get the title text from cell A1
        $titleText = $sheet->getCell('A1')->getValue();
        $titleLength = strlen($titleText);

        // Calculate required width based on character count and font size
        // Rough estimate: each character ~1.2 units at 14pt font
        $requiredWidth = ceil($titleLength * 1.2);

        // Distribute width across columns
        $columnsNeeded = $maxColumnIndex;
        $widthPerColumn = ceil($requiredWidth / $columnsNeeded) + 2; // Add padding

        for ($col = 1; $col <= $maxColumnIndex; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $currentWidth = $sheet->getColumnDimension($colLetter)->getWidth();
            $sheet->getColumnDimension($colLetter)->setWidth(max($currentWidth, $widthPerColumn));
        }

        // Year row (row 2)
        $sheet->mergeCells('A2:' . $maxColumn . '2');
        $sheet->getStyle('A2')->applyFromArray($headerStyle);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Spacer row (row 3)
        $sheet->getRowDimension(3)->setRowHeight(10);

        // Apply light blue style to topic headers
        // Semester row (row 4)
        $sheet->getStyle('C4:' . $maxColumn . '4')->applyFromArray($topicHeaderStyle);
        $sheet->getStyle('C4:' . $maxColumn . '4')->getFont()->setSize(14)->setBold(false);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Title row (row 5)
        $sheet->getStyle('C5:' . $maxColumn . '5')->applyFromArray($topicHeaderStyle);
        $sheet->getStyle('C5:' . $maxColumn . '5')->getFont()->setSize(14)->setBold(true);
        $sheet->getRowDimension(5)->setRowHeight(30);

        // Apply blue style to the first two columns (No and Nama) for all header rows
        $sheet->getStyle('A4:B7')->applyFromArray($headerStyle);

        // Data rows (starting from row 8)
        $dataStartRow = 8;
        $dataEndRow = $sheet->getHighestRow();
        if ($dataEndRow >= $dataStartRow) {
            $sheet->getStyle('A' . $dataStartRow . ':' . $maxColumn . $dataEndRow)->applyFromArray($dataStyle);

            // Center align for number columns (A) and grade columns (odd columns starting from C)
            for ($col = 1; $col <= $maxColumnIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);

                if ($col == 1) { // Column A (No)
                    $sheet->getStyle($colLetter . $dataStartRow . ':' . $colLetter . $dataEndRow)
                        ->applyFromArray($centerStyle);
                }

                // Center align grade columns (odd columns starting from C)
                if ($col >= 3 && $col % 2 == 1) { // Column C, E, G, etc.
                    $sheet->getStyle($colLetter . $dataStartRow . ':' . $colLetter . $dataEndRow)
                        ->applyFromArray($centerStyle);
                }
            }
        }

        return [];
    }
}
