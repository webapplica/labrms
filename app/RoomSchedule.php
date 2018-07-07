<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\FacultyView;

class RoomSchedule extends \Eloquent{
	//Database driver
	/*
		1 - Eloquent (MVC Driven)
		2 - DB (Directly query to SQL database, no model required)
	*/

	//The table in the database used by the model.
	protected $table = 'room_schedules';

	//The attribute that used as primary key.
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $fillable = ['room_id','faculty','academicyear','semester','day','timein','timeout','subject','section'];
	//Validation rules!
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