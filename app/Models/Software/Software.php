<?php

namespace App\Models\Software;

use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
	protected $table = 'softwares';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = [
		'name', 'type', 'license_type', 'company', 'minimum_requirements', 'recommended_requirements'
	];

	// public static $updateRules = array(
	// 	'Software Name' => 'alpha|min: 2|max: 100',
	// 	'Software Type' => 'alpha|min: 2|max: 100',
	// 	'License Type' => 'alpha|min: 2|max: 100',
	// 	'Company' => 'alpha|min: 2|max: 100',
	// 	'Minimum System Requirement' => 'alpha|min: 2|max: 100',
	// 	'Recommended System Requirement' => 'alpha|min: 2|max: 100'
	// );

	private static $licenseTypes = [
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

	// public static $installationRules = [
	// 	'Workstation' => 'required|exists:workstations,id',
	// 	'Software' => 'required|exists:softwares,id',
	// 	'License Key' => 'min:3|max:100'
	// ];

	/**
	 * Returns list of license types
	 *
	 * @return array
	 */
	public function getLicenseTypes()
	{
		return collect(array_combine(self::$licenseTypes, self::$licenseTypes));
	}

	// public function license() { 
	// 	return $this->hasMany(License::class, 'software_id', 'id');
	// }

	// public function rooms(){
	// 	return $this->belongsToMany('App\Room', 'room_software', 'software_id', 'room_id')
	// 			->withTimestamps();
	// }

	// public function workstations()
	// {
	// 	return $this->belongsToMany('App\Workstation','workstation_software','software_id','workstation_id')
	// 		->withPivot('license_id')
	// 		->withTimestamps();
	// }

	// public function getSoftwareTypes()
	// {
	// 	$types = Software::$types;
	// 	return compact($types);
	// }

	// public function install($id, $license = null)
	// {
	// 	$license_addons = [];
		
	// 	if(isset($license) && $license != "" && $license != null)
	// 	{
	// 		$otherFields = [
	// 			'software_id' => $this->id,
	// 			'usage' => 1
	// 		];

	// 		$license = SoftwareLicense::firstOrCreate([
	// 			'key' => $license
	// 		], $otherFields);

	// 		$license_addons = [
	// 			'license_id' => $license->id
	// 		];
	// 	}

	// 	$this->workstations()->attach($id, $license_addons);

	// 	$workstation = Workstation::find($id);

	// 	$title = 'Software Installation';
	// 	$staff_id = Auth::user()->id;
	// 	$details = "$this->name installed on Workstation $workstation->name";

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Maintenance'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->status = 'Closed';
	// 	$ticket->generate($workstation->systemunit->id);
	// }

	// public function uninstall($id)
	// {
	// 	$this->workstations()->detach($id);
	// 	$workstation = Workstation::find($id);

	// 	$title = 'Software Installation';
	// 	$staff_id = Auth::user()->id;
	// 	$details = "$this->name removed from Workstation $workstation->name";

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Maintenance'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->status = 'Closed';
	// 	$ticket->generate($workstation->systemunit->id);
	// }

	// public function updateSoftwareLicense($id, $license)
	// {
	// 	$license_addons = [];

	// 	if(isset($license) && $license != "" && $license != null)
	// 	{
	// 		$otherFields = [
	// 			'software_id' => $this->id,
	// 			'usage' => 1
	// 		];

	// 		$license = SoftwareLicense::firstOrCreate([
	// 			'key' => $license
	// 		], $otherFields);

	// 		$license_addons = [
	// 			'license_id' => $license->id
	// 		];
	// 	}

	// 	$this->workstations()->updateExistingPivot($id, $license_addons);

	// 	$workstation = Workstation::find($id);

	// 	$title = 'Software License Update';
	// 	$staff_id = Auth::user()->id;
	// 	$details = "$this->name license updated on Workstation $workstation->name";

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Maintenance'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->status = 'Closed';
	// 	$ticket->generate($workstation->systemunit->id);

	// }


}
