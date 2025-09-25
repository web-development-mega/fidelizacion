{{-- resources/views/landing.blade.php --}}
<x-public-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
      Programa de fidelización
    </h2>
  </x-slot>

  <div class="max-w-5xl mx-auto px-4">
    <p class="text-slate-700 mb-4">Elige un beneficio para generar tu bono digital.</p>

    {{-- ================= PANEL ESTILO AFICHE ================= --}}
    <section class="rounded-3xl overflow-hidden shadow-xl ring-1 ring-black/5 bg-[#138a84]">
      {{-- Faja superior (marca) --}}
      <div class="px-6 py-4 flex items-center gap-3 text-teal-50">
        {{-- Sustituye por tu logo real si lo tienes --}}
        <div class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
          <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor">
            <circle cx="12" cy="12" r="10"/>
            <path d="M15.5 16.5h-7v-9h2v7h5v2Z" fill="#138a84"/>
          </svg>
        </div>
        <p class="font-semibold tracking-wide">Mega Tecnincentro</p>
      </div>

      {{-- Tiles 2×2 --}}
      <div class="grid grid-cols-2 gap-5 px-6 pb-4">
        {{-- 10% cambio de aceite (clara) --}}
        <button type="button" data-open-modal data-benefit="cambio_aceite" class="tile tile--light">
          <div class="flex items-start gap-4">
            <svg class="h-12 w-12 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
              <path d="M7 3h4l1 2h5a2 2 0 0 1 2 2v2h-2V7H3v12h14v-2h2v2a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a4 4 0 0 1 4-4h2z"/>
              <path d="M20 11h-5v2h5v-2zM20 15h-5v2h5v-2z"/>
            </svg>
            <div>
              <p class="tile-title"><span class="font-semibold">10% cambio</span> de aceite</p>
              <p class="tile-legal">Aplican términos y condiciones.</p>
            </div>
          </div>
        </button>

        {{-- 10% trabajos autorizados (oscura) --}}
        <button type="button" data-open-modal data-benefit="trabajos_autorizados" class="tile tile--dark">
          <div class="flex items-start gap-4">
            <svg class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M21 7l-4 4-3-3 4-4a3 3 0 1 1 3 3zM3 21l6-2 9-9-3-3-9 9-2 6z"/>
            </svg>
            <div>
              <p class="tile-title text-teal-50"><span class="font-semibold">10% trabajos</span><br>autorizados</p>
              <p class="tile-legal text-teal-200/80">Aplican términos y condiciones.</p>
            </div>
          </div>
        </button>

        {{-- 70% mantenimiento preventivo (oscura) --}}
        <button type="button" data-open-modal data-benefit="mega_combo" class="tile tile--dark">
          <div class="flex items-start gap-4">
            <svg class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M5 20h14l1-9H4l1 9zm2-7h10l-.6 5H7.6L7 13zM18 6h-2V4h-2v2H10V4H8v2H6v2h12V6z"/>
            </svg>
            <div>
              <p class="tile-title text-teal-50"><span class="font-semibold">70% mantenimiento</span><br>preventivo</p>
              <p class="tile-legal text-teal-200/80">Aplican términos y condiciones.</p>
            </div>
          </div>
        </button>

        {{-- Revisión GRATIS de batería (clara) --}}
        <button type="button" data-open-modal data-benefit="revision_bateria" class="tile tile--light">
          <div class="flex items-start gap-4">
            <svg class="h-12 w-12 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
              <path d="M7 7h2V5h2v2h2V5h2v2h2a2 2 0 0 1 2 2v7H5V9a2 2 0 0 1 2-2zm0 5h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
            </svg>
            <div>
              <p class="tile-title"><span class="font-semibold">Revisión GRATIS</span> de batería</p>
              <p class="tile-legal">Aplican términos y condiciones.</p>
            </div>
          </div>
        </button>
      </div>

      {{-- Pie tipo afiche --}}
      <div class="bg-[#0f6f6a] px-6 py-5">
        <p class="text-white text-xl font-semibold leading-none">Escanea la imagen</p>
        <p class="text-white/95 text-lg">para redimir</p>
      </div>
    </section>

    <p class="mt-6 text-xs text-slate-500 text-center">
      Selecciona un beneficio para redimir.
    </p>
  </div>

  {{-- ================= MODAL (vanilla JS) ================= --}}
  <div id="voucher-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog" aria-labelledby="modal-title">
    <div class="absolute inset-0 bg-black/60" data-close-modal></div>

    <div class="relative h-full w-full">
      <div class="flex h-full items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
          <div class="flex items-start justify-between px-5 pt-5">
            <h3 id="modal-title" class="text-base font-semibold text-gray-900">
              Solicitar bono — <span id="benefit-label" class="font-normal text-slate-700"></span>
            </h3>
            <button type="button" class="p-2 rounded hover:bg-slate-100" data-close-modal aria-label="Cerrar">✕</button>
          </div>

          <div class="px-5 pb-5">
            {{-- Errores de validación (si los hubiera) --}}
            @if ($errors->any())
              <div class="mb-3 rounded-md bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('claim.store') }}" class="space-y-4" id="claim-form">
              @csrf
              <input type="hidden" name="benefit" id="benefit-input">
              {{-- Honeypot --}}
              <input type="text" name="company" class="hidden" tabindex="-1" autocomplete="off">

              <div>
                <label class="block text-sm font-medium text-gray-700">Fecha tentativa</label>
                <input type="date" name="tentative_date" required
                       value="{{ old('tentative_date') }}"
                       min="{{ now()->toDateString() }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
                @error('tentative_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nombre</label>
                  <input type="text" name="name" value="{{ old('name') }}"
                         class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
                  @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                  <input type="text" name="phone" value="{{ old('phone') }}"
                         class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
                  @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}"
                         class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
                  @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
              </div>

              <details class="rounded-lg border border-slate-200 p-3">
                <summary class="cursor-pointer font-medium">Agregar referidos (opcional, hasta 3)</summary>
                <div class="mt-3 space-y-3">
                  @for ($i = 0; $i < 3; $i++)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                      <input type="text"  name="referrals[{{ $i }}][name]"  value="{{ old("referrals.$i.name") }}"  placeholder="Nombre referido" class="rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                      <input type="text"  name="referrals[{{ $i }}][phone]" value="{{ old("referrals.$i.phone") }}" placeholder="Teléfono"        class="rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                      <input type="email" name="referrals[{{ $i }}][email]" value="{{ old("referrals.$i.email") }}" placeholder="Email"           class="rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                  @endfor
                </div>
              </details>

              <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" class="px-4 py-2 rounded-lg border" data-close-modal>Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                  Generar bono
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ================= ESTILOS LOCALES ================= --}}
  <style>
    .tile{ @apply rounded-2xl p-5 text-left shadow-sm transition; min-height: 132px; }
    .tile--light{ @apply bg-white hover:shadow-md; }
    .tile--dark { @apply bg-slate-900/95 hover:shadow-md; }
    .tile-title{ @apply text-sm text-slate-800; }
    .tile-legal{ @apply mt-2 text-xs; color: rgb(100 116 139); }
  </style>

  {{-- ================= JS MODAL (vanilla) ================= --}}
  <script>
    (function(){
      const modal  = document.getElementById('voucher-modal');
      const label  = document.getElementById('benefit-label');
      const hidden = document.getElementById('benefit-input');
      const BENEFITS = @json(\App\Models\Claim::BENEFITS);

      function openModal(b) {
        hidden.value = b;
        label.textContent = BENEFITS[b] || '';
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      }
      function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }

      document.querySelectorAll('[data-open-modal]').forEach(btn=>{
        btn.addEventListener('click', () => openModal(btn.dataset.benefit));
      });
      modal.addEventListener('click', e=>{
        if (e.target.matches('[data-close-modal]')) closeModal();
      });
      window.addEventListener('keydown', e=>{
        if (e.key === 'Escape') closeModal();
      });

      // Reabrir modal si hubo validación fallida
      @if ($errors->any() && old('benefit'))
        openModal(@js(old('benefit')));
      @endif
    })();
  </script>
</x-public-layout>
