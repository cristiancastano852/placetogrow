<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @vite('resources/css/app.css')
        @inertiaHead
    </head>
    <body class="font-sans antialiased overflow-hidden">
        @inertia
        <script type="text/javascript">
          window.Laravel = {
              csrfToken: "{{ csrf_token() }}",
              jsPermissions: {!! auth()->user()?->jsPermissions() !!}
          }
      </script>
    </body>
</html>

{{-- <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @vite('resources/js/app.js')
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html> --}}
{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml" />

        <title>Laravel</title>

        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html> --}}
