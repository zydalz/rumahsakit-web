<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Pelayanan RS - {{ $labelPeriode }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            font-size: 11pt;
            line-height: 1.4;
            background: #fff;
        }
        .header-kop {
            display: flex;
            align-items: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-kop img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 18px;
        }
        .header-kop .instansi h1 {
            font-size: 16pt;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            color: #0369a1;
        }
        .header-kop .instansi p {
            font-size: 9pt;
            margin: 2px 0;
            color: #475569;
        }
        .judul-laporan {
            text-align: center;
            margin-bottom: 25px;
        }
        .judul-laporan h2 {
            font-size: 13pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
            color: #0f172a;
        }
        .judul-laporan p {
            font-size: 10pt;
            margin: 4px 0 0;
            color: #64748b;
        }
        .ringkasan-box {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin: 18px 0 8px;
            color: #0f172a;
            border-left: 4px solid #0284c7;
            padding-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5pt;
        }
        table th, table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .tanda-tangan {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .tanda-tangan-box {
            text-align: center;
            width: 250px;
            font-size: 10pt;
        }
        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0284c7;
            color: white;
            border: none;
            padding: 10px 18px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>

    {{-- Kop Surat Resmi RS Oasis Husada --}}
    <div class="header-kop">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Oasis Husada">
        <div class="instansi">
            <h1>RUMAH SAKIT OASIS HUSADA</h1>
            <p>Jl. Kesehatan Husada No. 10, Jakarta &bull; Telp: (021) 8899-7711 &bull; Email: kontak.oasishusada@gmail.com</p>
            <p>Layanan Rawat Jalan, Poliklinik Spesialis, Farmasi & Laboratorium</p>
        </div>
    </div>

    {{-- Judul Laporan --}}
    <div class="judul-laporan">
        <h2>LAPORAN EKSEKUTIF PELAYANAN & KINERJA RUMAH SAKIT</h2>
        <p>Periode Laporan: <strong>{{ $labelPeriode }}</strong> (Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB)</p>
    </div>

    {{-- Ringkasan Kunci --}}
    <div class="ringkasan-box">
        <div>
            Total Penerimaan Kasir: <strong>Rp {{ number_format($totalPendapatanPeriode, 0, ',', '.') }}</strong>
            ({{ $totalTransaksiPeriode }} Transaksi)
        </div>
        <div>
            Total Pasien Terdaftar: <strong>{{ $totalKunjunganPeriode }} Pasien</strong>
            ({{ $kunjunganSelesai }} Selesai)
        </div>
    </div>

    {{-- 1. Rekapitulasi Pembayaran Kasir --}}
    <div class="section-title">1. Rekapitulasi Penerimaan Kasir per Metode Pembayaran</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Metode Pembayaran</th>
                <th class="text-center" width="25%">Jumlah Transaksi</th>
                <th class="text-right" width="35%">Total Penerimaan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapPembayaran as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="text-transform: uppercase;">{{ $item->metode }}</td>
                    <td class="text-center">{{ $item->jumlah_transaksi }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada transaksi tercatat pada periode ini.</td>
                </tr>
            @endforelse
            @if($rekapPembayaran->isNotEmpty())
                <tr style="font-weight: bold; background: #f8fafc;">
                    <td colspan="2" class="text-right">Total Keseluruhan:</td>
                    <td class="text-center">{{ $totalTransaksiPeriode }}</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatanPeriode, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- 2. Rekapitulasi Kunjungan Poli --}}
    <div class="section-title">2. Sebaran Kunjungan Pasien per Poliklinik</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Nama Poliklinik</th>
                <th class="text-center" width="30%">Jumlah Pasien</th>
                <th class="text-right" width="25%">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kunjunganPerPoli as $index => $poli)
                @php
                    $persen = $totalKunjunganPeriode > 0 ? round(($poli->pendaftarans_count / $totalKunjunganPeriode) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $poli->nama_poli }}</td>
                    <td class="text-center">{{ $poli->pendaftarans_count }}</td>
                    <td class="text-right">{{ $persen }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada poliklinik terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 3. Top 10 Diagnosa Penyakit (Morbiditas) --}}
    <div class="section-title">3. 10 Besar Morbiditas Penyakit Terbanyak</div>
    <table>
        <thead>
            <tr>
                <th width="8%" class="text-center">Peringkat</th>
                <th>Diagnosis Klinis</th>
                <th class="text-right" width="25%">Jumlah Kasus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topDiagnosa as $index => $diag)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $diag->diagnosis }}</td>
                    <td class="text-right font-bold">{{ $diag->total_kasus }} Kasus</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada rekam medis pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 4. Kinerja Pelayanan Dokter --}}
    <div class="section-title">4. Kinerja & Beban Pelayanan Dokter</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Nama Dokter</th>
                <th>Poliklinik</th>
                <th class="text-right" width="30%">Pasien Selesai Diperiksa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kinerjaDokter as $index => $dok)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $dok->nama ?? $dok->user->name ?? 'Dokter' }}</td>
                    <td>{{ $dok->poli->nama_poli ?? '-' }}</td>
                    <td class="text-right font-bold">{{ $dok->pemeriksaans_count }} Pasien</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada dokter terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Lembar Pengesahan --}}
    <div class="tanda-tangan">
        <div class="tanda-tangan-box">
            <p>Jakarta, {{ now()->translatedFormat('d F Y') }}<br>Mengetahui,</p>
            <p style="font-weight: bold; margin-bottom: 70px;">Kepala Rumah Sakit Oasis Husada</p>
            <p style="text-decoration: underline; font-weight: bold; margin: 0;">dr. H. Budi Santoso, MARS</p>
            <p style="font-size: 9pt; color: #475569; margin: 0;">NIP. 19780512 200312 1 002</p>
        </div>
    </div>

</body>
</html>
