@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 via-red-500 to-red-600 p-4 text-white shadow-lg shadow-rose-500/30']) }}
        role="alert" aria-live="assertive">
        {{-- Soft highlight for a glassy depth --}}
        <div class="pointer-events-none absolute inset-0 opacity-25"
            style="background: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, 0.9) 0%, transparent 55%);"></div>

        <div class="relative flex items-start gap-3">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/30 bg-white/20">
                <flux:icon name="exclamation-triangle" class="h-5 w-5 text-white" />
            </span>

            <div class="min-w-0 flex-1 space-y-1">
                <ul class="space-y-1 text-sm leading-relaxed text-white/90">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start gap-2">
                            <span class="mt-[7px] h-1 w-1 shrink-0 rounded-full bg-white/80"></span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
