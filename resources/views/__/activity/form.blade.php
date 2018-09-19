
<!-- Title -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('type','Maintenance Type') }}
	</div>
	<div class="col-sm-6">
	  <input type="radio" id="corrective" name="maintenancetype" value='corrective' checked/> Corrective
	</div>
	<div class="col-sm-6">
	  <input type="radio" id="preventive" name="maintenancetype" value='preventive' /> Preventive
	</div>
</div>
<div id="preventive-info" class="col-sm-12 alert alert-success" role="alert" hidden>
	Machine maintenance or the preventive maintenance (PM) has the following meanings:
	<ul>
		<li>
		The care and servicing by personnel for the purpose of maintaining equipment in satisfactory operating condition by providing for systematic inspection, detection, and correction of incipient failures either before they occur or before they develop into major defects.
		</li>
		<li>
		Preventive maintenance tends to follow planned guidelines from time-to-time to prevent equipment and machinery breakdown
		</li>
		<li>
		The work carried out on equipment in order to avoid its breakdown or malfunction. It is a regular and routine action taken on equipment in order to prevent its breakdown.
		</li>
		<li>
		Maintenance, including tests, measurements, adjustments, parts replacement, and cleaning, performed specifically to prevent faults from occurring.
		</li>
	</ul>
</div>
<div class="col-sm-12 alert alert-warning" role="alert" id="corrective-info">
	Corrective maintenance is a maintenance task performed to identify, isolate, and rectify a fault so that the failed equipment, machine, or system can be restored to an operational condition within the tolerances or limits established for in-service operations
</div>
<!-- Title -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('activity','Activity Title') }}
	{{ Form::text('activity', isset($maintenanceactivity->name) ? $maintenanceactivity->name : old('name'),[
		'class'=>'form-control',
		'placeholder'=>'Title of the activity done'
	]) }}
	</div>
</div>
<!-- Details -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('details','Details') }}
	{{ Form::textarea('details', isset($maintenanceactivity->details) ? $maintenanceactivity->details : old('details'),[
		'class'=>'form-control',
		'placeholder'=>'Description of the maintenance activity done'
	]) }}
	</div>
</div>