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

                @include('workstation.partials.form')

            {{ Form::close() }}
        </div>      
    </div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
    $(document).ready(function() {

        // initialize the columns to be used for autocomplete functionality
        // fetch the url from the data attribute of the said element
        var targetForAutoComplete = $('.has-autocomplete');
        var autocompleteUrl = targetForAutoComplete.data('url');
        
        // targets the specific column and triggers autocomplete functionality based
        // on the data given by the user
        targetForAutoComplete.autocomplete({ source: autocompleteUrl });

    });
</script>
@stop
