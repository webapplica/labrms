@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body">
    <legend>
        <h3 class="text-muted">Ticket: <small>{{ $ticket->title }}</small></h3>
    </legend>

    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Dashboard</a></li>
        <li><a href="{{ url('ticket') }}">Ticket</a></li>
        <li>{{ $ticket->id }}</li>
    </ol>

    @include('errors.alert')
    
    <table 
        class="table table-hover table-striped table-bordered table-condensed" 
        id="ticket-table" 
        cellspacing="0" 
        width="100%"
        data-base-url="{{ url('ticket/' . $ticket->id) }}"
        data-action-url="{{ url('ticket/' . $ticket->id . '/action') }}"
        data-action-text="{{ __('Add Action') }}"
        data-transfer-url="{{ url('ticket/' . $ticket->id . '/transfer') }}"
        data-transfer-text="{{ __('Transfer') }}"
        data-resolve-url="{{ url('ticket/' . $ticket->id . '/resolve') }}"
        data-resolve-text="{{ __('Resolve') }}"
        data-close-url="{{ url('ticket/' . $ticket->id . '/close') }}"
        data-close-text="{{ __('Close') }}"
        data-reopen-url="{{ url('ticket/' . $ticket->id . '/reopen') }}"
        data-reopen-text="{{ __('Reopen') }}"
        data-action-is-hidden="{{ (Auth::user()->isStaff() && $ticket->isOpenStatus()) ?: "display: none;" }}"
        data-transfer-is-hidden="{{ (Auth::user()->isStaff() && $ticket->isOpenStatus()) ?: "display: none;" }}"
        data-resolve-is-hidden="{{ (Auth::user()->isStaff() && $ticket->isOpenStatus()) ?: "display: none;" }}"
        data-close-is-hidden="{{ (Auth::user()->isStaff() && $ticket->isResolvedStatus()) ?: "display: none;" }}"
        data-reopen-is-hidden="{{ (Auth::user()->isStaff() && $ticket->isClosedStatus()) ?: "display: none;" }}"
        >
        
        <thead>
            <tr rowspan="2">
                <th class="text-left" colspan="3"> Title (Subject):  
                    <span style="font-weight: normal">{{ $ticket->title }}</span> 
                </th>
                <th class="text-left" colspan="3"> Type:  
                    <span style="font-weight: normal">{{ $ticket->type->name }}</span> 
                </th>
            </tr>

            <tr rowspan="2">
                <th class="text-left" colspan="3"> Details:  
                    <span style="font-weight: normal">{{ $ticket->details }}</span>  
                </th>
                <th class="text-left" colspan="3"> Author:
                    <span style="font-weight: normal">{{ $ticket->author }}</span>  
                </th>
            </tr>

            <tr rowspan="2">
                <th class="text-left" colspan="3"> Staff:  
                    <span style="font-weight: normal">{{ $ticket->staff_name }}</span>  
                </th>
                <th class="text-left" colspan="3"> Current Status:
                    <span style="font-weight: normal"><label class="label label-primary">{{ $ticket->status }}</label></span>  
                </th>
            </tr>

            <tr rowspan="2">
                <th colspan="6" class="text-center">***  Activities  ***</th>
            </tr>

            <tr>
                <th class="col-md-1">ID</th>
                <th class="col-md-3">Title</th>
                <th class="col-md-3">Details</th>
                <th class="col-md-1">Added By</th>
                <th class="col-md-1">Date Added</th>
              </tr>
        </thead>
    </table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
    $(document).ready(function ($) {
        var table = $('#ticket-table');
        var base_url = table.data('base-url');

        var dataTable = table.DataTable({
            serverSide: true,
            processing: true,
            select: {
                style: 'single'
            },
            language: {
                searchPlaceholder: "Search..."
            },
            order: [ [ 0, "desc" ] ],
            "dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            ajax: base_url,
            columns: [
                { data: "id" },
                { data: "title" },
                { data: "details" },
                { data: "author"},
                { data: "human_readable_date"},
            ],
        });

        var action_url = table.data('action-url');
        var action_text = table.data('action-text');
        var transfer_url = table.data('transfer-url');
        var transfer_text = table.data('transfer-text');
        var resolve_url = table.data('resolve-url');
        var resolve_text = table.data('resolve-text');
        var close_url = table.data('close-url');
        var close_text = table.data('close-text');
        var reopen_url = table.data('reopen-url');
        var reopen_text = table.data('reopen-text');
        var action_is_hidden = table.data('action-is-hidden');
        var transfer_is_hidden = table.data('transfer-is-hidden');
        var resolve_is_hidden = table.data('resolve-is-hidden');
        var close_is_hidden = table.data('close-is-hidden');
        var reopen_is_hidden = table.data('reopen-is-hidden');

        $('div.toolbar').append(
            $('<a>', {
                href: action_url,
                text: action_text,
                class: 'btn btn-primary',
                style: 'margin-right: 5px;' + action_is_hidden,
            }),

            $('<a>', {
                href: transfer_url,
                text: transfer_text,
                class: 'btn btn-default',
                style: 'margin-right: 5px;' + transfer_is_hidden,
            }),

            $('<a>', {
                href: resolve_url,
                text: resolve_text,
                class: 'btn btn-success',
                style: 'margin-right: 5px;' + resolve_is_hidden,
            }),

            $('<a>', {
                href: close_url,
                text: close_text,
                class: 'btn btn-danger',
                style: 'margin-right: 5px;' + close_is_hidden,
            }),

            $('<a>', {
                href: reopen_url,
                text: reopen_text,
                class: 'btn btn-info',
                style: 'margin-right: 5px;' + reopen_is_hidden,
            }),
        )
    })
</script>
@stop
