<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean Ruang Tunggu - Oasis Husada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta http-equiv="refresh" content="15">
    <style>
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.02); }
        }
        .anim-panggil {
            animation: pulse-subtle 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col font-sans select-none">

    {{-- Header Display Monitor --}}
    <header class="bg-slate-800/90 border-b border-slate-700/80 px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Oasis Husada" class="h-12 w-12 rounded-xl bg-white p-1 object-contain shadow">
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-wide text-white">RS OASIS HUSADA</h1>
                <p class="text-xs md:text-sm text-sky-400 font-medium">Sistem Informasi Panggilan Antrean Poliklinik</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            {{-- Filter Poli --}}
            <form method="GET" action="{{ route('antrean.display') }}" class="flex items-center gap-2">
                <label for="poli_id" class="text-xs text-slate-400 font-medium hidden sm:inline">Poli:</label>
                <select name="poli_id" id="poli_id" onchange="this.form.submit()"
                        class="bg-slate-700 text-white text-xs md:text-sm rounded-lg px-3 py-1.5 border border-slate-600 focus:outline-none focus:border-sky-500">
                    <option value="">Semua Poliklinik</option>
                    @foreach($polis as $poli)
                        <option value="{{ $poli->id }}" {{ (string)$selectedPoliId === (string)$poli->id ? 'selected' : '' }}>
                            {{ $poli->nama_poli }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Jam Realtime --}}
            <div class="text-right">
                <div id="clock" class="text-lg md:text-2xl font-mono font-bold text-sky-300">--:--:--</div>
                <div class="text-xs text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>
    </header>

    {{-- Konten Utama Monitor --}}
    <main class="flex-1 p-6 md:p-8 grid grid-cols-1 lg:grid-cols-12 gap-6 max-w-[1600px] w-full mx-auto">

        {{-- Layar Panggilan Utama (Kiri / Kolom Besar) --}}
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="flex-1 bg-gradient-to-br from-slate-800 to-slate-800/80 rounded-3xl border border-slate-700 p-8 shadow-2xl flex flex-col justify-between items-center text-center relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <span class="inline-flex items-center gap-2 bg-sky-500/20 text-sky-400 px-4 py-1.5 rounded-full text-sm font-semibold tracking-wider uppercase border border-sky-500/30">
                        <span class="h-2.5 w-2.5 rounded-full bg-sky-400 animate-ping"></span>
                        Panggilan Masuk Ruang Periksa
                    </span>
                    <h2 class="mt-3 text-2xl md:text-3xl font-bold text-slate-200">
                        {{ $antreanSaatIni->poli->nama_poli ?? 'Poliklinik Oasis Husada' }}
                    </h2>
                </div>

                {{-- Nomor Antrean Raksasa --}}
                <div class="my-6 anim-panggil">
                    <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Nomor Antrean</p>
                    <div class="font-mono text-7xl md:text-9xl font-black text-white tracking-tight drop-shadow-md">
                        #{{ $antreanSaatIni ? str_pad($antreanSaatIni->nomor_antrean, 2, '0', STR_PAD_LEFT) : '--' }}
                    </div>
                </div>

                <div class="w-full max-w-lg bg-slate-900/80 rounded-2xl p-5 border border-slate-700/60 shadow-inner">
                    <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nama Pasien</div>
                    <div class="text-xl md:text-2xl font-bold text-sky-300">
                        {{ $antreanSaatIni->pasien->nama ?? 'Menunggu Panggilan Pasien Berikutnya' }}
                    </div>
                    @if($antreanSaatIni)
                        <div class="mt-2 text-xs text-slate-400 flex items-center justify-center gap-3">
                            <span>Perkiraan Jam: <strong class="text-slate-200">{{ $antreanSaatIni->jam_estimasi ?: '08:00 - 09:00' }}</strong></span>
                            <span>&bull;</span>
                            <span class="text-emerald-400 font-semibold">{{ ucfirst(str_replace('_', ' ', $antreanSaatIni->status)) }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-xs text-slate-400 mt-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Silakan masuk ke dalam ruang poliklinik saat nomor antrean Anda dipanggil.
                </div>
            </div>
        </div>

        {{-- Daftar Antrean Selanjutnya (Kanan / Kolom Samping) --}}
        <div class="lg:col-span-4 flex flex-col gap-6">

            {{-- Kartu Ringkasan Cepat --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-800/90 rounded-2xl p-4 border border-slate-700 text-center">
                    <span class="text-xs text-slate-400">Menunggu</span>
                    <p class="text-3xl font-extrabold text-amber-400 mt-1">{{ $antreanMenunggu->count() }}</p>
                </div>
                <div class="bg-slate-800/90 rounded-2xl p-4 border border-slate-700 text-center">
                    <span class="text-xs text-slate-400">Selesai Diperiksa</span>
                    <p class="text-3xl font-extrabold text-emerald-400 mt-1">{{ $totalSelesai }}</p>
                </div>
            </div>

            {{-- Daftar Tunggu Antrean --}}
            <div class="flex-1 bg-slate-800/90 rounded-3xl border border-slate-700 p-6 flex flex-col overflow-hidden shadow-xl">
                <h3 class="text-base font-bold text-slate-200 mb-3 flex items-center gap-2">
                    <svg class="h-5 w-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Antrean Menunggu Berikutnya
                </h3>

                <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                    @forelse($antreanMenunggu->take(6) as $menunggu)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-900/60 border border-slate-700/50 hover:border-slate-600 transition">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xl font-bold text-sky-400 bg-sky-950/60 px-2.5 py-1 rounded-lg border border-sky-800">
                                    #{{ str_pad($menunggu->nomor_antrean, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div>
                                    <p class="font-semibold text-sm text-slate-200">{{ $menunggu->pasien->nama ?? 'Pasien' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $menunggu->poli->nama_poli ?? '' }} &bull; {{ $menunggu->jam_estimasi ?: '08:00 - 09:00' }}</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-medium text-amber-400 bg-amber-950/40 px-2 py-0.5 rounded border border-amber-900">
                                Menunggu
                            </span>
                        </div>
                    @empty
                        <div class="h-40 flex flex-col items-center justify-center text-center text-slate-400 text-sm">
                            <p>Tidak ada pasien yang sedang menunggu antrean.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pasien Dilewati (Jika ada) --}}
                @if($antreanDilewati->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-slate-700/60">
                        <span class="text-xs font-semibold text-rose-400 uppercase tracking-wider block mb-2">
                            Antrean Dilewati (Belum Masuk):
                        </span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($antreanDilewati as $skip)
                                <span class="font-mono text-xs font-bold text-rose-300 bg-rose-950/50 border border-rose-800 px-2.5 py-1 rounded-lg">
                                    #{{ str_pad($skip->nomor_antrean, 2, '0', STR_PAD_LEFT) }} ({{ $skip->pasien->nama ?? '' }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </main>

    {{-- Footer Display --}}
    <footer class="bg-slate-950/80 border-t border-slate-800 px-6 py-3 text-center text-xs text-slate-500">
        Rumah Sakit Oasis Husada &bull; Layanan Cepat, Tanggap & Bersahabat &bull; Halaman diperbarui otomatis setiap 15 detik.
    </footer>

    {{-- Realtime Clock Script --}}
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
