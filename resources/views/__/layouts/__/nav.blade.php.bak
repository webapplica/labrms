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
                <a 
                    href="{{ url('dashboard') }}">
                    <img 
                        class="img" 
                        src="{{ asset('images/logo/Dashboard/dashboard-16.png') }}" 
                        style="width: 25px; height: 25px; margin-right: 5px;" /> 
                        Dashboard
                </a>
            </li>

            @if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

            <!-- maintenance dropdown tab -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img 
                    class="img" 
                    src="{{ asset('images/logo/IS/infosys-16.png') }}" 
                    style="width: 25px;height: 25px;margin-right: 5px;" /> 
                    Information System 
                    <span class="caret"></span>
                </a>
                <!-- dropdown items -->
                <ul class="dropdown-menu">
                    <!-- maintenance tab -->
                    <li>{{ link_to('academicyear','Academic Year') }}</li>
                    <li>{{ link_to('maintenance/activity','Maintenance Activities') }}</li>
                    <li>{{ link_to('event','Event') }}</li>
                    <li>{{ link_to('faculty','Faculty') }}</li>
                    @if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
                    <li>{{ link_to('item/type','Item Types') }}</li>
                    <li>{{ link_to('room/category','Laboratory Room Category') }}</li>
                    <li>{{ HTML::link('schedule','Laboratory Schedule') }}</li>
                    <li>{{ link_to('room','Laboratory Room') }}</li>
                    <li>{{ link_to('purpose','Reservation Purpose') }}</li>
                    <li>{{ link_to('semester','Semester') }}</li>
                    <li>{{ link_to('unit','Unit') }}</li>
                    @endif
                    <li>{{ link_to('software','Software') }}</li>
                    <li>{{ link_to('software/type','Software Types') }}</li>
                </ul> <!-- end of dropdown items -->
            </li> <!-- end of maintenance dropdown tab -->

            <!-- dropdown tab -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img class="img" src="{{ asset('images/logo/Transaction/transaction-16.png') }}" style="width:25px;height:25px;margin-right: 5px;"> Transaction <span class="caret"></span></a>
                <!-- dropdown items -->
                <ul class="dropdown-menu">
                    <!-- reservation dropdown tab -->
                    <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Reservation </a>
                    <!-- dropdown items -->
                    <ul class="dropdown-menu">
                        <!-- create tab -->
                        <li>{{ link_to('reservation/create','Create') }}</li>
                        <!-- view all reservation -->
                        <li>{{ HTML::link('reservation/','List') }}</li>
                        <!-- view all reservation items -->
                        <li>{{ HTML::link('reservation/items/list','Items') }}</li>
                    </ul> <!-- end of dropdown items -->
                    </li> <!-- end of reservation dropdown tab -->

                    <!-- ticket dropdown tab -->
                    <li>
                    <a href="{{ url('ticket') }}">Ticketing</a>
                    </li> <!-- end of ticket dropdown tab -->

                    <!-- ticket dropdown tab -->
                    <li>
                    <a href="{{ url('receipt') }}">Receipts</a>
                    </li> <!-- end of ticket dropdown tab -->

                    <!-- inventory dropdown tab -->
                    <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Inventory
                    </a>
                    <!-- dropdown items -->
                    <ul class="dropdown-menu">
                        <!-- tenant inventory tab -->
                        <li>{{ link_to('inventory','Item') }}</li>
                        <li>{{ link_to('workstation','Workstation') }}</li>
                        <li>{{ HTML::link('inventory/room','Room') }}</li>
                    </ul> <!-- end of dropdown items -->
                    </li> <!-- end of inventory dropdown tab -->

                    <!-- ticket dropdown tab -->
                    <li>
                    <a href="{{ url('item/profile') }}">Items Profile</a>
                    </li> <!-- end of ticket dropdown tab -->

                    <!-- inventory dropdown tab -->
                    <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Log
                        </a>
                        <!-- dropdown items -->
                        <ul class="dropdown-menu">
                            <!-- tenant inventory tab -->
                            <li>{{ link_to('lostandfound','Lost And Found') }}</li>
                            <li>{{ link_to('lend','Lent Items') }}</li>
                            <li>{{ link_to('lend/supply','Lent Supply') }}</li>
                            <li>{{ link_to('room/log','Room') }}</li>
                        </ul> <!-- end of dropdown items -->
                    </li> <!-- end of inventory dropdown tab -->

                </ul> <!-- end of dropdown items -->
            </li> <!-- end of dropdown tab -->

            @if(Auth::user()->accesslevel == 0)
            <!-- utilities dropdown tab -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img 
                    class="img" 
                    src="{{ asset('images/logo/Utilities/utilities-16.png') }}" 
                    style="width: 25px; height: 25px; margin-right: 5px;"> 
                    Utilities 
                    <span class="caret"></span>
                </a>
                <!-- dropdown items -->
                <ul class="dropdown-menu">
                    <!-- utilities tab -->
                    <li>{{ link_to('account','Accounts') }}</li>
                    @if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1)
                        <li>{{ link_to('admin/backup','Backup and Restore') }}</li>
                    @endif
                </ul> <!-- end of dropdown items -->
            </li> <!-- end of utilities dropdown tab -->
            @endif

            @endif

            @if(Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4)
            <!-- home tab -->
            <li>
                <a 
                    href="{{ url('reservation/create') }}">
                    <img 
                        class="img" 
                        src="{{ asset('images/logo/Reservation/reservation-16.png') }}" 
                        style="width:25px;height:25px;margin-right: 5px;" /> 
                        Reservation
                </a>
            </li>
            <!-- home tab -->
            <li>
            <a href="{{ url('ticket') }}"><img class="img" src="{{ asset('images/logo/Ticket/ticket-16.png') }}" style="width:25px;height:25px;margin-right: 5px;"> 
                Complaint
            </a>
            </li>

            @endif

        </ul>
        <!-- navbar right -->
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
            <a href="#" class="dropdown-toggle text-capitalize" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <img 
                class="img"
                src="{{ Auth::user()->imageUrl }}"
                style="width:25px;height:25px;margin-right: 5px;">{{{ Auth::user()->firstname }}} {{{ Auth::user()->lastname }}}<span class="caret"></span></a>
            <!-- dropdown items -->
            <ul class="dropdown-menu">
                <li>{{ link_to('profile','Profile') }}</li>
                <li>{{ link_to('settings','Password') }}</li>
                @if(Auth::user()->isStaff())
                <li>{{ link_to('reports','Reports') }}</li>
                <li>{{ link_to('help','Help') }}</li>
                @endif
                <li role="separator" class="divider"></li>
                <li>{{ link_to('logout','Logout') }}</li>
            </ul> <!-- end of dropdown items -->
            </li> <!-- end of maintenance dropdown tab -->

        </ul><!-- end of navbar right -->
    </div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav><!-- /.navbar -->
      