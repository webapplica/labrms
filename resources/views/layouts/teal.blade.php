<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title : '::' . config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"  />
    
    <!-- Bootstrap -->
    {{ HTML::style(asset('css/jquery-ui.css')) }}
    {{ HTML::style(asset('css/bootstrap.min.css')) }}
    {{ HTML::style(asset('css/sweetalert.css')) }}
    {{ HTML::style(asset('css/dataTables.bootstrap.min.css')) }}
    @yield('style-include')

    {{ HTML::script(asset('js/jquery.min.js')) }}
    {{ HTML::script(asset('js/jquery-ui.js')) }}
    {{ HTML::script(asset('js/bootstrap.min.js')) }}
    {{ HTML::script(asset('js/sweetalert.min.js')) }}
    {{ HTML::script(asset('js/jquery.dataTables.min.js')) }}
    {{ HTML::script(asset('js/dataTables.bootstrap.min.js')) }}
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    @yield('script-include')
  </head>
  <body id="page-top" style="background-color: #264653;">
    @yield('navbar')
    @yield('content')
    @yield('script')
    <script>
      @if( Session::has("success-message") )
          swal("Success!","{{ Session::pull('success-message') }}","success");
      @endif
      @if( Session::has("error-message") )
          swal("Oops...","{{ Session::pull('error-message') }}","error");
      @endif
    </script>
  </body>
</html>
