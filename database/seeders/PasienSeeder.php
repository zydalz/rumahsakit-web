<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        $pasienData = [
            [
                'email' => 'pasien.budi@oasishusada.test',
                'nama' => 'Budi Santoso',
                'nomor_rm' => 'RM-OAS-0001',
                'nik' => '0000000000000001',
                'tanggal_lahir' => '1998-05-12',
                'jenis_kelamin' => 'L',
                'alamat' => 'Alamat contoh, Jakarta',
                'no_telepon' => '081300000001',
            ],
            [
                'email' => 'pasien.siti@oasishusada.test',
                'nama' => 'Siti Aminah',
                'nomor_rm' => 'RM-OAS-0002',
                'nik' => '0000000000000002',
                'tanggal_lahir' => '1995-09-21',
                'jenis_kelamin' => 'P',
                'alamat' => 'Alamat contoh, Jakarta',
                'no_telepon' => '081300000002',
            ],
            [
                'email' => 'pasien.rina@oasishusada.test',
                'nama' => 'Rina Lestari',
                'nomor_rm' => 'RM-OAS-0003',
                'nik' => '0000000000000003',
                'tanggal_lahir' => '2001-02-10',
                'jenis_kelamin' => 'P',
                'alamat' => 'Alamat contoh, Jakarta',
                'no_telepon' => '081300000003',
            ],
        ];

        DB::transaction(function () use ($pasienData) {
            foreach ($pasienData as $data) {
                $user = User::where('email', $data['email'])->first();

                if (! $user) {
                    continue;
                }

                Pasien::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama' => $data['nama'],
                        'nomor_rm' => $data['nomor_rm'],
                        'nik' => $data['nik'],
                        'tanggal_lahir' => $data['tanggal_lahir'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'alamat' => $data['alamat'],
                        'no_telepon' => $data['no_telepon'],
                    ]
                );
            }
        });
    }
}
