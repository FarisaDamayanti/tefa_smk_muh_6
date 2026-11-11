<?php
namespace App\Helpers;

class SemesterHelper
{
    /**
     * getSemester
     *
     * @return array
     */
    public static function getSemester()
    {
        $month = intval(now()->format('n'));
        $data = [];
        if ($month >= 7 && $month <= 12) {
            $data = [
                'semester' => 'Ganjil',
                'month' => [7, 8, 9, 10, 11]
            ];
        } else {
            $data = [
                'semester' => 'Genap',
                'month' => [1, 2, 3, 4, 5]
            ];
        }
        return $data;
    }

    /**
     * Get current semester number based on current month
     * @return 1|2
     */
    public static function getCurrentSemesterNumber(): int
    {
        $currentMonth = now()->month;
        return $currentMonth >= 7 ? 1 : 2;
    }

    /**
     * Get current semester string
     * @return 'ganjil'|'genap'
     */
    public static function getCurrentSemester(): string
    {
        return self::getCurrentSemesterNumber() === 1 ? 'ganjil' : 'genap';
    }

    /**
     * Undocumented function
     *
     * @return array{semester: string, month: int[]}[]
     */
    public static function allMonth()
    {
        $data = [];
        $data = [
            [
                'semester' => 'Ganjil',
                'month' => [7, 8, 9, 10, 11]
            ],
            [
                'semester' => 'Genap',
                'month' => [1, 2, 3, 4, 5]
            ]
        ];
        return $data;
    }

    /**
     * Undocumented function
     *
     * @param integer $semester
     * @return int[]|array
     */
    public static function getSemesterMonths(int $semester): array
    {
        return match ($semester) {
            1 => [7, 8, 9, 10, 11],
            2 => [1, 2, 3, 4, 5],
            default => []
        };
    }
}
