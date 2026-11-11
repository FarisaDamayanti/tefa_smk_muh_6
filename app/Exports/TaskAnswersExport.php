<?php

namespace App\Exports;

use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Enums\SubmissionTypeEnum;
use App\Models\Task;
use Exception;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

class TaskAnswersExport implements FromView, WithStyles, ShouldAutoSize
{
    public function __construct(
        private readonly TaskSubmissionRepository $taskSubmission,
        private readonly Task $task
    ) {}

    public function view(): View
    {
        if ($this->task->submission_type !== SubmissionTypeEnum::LINK) {
            throw new RuntimeException('Task harus berupa link');
        }

        $submissions = $this->taskSubmission->getByTask($this->task->id);
        if($submissions->isEmpty()){
            throw new Exception("Belum ada murid yang mengumpulkan.");
        }

        $first = $submissions->first();

        return view('export-excels.teacher.export-task-link-answers', [
            'data' => [
                'classroom'   => $first?->task?->topic?->classroom,
                'task'        => $this->task,
                'submissions' => $submissions,
            ],
        ]);
    }

public function styles(Worksheet $sheet)
{
    $highestRow = $sheet->getHighestRow();

    $sheet->mergeCells('A1:C1');
    $sheet->mergeCells('A2:C2');
    $sheet->mergeCells('A3:C3');

    $sheet->getStyle('A1:A3')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $sheet->getStyle('A5:C5')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'EDEDED'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ]);

    $sheet->getStyle("A6:C{$highestRow}")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $sheet->getStyle("C6:C{$highestRow}")->applyFromArray([
        'font' => [
            'color' => ['argb' => '0563C1'],
            'underline' => true,
        ],
    ]);
}

}
