<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Software extends \Eloquent{

	protected $table = 'softwares';
	public $timestamps = true;
	protected $primaryKey = 'id';
	public $fillable = [
		'name',
		'type',
		'license_type',
		'company',
		'minimum_requirements',
		'recommended_requirements'
	];

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

	public static $installationRules = [
		'Workstation' => 'required|exists:workstations,id',
		'Software' => 'required|exists:softwares,id',
		'License Key' => 'min:3|max:100'
	];

	public function licenseTypes()
	{
		return self::$licensetypes;
	}

	public function license(){ 
		return $this->hasMany('App\SoftwareLicense', 'software_id', 'id');
	}

	public function rooms(){
		return $this->belongsToMany('App\Room', 'room_software', 'software_id', 'room_id')
				->withTimestamps();
	}

	public function workstations()
	{
		return $this->belongsToMany('App\Workstation','workstation_software','software_id','workstation_id')
			->withPivot('license_id')
			->withTimestamps();
	}

	public function getSoftwareTypes()
	{
		$types = Software::$types;
		return compact($types);
	}

	public function install($id, $license = null)
	{
		$otherFields = [
			'software_id' => $this->id,
			'usage' => 1
		];

		$license = SoftwareLicense::firstOrCreate([
			'key' => $license
		], $otherFields);

		$this->workstations()->attach($id, [
			'license_id' => $license->id
		]);

		$workstation = Workstation::find($id);

		$title = 'Software Installation';
		$staff_id = Auth::user()->id;
		$details = '$this->name installed on Workstation $workstation->name';

		$type = TicketType::firstOrCreate([
			'name' => 'Maintenance'
		]);

		$ticket = new Ticket;
		$ticket->type_id = $type->id;
		$ticket->title = $title;
		$ticket->details = $details;
		$ticket->staff_id = $staff_id;
		$ticket->status = 'Closed';
		$ticket->generate($workstation->systemunit->id);
	}

	public function uninstall($id)
	{
		$this->workstations()->detach($id);
		$workstation = Workstation::find($id);

		$title = 'Software Installation';
		$staff_id = Auth::user()->id;
		$details = '$this->name removed from Workstation $workstation->name';

		$type = TicketType::firstOrCreate([
			'name' => 'Maintenance'
		]);

		$ticket = new Ticket;
		$ticket->type_id = $type->id;
		$ticket->title = $title;
		$ticket->details = $details;
		$ticket->staff_id = $staff_id;
		$ticket->status = 'Closed';
		$ticket->generate($workstation->systemunit->id);
	}

	public function updateSoftwareLicense($id, $license)
	{


		if(isset($license) && $license != "" && $license != null)
		{
			$otherFields = [
				'usage' => 1
			];

			$license = SoftwareLicense::firstOrCreate([
				'software_id' => $this->id,
				'key' => $license
			], $otherFields);

			$this->workstations()->attach($id, [
				'license_id' => $license->id
			]);
		}

		$workstation = Workstation::find($id);

		$title = 'Software License Update';
		$staff_id = Auth::user()->id;
		$details = '$this->name license updated on Workstation $workstation->name';

		$type = TicketType::firstOrCreate([
			'name' => 'Maintenance'
		]);

		$ticket = new Ticket;
		$ticket->type_id = $type->id;
		$ticket->title = $title;
		$ticket->details = $details;
		$ticket->staff_id = $staff_id;
		$ticket->status = 'Closed';
		$ticket->generate($workstation->systemunit->id);

	}


}
