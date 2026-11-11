<?php
        
namespace App\Contracts\Interfaces;
        
use App\Contracts\Interfaces\Eloquent\DeleteInterface; 
use App\Contracts\Interfaces\Eloquent\GetInterface; 
use App\Contracts\Interfaces\Eloquent\ShowInterface; 
use App\Contracts\Interfaces\Eloquent\StoreInterface; 
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Models\Graduation;

interface GraduationInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface 
{
    /**
     * check exists data by school year id
     * 
     * @param int $school_year_id
     * @return Graduation|null
     */
    public function getBySchoolYear(mixed $school_year_id): Graduation|null;

    /**
     * check file name
     * 
     * @param string $fileName
     * @return bool
     */
    public function checkFileName(string $fileName): bool;
}