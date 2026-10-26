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
                'email' => 'pasien.budi@gmail.com',
                'nama' => 'Budi Santoso',
                'nomor_rm' => 'RM-OAS-0001',
                'nik' => '3171011205980001',
                'tanggal_lahir' => '1998-05-12',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Salemba Raya No. 12, Senen, Jakarta Pusat',
                'no_telepon' => '081300000001',
            ],
            [
                'email' => 'pasien.siti@gmail.com',
                'nama' => 'Siti Aminah',
                'nomor_rm' => 'RM-OAS-0002',
                'nik' => '3174032109950002',
                'tanggal_lahir' => '1995-09-21',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Tebet Barat Dalam No. 45, Jakarta Selatan',
                'no_telepon' => '081300000002',
            ],
            [
                'email' => 'pasien.rina@gmail.com',
                'nama' => 'Rina Lestari',
                'nomor_rm' => 'RM-OAS-0003',
                'nik' => '3175021002010003',
                'tanggal_lahir' => '2001-02-10',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Rawamangun Muka No. 8, Jakarta Timur',
                'no_telepon' => '081300000003',
            ],
        ];

        DB::transaction(function () use ($pasienData) {
            foreach ($pasienData as $data) {
                $user = User::where('email', $data['email'])->first();

                if (! $user) {
                    continue;
                }

                Pasien::updateOrCreate(
                    ['nomor_rm' => $data['nomor_rm']],
                    [
                        'user_id' => $user->id,
                        'nama' => $data['nama'],
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
