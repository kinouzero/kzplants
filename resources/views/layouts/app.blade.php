<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'kzPlants') }}</title>

    <!-- Javascript -->
    <script src="/npm/jquery/dist/jquery.min.js"></script>
    <script src="/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/npm/chart.js/dist/chart.umd.js"></script>
    <script src="/npm/datatables.net/js/dataTables.min.js"></script>
    <script src="/npm/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/npm/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/npm/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="/npm/select2/dist/js/select2.full.min.js"></script>
    <script src="/npm/select2/dist/js/i18n/en.js"></script>
    @vite('resources/js/app.js')

    <!-- Styles -->
    <link href="/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/npm/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/npm/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="/npm/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

  </head>

  <body class="bg-light z-2">

      @if (auth()->check())
        @include('layouts.sidebar')
      @endif

      <div class="container p-3 {{ auth()->check() ? '' : 'ms-0' }}">
         @if (auth()->check())
          @include('layouts.header')
        @endif
        @if (session('success'))
            @include('layouts.alert', [
                'color' => 'success',
                'class' => 'd-flex align-items-center',
                'content' => sprintf(
                    '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                    session('success')),
            ])
        @endif

        @if (session('info'))
            @include('layouts.alert', [
                'color' => 'info',
                'class' => 'd-flex align-items-center',
                'content' => sprintf(
                    '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                    session('info')),
            ])
        @endif

        @if (session('warning'))
          @include('layouts.alert', [
              'color' => 'warning',
              'class' => 'd-flex align-items-center',
              'content' => sprintf(
                  '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                  session('warning')),
          ])
        @endif

        @if (session('error'))
          @include('layouts.alert', [
              'color' => 'danger',
              'class' => 'd-flex align-items-center',
              'content' => sprintf(
                  '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                  session('error')),
          ]) @endif

        @yield('content')
      </div>

      @include('layouts.footer')

  </body>

</html>
