<?php

namespace App\Models\Software;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
	
	protected $table = 'software_licenses';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = [ 'software_id', 'key', 'usage'];

	// public static $rules = array(
	// 	'Product Key' => 'required|string|min:5|max:100'
	// );

	// public static $updateRules = array(
	// 	'Product Key' => 'string|min:5|max:100'
	// );

	// public function software()
	// {
	// 	return $this->belongsTo('App\Software','software_id','id');
	// }

	// /**
	// *
	// *	add count to used software 
	// *	@param $id accepts software id
	// *
	// */
	// public static function install($id)
	// {
	// 	$softwarelicense = SoftwareLicense::find($id);
	// 	$softwarelicense->inuse = $softwarelicense->inuse + 1;
	// 	$softwarelicense->save();
	// }

	// /**
	// *
	// *	remove count from used software 
	// *	@param $id accepts software id
	// *
	// */
	// public static function uninstall($id){
	// 	$softwarelicense = SoftwareLicense::find($id);
	// 	$softwarelicense->inuse = ($softwarelicense->inuse > 0) ? $softwarelicense->inuse - 1 : 0;
	// 	$softwarelicense->save();
	// }
}
