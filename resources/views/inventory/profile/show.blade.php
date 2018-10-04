@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-offset-3 col-md-6" style="padding: 20px 40px; background-color: white;">

    <legend>
        <h3 style="color:#337ab7;">
            <span id="form-name">Item: {{ $item->local_id }}</span>
        </h3>
    </legend>

    <ul class="breadcrumb">
        <li><a href="{{ url('inventory') }}">Inventory</a></li>
        <li><a href="{{ url('inventory/' . $item->inventory->id) }}">{{ $item->inventory->summarized_name }}</a></li>
        <li>Item</li>
        <li class="active">{{ $item->local_id }}</li>
    </ul>

    <div style="margin: 10px 0;">
        <a href="{{ url('item/' . $item->id . '/edit') }}" class="btn btn-primary">Information Update</a>
        <a href="{{ url('item/' . $item->id . '/activity/add') }}" class="btn btn-default">Add Activity</a>
        <a href="{{ url('item/' . $item->id . '/activity/status-update') }}" class="btn btn-warning">Status Update</a>
        <a href="{{ url('item/' . $item->id . '/activity/reservation-update') }}" class="btn btn-success">Reservation Status Update</a>
        <a href="{{ url('item/' . $item->id . '/activity/condemn') }}" class="btn btn-danger">Condemn</a>
    </div>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Basic Information</div>
        
        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Main Property Number: </strong> {{ $item->property_number }}
            </li>
            <li class="list-group-item">
                <strong>Local ID: </strong> {{ $item->local_id }}
            </li>
            <li class="list-group-item">
                <strong>Serial ID: </strong> {{ $item->serial_number }}
            </li>
            <li class="list-group-item">
                <strong>Current Location: </strong> {{ $item->location }}
            </li>
            <li class="list-group-item">
                <strong>Brand: </strong> {{ $item->inventory->brand }}
            </li>
            <li class="list-group-item">
                <strong>Model: </strong> {{ $item->inventory->model }}
            </li>
            <li class="list-group-item">
                <strong>Type: </strong> {{ $item->inventory->type->name }}
            </li>
            <li class="list-group-item">
                <strong>Warranty: </strong> {{ $item->warranty }}
            </li>
            <li class="list-group-item">
                <strong>Status: </strong> {{ $item->status }}
            </li>
        </ul>
    </div>
    
    <legend>
        Activities
    </legend>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="col-md-1">ID</th>
                <th class="col-md-2">Title</th>
                <th class="col-md-3">Details</th>
                <th class="col-md-2">Added By</th>
                <th class="col-md-2">Date Added</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->details }}</td>
                    <td>{{ $ticket->author }}</td>
                    <td>{{ $ticket->human_readable_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan=5 class="text-center">No details found</td>
                </tr>
            @endforelse
    </table>
</div>
@stop
