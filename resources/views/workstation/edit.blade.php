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
                <li><a href="{{ url('workstation/' . $workstation->id) }}">{{ $workstation->name }}</a></li>
                <li class="active">Update</li>
            </ul>

            @include('errors.alert')

            <form
                method="post"
                action="{{ url('workstation/' . $workstation->id) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_method" value="PUT" />
                
                @include('workstation.partials.create_form')

                <div class="form-group">
                    <button 
                        class="btn btn-primary btn-lg btn-block" 
                        name="update-btn" 
                        type="submit">
                        <span class="glyphicon glyphicon-check"></span> Update
                    </button>
                </div>

            </form>
        </div>      
    </div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
    $(document).ready(function() {

        // targets multiple items for multi selecting   
        // attach multiselect functionality to the element     
        var selectOption = $('.multi-select');
        selectOption.selectpicker();

    });
</script>
@stop
