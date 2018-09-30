@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body">
    <legend>
        <h3 class="text-muted">Ticket: <small>{{ $ticket->title }}</small></h3>
    </legend>

    <ol class="breadcrumb">
        <li><a href="{{ url('ticket') }}">Ticket</a></li>
        <li>{{ $ticket->title }}</li>
        <li class="active"></li>
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
        >
        <thead>
            <tr rowspan="2">
                <th class="text-left" colspan="4">Title:  
                    <span style="font-weight: normal">{{ $ticket->title }}</span> 
                </th>
                <th class="text-left" colspan="4">Type:  
                    <span style="font-weight: normal">{{ $ticket->type->name }}</span> 
                </th>
            </tr>

            <tr rowspan="2">
                <th class="text-left" colspan="4">Details:  
                    <span style="font-weight: normal">{{ $ticket->details }}</span>  
                </th>
                <th class="text-left" colspan="4"> Author:
                    <span style="font-weight: normal">{{ $ticket->author }}</span>  
                </th>
            </tr>

            <tr>
                <th>ID</th>
                <th>Details</th>
                <th>Staff Assigned</th>
                <th>Date Created</th>
                <th>Status</th>
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
            order: [ [ 0,"desc" ] ],
            "dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            ajax: base_url,
            columns: [
                { data: "id" },
                { data: "details" },
                { data: "staff_name"},
                { data: "human_readable_date"},
                { data: "status" },
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

        $('div.toolbar').append(
            $('<a>', {
                href: action_url,
                text: action_text,
                class: 'btn btn-primary',
                style: 'margin-right: 5px;',
            }),

            $('<a>', {
                href: transfer_url,
                text: transfer_text,
                class: 'btn btn-default',
                style: 'margin-right: 5px;',
            }),

            $('<a>', {
                href: resolve_url,
                text: resolve_text,
                class: 'btn btn-success',
                style: 'margin-right: 5px;',
            }),

            $('<a>', {
                href: close_url,
                text: close_text,
                class: 'btn btn-danger',
                style: 'margin-right: 5px;',
            }),

            $('<a>', {
                href: reopen_url,
                text: reopen_text,
                class: 'btn btn-info',
                style: 'margin-right: 5px;',
            }),
        )
    })
</script>
@stop
