
<div class="form-group">
    {{ Form::label('title','Title') }}
    {{ Form::text('title', isset($faculty->title) ? $faculty->title :  old('title'), [
      'class'=>'form-control',
      'placeholder'=>'Title'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('firstname','First Name') }}
    {{ Form::text('firstname', isset($faculty->firstname) ? $faculty->firstname :  old('firstname'), [
      'class'=>'form-control',
      'placeholder'=>'firstname'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('middlename','Middle Name') }}
    {{ Form::text('middlename', isset($faculty->middlename) ? $faculty->middlename :  old('middlename'), [
      'class'=>'form-control',
      'placeholder'=>'middlename'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('lastname','Last Name') }}
    {{ Form::text('lastname', isset($faculty->lastname) ? $faculty->lastname :  old('lastname'), [
      'class'=>'form-control',
      'placeholder'=>'lastname'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('suffix','Suffix') }}
    {{ Form::text('suffix', isset($faculty->suffix) ? $faculty->suffix :  old('suffix'), [
      'class'=>'form-control',
      'placeholder'=>'Suffix'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('contactnumber','Contact Number') }}
    {{ Form::text('contactnumber', isset($faculty->contactnumber) ? $faculty->contactnumber :  old('contactnumber'), [
      'class'=>'form-control',
      'placeholder'=>'Contact Number'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('email','Email Address') }}
    {{ Form::email('email', isset($faculty->email) ? $faculty->email :  old('email'), [
      'class'=>'form-control',
      'placeholder'=>'Email Address'
    ]) }}
</div>