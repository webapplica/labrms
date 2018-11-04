<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
  
    protected $table = 'purposes';
    protected $id = 'id';
    public $timestamps = true;
	  protected $fillable = [
        'title', 'description'
    ];

    public function scopeTitle($query,$value)
    {
        return $query->where('title', '=', $value);
    }
}
