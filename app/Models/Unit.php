<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = ['name', 'description', 'abbreviation'];

	/**
	 * [scopeName description]
	 * @param  [type] $query [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function scopeName($query, $value)
	{
		return $query->where('name', '=', $value);
	}

	/**
	 * [scopeAbbreviation description]
	 * @param  [type] $query [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function scopeAbbreviation($query, $value)
	{
		return $query->where('abbreviation', '=', $value);
	}

}
