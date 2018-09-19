<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

	protected $table = 'room_schedules';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $fillable = ['room_id','faculty','academicyear','semester','day','timein','timeout','subject','section'];

	public static $rules = array(
		'Subject' => 'required|min:2|max:50',
		'Day' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
		'Room' => 'exists:rooms,id',
		'Semester' => 'exists:semesters,semester',
		'Academic Year' => 'exists:academic_years,name',
		'Faculty' => 'exists:users,id'
	);

	public static $updateRules = array(
		'Subject' => 'min:2|max:50',
		'Room' => 'exists:rooms,id',
		'Semester' => 'exists:semesters,semester',
		'Academic Year' => 'exists:academic_years,name',
		'Faculty' => 'exists:users,id'
	);

	public function faculty()
	{
		return $this->belongsTo('App\Faculty','faculty','id');
	}


}