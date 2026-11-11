<?php

namespace App\Exports\Evaluation;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TaskRecapReportExport implements WithMultipleSheets
{
    public function __construct(
        private Collection $collection,
    ) {}

    public function sheets(): array
    {
        return [
            new TaskRecapEvaluationSheet($this->collection),
            new TaskRecapTasksSheet($this->collection->topicTasks)
        ];
    }
}
