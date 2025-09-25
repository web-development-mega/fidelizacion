@extends('layouts.app')

@section('title', 'Programa de fidelización')

@section('content')
<div class="viewport py-8">
  <section class="square mx-auto" aria-label="Promo Mega Tecnicentro">

    {{-- HEADER (placeholder: reemplaza por tu imagen de cabecera) --}}
    <header class="header">
      <div class="ph">
        <div>
          Coloca tu imagen aquí <br>
          <small>ej. logo/cabecera (≈560×160)</small>
        </div>
      </div>
    </header>

    {{-- MAIN (cuadrantes 2×2) --}}
    <main class="main">
      <div class="gridwrap">
        <div class="gridbox">
          {{-- 1) Cambio de aceite (clara) --}}
          <button type="button" class="tile light" data-open-modal data-benefit="cambio_aceite" aria-label="10% cambio de aceite">
            <div>
              <img class="ico" alt="Cambio de aceite" src="{{ asset('img/icons/oil.png') }}">
              <p class="h">10% <span>cambio</span></p>
              <p class="h">de aceite</p>
            </div>
          </button>

          {{-- 2) Trabajos autorizados (oscura) --}}
          <button type="button" class="tile dark" data-open-modal data-benefit="trabajos_autorizados" aria-label="10% trabajos autorizados">
            <div>
              <img class="ico" alt="Trabajos autorizados" src="{{ asset('img/icons/wrench-white.png') }}">
              <p class="h">10% trabajos</p>
              <p class="h"><span>autorizados</span></p>
            </div>
          </button>

          {{-- 3) Mantenimiento preventivo (oscura) --}}
          <button type="button" class="tile dark" data-open-modal data-benefit="mega_combo" aria-label="70% mantenimiento preventivo">
            <div>
              <img class="ico" alt="Mantenimiento preventivo" src="{{ asset('img/icons/maintenance-white.png') }}">
              <p class="h">70% <span>mantenimiento</span></p>
              <p class="h">preventivo</p>
            </div>
          </button>

          {{-- 4) Revisión de batería (clara) --}}
          <button type="button" class="tile light" data-open-modal data-benefit="revision_bateria" aria-label="Revisión GRATIS de batería">
            <div>
              <img class="ico" alt="Revisión de batería" src="{{ asset('img/icons/battery.png') }}">
              <p class="h">Revisión <span>GRATIS</span></p>
              <p class="h">de batería</p>
            </div>
          </button>
        </div>
      </div>

      {{-- Legal --}}
      <div class="legal">Aplican términos y condiciones*</div>
    </main>

    {{-- FOOTER estilo arte, con más aire para el logo --}}
<footer class="bg-white grid place-items-center py-3">
  <div
    class="w-[94%] h-[78px]   {{-- alto interno del footer --}}
           grid grid-cols-2 items-center gap-4
           bg-white rounded-md
           outline outline-[1.5px] outline-black/35
           shadow-[inset_0_0_0_4px_#fff]
           px-5"
  >
    {{-- Columna izquierda: texto + logo --}}
    <div class="flex items-center gap-4 min-w-0">
      <p class="m-0 leading-tight font-extrabold text-[15px] text-black shrink-0">
        Somos el taller<br class="sm:hidden"> autorizado de
      </p>

      {{-- Logo (SVG/PNG). Cambia la ruta si lo necesitas --}}
      <img
        src="{{ asset('img/brands/renting-colombia.svg') }}"
        alt="Renting Colombia"
        class="h-[30px] w-auto max-w-[180px]"
        onerror="this.style.display='none';"
      />
    </div>

    {{-- Columna derecha: marco + mensaje --}}
    <div class="flex items-center justify-end gap-3">
      <span class="inline-block w-[30px] h-[30px] rounded-md border-2 border-[#00ba6d] opacity-85"></span>
      <p class="m-0 text-[17px] font-extrabold tracking-[0.2px] text-black">
        Selecciona para reclamar <span class="text-[#00ba6d]">tu beneficio</span>
      </p>
    </div>
  </div>
</footer>


  </section>
</div>

{{-- ===== Estilos específicos del “cuadro” ===== --}}
<style>
  :root{
    --brand:#00ba6d; --dark:#2b2b2b; --ring:rgba(0,0,0,.10);
    --size:420px;      /* lado del cuadrado exterior (máx) */
    --hdrH:64px;       /* alto header */
    --ftrH:74px;       /* alto footer (fila del grid) => ~62px + paddings */
    --legalH:20px;     /* alto del legal */
    --gap:12px; --pad:10px; --fs:14px; --ico:36px;
  }
  .viewport{min-height:100%; display:grid; place-items:center; padding:16px}

  .square{
    width:min(92vw,var(--size)); aspect-ratio:1/1;
    background:#fff; border-radius:16px; overflow:hidden;
    box-shadow:0 16px 40px rgba(0,0,0,.35); outline:1px solid var(--ring);
    display:grid; grid-template-rows: var(--hdrH) 1fr var(--ftrH);
  }

  .header{background:var(--brand); display:grid; place-items:center; padding:6px 10px}
  .ph{
    width:90%; height:calc(var(--hdrH) - 18px);
    background:#ffffffE6; border-radius:12px; outline:1px solid var(--ring);
    display:grid; place-items:center; text-align:center; color:#555; font-size:13px;
  }
  .ph small{opacity:.7; font-size:11px}

  .main{
    background:var(--brand);
    display:grid; grid-template-rows: 1fr var(--legalH);
    align-items:center; justify-items:center; padding:8px 0;
  }
  .gridwrap{width:100%; height:100%; display:grid; place-items:center}
  .gridbox{
    --side: min(92%, 100%);
    width:var(--side); height:var(--side);
    display:grid; gap:var(--gap);
    grid-template-columns:repeat(2, 1fr);
    grid-template-rows:repeat(2, minmax(0,1fr));
  }
  .legal{
    height:var(--legalH); line-height:var(--legalH);
    color:#fff; opacity:.9; font-size:11px; text-align:center;
  }

  .tile{
    width:100%; height:100%;
    border-radius:14px; padding:var(--pad);
    text-align:center; display:flex; align-items:center; justify-content:center;
    box-shadow:0 2px 6px rgba(0,0,0,.10); outline:1px solid rgba(0,0,0,.08);
    overflow:hidden; cursor:pointer;
  }
  .tile.dark{background:var(--dark); color:#fff; outline-color:rgba(0,0,0,.25)}
  .tile.light{background:#fff; color:#111}
  .ico{width:var(--ico); height:var(--ico); display:block; margin:0 auto 6px; object-fit:contain}
  .h{font-weight:800; line-height:1.06; font-size:var(--fs); margin:0}
  .h span{font-weight:900}
</style>

{{-- ===== Modal para generar bono ===== --}}
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
            <input type="text" name="company" class="hidden" tabindex="-1" autocomplete="off">

            <div>
              <label class="block text-sm font-medium text-gray-700">Fecha tentativa</label>
              <input type="date" name="tentative_date" required value="{{ old('tentative_date') }}"
                     min="{{ now()->toDateString() }}"
                     class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
              @error('tentative_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
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

@push('scripts')
<script>
(function(){
  const modal  = document.getElementById('voucher-modal');
  const label  = document.getElementById('benefit-label');
  const hidden = document.getElementById('benefit-input');
  const BENEFITS = @json(\App\Models\Claim::BENEFITS);

  function openModal(benefit){
    hidden.value = benefit;
    label.textContent = BENEFITS[benefit] ?? '';
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }
  function closeModal(){
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  document.querySelectorAll('[data-open-modal]').forEach(el=>{
    el.addEventListener('click', ()=> openModal(el.dataset.benefit));
  });
  modal.addEventListener('click', e=>{
    if (e.target.matches('[data-close-modal]')) closeModal();
  });
  window.addEventListener('keydown', e=>{
    if (e.key === 'Escape') closeModal();
  });

  @if ($errors->any() && old('benefit'))
    openModal(@js(old('benefit')));
  @endif
})();
</script>
@endpush
@endsection
