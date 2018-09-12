<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ( isset($title) ? $title . '::' : '' ) . config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"  />
    
    <!-- styles -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}" />
    @yield('style-include')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if(isset($isLoginPage) && $isLoginPage)
    {{-- display body color on page load --}}
    <style type="text/css">
      body {
        background-color: {{ isset($bodyColor) ? $bodyColor : '' }};
      }
    </style>
    @else
    {{-- display an image --}}
    <style>
        body {
          background-size: auto auto;
          background-image: url('{{ asset("images/background_v3.jpg")  }}');
        }
      </style>
    @endif

    @yield('styles-include')

    <!-- scripts -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    @yield('scripts-include')

  </head>
  <body id="page-body" class="page page-body page-parent-container">
    @yield('styles-prepend')
    {{-- include the partial navigation bar --}}
    <nav id="navigation" class="page-navigation">
      @include('partials.nav')
    </nav>
    {{-- display the content page --}}
    <section id="content" class="page-content">
      @yield('body-content')
    </section>
    {{-- adding scripts after the page loads --}}
    @yield('scripts-append')

    {{-- footer section --}}
    <footer id="footer" class="page-footer">
      @include('partials.footer')
    </footer>

    {{-- additional scripts used globally --}}
    <script type="text/javascript">
      @if( Session::has("success-message") )
          swal("Success!","{{ Session::pull('success-message') }}","success");
      @endif
      @if( Session::has("error-message") )
          swal("Oops...","{{ Session::pull('error-message') }}","error");
      @endif
    </script>
  </body>
</html>
