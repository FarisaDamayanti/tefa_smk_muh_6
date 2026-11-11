<?php

namespace App\Exports\Exam;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExamExport implements WithMultipleSheets
{
    protected array $sheets;
    protected array $filtering;

    public function __construct
    (
        array $sheets,
        array $filtering = [],
    )
    {
        $this->sheets = $sheets;
        $this->filtering = $filtering;
    }

    /**
     * data per sheet
     *
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheets as $sheet) {
            $sheets[] = new StudentExamSheetExport(
                $sheet['sheet_name'],
                $sheet['title'],
                $sheet['rows'],
                $this->filtering,
            );
        }

        return $sheets;
    }
}
