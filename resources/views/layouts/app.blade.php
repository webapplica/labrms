<!DOCTYPE html>
<html lang="{{ config('app.lang') }}">
  <head>
    <meta charset="{{ config('app.charset') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }} {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    
    <!-- styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"  />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nav-styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link href="{{ asset('css/jquery-clockpicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-clockpicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet" />

    @yield('styles-prepend')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if(isset($isPlainBackground) && $isPlainBackground)
        {{-- display body color on page load --}}
        <style type="text/css">
            body {
                background-color: {{ isset($bodyBackgroundColor) ? $bodyBackgroundColor : '' }};
            }
        </style>
    @else
        {{-- display an image --}}
        <style type="text/css">
            body {
                background-color: #8e8e8e;
                background-size: auto auto;
                background-image: url('{{ asset("images/background_v3.jpg")  }}');
            }
        </style>
    @endif

    @yield('styles-include')

    <!-- scripts -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

    {{-- notification functionality --}}
    <script type="text/javascript">

        // notification functionality for the system
        // can be added with the different notification provider
        var notify = {

          // calls sweetalert success message
          // if the title is not provided, the system
          // will use the default title message
          success: function (message, title = 'Success!') {
              swal(title, message, 'success');
          },

          // calls sweetalert error message
          // if the title is not provided, the system
          // will use the default title message
          error: function(message, title = 'Oops...') {
              swal(title, message, 'error');
          }   
        };
    </script>
    {{-- notification functionality --}}

    {{-- additional scripts needed before other scripts on the body --}}
    @yield('scripts-prepend')
    {{-- additional scripts needed before other scripts on the body --}}

  </head>
  <body 
    @if( session()->has("success-message") )
        data-notification-type="success"
        data-notification-message="{{ session()->pull('success-message') }}"
    @elseif( session()->has("error-message") )
        data-notification-type="error"
        data-notification-message="{{ session()->pull('error-message') }}"
    @endif
    id="page-body" 
    class="page page-body page-parent-container">

    {{-- styles on the first part of the body --}}
    @yield('styles-body-prepend')
    {{-- styles on the first part of the body --}}

    {{-- include the partial navigation bar --}}
    @if(Auth::check())
        <nav id="navigation" class="page-navigation">
            @include('layouts.partials.nav')
        </nav>
    @endif
    {{-- include the partial navigation bar --}}

    {{-- display the content page --}}
    <section id="content" class="page-content">
        @yield('content')
    </section>
    {{-- display the content page --}}

    {{-- adding scripts after the page loads --}}
    @yield('scripts-append')
    {{-- adding scripts after the page loads --}}

    {{-- footer section --}}
    <footer id="footer" class="page-footer">
        @include('layouts.partials.footer')
    </footer>
    {{-- footer section --}}

    {{-- additional scripts --}}
    @yield('scripts-include')
    {{-- additional scripts --}}
    
    {{-- scripts used globally --}}
    <script type="text/javascript">

        // creates a variable with the function as a global
        // that is used throughout each and every template
        var global = {

            // triggers a specific alert type depending on the 
            // passed data from the server whether it be a
            // success message or an error message
            alert: function () {
                bodyElement = $('#page-body');
                notificationType = bodyElement.data('notification-type');
                notificationMessage =  bodyElement.data('notification-message');
                
                // if the notification type is success
                // triggers a success notification message
                if(notificationType == 'success') {
                    notify.success(notificationMessage);
                }

                // if the notification type is error
                // triggers an error notification message
                else if(notificationType == 'error') {
                    notify.error(notificationMessage);
                }
            },
        }
        
        // run the alert function that triggers a notification
        // from sent data of a php script
        global.alert();
    </script>
  </body>
</html>
