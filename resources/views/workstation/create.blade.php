@extends('layouts.master-blue')

@section('style')
<style tyle="text/css">
  .form-group > .col-sm-12 > span {
    display: block;
    font-size: 0.9em;
    color: #545659;
  }
</style>
@endsection

@section('content')
<div class="container-fluid" id="page-body">

  <div class="panel panel-default col-md-offset-3 col-md-6" style="padding: 10px">
    <div class="panel-body">

      <legend><h3 class="text-primary">Workstation</h3></legend>
      <ul class="breadcrumb">
        <li><a href="{{ url('workstation') }}">Workstation</a></li>
        <li class="active">Add</li>
      </ul>
 
      @include('errors.alert')

      {{ Form::open(['method'=>'post','route'=>array('workstation.store'), 'class' => 'form-horizontal']) }}
          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('os','OS License Key') }}

              {{ Form::text('os', isset($workstation->oskey) ? $workstation->oskey : old('os')  ,[
                'id' => 'os',
                'class'=>'form-control',
                'placeholder'=>'Operating System Key'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('systemunit','System Unit') }}
              <span>Note: This field is required. Please input the property number for this item. </span>
              {{ Form::text('systemunit',Input::old('systemunit'),[
                'id'=>'systemunit',
                'class'=>'form-control',
                'placeholder' => 'System Unit'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('monitor','Monitor') }}
              <span>Note: This field accepts a property number.</span>
              {{ Form::text('monitor',Input::old('monitor'),[
                'id'=>'monitor',
                'class'=>'form-control',
                'placeholder' => 'Monitor'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('avr','AVR') }}
              <span>Note: This field accepts a property number.</span>
              {{ Form::text('avr',Input::old('avr'),[
                'id'=>'avr',
                'class'=>'form-control',
                'placeholder' => 'AVR'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('keyboard','Keyboard') }}
              <span>Note: This field accepts a property number.</span>
              {{ Form::text('keyboard',Input::old('keyboard'),[
                'id'=>'keyboard',
                'class'=>'form-control',
                'placeholder' => 'Keyboard'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('mouse','Mouse') }}
              <span> Note: This accepts the system-generated ID for its input. </span>
              {{ Form::text('mouse',Input::old('mouse'),[
                'id'=>'mouse',
                'class'=>'form-control',
                'placeholder' => 'Mouse'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              <button class="btn btn-primary btn-lg btn-block" name="create" type="submit"><span class="glyphicon glyphicon-check"></span> Add</button>
            </div>
          </div>

      {{ Form::close() }}
    </div>
  </div>
</div><!-- Container -->
@stop
@section('script')
<script>
  $(document).ready(function(){

    $('#keyboard').autocomplete({
      source: "{{ url('get/item/profile/keyboard/propertynumber') }}"
    });

    $('#monitor').autocomplete({
      source: "{{ url('get/item/profile/monitor/propertynumber') }}"
    });

    $('#systemunit').autocomplete({
      source: "{{ url('get/item/profile/systemunit/propertynumber') }}"

    });

    $('#avr').autocomplete({
      source: "{{ url('get/item/profile/avr/propertynumber') }}"

    });

    $('#mouse').autocomplete({
      source: "{{ url('get/item/profile/mouse/propertynumber') }}"

    });

  });
</script>
@stop
