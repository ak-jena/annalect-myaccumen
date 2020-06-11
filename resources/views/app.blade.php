<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('/img/favicon.ico') }}" >
    <title>@yield('title')</title>    
    <!-- begin loading assets -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/libs/bootstrap-fileinput/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/bower_components/admin-lte/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet" type="text/css" />    
    <!-- Unused 
    <link href="{{ asset('/bower_components/admin-lte/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    -->
    <link href="{{ asset('/bower_components/admin-lte/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/switchery/dist/switchery.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/bower_components/toastr/toastr.css')}}" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" type="text/css" href="{{ asset('/libs/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css" /> 
    <link href="{{ asset('/css/minerva.css')}}" rel="stylesheet" type="text/css" />     
    <link href="{{ asset('/css/accuen.css')}}" rel="stylesheet" type="text/css" />

    <!-- jquery UI theme -->
    @if(Route::currentRouteName() == 'tg-poc')
      <link href="{{ asset('/css/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    @endif

    <!-- ladda buttons -->
    <link rel="stylesheet" href="{{ asset("/js/ladda-bootstrap-master/dist/ladda-themeless.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- end loading assets -->
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- Those JS to be loaded in HTML header include jQuery and Accuen customised datatables -->
    <script src="{{ asset('/bower_components/admin-lte/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/libs/datatables/accuendt.js') }}"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/mathjs/3.17.0/math.min.js"></script>

    <!-- plugin for targeting grid tables -->
{{--    <link href="{{ asset("/js/tabulator-master/dist/css/semantic-ui/tabulator_semantic-ui.min.css") }}" rel="stylesheet">--}}
    <link href="{{ asset("/js/tabulator-master/dist/css/bootstrap/tabulator_bootstrap.min.css") }}" rel="stylesheet">
{{--    <link href="{{ asset("/js/tabulator-master/dist/css/tabulator_simple.min.css") }}" rel="stylesheet">--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.1/css/tabulator.min.css" rel="stylesheet">--}}
  </head>
  <body class="{{Auth::user()->site_skin}} sidebar-mini <?php if(Auth::user()->menubar_collapse){echo "sidebar-collapse";} ?> @if(Route::currentRouteName() == 'tg-poc') sidebar-collapse @endif">
    <div class="wrapper">
      @include('header')
      @include('sidebar')

        <div class=" @if(Route::currentRouteName() == 'dashboard') margin-top-filter @else margin-top-standard @endif content-wrapper @if(in_array(Route::currentRouteName(), array('dashboard', 'filter-dashboard'))) dashboard-background @endif">
        <section class="content-header">
          <h1>@yield('subtitle')</h1>
          {{--@include('partials.breadcrumbs')--}}
        </section>
        <section class="content">
          @include('partials.alerts.alerts')  
          @yield('content')
        </section>
      </div>
      @include('footer')
      @include('controlsidebar')
    </div>
    <!-- jQuery UI 1.11.2 -->
    {{--<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>--}}

    <script
      src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
      integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
      crossorigin="anonymous"></script>

    <!-- Below lines resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
       $.widget.bridge('uibutton', $.ui.button);
       $.widget.bridge('uitooltip', $.ui.tooltip);
    </script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('/bower_components/admin-lte/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
    <!-- Have to include this after the sweet alert js file -->
    @include('sweet::alert')


    {{--file input--}}
    <script src="{{ asset('/libs/bootstrap-fileinput/js/fileinput.js') }}"></script>

    <script src="{{ asset('/bower_components/admin-lte/plugins/knob/jquery.knob.js') }}" type="text/javascript"></script> 
    <!-- Unused 
    <script src="{{ asset('/bower_components/admin-lte/plugins/morris/morris.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>        
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('/bower_components/admin-lte/plugins/chartjs/Chart.min.js') }}" type="text/javascript"></script> 
    -->
    @if(Route::currentRouteName() !== 'tg-poc')
        <script src="{{ asset('/bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    @endif
    <script src="{{ asset('/bower_components/admin-lte/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/fastclick/fastclick.min.js') }}"></script>
    <script src="{{ asset("/bower_components/admin-lte/plugins/select2/select2.full.min.js") }}"></script>    
    <script src="{{ asset("/bower_components/switchery/dist/switchery.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/bower_components/admin-lte/dist/js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/bower_components/toastr/toast.js')}}"></script>
    <script src="{{ asset('/bower_components/toastr/toastr.js')}}"></script>
    <script src="{{ asset('/js/app.js')}}"></script>

    <!-- my boooking specific js -->
    @if(Route::currentRouteName() == 'workflow')
        <script src="{{ asset('/js/workflow/brief.js')}}"></script>
        <script src="{{ asset('/js/workflow/grid.js')}}"></script>
        <script src="{{ asset('/js/workflow/dsp.js')}}"></script>
        <script src="{{ asset('/js/workflow/io.js')}}"></script>
        <script src="{{ asset('/js/workflow/tag.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'booking')
        <script src="{{ asset('/js/workflow/booking.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'dashboard')
      <script src="{{ asset('/js/dashboard/dashboard.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'live-campaigns')
      <script src="{{ asset('/js/dashboard/live-dashboard.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'completed-campaigns')
      <script src="{{ asset('/js/dashboard/completed-dashboard.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'tg-poc')
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.1/js/tabulator.min.js"></script>
      <script src="{{ asset('/js/targeting-grid/grid-poc.js')}}"></script>
    @endif

    @if(Route::currentRouteName() == 'show-targeting-grid')
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.1/js/tabulator.min.js"></script>
      <script src="{{ asset('/js/targeting-grid/grid.js')}}"></script>
    @endif

    <!-- ladda buttons -->
    <script src="{{ asset('/js/ladda-bootstrap-master/dist/spin.min.js')}}"></script>
    <script src="{{ asset('/js/ladda-bootstrap-master/dist/ladda.min.js')}}"></script>

    @yield('pagejs')
    <!-- Google Analytics Code - Only for production env -->
    @if(env('APP_ENV','') == 'production')
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-79654758-3', 'auto');
            ga('send', 'pageview');
        </script>
    @endif
    <!-- Appnexus pixel test -->
    {{--<iframe width="0" height="0" frameborder="0" src="//secure.adnxs.com/getuid?https://d1ok0ntqh4z7uh.cloudfront.net/1/6390.gif?i={{ Auth::id() }}&a=$UID"></iframe>     --}}
  </body>
</html>
