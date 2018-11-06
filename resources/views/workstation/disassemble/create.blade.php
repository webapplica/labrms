@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
    <div class="panel panel-default col-md-offset-3 col-md-6" style="padding: 10px">
        <div class="panel-body">

            <legend>
                <h3 class="text-primary">Workstation</h3>
            </legend>

            <ul class="breadcrumb">       
                <li><a href="{{ url('workstation') }}">Workstation</a></li>
                <li><a href="{{ url('workstation/' . $workstation->id) }}">{{ $workstation->name }}</a></li>
                <li class="active">Disassemble</li>
            </ul>

            <p>{{ __('workstation.disassemble_additional_information') }}</p>
            @include('workstation.partials.list')
            @include('errors.alert')

            <form
                method="post"
                action="{{ url('workstation/' . $workstation->id . '/disassemble') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    {{ Form::label('remarks', 'Additional Remarks') }}

                    {{ Form::textarea('remarks', old('details'), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter additional remarks here...'
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::submit('Submit', [
                        'class'=> 'btn btn-lg btn-block btn-md btn-primary'
                    ]) }}
                </div>

            </form>
        </div>      
    </div>
</div>
@stop
