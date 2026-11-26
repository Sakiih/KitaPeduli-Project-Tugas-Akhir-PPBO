@props(['campaign'])

@php
    $progress = $campaign->progressPercentage();
    $remainingDays = $campaign->remainingDays();
    $isAccepting = $campaign->isAcceptingDonations();
@endphp

<article class="bg-white rounded-3xl border border-slate-200/80 p-3.5 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col h-full">
    <div class="relative bg-slate-100 rounded-2xl overflow-hidden aspect-[16/10] mb-3.5">
        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="block w-full h-full">
            <img src="{{ $campaign->bannerUrl() }}"
                 alt="{{ $campaign->title }}"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </a>

        @if($campaign->category)
            <span class="absolute top-3 right-3 bg-white/95 backdrop-blur-xs text-slate-800 text-[11px] font-bold px-3 py-1 rounded-full shadow-2xs">
                {{ $campaign->category->name }}
            </span>
        @endif

        @if($campaign->is_featured)
            <span class="absolute top-3 left-3 bg-teal-600 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-2xs">
                Unggulan
            </span>
        @endif
    </div>

    <div class="px-1.5 flex-1 flex flex-col">
        <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
            <span class="font-medium truncate">{{ $campaign->organizer_name }}</span>
            @if($campaign->is_verified_organizer)
                <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 text-[10px] font-bold shrink-0">Terverifikasi</span>
            @endif
        </div>

        <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-2 mb-3">
            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="hover:text-teal-600 transition-colors">
                {{ $campaign->title }}
            </a>
        </h3>

        <div class="mt-auto space-y-2.5">
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-teal-600 rounded-full transition-all duration-300"
                     style="width: {{ $progress }}%"></div>
            </div>

            <div class="flex items-baseline justify-between gap-2">
                <div>
                    <p class="text-[11px] text-slate-400 font-medium">Dana Terkumpul</p>
                    <p class="text-sm font-extrabold text-slate-900">
                        Rp {{ number_format((float) $campaign->collected_amount, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] text-slate-400 font-medium">Target</p>
                    <p class="text-xs font-semibold text-slate-600">
                        Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                <span><strong class="text-slate-700">{{ $campaign->donor_count }}</strong> Donasi</span>
                <span>
                    @if($isAccepting)
                        <strong class="text-slate-700">{{ $remainingDays }}</strong> hari tersisa
                    @else
                        <span class="text-rose-600 font-semibold">Berakhir / Nonaktif</span>
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2">
                <a href="{{ route('campaigns.show', $campaign->slug) }}"
                   class="text-center py-2.5 px-3 rounded-full border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-800 text-xs font-bold transition-colors">
                    Lihat Detail
                </a>

                @if($isAccepting)
                    <a href="{{ route('donations.create', $campaign->slug) }}"
                       class="text-center py-2.5 px-3 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-bold transition-colors">
                        Donasi Sekarang
                    </a>
                @else
                    <span class="text-center py-2.5 px-3 rounded-full bg-slate-200 text-slate-500 text-xs font-bold cursor-not-allowed">
                        Ditutup
                    </span>
                @endif
            </div>
        </div>
    </div>
</article>
