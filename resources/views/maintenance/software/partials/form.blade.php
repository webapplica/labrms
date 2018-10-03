
<!-- Software types -->
<div class="form-group">
    {{ Form::label('software-type', 'Software Type') }}
    {{ Form::select('software_type', $softwareTypes, isset($software->type) ? $software->type : old('software_type'),[
        'id' => 'software-type',
        'class' => 'form-control'
    ]) }}
</div>

<!-- Title -->
<div class="form-group">
    {{ Form::label('name', 'Software Name') }}
    {{ Form::text('name', isset($software->name) ? $software->name : old('name'), [
        'class' => 'form-control',
        'placeholder' => 'Software Name'
    ]) }}
</div>

<!-- Company -->
<div class="form-group">
    {{ Form::label('company', 'Company') }}
    {{ Form::text('company', isset($software->company) ? $software->company : old('company'), [
        'class' => 'form-control',
        'placeholder' => 'Company'
    ]) }}
</div>

<!-- License Type -->
<div class="form-group">
    {{ Form::label('license-type', 'License Type') }}
    {{ Form::select('license_type', $licenseTypes, isset($software->license_type) ? $software->license_type : old('license_type'), [
        'id' => 'license-type',
        'class' => 'form-control'
    ]) }}
</div>

<div class="form-group">
    <!-- description -->
    {{ Form::label('minimum-requirements', 'Minimum System Requirements') }}
    {{ Form::textarea('minimum_requirements', isset($software->minimum_requirements) ? $software->minimum_requirements : old('minimum_requirements'), [
        'class' => 'form-control',
        'placeholder' => 'Enter minimum system requirements here...',
        'data-autoresize',
        'rows' => '4'
    ]) }}
</div>

<div class="form-group">
    <!-- description -->
    {{ Form::label('recommended-requirements', 'Recommended System Requirements') }}
    {{ Form::textarea('recommended_requirements', isset($software->recommended_requirements) ? $software->recommended_requirements : old('recommended_requirements'), [
        'class' => 'form-control',
        'placeholder' => 'Recommended system requirements here...',
        'data-autoresize',
        'rows' => '4'
    ]) }}
</div>