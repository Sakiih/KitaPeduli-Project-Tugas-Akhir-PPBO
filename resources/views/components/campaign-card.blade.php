@props(['campaign'])

@php
    $progress = $campaign->progressPercentage();
    $remainingDays = $campaign->remainingDays();
    $isAccepting = $campaign->isAcceptingDonations();
@endphp

<article class="kp-card p-3.5 hover:-translate-y-0.5 hover:shadow-md hover:border-emerald-200 transition-all duration-200 flex flex-col h-full group">
    <div class="relative bg-[#eef3ef] rounded-2xl overflow-hidden aspect-[16/10] mb-3.5">
        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="block w-full h-full">
            <img src="{{ $campaign->bannerUrl() }}"
                 alt="{{ $campaign->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </a>

        @if($campaign->category)
            <span class="absolute top-3 right-3 bg-white/95 backdrop-blur-xs text-[#122620] text-[11px] font-bold px-3 py-1 rounded-full shadow-xs border border-black/5">
                {{ $campaign->category->name }}
            </span>
        @endif

        @if($campaign->is_featured)
            <span class="absolute top-3 left-3 bg-gradient-to-r from-emerald-600 to-sky-600 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-xs">
                Unggulan
            </span>
        @endif
    </div>

    <div class="px-1.5 flex-1 flex flex-col">
        <div class="flex items-center gap-1.5 text-xs text-[#52665f] mb-1.5">
            <span class="font-medium truncate">{{ $campaign->organizer_name }}</span>
            @if($campaign->is_verified_organizer)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[10px] font-bold shrink-0">
                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Terverifikasi
                </span>
            @endif
        </div>

        <h3 class="font-bold text-[#122620] text-base leading-snug line-clamp-2 mb-3.5">
            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="hover:text-emerald-700 transition-colors">
                {{ $campaign->title }}
            </a>
        </h3>

        <div class="mt-auto space-y-3">
            <div class="space-y-1.5">
                <div class="w-full h-2 bg-[#eaf1ec] rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-500 to-sky-500 rounded-full transition-all duration-300"
                         style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex items-baseline justify-between gap-2">
                    <div>
                        <p class="text-[11px] text-[#647770] font-medium">Terkumpul</p>
                        <p class="text-sm font-extrabold text-[#122620]">
                            Rp {{ number_format((float) $campaign->collected_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-[#647770] font-medium">Target ({{ $progress }}%)</p>
                        <p class="text-xs font-semibold text-[#52665f]">
                            Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-[#52665f] pt-2 border-t border-[#edf2ee]">
                <span><strong class="text-[#122620]">{{ $campaign->donor_count }}</strong> Donasi</span>
                <span>
                    @if($isAccepting)
                        <strong class="text-sky-700">{{ $remainingDays }}</strong> hari tersisa
                    @else
                        <span class="text-rose-600 font-semibold">Berakhir</span>
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <a href="{{ route('campaigns.show', $campaign->slug) }}"
                   class="kp-btn-secondary py-2 px-3 text-xs">
                    Detail
                </a>

                @if($isAccepting)
                    <a href="{{ route('donations.create', $campaign->slug) }}"
                       class="kp-btn-primary py-2 px-3 text-xs">
                        Donasi
                    </a>
                @else
                    <span class="text-center py-2 px-3 rounded-full bg-[#edf2ee] text-[#7a8c85] text-xs font-bold cursor-not-allowed">
                        Ditutup
                    </span>
                @endif
            </div>
        </div>
    </div>
</article>
