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
                'golongan_darah' => 'O',
                'riwayat_alergi' => 'Alergi Dingin, Penisilin',
                'alamat' => 'Jl. Salemba Raya No. 12, Senen, Jakarta Pusat',
                'no_telepon' => '081300000001',
                'kontak_darurat' => '081299881122',
                'hubungan_darurat' => 'Istri',
            ],
            [
                'email' => 'pasien.siti@gmail.com',
                'nama' => 'Siti Aminah',
                'nomor_rm' => 'RM-OAS-0002',
                'nik' => '3174032109950002',
                'tanggal_lahir' => '1995-09-21',
                'jenis_kelamin' => 'P',
                'golongan_darah' => 'B',
                'riwayat_alergi' => 'Alergi Udang & Makanan Laut',
                'alamat' => 'Jl. Tebet Barat Dalam No. 45, Jakarta Selatan',
                'no_telepon' => '081300000002',
                'kontak_darurat' => '081299883344',
                'hubungan_darurat' => 'Suami',
            ],
            [
                'email' => 'pasien.rina@gmail.com',
                'nama' => 'Rina Lestari',
                'nomor_rm' => 'RM-OAS-0003',
                'nik' => '3175021002010003',
                'tanggal_lahir' => '2001-02-10',
                'jenis_kelamin' => 'P',
                'golongan_darah' => 'A',
                'riwayat_alergi' => null,
                'alamat' => 'Jl. Rawamangun Muka No. 8, Jakarta Timur',
                'no_telepon' => '081300000003',
                'kontak_darurat' => '081299885566',
                'hubungan_darurat' => 'Ibu Kandung',
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
                        'golongan_darah' => $data['golongan_darah'],
                        'riwayat_alergi' => $data['riwayat_alergi'],
                        'alamat' => $data['alamat'],
                        'no_telepon' => $data['no_telepon'],
                        'kontak_darurat' => $data['kontak_darurat'],
                        'hubungan_darurat' => $data['hubungan_darurat'],
                    ]
                );
            }
        });
    }
}
