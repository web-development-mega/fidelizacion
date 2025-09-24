<x-guest-layout>
  <div class="min-h-[80vh] grid place-items-center bg-slate-50 py-12">
    <div class="w-full max-w-[720px]">
      <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('claim.form','mega_combo') }}" class="rounded-2xl bg-[#1E90FF] text-white text-center p-8 shadow hover:opacity-90 transition">
          <span class="block text-2xl font-bold leading-tight">70% Mega<br>Combo</span>
        </a>
        <a href="{{ route('claim.form','revision_bateria') }}" class="rounded-2xl bg-[#22C55E] text-white text-center p-8 shadow hover:opacity-90 transition">
          <span class="block text-2xl font-bold leading-tight">Revisión<br>GRATIS<br>de batería</span>
        </a>
        <a href="{{ route('claim.form','cambio_aceite') }}" class="rounded-2xl bg-[#F9A8D4] text-white text-center p-8 shadow hover:opacity-90 transition">
          <span class="block text-2xl font-bold leading-tight">10% cambio<br>de aceite</span>
        </a>
        <a href="{{ route('claim.form','trabajos_autorizados') }}" class="rounded-2xl bg-[#A16207] text-white text-center p-8 shadow hover:opacity-90 transition">
          <span class="block text-2xl font-bold leading-tight">10% Trabajos<br>autorizados</span>
        </a>
      </div>
    </div>
  </div>
</x-guest-layout>
