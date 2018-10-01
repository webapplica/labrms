<!-- navbar right -->
<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle text-capitalize" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <img 
                class="img"
                src="{{ url(Auth::user()->imageUrl) }}"
                style="width: 25px; height: 25px; margin-right: 5px;">

            {{ Auth::user()->full_name }} <span class="caret"></span>
        </a>

        <!-- dropdown items -->
        <ul class="dropdown-menu">
            {{-- <li>{{ link_to('profile','Profile') }}</li> --}}
            {{-- <li>{{ link_to('settings','Password') }}</li> --}}
            @if(Auth::user()->isStaff())
            {{-- <li>{{ link_to('reports','Reports') }}</li> --}}
            @endif
            {{-- <li>{{ link_to('help','Help') }}</li> --}}
            {{-- <li role="separator" class="divider"></li> --}}
            <li>{{ link_to('logout','Logout') }}</li>
        </ul> <!-- end of dropdown items -->

    </li> <!-- end of maintenance dropdown tab -->
</ul><!-- end of navbar right -->