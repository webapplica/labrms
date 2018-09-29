<?php

namespace App\Commands\User\DeleteUser;

use App\Models\User;
use Illuminate\Http\Request;

class DeleteUser
{

	protected $id;

	/**
	 * [__construct description]
	 * @param int $id [description]
	 */
	public function __construct(int $id)
	{
		$this->id = $id;
	}

	/**
	 * [handle description]
	 * @return [type] [description]
	 */
	public function handle()
	{
		User::findOrFail($this->id)->delete();
	}
}