<?php

namespace App\Services;

use App\Contracts\Interfaces\LessonHourInterface;
use App\Http\Requests\StoreLessonHourRequest;
use App\Http\Requests\UpdateLessonHourRequest;
use App\Models\LessonHour;
use App\Models\Student;

class LessonHourService
{
    private LessonHourInterface $lessonHour;

    public function __construct(LessonHourInterface $lessonHour)
    {
        $this->lessonHour = $lessonHour;
    }

    public function store(StoreLessonHourRequest $request, string $day): array|bool
    {
        $data = $request->validated();

        $name = "";
        if ($request->has('rest')) {
            $name = 'Istirahat';
        } else {
            $name = 'Jam ke - '. $data['name'];
        }

        $data['name'] = $name;
        $data['day'] = $day;
        return $data;
    }

    public function update(LessonHour $lessonHour, UpdateLessonHourRequest $request): array|bool
    {
        $data = $request->validated();

        $name = "";
        if ($request->has('rest')) {
            $name = 'Istirahat';
        } else {
            $name = 'Jam ke - '. $data['name'];
        }

        $data['name'] = $name;
        return $data;
    }

    public function delete(Student $student)
    {
        //
    }

    public function get()
    {
        $days = [
            'monday', 'tuesday', 'wednesday',
            'thursday', 'friday', 'saturday', 'sunday',
        ];

        $data = [];
        foreach ($days as $day) {
            $latest = $this->lessonHour->groupByLatest($day);
                    
            $nextOrder = 1;
            if ($latest) {
                preg_match('/\d+/', $latest->name, $matches);
                $nextOrder = isset($matches[0]) ? (int)$matches[0] + 1 : 1;
            }

            $data[] = [
                $latest,          
                $nextOrder      
            ];
        }

        return $data;
    }
}
