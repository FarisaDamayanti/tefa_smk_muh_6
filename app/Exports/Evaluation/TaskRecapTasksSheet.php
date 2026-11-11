<?php

namespace App\Exports\Evaluation;

use Exception;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskRecapTasksSheet implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        private array $tasksTopics
    ) {}

    public function title(): string
    {
        return "Topic - Tasks";
    }

    public function view(): View
    {
        return view('export-excels.teacher.export-all-task-recap-tasks-topic', [
            'data' => $this->tasksTopics
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Style only the cells that have content in first two rows
        $highestColumn = $sheet->getHighestColumn();
        $highestRow =  $sheet->getHighestDataRow();

        // Apply background color to the first two rows
        $sheet->getStyle("A1:{$highestColumn}2")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF4A6FA5', // Blue color #4A6FA5
                ],
            ],
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF', // White text for better contrast
                ],
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'ffffff'],
                ],
            ]
        ]);

        $sheet->getStyle("A3:{$highestColumn}{$highestRow}")->applyFromArray([
            'font' => [
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '6B8CBC'],
                ],
            ]
        ]);
    }
}
