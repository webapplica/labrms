<!-- navbar for login in -->
<nav class="navbar navbar-default" style="border: none; border-radius: 0px;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-2" style="border: none;">
                <span class="sr-only">Toggle navigation</span>
                <span class="glyphicon glyphicon-tasks"></span>
            </button>
            <p role="none" class="navbar-brand navbar-brand-zero" href="#">
                <img class="img" src="{{ asset('images/logo/logo-black.png') }}">
            </p>
        </div><!-- end of brand toggle -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-2">
        <!-- navbar left -->
        <ul class="nav navbar-nav">
            <!-- home tab -->
            <li>
                <a href="{{ url('dashboard') }}">
                    <img class="img" 
                        src="{{ asset('images/logo/Dashboard/dashboard-16.png') }}" 
                        style="width: 25px; height: 25px; margin-right: 5px;" /> 
                        Dashboard
                </a>
            </li>

            @include( Auth::user()->getCorrespondingNavigation() );
        </ul>

        @include('layouts.partials.navigation.profile')
    </div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav><!-- /.navbar -->
      