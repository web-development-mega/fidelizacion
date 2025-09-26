@extends('layouts.app')

@section('content')
<style>
  :root{
    --brand:#10b981; --dark:#0f172a; --ring:rgba(0,0,0,.10); --size:680px;
  }
  .vp-center{ min-height:100svh; display:grid; place-items:center; padding:24px;
    background:linear-gradient(180deg,#f8fafc, #eef2f7) }
  .promo-wrap{
    width:min(92vw,var(--size));
    border-radius:16px; overflow:hidden; background:#fff;
    outline:1px solid var(--ring); box-shadow:0 28px 70px rgba(0,0,0,.18);
  }
  .promo-header{ background:var(--brand); padding:10px 16px; display:grid; place-items:center; }
  .promo-header .ph{
    width:100%; max-width:820px; height:56px; background:#ffffffF2; border-radius:12px;
    outline:1px solid var(--ring); display:grid; place-items:center; color:#475569; text-align:center; font-size:14px;
  }
  .promo-main{ background:var(--brand); padding:16px 16px 8px; }
  .promo-grid{ width:100%; max-width:820px; margin:0 auto; aspect-ratio:1/1;
    display:grid; gap:12px; grid-template-columns:repeat(2,1fr) }
  .tile{ display:flex; align-items:center; justify-content:center; text-align:center;
    border-radius:14px; padding:12px; cursor:pointer; user-select:none;
    box-shadow:0 2px 6px rgba(0,0,0,.10); outline:1px solid rgba(0,0,0,.08);
    transition:transform .08s ease, box-shadow .15s ease }
  .tile:active{ transform:translateY(1px) }
  .tile.light{ background:#fff; color:#0f172a }
  .tile.dark { background:#0f172a; color:#fff; outline-color:rgba(0,0,0,.25) }
  .tile .ico{ width:36px; height:36px; display:block; margin:0 auto 6px; object-fit:contain }
  .tile.dark .ico{ filter:invert(1) brightness(1.05) }
  .tile .h{ font-weight:800; line-height:1.08; font-size:15px; margin:0 }
  .promo-legal{ height:20px; line-height:20px; color:#fff; text-align:center; font-size:11px; opacity:.95; margin-top:8px }

  /* -------- Footer (logo debajo del texto) -------- */
  .promo-footer{ background:#fff; padding:18px 20px }
  .footer-inner{
    max-width:820px; margin:0 auto;
    display:grid; grid-template-columns:1fr;
    gap:12px; justify-items:center; text-align:center;
  }
  .renting-logo{
    width:160px; height:44px; border-radius:10px; background:#ecfdf5;
    outline:1px solid var(--ring); display:grid; place-items:center;
    color:#059669; font-weight:700; font-size:14px
  }

  /* ======= centrado perfecto en móvil ======= */
  @media (max-width:1024px){ :root{ --size:540px } }
  @media (max-width:640px){
    :root{ --size:420px } .promo-grid{ aspect-ratio:auto }
  }
  @media (max-width: 640px){
    .vp-center{
      min-height: 100dvh;
      min-height: calc(100dvh - env(safe-area-inset-top) - env(safe-area-inset-bottom));
      display:flex; align-items:center; justify-content:center; padding:16px;
    }
    .promo-wrap{ margin-inline:auto; width:min(96vw, var(--size)); }
  }
  @supports (height: 100svh){
    @media (max-width: 640px){
      .vp-center{
        min-height: 100svh;
        min-height: calc(100svh - env(safe-area-inset-top) - env(safe-area-inset-bottom));
      }
    }
  }
  /* ⛔️ Importante: sin max-height en .promo-wrap para no cortar el footer */
</style>

<div class="vp-center">
  <section class="promo-wrap">
    <header class="promo-header">
      <div class="ph">Coloca tu imagen aquí · <span style="opacity:.7">ej. logo/cabecera (≈560×160)</span></div>
    </header>

    <div class="promo-main">
      <div class="promo-grid">
        {{-- 1 --}}
        <button type="button" class="tile light js-open-claim" data-benefit="cambio_aceite" data-label="10% cambio de aceite">
          <div>
            <img class="ico" src="{{ asset('img/icons/oil.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';">
            <p class="h">10% <strong>cambio</strong></p><p class="h">de aceite</p>
          </div>
        </button>
        {{-- 2 --}}
        <button type="button" class="tile dark js-open-claim" data-benefit="trabajos_autorizados" data-label="10% trabajos autorizados">
          <div>
            <img class="ico" src="{{ asset('img/icons/wrench.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';">
            <p class="h">10% trabajos</p><p class="h"><strong>autorizados</strong></p>
          </div>
        </button>
        {{-- 3 --}}
        <button type="button" class="tile dark js-open-claim"
                data-benefit="mega_combo"
                data-label="70% en alineación y balanceo">
          <div>
            <img class="ico" src="{{ asset('img/icons/maintenance.svg') }}"
                onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';">

            <p class="h">70% en</p>
            <p class="h"><strong>alineación y balanceo</strong></p>
          </div>
        </button>
        {{-- 4 --}}
        <button type="button" class="tile light js-open-claim" data-benefit="revision_bateria" data-label="Revisión GRATIS de batería">
          <div>
            <img class="ico" src="{{ asset('img/icons/battery.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';">
            <p class="h">Revisión <strong>GRATIS</strong></p><p class="h">de batería</p>
          </div>
        </button>
      </div>
      <div class="promo-legal">Aplican términos y condiciones*</div>
    </div>

    <footer class="promo-footer">
      <div class="footer-inner">
        <div class="text-[15px] font-semibold leading-tight text-zinc-900">
          Somos el taller autorizado de
        </div>
        <div class="renting-logo">Logo Renting</div>
        <div class="text-lg sm:text-xl font-bold leading-tight">
          <span class="text-emerald-600">Selecciona</span> para reclamar
          <span class="text-emerald-700">tu beneficio</span>
        </div>
      </div>
    </footer>
  </section>
</div>

{{-- Form oculto que enviamos a Laravel --}}
<form id="claim-hidden-form" action="{{ route('claim.store') }}" method="POST" class="hidden">
  @csrf
  <input type="hidden" name="benefit" id="f_benefit">
  <input type="hidden" name="tentative_date" id="f_date">
  <input type="hidden" name="name" id="f_name">
  <input type="hidden" name="phone" id="f_phone">
  <input type="hidden" name="email" id="f_email">
  {{-- Referidos (3) --}}
  @for ($i=1; $i<=3; $i++)
    <input type="hidden" name="referrals[{{ $i }}][name]"  id="f_r{{$i}}_name">
    <input type="hidden" name="referrals[{{ $i }}][phone]" id="f_r{{$i}}_phone">
    <input type="hidden" name="referrals[{{ $i }}][email]" id="f_r{{$i}}_email">
  @endfor
</form>

@push('scripts')
<script>
(function () {
  function modalHTML(benefitLabel){
    return `
      <div>
        <div class="muted mb-2">Estás solicitando: <strong>${benefitLabel}</strong></div>

        <label class="mini-label">Fecha tentativa</label>
        <input type="date" id="m_date" min="{{ now()->format('Y-m-d') }}">

        <label class="mini-label">Tu nombre</label>
        <input type="text" id="m_name" placeholder="Opcional">

        <label class="mini-label">Tu teléfono</label>
        <input type="tel" id="m_phone" placeholder="Opcional">

        <label class="mini-label">Tu email</label>
        <input type="email" id="m_email" placeholder="Opcional">

        <div class="groupbox">
          <div class="muted"><strong>¿Quieres referir amigos?</strong> (opcional, hasta 3)</div>

          <div class="row-2">
            <div>
              <label class="mini-label">Nombre (1)</label>
              <input type="text" id="m_r1_name" placeholder="">
            </div>
            <div>
              <label class="mini-label">Teléfono (1)</label>
              <input type="tel" id="m_r1_phone" placeholder="">
            </div>
          </div>
          <label class="mini-label">Email (1)</label>
          <input type="email" id="m_r1_email" placeholder="">

          <div class="row-2">
            <div>
              <label class="mini-label">Nombre (2)</label>
              <input type="text" id="m_r2_name" placeholder="">
            </div>
            <div>
              <label class="mini-label">Teléfono (2)</label>
              <input type="tel" id="m_r2_phone" placeholder="">
            </div>
          </div>
          <label class="mini-label">Email (2)</label>
          <input type="email" id="m_r2_email" placeholder="">

          <div class="row-2">
            <div>
              <label class="mini-label">Nombre (3)</label>
              <input type="text" id="m_r3_name" placeholder="">
            </div>
            <div>
              <label class="mini-label">Teléfono (3)</label>
              <input type="tel" id="m_r3_phone" placeholder="">
            </div>
          </div>
          <label class="mini-label">Email (3)</label>
          <input type="email" id="m_r3_email" placeholder="">
        </div>
      </div>
    `;
  }

  function openClaimModal(benefit, label){
    Swal.fire({
      title: 'Solicitar bono',
      html: modalHTML(label),
      width: Math.min(window.innerWidth - 40, 720),
      showCancelButton: true,
      confirmButtonText: 'Enviar solicitud',
      cancelButtonText: 'Cancelar',
      focusConfirm: false,
      preConfirm: () => {
        const dt = document.getElementById('m_date').value;
        if (!dt){
          Swal.showValidationMessage('Indica una <b>fecha tentativa</b>.');
          return false;
        }
        return {
          date:  dt,
          name:  document.getElementById('m_name').value.trim(),
          phone: document.getElementById('m_phone').value.trim(),
          email: document.getElementById('m_email').value.trim(),

          r1_name:  document.getElementById('m_r1_name').value.trim(),
          r1_phone: document.getElementById('m_r1_phone').value.trim(),
          r1_email: document.getElementById('m_r1_email').value.trim(),

          r2_name:  document.getElementById('m_r2_name').value.trim(),
          r2_phone: document.getElementById('m_r2_phone').value.trim(),
          r2_email: document.getElementById('m_r2_email').value.trim(),

          r3_name:  document.getElementById('m_r3_name').value.trim(),
          r3_phone: document.getElementById('m_r3_phone').value.trim(),
          r3_email: document.getElementById('m_r3_email').value.trim(),
        };
      },
      customClass:{ popup: 'px-2' }
    }).then((res) => {
      if(!res.isConfirmed) return;

      const f = document.getElementById('claim-hidden-form');

      document.getElementById('f_benefit').value = benefit;
      document.getElementById('f_date').value    = res.value.date;
      document.getElementById('f_name').value    = res.value.name;
      document.getElementById('f_phone').value   = res.value.phone;
      document.getElementById('f_email').value   = res.value.email;

      document.getElementById('f_r1_name').value  = res.value.r1_name;
      document.getElementById('f_r1_phone').value = res.value.r1_phone;
      document.getElementById('f_r1_email').value = res.value.r1_email;

      document.getElementById('f_r2_name').value  = res.value.r2_name;
      document.getElementById('f_r2_phone').value = res.value.r2_phone;
      document.getElementById('f_r2_email').value = res.value.r2_email;

      document.getElementById('f_r3_name').value  = res.value.r3_name;
      document.getElementById('f_r3_phone').value = res.value.r3_phone;
      document.getElementById('f_r3_email').value = res.value.r3_email;

      f.submit();
    });
  }

  document.querySelectorAll('.js-open-claim').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      openClaimModal(btn.dataset.benefit, btn.dataset.label);
    });
  });
})();
</script>
@endpush

{{-- Ajuste de encaje en desktop: reduce solo el grid cuadrado para que TODO (footer incluido) quepa --}}
@push('scripts')
<script>
(function(){
  function fitGridToViewport(){
    const wrap = document.querySelector('.promo-wrap');
    const grid = wrap?.querySelector('.promo-grid');
    if(!wrap || !grid) return;

    // Reset para medir tamaño natural
    grid.style.width  = '';
    grid.style.height = '';

    // Solo en desktop; en móvil ya está controlado
    if (window.innerWidth < 1024) return;

    const vh = window.innerHeight;

    // Medimos el alto natural del wrapper y del grid
    const naturalWrapH = wrap.offsetHeight;
    const gridH        = grid.offsetHeight;

    // Si ya cabe, no tocamos nada
    if (naturalWrapH <= vh) return;

    // Todo lo que no es grid (header, paddings, legal, footer…)
    const extra = naturalWrapH - gridH;

    // Tamaño objetivo del cuadrado para que el wrapper quepa en el viewport
    let size = Math.min(grid.offsetWidth, vh - extra - 12); // 12px de respiro
    size = Math.max(320, size); // límite inferior para no quedar minúsculo
    size = Math.min(size, wrap.clientWidth); // no más ancho que el contenedor

    grid.style.width  = size + 'px';
    grid.style.height = size + 'px';

    // Ajuste fino por redondeos: si aún se pasa, reduce un poco más
    const afterH = wrap.offsetHeight;
    if (afterH > vh){
      const delta    = afterH - vh;
      const adjusted = Math.max(300, size - delta - 8);
      grid.style.width  = adjusted + 'px';
      grid.style.height = adjusted + 'px';
    }
  }

  // Ejecutar en carga, resize y tras un pequeño delay por fuentes/reflows
  window.addEventListener('load', () => {
    fitGridToViewport();
    setTimeout(fitGridToViewport, 60);
  });
  window.addEventListener('resize', () => {
    clearTimeout(window.__fitGridT);
    window.__fitGridT = setTimeout(fitGridToViewport, 80);
  });
})();
</script>
@endpush
@endsection
