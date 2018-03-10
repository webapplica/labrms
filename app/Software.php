<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Software extends \Eloquent{

	protected $table = 'softwares';
	public $timestamps = true;
	protected $primaryKey = 'id';
	public $fillable = ['softwarename','softwaretype','licensetype','company','minsysreq','maxsysreq'];

	public static $rules = array(
		'Software Name' => 'required|min: 2|max: 100',
		'Software Type' => 'required|min: 2|max: 100',
		'License Type' => 'required|min: 2|max: 100',
		'Company' => 'min: 2|max: 100',
		'Minimum System Requirement' => 'min: 2|max: 100',
		'Recommended System Requirement' => 'min: 2|max: 100'

	);

	public static $updateRules = array(
		'Software Name' => 'alpha|min: 2|max: 100',
		'Software Type' => 'alpha|min: 2|max: 100',
		'License Type' => 'alpha|min: 2|max: 100',
		'Company' => 'alpha|min: 2|max: 100',
		'Minimum System Requirement' => 'alpha|min: 2|max: 100',
		'Recommended System Requirement' => 'alpha|min: 2|max: 100'
	);

	public static $types = [
		'Programming',
		'Database',
		'Multimedia',
		'Networking'
	];

	public static $licensetypes = [
		'Proprietary license',
		'GNU General Public License',
		'End User License Agreement (EULA)',
		'Workstation licenses',
		'Concurrent use license',
		'Site licenses',
		'Perpetual licenses',
		'Non-perpetual licenses',
		'License with Maintenance'
	];

	public function licenseTypes()
	{
		return self::$licensetypes;
	}

	public function license(){ 
		return $this->hasMany('App\SoftwareLicense');
	}

	public function rooms(){
		return $this->belongsToMany('App\Room', 'room_software', 'software_id', 'room_id')
				->withTimestamps();
	}

	public function pc()
	{
		return $this->belongsToMany('App\Pc','software_software','pc_id','pc_id')->withPivot('softwarelicense_id')->withTimestamps();
	}

	public function getSoftwareTypes()
	{
		$types = Software::$types;
		return compact($types);
	}


}
