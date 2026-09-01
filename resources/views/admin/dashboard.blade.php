@extends('layouts.admin')

@section('page_title', __('messages.admin_dashboard_overview'))

@section('content')
<div class="space-y-3">
    <!-- KPI Cards Grid (7 Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        {{-- Total Approved Members --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-primary-50 text-primary-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_members'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.approved_members') }}</p>
            </div>
        </div>

        {{-- Registered Businesses --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_businesses'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.registered_shops') }}</p>
            </div>
        </div>

        {{-- Total Events --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_events'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.total_events') }}</p>
            </div>
        </div>

        {{-- Passes Sold --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['passes_sold'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.passes_sold') }}</p>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-teal-50 text-teal-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-lg sm:text-xl font-black text-slate-900 leading-tight block truncate">₹{{ number_format($stats['total_revenue'], 0) }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.total_revenue') }}</p>
            </div>
        </div>

        {{-- Sponsorship Revenue --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-violet-50 text-violet-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13M5 12h14M5 12a2 2 0 110-4h14a2 2 0 110 4M12 8V5.5a2.5 2.5 0 10-2.5 2.5H12zm0 0V5.5A2.5 2.5 0 1114.5 8H12z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-lg sm:text-xl font-black text-slate-900 leading-tight block truncate">₹{{ number_format($stats['sponsorship_revenue'], 0) }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.sponsorship_revenue') }}</p>
            </div>
        </div>

        {{-- Pending Approvals --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-rose-50 text-rose-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.062 19h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.33 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </span>
            <div class="min-w-0">
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['pending_approvals'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.pending_approvals') }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        {{-- Registrations Trend --}}
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
            <div class="border-b border-slate-100 pb-2.5 mb-3">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.registrations_trend') }}</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.registrations_trend_desc') }}</p>
            </div>
            <div class="h-64">
                <canvas id="registrationsTrendChart"></canvas>
            </div>
        </div>

        {{-- Event Pass Purchases --}}
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
            <div class="border-b border-slate-100 pb-2.5 mb-3">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.event_pass_purchases') }}</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.event_pass_purchases_desc') }}</p>
            </div>
            <div class="h-64">
                <canvas id="passPurchasesChart"></canvas>
            </div>
        </div>

        {{-- Passes Sold by Event --}}
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
            <div class="border-b border-slate-100 pb-2.5 mb-3">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.pass_purchases_by_event') }}</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.pass_purchases_by_event_desc') }}</p>
            </div>
            <div class="h-64">
                @if(count($eventLabels))
                    <canvas id="passesByEventChart"></canvas>
                @else
                    <p class="h-full flex items-center justify-center text-center text-xs text-slate-400 font-medium">{{ __('messages.no_data_available') }}</p>
                @endif
            </div>
        </div>

        {{-- Revenue Breakdown --}}
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
            <div class="border-b border-slate-100 pb-2.5 mb-3">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.revenue_breakdown') }}</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.revenue_breakdown_desc') }}</p>
            </div>
            <div class="h-64">
                @if(array_sum($revenueBreakdown) > 0)
                    <canvas id="revenueBreakdownChart"></canvas>
                @else
                    <p class="h-full flex items-center justify-center text-center text-xs text-slate-400 font-medium">{{ __('messages.no_data_available') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- Recent Registrations -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.recent_member_signups') }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 hover:underline">{{ __('messages.manage_all') }} &rarr;</a>
            </div>

            <div class="space-y-2">
                @forelse($latestMembers as $m)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/70 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate leading-snug">{{ $m->name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ $m->email ?: ($m->mobile ?? '—') }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $m->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                            {{ __('messages.' . $m->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400 font-medium">{{ __('messages.no_recent_signups') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Businesses -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.recent_business_submissions') }}</h3>
                <a href="{{ route('admin.businesses.index') }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 hover:underline">{{ __('messages.manage_all') }} &rarr;</a>
            </div>

            <div class="space-y-2">
                @forelse($latestBusinesses as $b)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/70 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate leading-snug">{{ $b->business_name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ __('messages.owner_label', ['owner' => $b->owner_name]) }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $b->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                            {{ __('messages.' . $b->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400 font-medium">{{ __('messages.no_recent_businesses') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthLabels = @json($monthLabels);
        const memberTrend = @json($memberTrend);
        const businessTrend = @json($businessTrend);
        const passTrend = @json($passTrend);
        const eventLabels = @json($eventLabels);
        const eventValues = @json($eventValues);
        const revenueBreakdown = @json($revenueBreakdown);

        Chart.defaults.font.family = "'Inter', 'Figtree', system-ui, sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.color = '#64748b';

        new Chart(document.getElementById('registrationsTrendChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [
                    {
                        label: '{{ __('messages.total_members') }}',
                        data: memberTrend,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.1)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                    },
                    {
                        label: '{{ __('messages.total_businesses') }}',
                        data: businessTrend,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.1)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });

        new Chart(document.getElementById('passPurchasesChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [
                    {
                        label: '{{ __('messages.passes_sold') }}',
                        data: passTrend,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        maxBarThickness: 36,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });

        if (eventLabels.length) {
            new Chart(document.getElementById('passesByEventChart'), {
                type: 'bar',
                data: {
                    labels: eventLabels,
                    datasets: [
                        {
                            label: '{{ __('messages.passes_sold') }}',
                            data: eventValues,
                            backgroundColor: '#7c3aed',
                            borderRadius: 6,
                            maxBarThickness: 28,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        }

        const revenueTotal = Object.values(revenueBreakdown).reduce((a, b) => a + b, 0);
        if (revenueTotal > 0) {
            new Chart(document.getElementById('revenueBreakdownChart'), {
                type: 'doughnut',
                data: {
                    labels: [
                        '{{ __('messages.membership_fees') }}',
                        '{{ __('messages.business_fees') }}',
                        '{{ __('messages.event_fees') }}',
                        '{{ __('messages.sponsorship_fees') }}',
                    ],
                    datasets: [
                        {
                            data: [
                                revenueBreakdown.membership,
                                revenueBreakdown.business,
                                revenueBreakdown.events,
                                revenueBreakdown.sponsorship,
                            ],
                            backgroundColor: ['#4f46e5', '#059669', '#2563eb', '#7c3aed'],
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        }
    });
</script>
@endpush
@endsection
