<legend>
    <h3 class="text-muted">
        Items

        <small>Quantity to profile: <span class="profile-quantity"></span></small>
    </h3>
</legend>	
<small class="text-muted">{{ __('instructions.label') . ': ' . __('instructions.profile_add') }}</small>

<table id="items-table" class="table table-bordered table-hover" style="margin-top: 20px;">
    <thead class="items-table__thead">
        <th class="col-md-1">University Property Number</th>
        <th class="col-md-1">Local Property Number</th>
        <th class="col-md-1">Serial ID</th>
        <th class="col-md-1"></th>
    </thead>
    <tbody class="items-table__tbody"></tbody>
</table>