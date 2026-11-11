<?php

namespace App\Exports\Student;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExport implements WithMultipleSheets
{
    protected array $sheets;

    public function __construct
    (
        array $sheets,
    )
    {
        $this->sheets = $sheets;
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
            $sheets[] = new StudentSheetExport(
                $sheet['sheet_name'],
                $sheet['rows'],
            );
        }

        return $sheets;
    }
}
