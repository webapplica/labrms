
<div class="form-group">
  {{ Form::label('username','Identification Number') }}
  {{ Form::text('username', isset($user->username) ? $user->username : old('username'),[
      'class' => 'form-control',
      'id' => 'username',
      'placeholder' => 'Identification Number'
  ])}}
  <p class="text-muted" style="font-size: 10px;">
    <span class="text-success">Note:</span>The Identification Number will be used as the username of the said person.
  </p>
</div>

<div class="form-group">
  {{ Form::label('firstname','Firstname') }}
  {{ Form::text('firstname',  isset($user->firstname) ? $user->firstname : old('firstname'),[
      'class' => 'form-control',
      'id' => 'firstname',
      'placeholder' => 'First name'
  ]) }}
</div>

<div class="form-group">
  {{ Form::label('middlename','Middlename') }}
  {{  Form::text('middlename', isset($user->middlename) ? $user->middlename : old('middlename'),[
      'class' => 'form-control',
      'id' => 'middlename',
      'placeholder' => 'Middle name'
    ]) }}
</div>

<div class="form-group">
  {{ Form::label('lastname','Lastname') }}
   {{ Form::text('lastname',  isset($user->lastname) ? $user->lastname : old('lastname'),[
      'class' => 'form-control',
      'id' => 'lastname',
      'placeholder' => 'Last name'
   ]) }}
</div>

<div class="form-group">
  {{ Form::label('contact-number','Contact Number') }}
  {{ Form::text('contactnumber',  isset($user->contactnumber) ? $user->contactnumber : old('contactnumber'),[
      'class' => 'form-control',
      'id' => 'contact-number',
      'placeholder' => 'Contact Number'
  ]) }}
</div>

<div class="form-group">
  {{ Form::label('email','Email') }}
  {{ Form::text('email', isset($user->email) ? $user->email : old('email'),[
      'class' => 'form-control',
      'id' => 'email',
      'placeholder' => 'Email'
  ]) }}
</div>

<div class="form-group">
  {{ Form::label('accesslevel','Role') }}
  <select class="form-control" name="accesslevel" id="access-level">
  @foreach($roles as $key => $value)
  <option 
    value="{{ $key }}"
    {{ ((isset($user->accesslevel) && $user->accesslevel == $key ) || old('accesslevel') == $key ) ? 'selected' : '' }}>
    {{ $value }}
  </option>
  @endforeach
  </select>
</div>