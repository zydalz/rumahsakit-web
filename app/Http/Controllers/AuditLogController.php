<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuditLogController extends Controller
{
    /**
     * Menampilkan daftar riwayat audit log aktivitas operasional rumah sakit.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('id');

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->query('modul'));
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->query('role'));
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->query('tanggal_mulai')));
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->query('tanggal_selesai')));
        }

        // Pencarian kata kunci
        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('deskripsi', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%")
                    ->orWhere('aksi', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }

        $auditLogs = $query->paginate(20)->withQueryString();

        // Opsi modul dan role untuk filter dropdown
        $modulList = [
            'Pendaftaran',
            'Pelayanan Poli',
            'Pemeriksaan Medis',
            'Kasir & Tagihan',
            'Farmasi',
            'Master Data',
            'Autentikasi',
        ];

        $roleList = [
            'admin' => 'Admin Sistem',
            'dokter' => 'Dokter',
            'perawat' => 'Perawat / Staf',
            'kasir' => 'Kasir',
            'farmasi' => 'Farmasi / Apoteker',
            'kepala_rs' => 'Kepala Rumah Sakit',
            'pasien' => 'Pasien',
        ];

        $totalHariIni = AuditLog::whereDate('created_at', Carbon::today())->count();
        $totalAktivitas = AuditLog::count();

        return view('admin.audit_log.index', compact(
            'auditLogs',
            'modulList',
            'roleList',
            'totalHariIni',
            'totalAktivitas'
        ));
    }
}
