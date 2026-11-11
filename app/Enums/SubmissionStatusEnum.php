<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

enum SubmissionStatusEnum: string
{
    case NOT_SUBMITTED = 'not_submitted'; // Belum ngumpulin dan telat deadline (allow overdue false)
    case NOT_YET_SUBMITTED = 'not_yet_submitted'; // Belum ngumpulin tapi masih masih deadline (termasuk jika allow overdue true)
    case NOT_EVALUATED = 'not_evaluated'; // Nggak ngumpulin (karena dia belum)
    case EVALUATED_ON_TIME = "evaluated_on_time"; // Dinilai tepat waktu
    case EVALUATED_BUT_BEYOND_DEADLINE = "evaluated_but_beyond_deadline"; // Dinilai dan melebihi deadline

    public static function compute(
        StatusSubmissionTaskEnum|string|null $status,
        bool $allowOverdue = false,
        ?int $grade = null
    ): ?static {

        if(is_null($status)){
            return self::NOT_SUBMITTED;
        }

        $status = is_string($status) ? StatusSubmissionTaskEnum::tryFrom($status) : $status;

        if (is_null($status) || ($status == StatusSubmissionTaskEnum::OVERDUE && !$allowOverdue && $grade === null)) {
            return self::NOT_SUBMITTED;
        }

        if ($grade === null) {
            return self::NOT_EVALUATED;
        }

        return $status == StatusSubmissionTaskEnum::SUBMITTED
            ? self::EVALUATED_ON_TIME
            : self::EVALUATED_BUT_BEYOND_DEADLINE;
    }

    public function color(): string
    {
        return match($this){
            self::NOT_SUBMITTED => 'danger',
            self::NOT_YET_SUBMITTED => 'warning',
            self::NOT_EVALUATED => 'gray',
            self::EVALUATED_ON_TIME => 'success',
            self::EVALUATED_BUT_BEYOND_DEADLINE => 'warning',
        };
    }

    public function label():string
    {
        return match($this){
            self::NOT_SUBMITTED => 'Tidak Mengumpulkan',
            self::NOT_YET_SUBMITTED => 'Belum Mengumpulkan',
            self::NOT_EVALUATED => 'Belum Dinilai',
            self::EVALUATED_ON_TIME => 'Dinilai',
            self::EVALUATED_BUT_BEYOND_DEADLINE => 'Dinilai',
        };
    }
}
