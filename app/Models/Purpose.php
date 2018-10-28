<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
  
    protected $table = 'purposes';
    protected $id = 'id';
    public $timestamps = true;
	  protected $fillable = [
        'title', 'description'
    ];

    public static $rules = [
        'title' => 'required|max:50',
        'description' => 'required',
        'points' => 'required' 
    ];

    public static $updateRules = [
        'title' => '',
        'description' => ''
    ];

    public function scopeTitle($query,$value)
    {
        return $query->where('title', '=', $value);
    }
}
