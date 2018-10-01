
@if(session()->has('success-info'))

<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <ul class="list-unstyled" style='margin-left: 10px;'>
        <li class="text-capitalize"><span class="glyphicon glyphicon-ok"></span> {{ session()->pull('success-info') }}</li>
    </ul>
</div>

@endif