@extends('layouts.master-blue')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
    <legend class="text-muted">
        <h3>Room Log</h3>
    </legend>

    <ul class="breadcrumb">
        <li><a href="{{ url('room/log') }}">Room</a></li>
        <li class="active">{{ $room->name }}</li>
    </ul>

    <table class="table table-hover table-bordered" id="room-log-table" cellspacing="0" width="100%">
        <thead>
            <tr rowspan="2">
                <th class="text-left" colspan="4">Name:  
                    <span style="font-weight:normal">{{ $room->name }}</span> 
                </th>
                <th class="text-left" colspan="4">Description:  
                    <span style="font-weight:normal">{{ $room->description }}</span> 
                </th>
            </tr>

            <tr rowspan="2">
                <th class="text-left" colspan="4">Category:  
                    <span style="font-weight:normal">{{ $room->category }}</span>  
                </th>
                <th class="text-left" colspan="4">
                    <span style="font-weight:normal"></span> 
                </th>
            </tr>

            <tr rowspan="2">
                <th class="text-center" colspan="12">Log</th>
            </tr>

          <tr>
            <th>Ticket ID</th>
            <th>Ticket Type</th>
            <th>Name</th>
            <th>Details</th>
            <th>Author</th>
            <th>Staff Assigned</th>
            <th>Status</th>
          </tr>

        </thead>

        <tbody>
            @forelse($room->ticket as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->type }}</td>
                    <td>{{ $ticket->name }}</td>
                    <td>{{ $ticket->details }}</td>
                    <td>{{ $ticket->author }}</td>
                    <td>{{ $ticket->user->full_name }}</td>
                    <td>{{ $ticket->status }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
@stop
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    $('#room-log-table').DataTable();
  })
</script>
@stop
