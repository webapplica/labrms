@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
  <div class="panel panel-default col-md-offset-3 col-md-6" style="padding:10px;">
    <div class="panel-body">
      <legend><h3 class="text-primary">Workstation</h3></legend>
      <ul class="breadcrumb">
        <li>
          <a href="{{ url('workstation') }}">Workstation</a>
        </li>
        <li>
          <a href="{{ url("workstation/$workstation->id") }}">{{ $workstation->name }}</a>
        </li>
        <li class="active">Update</li>
      </ul>

      @include('errors.alert')

      {{ Form::open(['method'=>'put','route'=>array('workstation.update',$workstation->id)]) }}
          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('os','Operating System Key') }}
              {{ Form::text('os',$workstation->oskey,[
                'id' => 'os',
                'class'=>'form-control',
                'placeholder'=>'Operating System Key'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('monitor','Monitor Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($workstation->monitor)
                  {{ $workstation->monitor->property_number }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('monitor',Input::old('monitor'),[
                'id'=>'monitor',
                'class'=>'form-control',
                'placeholder' => 'Monitor'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('avr','AVR Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($workstation->avr)
                  {{ $workstation->avr->property_number }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('avr',Input::old('avr'),[
                'id'=>'avr',
                'class'=>'form-control',
                'placeholder' => 'AVR'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('keyboard','Keyboard Property Number') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($workstation->keyboard)
                  {{ $workstation->keyboard->property_number }}
                  @else
                  None
                  @endif
                </span>
              </p>
              {{ Form::text('keyboard',Input::old('keyboard'),[
                'id'=>'keyboard',
                'class'=>'form-control',
                'placeholder' => 'Keyboard'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              {{ Form::label('mouse','Mouse Brand') }}
              <p>
                <span class="text-danger">Old Value:</span>
                <span class="text-muted">
                  @if($workstation->mouse)
                  {{ $workstation->mouse->local_id }}
                  @else
                  None
                  @endif
                </span>
              </p>
            </div>
            <div class="col-sm-12">
              <input type="checkbox" value="true" id="toggle-mouse" name="mousetag" /> Replace mouse?
              {{ Form::text('mouse', Input::old('mouse'),[
                'id'=>'mouse',
                'class'=>'form-control',
                'placeholder' => 'Mouse Brand',
                'style' => 'display:none;'
              ]) }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-12">
              <button class="btn btn-primary btn-lg btn-block btn-flat" name="create" type="submit"><span class="glyphicon glyphicon-check"></span> Update </button>
            </div>
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
      source: "{{ url('get/supply/mouse/brand') }}"
    });

    $('#toggle-mouse').change(function()
    {
      if($('#toggle-mouse').is(':checked'))
      {
        $('#mouse').show()
      }
      else
      {
        $('#mouse').hide()
      }
    })

    $('#page-body').show();

  });
</script>
@stop
