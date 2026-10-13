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
        $password = env('SEED_DEFAULT_PASSWORD');

        if (! is_string($password) || strlen($password) < 8) {
            throw new RuntimeException(
                'Tambahkan SEED_DEFAULT_PASSWORD dengan minimal 8 karakter pada .env lokal.'
            );
        }

        $accounts = [
            [
                'name' => 'Administrator',
                'email' => 'admin@oasishusada.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Staf Farmasi',
                'email' => 'farmasi@oasishusada.test',
                'role' => 'farmasi',
            ],
            [
                'name' => 'Staf Kasir',
                'email' => 'kasir@oasishusada.test',
                'role' => 'kasir',
            ],
            [
                'name' => 'Dr. Andi Pratama',
                'email' => 'dokter.andi@oasishusada.test',
                'role' => 'dokter',
            ],
            [
                'name' => 'Dr. Siti Rahma',
                'email' => 'dokter.siti@oasishusada.test',
                'role' => 'dokter',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'pasien.budi@oasishusada.test',
                'role' => 'pasien',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'pasien.siti@oasishusada.test',
                'role' => 'pasien',
            ],
            [
                'name' => 'Rina Lestari',
                'email' => 'pasien.rina@oasishusada.test',
                'role' => 'pasien',
            ],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
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
