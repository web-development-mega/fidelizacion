{{-- Modal centrado, oculto por defecto --}}
<div id="voucher-modal"
     class="hidden fixed inset-0 z-50 grid place-items-center"
     aria-modal="true" role="dialog" aria-labelledby="benefit-label">

  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/60"></div>

  {{-- Contenido --}}
  <div class="relative w-[min(92vw,980px)] max-h-[92vh] overflow-auto bg-white rounded-2xl shadow-2xl">
    <div class="p-6 sm:p-8">
      <div class="flex items-start justify-between gap-4 mb-4">
        <h2 class="text-xl font-semibold">Solicitar bono — <span id="benefit-label">—</span></h2>
        <button type="button" class="shrink-0 rounded-lg p-2 hover:bg-zinc-100" data-close-modal aria-label="Cerrar">✕</button>
      </div>

      {{-- Formulario --}}
      <form method="POST" action="{{ route('claim.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="benefit" id="benefit-input" value="">

        <div>
          <label class="block text-sm font-medium mb-1">Fecha tentativa</label>
          <input type="date" name="tentative_date" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tu nombre</label>
          <input type="text" name="name" placeholder="Opcional" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tu teléfono</label>
          <input type="text" name="phone" placeholder="Opcional" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tu email</label>
          <input type="email" name="email" placeholder="Opcional" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
        </div>

        {{-- Referidos (opcional, hasta 3) --}}
        <details class="rounded-lg border border-zinc-200">
          <summary class="cursor-pointer px-4 py-3 text-sm font-medium">¿Quieres referir amigos? (opcional, hasta 3)</summary>
          <div class="p-4 space-y-6">

            @for($i=1; $i<=3; $i++)
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1">Nombre ({{ $i }})</label>
                <input type="text" name="referrals[{{ $i-1 }}][name]" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Teléfono ({{ $i }})</label>
                <input type="text" name="referrals[{{ $i-1 }}][phone]" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Email ({{ $i }})</label>
                <input type="email" name="referrals[{{ $i-1 }}][email]" class="w-full rounded-lg border-zinc-300 focus:border-emerald-600 focus:ring-emerald-600">
              </div>
            </div>
            @endfor

          </div>
        </details>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" class="px-4 py-2 rounded-lg text-zinc-700 bg-zinc-100 hover:bg-zinc-200" data-close-modal>Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded-lg text-white bg-emerald-600 hover:bg-emerald-700">Generar bono</button>
        </div>
      </form>
    </div>
  </div>
</div>
