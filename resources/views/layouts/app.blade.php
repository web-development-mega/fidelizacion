<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name', 'Fidelización'))</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-slate-900 antialiased">
  <main class="min-h-screen">
    @yield('content')
  </main>
  @stack('scripts')
</body>
</html>

