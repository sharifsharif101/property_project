<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'عنوان الصفحة')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/iCheck/flat/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker-bs3.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

    <!-- RTL & Fonts -->
    <link rel="stylesheet" href="{{ asset('adminlte/fonts/fonts-fa.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/bootstrap-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/rtl.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}">

    <!-- Vite (إذا كنت تستخدم Tailwind أو غيره) -->
    @vite('resources/css/app.css')

    <!-- دعم HTML5 للمتصفحات القديمة -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">



        @include('layouts.nav')
        @include('layouts.sidebar')
        
        <div class="content-wrapper">
 
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>

    <!-- Bootstrap -->
    <script src="{{ asset('adminlte/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset('adminlte/plugins/morris/morris.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('adminlte/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- jvectormap -->
    <script src="{{ asset('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

    <!-- jQuery Knob Chart -->
    <script src="{{ asset('adminlte/plugins/knob/jquery.knob.js') }}"></script>

    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- datepicker -->
    <script src="{{ asset('adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

    <!-- Slimscroll -->
    <script src="{{ asset('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>

    <!-- FastClick -->
    <script src="{{ asset('adminlte/plugins/fastclick/fastclick.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/js/app.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('.example2').DataTable({
                paging: true,
                lengthChange: false,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false
            });
        });
    </script>

    <!-- AdminLTE dashboard demo -->
    <script src="{{ asset('adminlte/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('adminlte/js/demo.js') }}"></script>
</body>
</html>