<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Tailwind / Vite (ajusta a tu setup) --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- SweetAlert2 (CDN estable) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>

    <style>
      /* Ajustes visuales de los botones del modal */
      .swal2-popup{
        border-radius:16px !important;
      }
      .swal2-confirm{
        background:#10b981 !important; /* brand */
        border-radius:10px !important;
        box-shadow:none !important;
      }
      .swal2-cancel{
        background:#e5e7eb !important;
        color:#111 !important;
        border-radius:10px !important;
        box-shadow:none !important;
      }
      .swal2-title{
        font-weight:800 !important;
      }
      /* Inputs consistentes */
      .swal2-html-container input{
        border-radius:.75rem; border:1px solid #d1d5db; padding:.65rem .8rem;
        width:100%;
        outline: none;
      }
      .swal2-html-container input:focus{
        border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15);
      }
      .mini-label{font-size:.82rem;color:#374151;font-weight:600;margin:.6rem 0 .25rem}
      .row-2{display:grid;grid-template-columns:1fr 1fr;gap:.6rem}
      .muted{font-size:.78rem;color:#6b7280}
      .groupbox{
        background:#f8fafc; border:1px dashed #d1d5db; border-radius:12px; padding:.75rem;
        margin-top:.65rem
      }
    </style>
    @stack('head')
  </head>
  <body class="font-sans antialiased text-slate-900">
    {{-- Tu navegación / layout general --}}
    <div class="min-h-screen">
      @yield('content')
    </div>

    @stack('scripts')
  </body>
</html>
