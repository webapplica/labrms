@extends('layouts.app')

@section('styles-include')
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

            <legend>
                <h3 class="text-primary">Workstation</h3>
            </legend>

            <ul class="breadcrumb">       
                <li><a href="{{ url('workstation') }}">Workstation</a></li>
                <li class="active">Assemble</li>
            </ul>

            @include('errors.alert')

            {{ Form::open(['method' => 'post','route' => array('workstation.store')]) }}

                <div class="form-group">
                    {{ Form::label('os','OS License Key') }}

                    {{ Form::text('os', isset($workstation->oskey) ? $workstation->oskey : old('os'), [
                        'id' => 'os',
                        'class' => 'form-control',
                        'placeholder' => 'OS License Key'
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('systemunit','System Unit') }}
                    {{ Form::text('systemunit', old('systemunit'), [
                        'id' => 'systemunit',
                        'class' => 'form-control',
                        'placeholder' => 'This field accepts a local id for input',
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('monitor', 'Monitor') }}
                    {{ Form::text('monitor',old('monitor'), [
                        'id' => 'monitor',
                        'class' => 'form-control',
                        'placeholder' => 'This field accepts a local id for input',
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('avr', 'AVR') }}
                    {{ Form::text('avr',old('avr'), [
                        'id' => 'avr',
                        'class' => 'form-control',
                        'placeholder' => 'This field accepts a local id for input.'
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('keyboard','Keyboard') }}
                    {{ Form::text('keyboard', old('keyboard'), [
                        'id' => 'keyboard',
                        'class' => 'form-control',
                        'placeholder' => 'This field accepts a local id for input'
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('mouse', 'Mouse: ') }}
                    {{ Form::checkbox('mouse', old('mouse'), [
                        'id' => 'mouse',
                    ]) }}
                </div>

                <div class="form-group">
                    <button 
                        class="btn btn-primary btn-lg btn-block" 
                        name="create" 
                        type="submit">
                        <span class="glyphicon glyphicon-check"></span> Add
                    </button>
                </div>

            {{ Form::close() }}
        </div>      
    </div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
    $(document).ready(function() {

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
