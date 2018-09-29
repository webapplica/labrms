
		DB::beginTransaction();

$room = $this->sanitizeString($request->get('room'));
$workstation = $this->sanitizeString($request->get('items'));
$name = $this->sanitizeString($request->get('name'));

App\Workstation::setWorkstationLocation($workstation,$room);
$workstation = App\Workstation::find($workstation);
$workstation->name = $name;
$workstation->save();

DB::commit();

/**
*
*	check if the request is ajax
*
*/
if($request->ajax())
{
    return json_encode('success');
}
