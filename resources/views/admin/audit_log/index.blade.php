@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')
@section('header', 'Audit Log & Riwayat Aktivitas')

@section('content')
<div class="space-y-6">

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total Log Aktivitas</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($totalAktivitas, 0, ',', '.') }}</p>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-500">Seluruh aksi terekam dalam basis data audit</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Aktivitas Hari Ini</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($totalHariIni, 0, ',', '.') }}</p>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-500">Pencatatan real-time tanggal {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Status Keamanan</p>
                    <p class="text-base font-bold text-emerald-600 flex items-center gap-1.5">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Audit Trail Aktif
                    </p>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-500">Perekaman IP & User Agent otomatis</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ url()->current() }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Cari Kata Kunci</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, aksi, IP..."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Modul</label>
                    <select name="modul" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                        <option value="">Semua Modul</option>
                        @foreach ($modulList as $modul)
                            <option value="{{ $modul }}" {{ request('modul') === $modul ? 'selected' : '' }}>
                                {{ $modul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Peran Pengguna</label>
                    <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                        <option value="">Semua Peran</option>
                        @foreach ($roleList as $roleKey => $roleName)
                            <option value="{{ $roleKey }}" {{ request('role') === $roleKey ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                @if(request()->hasAny(['q', 'modul', 'role', 'tanggal_mulai', 'tanggal_selesai']))
                    <a href="{{ url()->current() }}" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2 text-xs font-medium text-white hover:bg-sky-800 shadow-sm transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat Aktivitas --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Catatan Aktivitas Operasional</h2>
            <span class="text-xs text-slate-500">Menampilkan {{ $auditLogs->firstItem() ?? 0 }} - {{ $auditLogs->lastItem() ?? 0 }} dari {{ $auditLogs->total() }} entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">Waktu (WIB)</th>
                        <th class="px-5 py-3.5 font-semibold">Modul</th>
                        <th class="px-5 py-3.5 font-semibold">Aksi</th>
                        <th class="px-5 py-3.5 font-semibold">Pengguna & Peran</th>
                        <th class="px-5 py-3.5 font-semibold">Rincian Aktivitas</th>
                        <th class="px-5 py-3.5 font-semibold">IP & Perangkat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($auditLogs as $log)
                        @php
                            $badgeColor = match($log->modul) {
                                'Pendaftaran' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'Pemeriksaan Medis' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Pelayanan Poli' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Kasir & Tagihan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Farmasi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'Master Data' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'Autentikasi' => 'bg-slate-100 text-slate-700 border-slate-300',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };

                            $roleBadge = match($log->role) {
                                'admin' => 'bg-red-50 text-red-700',
                                'dokter' => 'bg-emerald-50 text-emerald-700',
                                'farmasi' => 'bg-purple-50 text-purple-700',
                                'kasir' => 'bg-amber-50 text-amber-700',
                                'perawat' => 'bg-blue-50 text-blue-700',
                                'kepala_rs' => 'bg-indigo-50 text-indigo-700',
                                'pasien' => 'bg-teal-50 text-teal-700',
                                default => 'bg-slate-50 text-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-500 font-mono">
                                <div>{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="font-bold text-slate-700">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badgeColor }}">
                                    {{ $log->modul }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 font-medium text-slate-800">
                                {{ $log->aksi }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <div class="font-semibold text-slate-800 text-xs">{{ $log->user_name }}</div>
                                <div class="mt-0.5">
                                    <span class="inline-block rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $roleBadge }}">
                                        {{ $log->role }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600 leading-relaxed max-w-md">
                                {{ $log->deskripsi }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-[11px] text-slate-500 font-mono">
                                <div class="text-slate-700">{{ $log->ip_address ?? '127.0.0.1' }}</div>
                                <div class="truncate max-w-[140px] text-slate-400" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ? Str::limit($log->user_agent, 20) : '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada riwayat aktivitas yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($auditLogs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                {{ $auditLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
