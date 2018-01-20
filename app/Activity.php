<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Activity extends \Eloquent{
  //Database driver
  /*
  1 - Eloquent (MVC Driven)
  2 - DB (Directly query to SQL database, no model required)
  */
  //The table in the database used by the model.


  //The table in the database used by the model.
  protected $table = 'activities';
  public $fillable = ['type','activity','details'];
  public $timestamps = true;
  //Validation rules!
  protected $primaryKey = 'id';

  public static $rules = [
    'Type' => 'required',
    'Activity' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    'Details' => '',
  ];

  public static $updateRules = [
    'Activity' => 'required|max:50'
  ];

  public $activity_types = [
    'P' => 'Preventive',
    'C' => 'Corrective',
    'U' => 'Unidentified'
  ];

  public function setTypeAttribute($value)
  {
    $type = ucfirst($value);

    foreach($this->activity_types as $key=>$value):
      if($value == $type) $type = $key;
    endforeach;

    $this->attributes['type'] = $type;
  }

  public function getTypeAttribute($value)
  {
    $type = ucfirst($value);

    foreach($this->activity_types as $key=>$value):
      if($key == $type) $type = $value;
    endforeach;

    return $type;
  }

  public function scopeType($query,$type)
  {
    return $query->where('type','=',$type);
  }

}
