<?php

namespace App\Models\Scheduling;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $fillable = [
        'name', 'start', 'end'
    ];
}
