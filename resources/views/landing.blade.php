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

  /* --- Header / logo --- */
  .promo-header .ph{ height:auto; min-height:60px; padding:8px 12px; }
  .header-logo{ display:block; max-height:60px; width:auto; max-width:100%; object-fit:contain; margin-inline:auto; }
  @media (max-width:640px){ .header-logo{ max-height:46px; } }

  /* -------- Footer (logo debajo del texto) -------- */
  .promo-footer{ background:#fff; padding:18px 20px }
  .footer-inner{ max-width:820px; margin:0 auto; display:grid; grid-template-columns:1fr;
    gap:12px; justify-items:center; text-align:center; }
  .renting-logo{ width:160px; height:44px; border-radius:10px; background:#ecfdf5;
    outline:1px solid var(--ring); display:grid; place-items:center; color:#059669; font-weight:700; font-size:14px }

  .ico--white{ filter: brightness(0) invert(1) !important; }

  /* Tamaño de iconos (doble en desktop) */
  .tile .ico{ width:72px; height:72px; display:block; margin:6px auto 10px; object-fit:contain; }
  .footer-logo{ display:block; height:auto; max-width: 220px; margin-inline:auto; object-fit:contain; }
  @media (max-width: 640px){ .footer-logo{ max-width: 160px; } }

  /* En pantallas pequeñas, un poco más chicos para no romper el grid */
  @media (max-width:640px){ .tile .ico{ width:56px; height:56px; margin:4px auto 8px; } }

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
      <div class="ph">
        <img
          class="header-logo"
          src="{{ asset('logo.svg') }}"
          alt="Mega Tecnicentro"
          onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;200&quot; height=&quot;40&quot;><rect width=&quot;100%&quot; height=&quot;100%&quot; rx=&quot;10&quot; fill=&quot;%23eef2f7&quot;/><text x=&quot;50%&quot; y=&quot;56%&quot; text-anchor=&quot;middle&quot; font-size=&quot;14&quot; fill=&quot;%236b7280&quot; font-family=&quot;Arial,Helvetica&quot;>LOGO</text></svg>';"
        >
      </div>
    </header>

    <div class="promo-main">
      <div class="promo-grid">
        {{-- 1 --}}
        <button type="button" class="tile light js-open-claim" data-benefit="cambio_aceite" data-label="10% cambio de aceite">
          <div>
            <img class="ico" src="{{ asset('img/icons/oil.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';"
            >
            <p class="h">10% <strong>cambio</strong></p><p class="h">de aceite</p>
          </div>
        </button>
        {{-- 2 --}}
        <button type="button" class="tile dark js-open-claim" data-benefit="trabajos_autorizados" data-label="10% trabajos autorizados">
          <div>
            <img class="ico ico--white" src="{{ asset('img/icons/tools.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';"
            >
            <p class="h">10% trabajos</p><p class="h"><strong>autorizados</strong></p>
          </div>
        </button>
        {{-- 3 --}}
        <button type="button" class="tile dark js-open-claim"
                data-benefit="mega_combo"
                data-label="70% en alineación y balanceo">
          <div>
            <img class="ico ico--white" src="{{ asset('img/icons/maintenance.svg') }}"
                onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';"
            >
            <p class="h">70% en</p>
            <p class="h"><strong>alineación y balanceo</strong></p>
          </div>
        </button>
        {{-- 4 --}}
        <button type="button" class="tile light js-open-claim" data-benefit="revision_bateria" data-label="Revisión GRATIS de batería">
          <div>
            <img class="ico" src="{{ asset('img/icons/battery.svg') }}"
              onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';"
            >
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
        <div>
          <img
            class="footer-logo"
            src="{{ asset('img/renting-colombia.webp') }}"
            alt="Renting Colombia"
            onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;200&quot; height=&quot;44&quot;><rect width=&quot;100%&quot; height=&quot;100%&quot; rx=&quot;8&quot; fill=&quot;%23ecfdf5&quot;/><text x=&quot;50%&quot; y=&quot;58%&quot; text-anchor=&quot;middle&quot; font-size=&quot;14&quot; fill=&quot;%23059669&quot; font-family=&quot;Arial,Helvetica&quot;>Logo Renting</text></svg>';"
          />
        </div>
        <div class="text-lg sm:text-xl font-bold leading-tight">
          <span class="text-emerald-600">Selecciona</span> para reclamar
          <span class="text-emerald-700">tu beneficio</span>
        </div>
      </div>
    </footer>
  </section>
</div>

@push('scripts')
<script>
(function () {
  const csrf    = document.querySelector('meta[name="csrf-token"]').content;
  const minDate = @json(now()->toDateString());

  function modalHTML(benefitLabel){
    return `
      <div>
        <div class="muted mb-2">Estás solicitando: <strong>${benefitLabel}</strong></div>

        <label class="mini-label">Fecha tentativa</label>
        <input type="date" id="m_date" min="${minDate}" required>

        <label class="mini-label">Tu nombre</label>
        <input type="text" id="m_name" placeholder="Opcional">

        <label class="mini-label">Tu teléfono</label>
        <input type="tel" id="m_phone" placeholder="Opcional">

        <label class="mini-label">Tu email</label>
        <input type="email" id="m_email" placeholder="tucorreo@mail.com" required>
        <p id="m_email_help" class="muted" style="display:none;margin-top:.35rem"></p>

        <div class="groupbox" style="margin-top:.75rem">
          <div class="muted"><strong>¿Quieres referir amigos?</strong> (opcional, hasta 3)</div>

          <div class="row-2" style="margin-top:.5rem">
            <div>
              <label class="mini-label">Nombre (1)</label>
              <input type="text" id="m_r1_name">
            </div>
            <div>
              <label class="mini-label">Teléfono (1)</label>
              <input type="tel" id="m_r1_phone">
            </div>
          </div>
          <label class="mini-label">Email (1)</label>
          <input type="email" id="m_r1_email">

          <div class="row-2" style="margin-top:.5rem">
            <div>
              <label class="mini-label">Nombre (2)</label>
              <input type="text" id="m_r2_name">
            </div>
            <div>
              <label class="mini-label">Teléfono (2)</label>
              <input type="tel" id="m_r2_phone">
            </div>
          </div>
          <label class="mini-label">Email (2)</label>
          <input type="email" id="m_r2_email">

          <div class="row-2" style="margin-top:.5rem">
            <div>
              <label class="mini-label">Nombre (3)</label>
              <input type="text" id="m_r3_name">
            </div>
            <div>
              <label class="mini-label">Teléfono (3)</label>
              <input type="tel" id="m_r3_phone">
            </div>
          </div>
          <label class="mini-label">Email (3)</label>
          <input type="email" id="m_r3_email">
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
      confirmButtonText: 'Generar bono',
      cancelButtonText: 'Cancelar',
      focusConfirm: false,
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: async () => {
        const dateEl  = document.getElementById('m_date');
        const emailEl = document.getElementById('m_email');
        const helpEl  = document.getElementById('m_email_help');

        // limpiar estado previo
        emailEl.classList.remove('border-red-500');
        helpEl.style.display = 'none'; helpEl.textContent = '';

        if (!dateEl.value) {
          Swal.showValidationMessage('Indica una <b>fecha tentativa</b>.');
          return false;
        }

        // Construir payload
        const fd = new FormData();
        fd.append('benefit', benefit);
        fd.append('tentative_date', dateEl.value);
        fd.append('name',  (document.getElementById('m_name').value || '').trim());
        fd.append('phone', (document.getElementById('m_phone').value || '').trim());
        fd.append('email', (emailEl.value || '').trim());

        // Referidos opcionales
        ['1','2','3'].forEach(i => {
          const n = (document.getElementById(`m_r${i}_name`)  ?.value || '').trim();
          const p = (document.getElementById(`m_r${i}_phone`) ?.value || '').trim();
          const e = (document.getElementById(`m_r${i}_email`) ?.value || '').trim();
          if (n || p || e) {
            fd.append(`referrals[${i-1}][name]`,  n);
            fd.append(`referrals[${i-1}][phone]`, p);
            fd.append(`referrals[${i-1}][email]`, e);
          }
        });

        try {
          const res = await fetch(@json(route('claim.store')), {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrf
            },
            body: fd
          });

          if (res.ok) {
            const data = await res.json(); // { redirect: '...' }
            return data;
          }

          if (res.status === 422) {
            const data  = await res.json();
            const first = data.errors && Object.values(data.errors)[0]
              ? Object.values(data.errors)[0][0]
              : 'Revisa los campos.';

            Swal.showValidationMessage(first);

            if (data.errors && data.errors.email) {
              emailEl.classList.add('border-red-500');
              helpEl.textContent = data.errors.email[0];
              helpEl.style.display = 'block';
            }
            throw new Error('validation');
          }

          const text = await res.text();
          Swal.showValidationMessage('Error inesperado ('+res.status+').');
          throw new Error('server: '+text);

        } catch (err) {
          return false; // mantiene abierto el modal
        }
      }
    }).then((result) => {
      if (result.isConfirmed && result.value && result.value.redirect) {
        window.location.href = result.value.redirect;
      }
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
