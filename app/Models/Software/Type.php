<?php

namespace App\Models\Software;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'software_types';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = ['type'];
	
	public static $rules = array(
		'Type' => 'required|string|min:5|max:100'
	);

	public static $updateRules = array(
		'Type' => 'string|min:5|max:100'
	);
}
