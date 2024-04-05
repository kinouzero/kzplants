<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'kzPlants') }}</title>

    <!-- Javascript -->
    <script src="/npm/jquery/dist/jquery.min.js"></script>
    <script src="/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/npm/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/npm/bootstrap-fileinput/js/plugins/buffer.min.js"></script>
    <script src="/npm/bootstrap-fileinput/js/plugins/filetype.min.js"></script>
    <script src="/npm/bootstrap-fileinput/js/plugins/piexif.min.js"></script>
    <script src="/npm/bootstrap-fileinput/js/locales/{{ app()->getLocale() }}.js"></script>
    <script src="/npm/bootstrap-fileinput/themes/fa6/theme.min.js"></script>
    <script src="/npm/datatables.net/js/dataTables.min.js"></script>
    <script src="/npm/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/npm/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/npm/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="/npm/lightbox2/dist/js/lightbox.min.js"></script>
    <script src="/npm/select2/dist/js/select2.full.min.js"></script>
    <script src="/npm/select2/dist/js/i18n/{{ app()->getLocale() }}.js"></script>
    <script src="/npm/masonry-layout/dist/masonry.pkgd.min.js"></script>
    @vite('resources/js/app.js')

    <!-- Styles -->
    <link href="/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/npm/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet">
    <link href="/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/npm/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/npm/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="/npm/lightbox2/dist/css/lightbox.min.css" rel="stylesheet">
    <link href="/npm/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
  </head>

  <body data-bs-theme="{{ \App\Models\User::getTheme() }}">

    @include('template.header')

    <div class="page-wrapper default-theme">

      @if (auth()->check())
        @include('template.sidebar')
      @endif

      <div class="page-content p-3 {{ auth()->check() ? '' : 'ms-0' }}">
        @if (session('success'))
            @include('template.alert', [
                'color' => 'success',
                'class' => 'd-flex align-items-center',
                'content' => sprintf(
                    '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                    session('success')),
            ])
        @endif

        @if (session('info'))
          @include('template.alert', [
              'color' => 'info',
              'class' => 'd-flex align-items-center',
              'content' => sprintf(
                  '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                  session('info')),
          ])
        @endif

        @if (session('warning'))
          @include('template.alert', [
              'color' => 'warning',
              'class' => 'd-flex align-items-center',
              'content' => sprintf(
                  '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                  session('warning')),
          ])
        @endif

        @if (session('error'))
          @include('template.alert', [
              'color' => 'danger',
              'class' => 'd-flex align-items-center',
              'content' => sprintf(
                  '<span>%s</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>',
                  session('error')),
          ]) @endif

        @yield('content')

      </div>

      @include('template.footer')
    </div>

  </body>

</html>
