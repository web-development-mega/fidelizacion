<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Promo — Mega Tecnicentro (mobile-first fijo)</title>

  <!-- Tailwind con plugin aspect-ratio (para aspect-square) -->
  <script src="https://cdn.tailwindcss.com?plugins=aspect-ratio"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#00ba6d', dark: '#2b2b2b' }
        }
      }
    }
  </script>
  <style>html,body{background:#111}</style>
</head>
<body class="min-h-screen grid place-items-center p-4">

  <!-- CONTENEDOR “MOBILE” FIJO: mismo look en cualquier dispositivo -->
  <section class="w-full max-w-[420px] bg-white rounded-2xl shadow-2xl ring-1 ring-black/10 overflow-hidden">
    <!-- Cabecera con placeholder de imagen -->
    <header class="bg-brand px-5 py-5">
      <!-- Reemplaza este div por tu <img src="..."> si lo deseas -->
      <div class="w-full aspect-[7/2] bg-white/90 rounded-xl ring-1 ring-black/10 grid place-items-center">
        <span class="text-center text-[13px] font-medium text-gray-700">
          Placeholder de imagen<br>
          <span class="font-normal opacity-70">ej. logo/cabecera (≈560×160)</span>
        </span>
      </div>
    </header>

    <!-- Grid de beneficios (SIEMPRE 2×2) -->
    <div class="bg-brand px-5 pb-5 pt-3">
      <div class="grid grid-cols-2 gap-3">
        <!-- Card 1 -->
        <article class="bg-white text-gray-900 rounded-xl shadow-sm ring-1 ring-black/5 p-3 aspect-square flex">
          <div class="m-auto text-center space-y-1.5 leading-tight">
            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9 text-gray-700" aria-hidden="true">
              <path fill="currentColor" d="M15 4h-2l-2 2H7a2 2 0 0 0-2 2v3h2V8h4l2-2h2a2 2 0 0 1 2 2v2h2V8a4 4 0 0 0-4-4Zm-2 10a4 4 0 1 0 8 0h-8Zm-10 2a2 2 0 1 0 4 0H3Z"/>
            </svg>
            <p class="text-[17px] font-extrabold">10% <span class="font-bold">cambio</span></p>
            <p class="text-[17px] font-extrabold">de aceite</p>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="bg-dark text-white rounded-xl shadow-sm ring-1 ring-black/20 p-3 aspect-square flex">
          <div class="m-auto text-center space-y-1.5 leading-tight">
            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9 text-white" aria-hidden="true">
              <path fill="currentColor" d="M22 19v-2l-6.6-6.6a5 5 0 1 0-2.8 2.8L19 19h3Zm-14-9a3 3 0 1 1 6 0a3 3 0 0 1-6 0Z"/>
            </svg>
            <p class="text-[17px] font-extrabold">10% trabajos</p>
            <p class="text-[17px] font-extrabold">autorizados</p>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="bg-dark text-white rounded-xl shadow-sm ring-1 ring-black/20 p-3 aspect-square flex">
          <div class="m-auto text-center space-y-1.5 leading-tight">
            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9 text-white" aria-hidden="true">
              <path fill="currentColor" d="M4 20v-2h2v2H4Zm14 0v-2h2v2h-2ZM6 14h12l1 2H5l1-2Zm1-6h10l2 6H5l2-6Zm2-2h6l1 2H8l1-2Z"/>
            </svg>
            <p class="text-[17px] font-extrabold">70% <span class="font-bold">mantenimiento</span></p>
            <p class="text-[17px] font-extrabold">preventivo</p>
          </div>
        </article>

        <!-- Card 4 -->
        <article class="bg-white text-gray-900 rounded-xl shadow-sm ring-1 ring-black/5 p-3 aspect-square flex">
          <div class="m-auto text-center space-y-1.5 leading-tight">
            <svg viewBox="0 0 24 24" class="mx-auto h-9 w-9 text-gray-700" aria-hidden="true">
              <path fill="currentColor" d="M4 8h1V6h2v2h10V6h2v2h1a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Zm5 4h2l-1 2h2l-3 4v-3H7l2-3Z"/>
            </svg>
            <p class="text-[17px] font-extrabold">Revisión <span class="font-black">GRATIS</span></p>
            <p class="text-[17px] font-extrabold">de batería</p>
          </div>
        </article>
      </div>

      <p class="mt-5 text-center text-white/90 text-[12px]">Aplican términos y condiciones*</p>
    </div>

    <!-- Llamado inferior -->
    <footer class="px-5 py-4 text-center">
      <p class="text-brand font-extrabold text-[20px] leading-tight">Escanea la imagen</p>
      <p class="text-gray-900 font-black text-[18px] -mt-0.5">para redimir</p>
    </footer>
  </section>

</body>
</html>
