<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class KepalaRumahSakitTest extends TestCase
{
    public function test_kepala_rs_dapat_mengakses_dashboard_dan_laporan(): void
    {
        $kepalaRs = User::factory()->create([
            'role' => 'kepala_rs',
            'is_active' => true,
        ]);

        // 1. Uji redirect dari /dashboard ke dashboard eksekutif
        $responseDashboardRedirect = $this->actingAs($kepalaRs)->get(route('dashboard'));
        $responseDashboardRedirect->assertRedirect(route('kepala-rs.dashboard'));

        // 2. Uji akses dashboard eksekutif
        $responseDashboard = $this->actingAs($kepalaRs)->get(route('kepala-rs.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Ringkasan Eksekutif Rumah Sakit');
        $responseDashboard->assertSee('Penerimaan Kasir Hari Ini');

        // 3. Uji akses laporan kinerja & pelayanan
        $responseLaporan = $this->actingAs($kepalaRs)->get(route('kepala-rs.laporan'));
        $responseLaporan->assertStatus(200);
        $responseLaporan->assertSee('Laporan Kinerja & Pelayanan Eksekutif');
        $responseLaporan->assertSee('10 Besar Kasus Penyakit (Morbiditas)');

        // 4. Uji filter periode pada laporan
        $responseFilter = $this->actingAs($kepalaRs)->get(route('kepala-rs.laporan', ['periode' => 'hari_ini']));
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Hari Ini');

        // 5. Uji halaman cetak laporan resmi
        $responseCetak = $this->actingAs($kepalaRs)->get(route('kepala-rs.laporan.cetak'));
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('RUMAH SAKIT OASIS HUSADA');
        $responseCetak->assertSee('LAPORAN EKSEKUTIF PELAYANAN');
        $responseCetak->assertSee('KINERJA RUMAH SAKIT');
    }

    public function test_role_lain_tidak_dapat_mengakses_dashboard_kepala_rs(): void
    {
        $pasien = User::factory()->create([
            'role' => 'pasien',
            'is_active' => true,
        ]);

        $response = $this->actingAs($pasien)->get(route('kepala-rs.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_juga_dapat_melihat_laporan_eksekutif(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('kepala-rs.laporan'));
        $response->assertStatus(200);
    }
}
