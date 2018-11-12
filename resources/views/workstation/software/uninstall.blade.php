@extends('layouts.app')

@section('content')
<div class="container col-md-offset-2 col-md-8" id="page-body">
	<div class="panel panel-default">
		<div class="panel-body">
			<legend>
				<h3 class="text-muted">Workstation: {{ $workstation->name }}</h3>
			</legend>

			<ul class="breadcrumb">
				<li><a href="{{ url('workstation') }}">Workstation</a></li>
				<li><a href="{{ url('workstation/' . $workstation->id . '/software') }}">{{ $workstation->name }}</a></li>
				<li class="active">{{ __('Uninstall') }}</li>
            </ul>
            
            <form
                method="post"
                action="{{ url("workstation/$workstation->id/software/$software->id/uninstall") }}"
                >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />   
                <div class="form-group">
                    <label for="remarks">{{ __('Additional Remarks') }}</label>
                    <p class="text-muted">{{ __('workstation.warning_before_uninstalling', [
                        'software' => $software->name,
                        'workstation' => $workstation->name,
                    ]) }}</p>       
                    <p>{{ __('workstation.note_before_uninstalling') }}</p>
                    <textarea
                        id="reamrks"
                        class="form-control"
                        name="remarks"
                        rows="12"
                        placeholder="{{ __('Input text here...') }}"
                        >{{ old('remarks') }}</textarea>
                </div>

                <div class="form-group">
                    <button 
                        type="submit"
                        class="btn btn-lg btn-primary btn-block"
                        >{{ __('Uninstall') }}</button>
                </div>
            </form>
		</div>
	</div>
</div>
@stop