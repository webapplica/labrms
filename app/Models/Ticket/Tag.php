<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = ['name'];

	/**
	 * Filter name
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return void
	 */
	public function scopeName($query, $value)
	{
		return $query->where('name', '=', $value);
	}

}
