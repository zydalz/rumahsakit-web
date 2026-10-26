<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran - {{ $tagihan->nomor_tagihan }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #222;
            background: #f3f4f6;
            margin: 0;
            padding: 24px;
        }

        .nota {
            max-width: 420px;
            margin: 0 auto;
            padding: 28px;
            background: white;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 1px dashed #999;
        }

        .header .logo {
            max-height: 52px;
            width: auto;
            margin: 0 auto 8px;
            display: block;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 21px;
            color: #173D59;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
        }

        .judul {
            margin: 18px 0;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .baris {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin: 10px 0;
            font-size: 13px;
        }

        .label {
            color: #666;
        }

        .nilai {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .pemisah {
            border: 0;
            border-top: 1px dashed #999;
            margin: 16px 0;
        }

        .nominal {
            font-size: 18px;
            font-weight: bold;
            color: #166534;
        }

        .sisa {
            font-weight: bold;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
        }

        .aksi {
            max-width: 420px;
            margin: 16px auto;
            text-align: center;
        }

        .tombol {
            display: inline-block;
            border: 0;
            border-radius: 6px;
            padding: 10px 16px;
            margin: 4px;
            background: #173D59;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 5mm;
            }

            body {
                padding: 0;
                margin: 0;
                background: white;
            }

            .nota {
                width: 100%;
                max-width: none;
                padding: 4px;
                border: none;
                margin: 0;
            }

            .aksi {
                display: none !important;
            }
        }
    </style>
</head>
<body>

@php
    $pasien = $tagihan->pendaftaran?->pasien;
@endphp

<div class="nota">
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo PT Oasis Husada" class="logo">
        <h1>PT OASIS HUSADA</h1>
        <p>BUKTI PEMBAYARAN</p>
        <p>Dokumen transaksi kasir</p>
    </div>

    <div class="judul">NOTA PEMBAYARAN</div>

    <div class="baris">
        <span class="label">No. Nota</span>
        <span class="nilai">BYR-{{ $pembayaran->id }}</span>
    </div>

    <div class="baris">
        <span class="label">No. Tagihan</span>
        <span class="nilai">{{ $tagihan->nomor_tagihan }}</span>
    </div>

    <div class="baris">
        <span class="label">Tanggal</span>
        <span class="nilai">
            {{ \Illuminate\Support\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
        </span>
    </div>

    <hr class="pemisah">

    <div class="baris">
        <span class="label">Nama Pasien</span>
        <span class="nilai">{{ $pasien?->nama ?? '-' }}</span>
    </div>

    <div class="baris">
        <span class="label">No. Rekam Medis</span>
        <span class="nilai">{{ $pasien?->nomor_rm ?? '-' }}</span>
    </div>

    <div class="baris">
        <span class="label">Metode Pembayaran</span>
        <span class="nilai">{{ strtoupper($pembayaran->metode) }}</span>
    </div>

    @if($pembayaran->referensi)
        <div class="baris">
            <span class="label">Referensi</span>
            <span class="nilai">{{ $pembayaran->referensi }}</span>
        </div>
    @endif

    <hr class="pemisah">

    <div class="baris">
        <span class="label">Total Tagihan</span>
        <span class="nilai">
            Rp{{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
        </span>
    </div>

    <div class="baris">
        <span class="label">Pembayaran Saat Ini</span>
        <span class="nilai nominal">
            Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
        </span>
    </div>

    <div class="baris">
        <span class="label">Total Sudah Dibayar</span>
        <span class="nilai">
            Rp{{ number_format($totalDibayar, 0, ',', '.') }}
        </span>
    </div>

    <div class="baris sisa">
        <span>Sisa Tagihan</span>
        <span class="nilai">
            Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
        </span>
    </div>

    <hr class="pemisah">

    <div class="baris">
        <span class="label">Status Transaksi</span>
        <span class="nilai">BERHASIL</span>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembayaran Anda.</p>
        <p>Simpan nota ini sebagai bukti transaksi.</p>
        <p>Nota ini merupakan bukti pembayaran yang tercatat di sistem.</p>
    </div>
</div>

<div class="aksi">
    <button class="tombol" onclick="window.print()">Cetak Nota</button>
    <button class="tombol" onclick="window.close()">Tutup</button>
</div>

</body>
</html>