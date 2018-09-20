<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    protected $table = 'room_categories';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $incrementing = true;
	public $fillable = ['name'];
}