<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_and_patient_profile(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Pasien Percobaan',
            'email' => 'pasien@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'pasien@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('pasien', $user->role);
        $this->assertTrue((bool) $user->is_active);

        $pasien = Pasien::where('user_id', $user->id)->first();

        $this->assertNotNull($pasien);
        $this->assertSame('Pasien Percobaan', $pasien->nama);
        $this->assertNotEmpty($pasien->nomor_rm);
        $this->assertTrue($this->isAuthenticated());
    }

    private function isAuthenticated(): bool
    {
        return auth()->check();
    }
}
