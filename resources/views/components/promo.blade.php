@php
  $pid = 'promo_'.bin2hex(random_bytes(3));
  $benefits = \App\Models\Claim::BENEFITS;
@endphp

<div id="{{ $pid }}" class="promo-root" aria-label="Promo Mega Tecnicentro">
  <style>
    #{{ $pid }} { --brand:#00ba6d; --dark:#2b2b2b; --ring:rgba(0,0,0,.10); }
    #{{ $pid }} * { box-sizing: border-box; }

    #{{ $pid }} .promo-square{
      width:min(92vw, 420px);
      aspect-ratio: 1 / 1;
      background:#fff; border-radius:16px; overflow:hidden;
      box-shadow:0 16px 40px rgba(0,0,0,.35);
      outline:1px solid var(--ring);
      display:flex; flex-direction:column;
    }

    #{{ $pid }} .promo-header{
      height:64px; background:var(--brand);
      display:grid; place-items:center; padding:6px 10px;
    }
    #{{ $pid }} .promo-ph{
      width:90%; height:48px; background:#ffffffE6; border-radius:12px;
      outline:1px solid var(--ring); display:grid; place-items:center;
      text-align:center; color:#555; font-size:13px;
    }
    #{{ $pid }} .promo-ph small{opacity:.7; font-size:11px}

    #{{ $pid }} .promo-main{
      flex:1; background:var(--brand);
      display:grid; grid-template-rows: 1fr 20px; padding:8px 0;
    }
    #{{ $pid }} .promo-grid-wrap{ display:grid; place-items:center; }
    #{{ $pid }} .promo-grid{
      width:92%; height:92%;
      display:grid; gap:12px;
      grid-template-columns:repeat(2, 1fr);
      grid-template-rows:repeat(2, minmax(0,1fr));
    }
    #{{ $pid }} .promo-legal{
      height:20px; line-height:20px; color:#fff; opacity:.95;
      font-size:11px; text-align:center;
    }

    #{{ $pid }} .tile{
      width:100%; height:100%; border-radius:14px; padding:10px;
      text-align:center; display:flex; align-items:center; justify-content:center;
      box-shadow:0 2px 6px rgba(0,0,0,.10);
      outline:1px solid rgba(0,0,0,.08);
      overflow:hidden; cursor:pointer; user-select:none;
    }
    #{{ $pid }} .tile.light{ background:#fff; color:#111; }
    #{{ $pid }} .tile.dark{  background:var(--dark); color:#fff; outline-color:rgba(0,0,0,.25) }

    #{{ $pid }} .ico{ width:36px; height:36px; display:block; margin:0 auto 6px; object-fit:contain; }
    #{{ $pid }} .tile.dark .ico{ filter: invert(1) brightness(1.1); }

    #{{ $pid }} .h{ font-weight:800; line-height:1.06; font-size:14px; margin:0 }
    #{{ $pid }} .h .b{ font-weight:900; }

    #{{ $pid }} .promo-footer{ background:#fff; display:grid; place-items:center; padding:10px 8px; }
    #{{ $pid }} .promo-footer-inner{
      width:94%; min-height:86px;
      display:grid; grid-template-columns:1fr 1fr; align-items:center; gap:12px;
      background:#fff; border-radius:10px; outline:1.5px solid rgba(0,0,0,.35);
      box-shadow: inset 0 0 0 4px #fff; padding:0 16px;
    }
    #{{ $pid }} .f-left{ display:flex; align-items:center; gap:14px; min-width:0; }
    #{{ $pid }} .f-left p{ margin:0; line-height:1.15; font-weight:800; font-size:15px; color:#111; white-space:nowrap }
    #{{ $pid }} .f-right{ display:flex; align-items:center; justify-content:flex-end; gap:10px; }
    #{{ $pid }} .scan-box{ width:30px; height:30px; border-radius:6px; border:2px solid var(--brand); opacity:.9; }
    #{{ $pid }} .f-msg{ margin:0; font-size:17px; font-weight:900; color:#111; }
    #{{ $pid }} .f-msg .brand{ color:var(--brand); }
    #{{ $pid }} .logo{ height:32px; width:auto; max-width:190px; }
  </style>

  <section class="promo-square">
    <header class="promo-header">
      <div class="promo-ph">
        Coloca tu imagen aquí<br><small>ej. logo/cabecera (≈560×160)</small>
      </div>
    </header>

    <main class="promo-main">
      <div class="promo-grid-wrap">
        <div class="promo-grid">
          <button type="button" class="tile light" data-benefit="cambio_aceite">
            <div>
              <img class="ico" src="{{ asset('img/icons/oil.png') }}" alt="Cambio de aceite">
              <p class="h">10% <span class="b">cambio</span></p>
              <p class="h">de aceite</p>
            </div>
          </button>

          <button type="button" class="tile dark" data-benefit="trabajos_autorizados">
            <div>
              <img class="ico" src="{{ asset('img/icons/wrench-white.png') }}" alt="Trabajos autorizados">
              <p class="h">10% trabajos</p>
              <p class="h"><span class="b">autorizados</span></p>
            </div>
          </button>

          <button type="button" class="tile dark" data-benefit="mega_combo">
            <div>
              <img class="ico" src="{{ asset('img/icons/maintenance-white.png') }}" alt="Mantenimiento preventivo">
              <p class="h">70% <span class="b">mantenimiento</span></p>
              <p class="h">preventivo</p>
            </div>
          </button>

          <button type="button" class="tile light" data-benefit="revision_bateria">
            <div>
              <img class="ico" src="{{ asset('img/icons/battery.png') }}" alt="Revisión de batería">
              <p class="h">Revisión <span class="b">GRATIS</span></p>
              <p class="h">de batería</p>
            </div>
          </button>
        </div>
      </div>

      <div class="promo-legal">Aplican términos y condiciones*</div>
    </main>

    <footer class="promo-footer">
      <div class="promo-footer-inner">
        <div class="f-left">
          <p>Somos el taller<br>autorizado de</p>
          <img class="logo" src="{{ asset('img/brands/renting-colombia.svg') }}" alt="Renting Colombia" onerror="this.style.display='none'">
        </div>
        <div class="f-right">
          <span class="scan-box" aria-hidden="true"></span>
          <p class="f-msg">Selecciona para reclamar <span class="brand">tu beneficio</span></p>
        </div>
      </div>
    </footer>
  </section>

  <script>
    (function(){
      const root     = document.getElementById(@json($pid));
      const BENEFITS = @json($benefits);

      function triggerModal(benefit){
        if (typeof window.openClaimModal === 'function') {
          return window.openClaimModal(benefit, BENEFITS[benefit] || '');
        }
        const modal = document.getElementById('voucher-modal');
        const input = document.getElementById('benefit-input');
        const label = document.getElementById('benefit-label');
        if (modal && input && label) {
          input.value = benefit;
          label.textContent = BENEFITS[benefit] || '';
          modal.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        } else {
          alert('No se encontró el modal de reclamo.');
        }
      }

      root.querySelectorAll('[data-benefit]').forEach(b=>{
        b.addEventListener('click', ()=> triggerModal(b.dataset.benefit));
      });
    })();
  </script>
</div>
