<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_DEFAULT_PASSWORD') ?: 'password123';

        if (! is_string($password) || strlen($password) < 8) {
            $password = 'password123';
        }

        // Transisi halus: Perbarui akun lama dengan domain @oasishusada.test menjadi @gmail.com jika ada
        $emailMigrations = [
            'admin@oasishusada.test' => 'admin@gmail.com',
            'kepala.rs@oasishusada.test' => 'kepala.rs@gmail.com',
            'farmasi@oasishusada.test' => 'farmasi@gmail.com',
            'kasir@oasishusada.test' => 'kasir@gmail.com',
            'dokter.andi@oasishusada.test' => 'dokter.andi@gmail.com',
            'dokter.siti@oasishusada.test' => 'dokter.siti@gmail.com',
            'pasien.budi@oasishusada.test' => 'pasien.budi@gmail.com',
            'pasien.siti@oasishusada.test' => 'pasien.siti@gmail.com',
            'pasien.rina@oasishusada.test' => 'pasien.rina@gmail.com',
        ];

        foreach ($emailMigrations as $oldEmail => $newEmail) {
            $existingNew = User::where('email', $newEmail)->first();
            $existingOld = User::where('email', $oldEmail)->first();

            if ($existingOld && ! $existingNew) {
                $existingOld->update(['email' => $newEmail]);
            }
        }

        $accounts = [
            // Pimpinan & Manajemen
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'dr. H. Budi Santoso, MARS',
                'email' => 'kepala.rs@gmail.com',
                'role' => 'kepala_rs',
            ],
            // Tenaga Farmasi & Kasir
            [
                'name' => 'Staf Farmasi',
                'email' => 'farmasi@gmail.com',
                'role' => 'farmasi',
            ],
            [
                'name' => 'Staf Kasir',
                'email' => 'kasir@gmail.com',
                'role' => 'kasir',
            ],
            // Dokter Poli Umum
            [
                'name' => 'dr. Andi Pratama',
                'email' => 'dokter.andi@gmail.com',
                'role' => 'dokter',
            ],
            [
                'name' => 'dr. Dina Mariana',
                'email' => 'dokter.dina@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Gigi
            [
                'name' => 'drg. Siti Rahma',
                'email' => 'dokter.siti@gmail.com',
                'role' => 'dokter',
            ],
            [
                'name' => 'drg. Fajar Nugraha',
                'email' => 'dokter.fajar@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Anak
            [
                'name' => 'dr. Bambang Hermanto, Sp.A',
                'email' => 'dokter.bambang@gmail.com',
                'role' => 'dokter',
            ],
            [
                'name' => 'dr. Nurul Hidayah, Sp.A',
                'email' => 'dokter.nurul@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Penyakit Dalam
            [
                'name' => 'dr. Dewi Lestari, Sp.PD',
                'email' => 'dokter.dewi@gmail.com',
                'role' => 'dokter',
            ],
            [
                'name' => 'dr. Hendra Gunawan, Sp.PD',
                'email' => 'dokter.hendra@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Kandungan & Kebidanan
            [
                'name' => 'dr. Farhan Maulana, Sp.OG',
                'email' => 'dokter.farhan@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Mata
            [
                'name' => 'dr. Maya Kusuma, Sp.M',
                'email' => 'dokter.maya@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli Bedah
            [
                'name' => 'dr. Rizki Aditya, Sp.B',
                'email' => 'dokter.rizki@gmail.com',
                'role' => 'dokter',
            ],
            // Dokter Poli THT
            [
                'name' => 'dr. Anita Wulandari, Sp.THT-KL',
                'email' => 'dokter.anita@gmail.com',
                'role' => 'dokter',
            ],
            // Pasien
            [
                'name' => 'Budi Santoso',
                'email' => 'pasien.budi@gmail.com',
                'role' => 'pasien',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'pasien.siti@gmail.com',
                'role' => 'pasien',
            ],
            [
                'name' => 'Rina Lestari',
                'email' => 'pasien.rina@gmail.com',
                'role' => 'pasien',
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($password),
                    'role' => $account['role'],
                    'is_active' => true,
                ]
            );
        }
    }
}
