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
    width:100%; max-width:820px; min-height:60px; padding:8px 12px; background:#ffffffF2; border-radius:12px;
    outline:1px solid var(--ring); display:grid; place-items:center; color:#475569; text-align:center; font-size:14px;
  }
  .header-logo{ display:block; max-height:60px; width:auto; max-width:100%; object-fit:contain; margin-inline:auto; }
  @media (max-width:640px){ .header-logo{ max-height:46px; } }

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
  .tile .ico{ width:72px; height:72px; display:block; margin:6px auto 10px; object-fit:contain }
  .tile.dark .ico{ filter:invert(1) brightness(1.05) }
  .tile .h{ font-weight:800; line-height:1.08; font-size:15px; margin:0 }
  .promo-legal{ height:20px; line-height:20px; color:#fff; text-align:center; font-size:11px; opacity:.95; margin-top:8px }

  .promo-footer{ background:#fff; padding:18px 20px }
  .footer-inner{ max-width:820px; margin:0 auto; display:grid; grid-template-columns:1fr;
    gap:12px; justify-items:center; text-align:center; }
  .footer-logo{ display:block; height:auto; max-width: 220px; margin-inline:auto; object-fit:contain; }
  @media (max-width: 640px){ .footer-logo{ max-width: 160px; } }

  .ico--white{ filter: brightness(0) invert(1) !important; }

  /* En pantallas pequeñas el grid deja de forzar cuadrado */
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

  /* --- Estilos mínimos para inputs del modal --- */
  .mini-label{ display:block; font-size:12px; color:#475569; margin:.35rem 0 .25rem }
  .muted{ font-size:12px; color:#64748b }
  .groupbox{ border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; background:#f8fafc }
  input[type="text"], input[type="tel"], input[type="email"], input[type="date"], input[type="time"], select{
    width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:8px 10px; font-size:14px; outline:none;
    background:#fff;
  }
  input[type="text"]:focus, input[type="tel"]:focus, input[type="email"]:focus, input[type="date"]:focus, input[type="time"]:focus, select:focus{
    border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15)
  }
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
                data-label="70% en MegaCombo 7 servicios">
          <div>
            <img class="ico ico--white" src="{{ asset('img/icons/maintenance.svg') }}"
                onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<?xml version=&quot;1.0&quot;?><svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;36&quot; height=&quot;36&quot;><rect width=&quot;36&quot; height=&quot;36&quot; rx=&quot;8&quot; fill=&quot;%23e5e7eb&quot;/><text x=&quot;50%&quot; y=&quot;54%&quot; text-anchor=&quot;middle&quot; font-size=&quot;9&quot; fill=&quot;%23666&quot; font-family=&quot;Arial,Helvetica&quot;>ICONO</text></svg>';"
            >
            <p class="h">70% en</p>
            <p class="h"><strong>MegaCombo 7 servicios</strong></p>
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
          <span class="text-emerald-700">tu beneficio.</span>
        </div>
      </div>
    </footer>
  </section>
</div>

@push('scripts')
<script>
(function () {
  const csrf    = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const minDate = @json(now()->toDateString());

  function ensureSwal(cb){
    if (window.Swal && typeof Swal.fire === 'function') { cb(); return; }
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js';
    s.defer = true; s.onload = cb; document.head.appendChild(s);
    const l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
    document.head.appendChild(l);
  }

  function isWeekend(yyyyMmDd){
    const d = new Date(yyyyMmDd+'T00:00:00');
    const day = d.getDay(); // 0=Dom,6=Sab
    return day === 0 || day === 6;
  }

  function pad(n){ return String(n).padStart(2,'0'); }

  function generateSlots(start, end, stepMin){
    // start/end "HH:MM"
    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const slots = [];
    let curH = sh, curM = sm;
    while (curH < eh || (curH === eh && curM <= em)) {
      slots.push(pad(curH)+':'+pad(curM));
      curM += stepMin;
      while (curM >= 60) { curM -= 60; curH += 1; }
    }
    return slots;
  }

  function renderHourOptions(selectEl, yyyyMmDd){
    const weekend = isWeekend(yyyyMmDd);
    const base = generateSlots('06:30', weekend ? '10:30' : '14:00', 30);
    if (weekend && !base.includes('10:40')) base.push('10:40'); // excepción pedida

    selectEl.innerHTML = '<option value="" disabled selected>Selecciona…</option>' +
      base.map(h => `<option value="${h}">${h}</option>`).join('');
  }

  function modalHTML(benefitLabel){
    return `
      <div>
        <div class="muted mb-2">Estás solicitando: <strong>${benefitLabel}</strong></div>

        <div class="row-2" style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
          <div>
            <label class="mini-label">Fecha tentativa *</label>
            <input type="date" id="m_fecha_tentativa" min="${minDate}" required>
          </div>
          <div>
            <label class="mini-label">Hora tentativa *</label>
            <select id="m_hora_tentativa" required></select>
          </div>
        </div>

        <div class="row-2" style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:.75rem">
          <div>
            <label class="mini-label">Nombre *</label>
            <input type="text" id="m_nombre" minlength="3" maxlength="100" required placeholder="Tu nombre completo">
          </div>
          <div>
            <label class="mini-label">Cédula *</label>
            <input type="text" id="m_cedula" inputmode="numeric" pattern="\\d{6,12}" required placeholder="Solo números (6–12)">
          </div>
        </div>

        <div class="row-2" style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:.75rem">
          <div>
            <label class="mini-label">Teléfono *</label>
            <input type="text" id="m_telefono" inputmode="tel" pattern="\\d{7,10}" required placeholder="3001234567">
          </div>
          <div>
            <label class="mini-label">Correo electrónico *</label>
            <input type="email" id="m_email" maxlength="150" required placeholder="tucorreo@mail.com">
          </div>
        </div>

        <div class="row-2" style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:.75rem">
          <div>
            <label class="mini-label">Dirección *</label>
            <input type="text" id="m_direccion" maxlength="160" required placeholder="Calle 10 #20-30, Ciudad">
          </div>
          <div>
            <label class="mini-label">Placa *</label>
            <input type="text" id="m_placa" pattern="[A-Za-z]{3}\\d{3}" required placeholder="AAA123"
                   oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,6)">
          </div>
        </div>

        <div style="margin-top:.75rem">
          <label class="mini-label">Marca y modelo *</label>
          <input type="text" id="m_marca_modelo" maxlength="100" required placeholder="Ej: Chevrolet Onix 1.0">
        </div>

        <details class="groupbox" style="margin-top:.9rem">
          <summary class="muted"><strong>¿Quieres referir amigos?</strong> (opcional, hasta 3)</summary>
          <div style="margin-top:.5rem">
            ${[1,2,3].map(i => `
              <div class="row-2" style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:.5rem">
                <div>
                  <label class="mini-label">Nombre (${i})</label>
                  <input type="text" id="m_r${i}_name" maxlength="120">
                </div>
                <div>
                  <label class="mini-label">Teléfono (${i})</label>
                  <input type="tel" id="m_r${i}_phone" maxlength="30">
                </div>
              </div>
              <label class="mini-label">Email (${i})</label>
              <input type="email" id="m_r${i}_email" maxlength="150">
            `).join('')}
          </div>
        </details>
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
      didOpen: () => {
        // prellenar horas acorde a la fecha elegida (por defecto, hoy)
        const dateEl = document.getElementById('m_fecha_tentativa');
        const hourEl = document.getElementById('m_hora_tentativa');

        const today = new Date();
        const yyyy = today.getFullYear(), mm = String(today.getMonth()+1).padStart(2,'0'), dd = String(today.getDate()).padStart(2,'0');
        dateEl.value = `${yyyy}-${mm}-${dd}`;
        renderHourOptions(hourEl, dateEl.value);

        dateEl.addEventListener('change', () => {
          if (!dateEl.value) return;
          renderHourOptions(hourEl, dateEl.value);
        });
      },
      preConfirm: async () => {
        const val = id => (document.getElementById(id)?.value || '').trim();
        const el  = id => document.getElementById(id);

        // limpiar marcas previas
        ['nombre','cedula','telefono','direccion','email','placa','marca_modelo','fecha_tentativa','hora_tentativa']
          .forEach(f => el('m_'+f)?.classList?.remove('border-red-500'));

        // checks mínimos front
        if (!val('m_fecha_tentativa')) { Swal.showValidationMessage('Indica la <b>fecha tentativa</b>.'); el('m_fecha_tentativa')?.classList.add('border-red-500'); return false; }
        if (!val('m_hora_tentativa'))  { Swal.showValidationMessage('Indica la <b>hora tentativa</b>.');  el('m_hora_tentativa')?.classList.add('border-red-500');  return false; }
        if (!val('m_nombre'))          { Swal.showValidationMessage('El <b>nombre</b> es obligatorio.');   el('m_nombre')?.classList.add('border-red-500');          return false; }
        if (!val('m_cedula'))          { Swal.showValidationMessage('La <b>cédula</b> es obligatoria.');    el('m_cedula')?.classList.add('border-red-500');          return false; }
        if (!val('m_telefono'))        { Swal.showValidationMessage('El <b>teléfono</b> es obligatorio.'); el('m_telefono')?.classList.add('border-red-500');        return false; }
        if (!val('m_direccion'))       { Swal.showValidationMessage('La <b>dirección</b> es obligatoria.'); el('m_direccion')?.classList.add('border-red-500');      return false; }
        if (!val('m_email'))           { Swal.showValidationMessage('El <b>correo</b> es obligatorio.');    el('m_email')?.classList.add('border-red-500');           return false; }
        if (!val('m_placa'))           { Swal.showValidationMessage('La <b>placa</b> es obligatoria.');     el('m_placa')?.classList.add('border-red-500');           return false; }
        if (!val('m_marca_modelo'))    { Swal.showValidationMessage('La <b>marca y modelo</b> es obligatoria.'); el('m_marca_modelo')?.classList.add('border-red-500'); return false; }

        // payload NUEVO
        const fd = new FormData();
        fd.append('benefit',          benefit);
        fd.append('fecha_tentativa',  val('m_fecha_tentativa'));
        fd.append('hora_tentativa',   val('m_hora_tentativa'));
        fd.append('nombre',           val('m_nombre'));
        fd.append('cedula',           val('m_cedula'));
        fd.append('telefono',         val('m_telefono'));
        fd.append('direccion',        val('m_direccion'));
        fd.append('email',            val('m_email'));
        fd.append('placa',            val('m_placa'));
        fd.append('marca_modelo',     val('m_marca_modelo'));

        // Referidos (opcionales)
        ['1','2','3'].forEach(i => {
          const n = val(`m_r${i}_name`), p = val(`m_r${i}_phone`), e = val(`m_r${i}_email`);
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
            const data   = await res.json();
            const errors = data.errors || {};
            const firstKey = Object.keys(errors)[0];
            const firstMsg = firstKey ? errors[firstKey][0] : 'Revisa los campos.';

            Swal.showValidationMessage(firstMsg);

            // marca el campo con error principal
            if (firstKey) {
              const map = {
                nombre: 'm_nombre',
                cedula: 'm_cedula',
                telefono: 'm_telefono',
                direccion: 'm_direccion',
                email: 'm_email',
                placa: 'm_placa',
                marca_modelo: 'm_marca_modelo',
                fecha_tentativa: 'm_fecha_tentativa',
                hora_tentativa: 'm_hora_tentativa',
              };
              const markId = map[firstKey];
              if (markId) document.getElementById(markId)?.classList.add('border-red-500');
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

  // Bind de los tiles: garantizamos SweetAlert antes de abrir el modal
  document.querySelectorAll('.js-open-claim').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      ensureSwal(() => openClaimModal(btn.dataset.benefit, btn.dataset.label));
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
