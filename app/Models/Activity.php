<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
  protected $table = 'activities';
  protected $primaryKey = 'id';
  public $timestamps = true;
  public $fillable = ['type', 'name', 'details'];

}
