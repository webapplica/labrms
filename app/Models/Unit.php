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
	 * Filter the query by unit name
	 * 
	 * @param  Builder $query
	 * @param  string $value
	 * @return        
	 */
	public function scopeName($query, $value)
	{
		return $query->where('name', '=', $value);
	}

	/**
	 * Filter the query by unit abbreviation
	 * 
	 * @param  Builder $query
	 * @param  string $value
	 * @return        
	 */
	public function scopeAbbreviation($query, $value)
	{
		return $query->where('abbreviation', '=', $value);
	}

}
