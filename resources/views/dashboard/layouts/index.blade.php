<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PPIC-DELIVERY</title>
    
    <!-- Auto-refresh halaman setiap 10 menit (600 detik) - Tidak merusak UI -->
    <meta http-equiv="refresh" content="600">

    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/img/kaiadmin/logosakura.jpeg') }}" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">


    <!-- Fonts - Using local assets (no internet required) -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}">

    <style>
        /* System fonts fallback - no internet required */
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .fw-bold,
        .fw-semibold,
        .fw-medium {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>

    <style>
        /* Pagination kecil dan rapi */

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.25;
            border-radius: 0.25rem;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            background-color: #1572e8;
            border-color: #1572e8;
        }

        .pagination .page-link:hover {
            background-color: #f1f1f1;
        }

        .pagination .page-item.disabled .page-link {
            cursor: not-allowed;
        }
    </style>

</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        @include('dashboard.layouts.sidebar')
        <!-- End Sidebar -->


        <div class="main-panel">

            @include('dashboard.layouts.header')

            <div class="container">
                @yield('content')
            </div>

            @include('dashboard.layouts.footer')

        </div>

    </div>


    <!-- CORE JS -->

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>


    <!-- Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Kaiadmin -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script>
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23,125,255,0.14)"
        });

        $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#f3545d",
            fillColor: "rgba(243,84,93,.14)"
        });

        $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#ffa534",
            fillColor: "rgba(255,165,52,.14)"
        });
    </script>



<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
{{-- <script>
    // Setup Swal alias untuk compatibility
    window.Swal = window.Sweetalert2 || window.Swal;
    
    // Test SweetAlert2 - akan dijalankan setelah page load
    document.addEventListener('DOMContentLoaded', function() {
        var Alert = window.Swal || window.Sweetalert2;
        if (Alert) {
            Alert.fire({
                title: 'SweetAlert2 Test',
                text: 'Alert ini menggunakan SweetAlert2 lokal!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            console.error('SweetAlert2 not loaded - check console');
        }
    });
</script> --}}

    @stack('scripts')

</body>

</html>
