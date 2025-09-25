<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    @isset($header)
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endisset

    <main>
      <div class="py-8">
        {{ $slot }}
      </div>
    </main>

    <footer class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500">
        © {{ date('Y') }} {{ config('app.name') }} · Todos los derechos reservados
      </div>
    </footer>
  </div>
</body>
</html>
