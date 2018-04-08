@extends('layouts.master-blue')

@section('style')
<link href="{{ asset('css/select.bootstrap.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
@include('modal.ticket.transfer')
@if($ticket->type->name == 'Complaint')
@include('modal.ticket.resolve') 
@endif
@endif
<div class="container-fluid" id="page-body">
  <div class="col-md-12">
    <div class="panel panel-body ">

      <div class='col-md-12'>
          <legend><h3 class="text-muted">Ticket {{ $ticket->id }} History</h3></legend>
          <ol class="breadcrumb">
              <li><a href="{{ url('ticket') }}">Ticket</a></li>
              <li>{{ $id }}</li>
              <li class="active">History</li>
          </ol>
          @include('errors.alert')
          <table class="table table-hover table-striped table-bordered table-condensed" id="ticketTable" cellspacing="0" width="100%">
            <thead>
                  <tr rowspan="2">
                      <th class="text-left" colspan="4">Ticket Name:  
                        <span style="font-weight:normal">{{ $ticket->title }}</span> 
                      </th>
                      <th class="text-left" colspan="4">Ticket Type:  
                        <span style="font-weight:normal">{{ $ticket->type->name }}</span> 
                      </th>
                  </tr>
                  <tr rowspan="2">
                      <th class="text-left" colspan="4">Details:  
                        <span style="font-weight:normal">{{ $ticket->details }}</span>  
                      </th>
                      <th class="text-left" colspan="4"> Author:
                        <span style="font-weight:normal">{{ $ticket->author }}</span>  
                      </th>
                  </tr>
                    <tr>
                <th>Ticket ID</th>
                <th>Details</th>
                <th>Staff Assigned</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Comment</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div><!-- Row -->
</div><!-- Container -->
@stop
@section('script')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/dataTables.select.min.js') }}"></script>
<script>
  $(document).ready(function(){

    var table = $('#ticketTable').DataTable({
      select: {
        style: 'single'
      },
      language: {
          searchPlaceholder: "Search..."
      },
      order: [ [ 0,"desc" ] ],
      "dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
     "processing": true,
      ajax: "{{ url('ticket') }}/" + {{ $id }},
      columns: [
          { data: "id" },
          { data: "details" },
          { data: "staff_name"},
          {data: "parsed_date"},
          { data: "status" },
          { data: "comments" }
      ],
    });

    $('.toolbar').html(`

        @if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

          @if( $ticket->status == 'open' || $ticket->status == 'Open' )

            @if($ticket->type->name == 'Complaint' || $ticket->type->name == 'Incident' )

            <button type="button" id="assign" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-share-alt"></span> Assign </button>

            <button id="resolve" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-check"></span> Create an Action</button>
            @endif

          <button type="button" id="close" class="btn btn-danger btn-sm" data-id="{{ $ticket->id }}">
            <span class="glyphicon glyphicon-off"></span> Close
          </button>

          @endif

          @if( $ticket->status == 'closed' || $ticket->status == 'Closed' )

          <button type="button" id="reopen" class="btn btn-info btn-sm" data-id="{{ $ticket->id }}">
            <span class="glyphicon glyphicon-off"></span> Reopen
          </button>

          @endif

        @endif
    `)

    @if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

      $('#assign').click( function () {
          $('#transfer-id').val('{{ $ticket->id }}')
          $('#transfer-date').text(moment('{{ $ticket->date }}').format("dddd, MMMM Do YYYY, h:mm a"))
          $('#transfer-tag').text('{{ $ticket->tag }}')
          $('#transfer-title').text('{{ $ticket->title }}')
          $('#transfer-details').text('{{ $ticket->details }}')
          $('#transfer-author').text('{{ $ticket->author }}')
          $('#transfer-assigned').text('{{ $ticket->staffassigned }}')
          $('#transferTicketModal').modal('show')
      } );

      @if( $ticket->status == 'open' || $ticket->status == 'Open' )

       @if($ticket->type->name == 'Complaint' || $ticket->type->name == 'Incident' )

          $('#resolve').click( function () {
                $('#resolve-id').val('{{ $ticket->id }}');
                  tag = '{{ $ticket->tag }}' 
                if(tag.indexOf('PC') !== -1 || tag.indexOf('Item') !== -1)
                {
                  if(tag.indexOf('PC') !== -1)
                  {
                    $('#item-tag').val(tag.substr(4))
                  }

                  if(tag.indexOf('Item') !== -1)
                  {
                    $('#item-tag').val(tag.substr(6))
                  }

                  $('#resolve-equipment').show()
                }
                else
                {
                  $('#item-tag').val("")
                  $('#resolve-equipment').hide()
                }

                $('#resolveTicketModal').modal('show')
          } );
          
        @endif

      $('#close').on( 'click', function () {
          id = $(this).data('id')
          swal({
            title: "Are you sure?",
            text: "Do you really want to close the ticket?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, close it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm){
            if (isConfirm) {
                $.ajax({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type: 'delete',
                  url: '{{ url("ticket") }}' + "/" + id,
                  data: {
                    'id': id
                  },
                  dataType: 'json',
                  success: function(response){
                    if(response.length > 0){
                      swal('Operation Successful','Ticket has been closed','success')
                    }else{
                      swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
                    }

                    table.ajax.reload()
                  },
                  error: function(){
                    swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
                  }
                });
              } else {
                swal("Cancelled", "Operation Cancelled", "error");
              }
          })
      } );

      @endif

      @if( $ticket->status == 'closed' || $ticket->status == 'Closed' )

      $('#reopen').on( 'click', function () {
        id = $(this).data('id')
          swal({
            title: "Are you sure?",
            text: "Do you really want to reopen the ticket.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, reopen it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm){
            if (isConfirm) {
        $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
          type: 'post',
          url: '{{ url("ticket") }}' + "/" + id + '/reopen',
          data: {
            'id': id
          },
          dataType: 'json',
          success: function(response){
            if(response.length > 0){
              swal('Operation Successful','Ticket has been reopened','success')
                  table.ajax.reload().order([ 0, "desc" ]);
            }else{
              swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
            }
          },
          error: function(){
            swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
          }
        });
            } else {
              swal("Cancelled", "Operation Cancelled", "error");
            }
          })
      } );

      @endif
    @endif
  })
</script>
@stop
