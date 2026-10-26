<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $poliData = [
                [
                    'nama_poli' => 'Poli Umum',
                    'deskripsi' => 'Pelayanan kesehatan umum, pemeriksaan awal, dan penanganan keluhan medis dasar.',
                ],
                [
                    'nama_poli' => 'Poli Gigi',
                    'deskripsi' => 'Pemeriksaan, penambalan, pembersihan karang, dan perawatan kesehatan gigi serta mulut.',
                ],
                [
                    'nama_poli' => 'Poli Anak',
                    'deskripsi' => 'Pelayanan kesehatan bayi dan anak, imunisasi, tumbuh kembang, dan infeksi anak.',
                ],
                [
                    'nama_poli' => 'Poli Penyakit Dalam',
                    'deskripsi' => 'Diagnosis dan penanganan medis non-bedah untuk penyakit organ dalam dewasa.',
                ],
                [
                    'nama_poli' => 'Poli Kandungan & Kebidanan',
                    'deskripsi' => 'Pemeriksaan kehamilan, USG kandungan, program hamil, dan kesehatan reproduksi wanita.',
                ],
                [
                    'nama_poli' => 'Poli Mata',
                    'deskripsi' => 'Pemeriksaan refraksi, gangguan penglihatan, katarak, dan kesehatan bola mata.',
                ],
                [
                    'nama_poli' => 'Poli Bedah',
                    'deskripsi' => 'Konsultasi tindakan bedah minor dan mayor, perawatan luka pascaoperasi, dan trauma.',
                ],
                [
                    'nama_poli' => 'Poli THT',
                    'deskripsi' => 'Pemeriksaan dan terapi gangguan telinga, hidung, tenggorokan, kepala, dan leher.',
                ],
            ];

            foreach ($poliData as $data) {
                Poli::updateOrCreate(
                    ['nama_poli' => $data['nama_poli']],
                    [
                        'deskripsi' => $data['deskripsi'],
                        'is_active' => true,
                    ]
                );
            }

            $dokterData = [
                // Poli Umum
                [
                    'email' => 'dokter.andi@gmail.com',
                    'poli' => 'Poli Umum',
                    'sip' => 'SIP-001/DU/2022',
                    'telepon' => '081200000001',
                ],
                [
                    'email' => 'dokter.dina@gmail.com',
                    'poli' => 'Poli Umum',
                    'sip' => 'SIP-002/DU/2023',
                    'telepon' => '081200000002',
                ],
                // Poli Gigi
                [
                    'email' => 'dokter.siti@gmail.com',
                    'poli' => 'Poli Gigi',
                    'sip' => 'SIP-003/DG/2021',
                    'telepon' => '081200000003',
                ],
                [
                    'email' => 'dokter.fajar@gmail.com',
                    'poli' => 'Poli Gigi',
                    'sip' => 'SIP-004/DG/2022',
                    'telepon' => '081200000004',
                ],
                // Poli Anak
                [
                    'email' => 'dokter.bambang@gmail.com',
                    'poli' => 'Poli Anak',
                    'sip' => 'SIP-005/SPA/2020',
                    'telepon' => '081200000005',
                ],
                [
                    'email' => 'dokter.nurul@gmail.com',
                    'poli' => 'Poli Anak',
                    'sip' => 'SIP-006/SPA/2022',
                    'telepon' => '081200000006',
                ],
                // Poli Penyakit Dalam
                [
                    'email' => 'dokter.dewi@gmail.com',
                    'poli' => 'Poli Penyakit Dalam',
                    'sip' => 'SIP-007/SPPD/2019',
                    'telepon' => '081200000007',
                ],
                [
                    'email' => 'dokter.hendra@gmail.com',
                    'poli' => 'Poli Penyakit Dalam',
                    'sip' => 'SIP-008/SPPD/2021',
                    'telepon' => '081200000008',
                ],
                // Poli Kandungan & Kebidanan
                [
                    'email' => 'dokter.farhan@gmail.com',
                    'poli' => 'Poli Kandungan & Kebidanan',
                    'sip' => 'SIP-009/SPOG/2020',
                    'telepon' => '081200000009',
                ],
                // Poli Mata
                [
                    'email' => 'dokter.maya@gmail.com',
                    'poli' => 'Poli Mata',
                    'sip' => 'SIP-010/SPM/2021',
                    'telepon' => '081200000010',
                ],
                // Poli Bedah
                [
                    'email' => 'dokter.rizki@gmail.com',
                    'poli' => 'Poli Bedah',
                    'sip' => 'SIP-011/SPB/2018',
                    'telepon' => '081200000011',
                ],
                // Poli THT
                [
                    'email' => 'dokter.anita@gmail.com',
                    'poli' => 'Poli THT',
                    'sip' => 'SIP-012/SPTHT/2022',
                    'telepon' => '081200000012',
                ],
            ];

            foreach ($dokterData as $data) {
                $user = User::where('email', $data['email'])->first();
                $poli = Poli::where('nama_poli', $data['poli'])->first();

                if (! $user || ! $poli) {
                    continue;
                }

                Dokter::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'poli_id' => $poli->id,
                        'nomor_sip' => $data['sip'],
                        'no_telepon' => $data['telepon'],
                        'is_active' => true,
                    ]
                );
            }

            $jadwalData = [
                // Dokter Andi (Poli Umum)
                ['email' => 'dokter.andi@gmail.com', 'hari' => 'Senin', 'mulai' => '08:00:00', 'selesai' => '12:00:00', 'kuota' => 20],
                ['email' => 'dokter.andi@gmail.com', 'hari' => 'Rabu', 'mulai' => '08:00:00', 'selesai' => '12:00:00', 'kuota' => 20],
                ['email' => 'dokter.andi@gmail.com', 'hari' => 'Jumat', 'mulai' => '08:00:00', 'selesai' => '11:30:00', 'kuota' => 15],
                // Dokter Dina (Poli Umum)
                ['email' => 'dokter.dina@gmail.com', 'hari' => 'Selasa', 'mulai' => '13:00:00', 'selesai' => '17:00:00', 'kuota' => 20],
                ['email' => 'dokter.dina@gmail.com', 'hari' => 'Kamis', 'mulai' => '13:00:00', 'selesai' => '17:00:00', 'kuota' => 20],
                ['email' => 'dokter.dina@gmail.com', 'hari' => 'Sabtu', 'mulai' => '08:00:00', 'selesai' => '12:00:00', 'kuota' => 15],

                // Dokter Siti (Poli Gigi)
                ['email' => 'dokter.siti@gmail.com', 'hari' => 'Selasa', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],
                ['email' => 'dokter.siti@gmail.com', 'hari' => 'Kamis', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],
                ['email' => 'dokter.siti@gmail.com', 'hari' => 'Sabtu', 'mulai' => '09:00:00', 'selesai' => '12:00:00', 'kuota' => 12],
                // Dokter Fajar (Poli Gigi)
                ['email' => 'dokter.fajar@gmail.com', 'hari' => 'Senin', 'mulai' => '13:00:00', 'selesai' => '17:00:00', 'kuota' => 15],
                ['email' => 'dokter.fajar@gmail.com', 'hari' => 'Rabu', 'mulai' => '13:00:00', 'selesai' => '17:00:00', 'kuota' => 15],
                ['email' => 'dokter.fajar@gmail.com', 'hari' => 'Jumat', 'mulai' => '13:30:00', 'selesai' => '17:00:00', 'kuota' => 12],

                // Dokter Bambang (Poli Anak)
                ['email' => 'dokter.bambang@gmail.com', 'hari' => 'Senin', 'mulai' => '08:30:00', 'selesai' => '12:30:00', 'kuota' => 20],
                ['email' => 'dokter.bambang@gmail.com', 'hari' => 'Rabu', 'mulai' => '08:30:00', 'selesai' => '12:30:00', 'kuota' => 20],
                ['email' => 'dokter.bambang@gmail.com', 'hari' => 'Jumat', 'mulai' => '08:30:00', 'selesai' => '11:30:00', 'kuota' => 15],
                // Dokter Nurul (Poli Anak)
                ['email' => 'dokter.nurul@gmail.com', 'hari' => 'Selasa', 'mulai' => '10:00:00', 'selesai' => '14:00:00', 'kuota' => 20],
                ['email' => 'dokter.nurul@gmail.com', 'hari' => 'Kamis', 'mulai' => '10:00:00', 'selesai' => '14:00:00', 'kuota' => 20],
                ['email' => 'dokter.nurul@gmail.com', 'hari' => 'Sabtu', 'mulai' => '08:30:00', 'selesai' => '12:00:00', 'kuota' => 15],

                // Dokter Dewi (Poli Penyakit Dalam)
                ['email' => 'dokter.dewi@gmail.com', 'hari' => 'Senin', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 18],
                ['email' => 'dokter.dewi@gmail.com', 'hari' => 'Rabu', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 18],
                ['email' => 'dokter.dewi@gmail.com', 'hari' => 'Kamis', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 18],
                // Dokter Hendra (Poli Penyakit Dalam)
                ['email' => 'dokter.hendra@gmail.com', 'hari' => 'Selasa', 'mulai' => '13:00:00', 'selesai' => '17:00:00', 'kuota' => 18],
                ['email' => 'dokter.hendra@gmail.com', 'hari' => 'Jumat', 'mulai' => '13:30:00', 'selesai' => '17:00:00', 'kuota' => 15],
                ['email' => 'dokter.hendra@gmail.com', 'hari' => 'Sabtu', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],

                // Dokter Farhan (Poli Kandungan & Kebidanan)
                ['email' => 'dokter.farhan@gmail.com', 'hari' => 'Senin', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],
                ['email' => 'dokter.farhan@gmail.com', 'hari' => 'Rabu', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],
                ['email' => 'dokter.farhan@gmail.com', 'hari' => 'Jumat', 'mulai' => '08:30:00', 'selesai' => '11:30:00', 'kuota' => 12],

                // Dokter Maya (Poli Mata)
                ['email' => 'dokter.maya@gmail.com', 'hari' => 'Selasa', 'mulai' => '08:30:00', 'selesai' => '12:30:00', 'kuota' => 15],
                ['email' => 'dokter.maya@gmail.com', 'hari' => 'Kamis', 'mulai' => '08:30:00', 'selesai' => '12:30:00', 'kuota' => 15],

                // Dokter Rizki (Poli Bedah)
                ['email' => 'dokter.rizki@gmail.com', 'hari' => 'Senin', 'mulai' => '10:00:00', 'selesai' => '14:00:00', 'kuota' => 12],
                ['email' => 'dokter.rizki@gmail.com', 'hari' => 'Kamis', 'mulai' => '10:00:00', 'selesai' => '14:00:00', 'kuota' => 12],

                // Dokter Anita (Poli THT)
                ['email' => 'dokter.anita@gmail.com', 'hari' => 'Rabu', 'mulai' => '09:00:00', 'selesai' => '13:00:00', 'kuota' => 15],
                ['email' => 'dokter.anita@gmail.com', 'hari' => 'Sabtu', 'mulai' => '09:00:00', 'selesai' => '12:00:00', 'kuota' => 12],
            ];

            foreach ($jadwalData as $data) {
                $user = User::where('email', $data['email'])->first();

                $dokter = $user
                    ? Dokter::where('user_id', $user->id)->first()
                    : null;

                if (! $dokter) {
                    continue;
                }

                JadwalDokter::updateOrCreate(
                    [
                        'dokter_id' => $dokter->id,
                        'hari' => $data['hari'],
                        'jam_mulai' => $data['mulai'],
                        'jam_selesai' => $data['selesai'],
                    ],
                    [
                        'kuota' => $data['kuota'],
                        'is_active' => true,
                    ]
                );
            }

            $obatData = [
                [
                    'kode_obat' => 'OBT-001',
                    'nama_obat' => 'Paracetamol 500 mg',
                    'satuan' => 'Tablet',
                    'harga' => 1500,
                    'stok' => 200,
                    'stok_minimum' => 20,
                ],
                [
                    'kode_obat' => 'OBT-002',
                    'nama_obat' => 'Amoxicillin 500 mg',
                    'satuan' => 'Kapsul',
                    'harga' => 2500,
                    'stok' => 150,
                    'stok_minimum' => 20,
                ],
                [
                    'kode_obat' => 'OBT-003',
                    'nama_obat' => 'Antasida',
                    'satuan' => 'Tablet',
                    'harga' => 1000,
                    'stok' => 120,
                    'stok_minimum' => 15,
                ],
                [
                    'kode_obat' => 'OBT-004',
                    'nama_obat' => 'Oralit',
                    'satuan' => 'Sachet',
                    'harga' => 2000,
                    'stok' => 100,
                    'stok_minimum' => 15,
                ],
                [
                    'kode_obat' => 'OBT-005',
                    'nama_obat' => 'Vitamin C 500 mg',
                    'satuan' => 'Tablet',
                    'harga' => 1200,
                    'stok' => 180,
                    'stok_minimum' => 20,
                ],
            ];

            foreach ($obatData as $data) {
                Obat::firstOrCreate(
                    ['kode_obat' => $data['kode_obat']],
                    [
                        'nama_obat' => $data['nama_obat'],
                        'satuan' => $data['satuan'],
                        'harga' => $data['harga'],
                        'stok' => $data['stok'],
                        'stok_minimum' => $data['stok_minimum'],
                        'is_active' => true,
                    ]
                );
            }

            $galeriData = [
                [
                    'judul' => 'Pelayanan Kesehatan Modern & Prima RS Oasis Husada',
                    'kategori' => 'banner',
                    'file_path' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=1200&q=85',
                    'deskripsi' => 'Tenaga dokter spesialis profesional siap memberikan pelayanan terbaik dengan fasilitas medis berstandar tinggi.',
                    'urutan' => 1,
                ],
                [
                    'judul' => 'Instalasi Gawat Darurat (UGD 24 Jam)',
                    'kategori' => 'fasilitas',
                    'file_path' => 'https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Pelayanan tanggap darurat 24 jam dengan armada ambulans siaga dan tim trauma medis bersertifikasi.',
                    'urutan' => 1,
                ],
                [
                    'judul' => 'Laboratorium Diagnostik Klinis Terpadu',
                    'kategori' => 'fasilitas',
                    'file_path' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Pemeriksaan hematologi, kimia darah, dan serologi dengan alat otomatis akurat dan cepat.',
                    'urutan' => 2,
                ],
                [
                    'judul' => 'Instalasi Farmasi & Apotek Ramah Pasien',
                    'kategori' => 'fasilitas',
                    'file_path' => 'https://images.unsplash.com/photo-1586015555751-63bb77f4322a?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Ketersediaan obat bermutu tinggi dengan sistem antrean obat terkomputerisasi.',
                    'urutan' => 3,
                ],
                [
                    'judul' => 'Ruang Rawat Inap & Poliklinik Nyaman',
                    'kategori' => 'fasilitas',
                    'file_path' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Lingkungan perawatan yang bersih, tenang, dan higienis demi kenyamanan pemulihan pasien.',
                    'urutan' => 4,
                ],
                [
                    'judul' => 'Pemeriksaan Kesehatan Rutin Masyarakat',
                    'kategori' => 'kegiatan',
                    'file_path' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Kegiatan bakti sosial dan skrining kesehatan berkala bagi warga sekitar rumah sakit.',
                    'urutan' => 1,
                ],
                [
                    'judul' => 'Seminar Edukasi Pencegahan Penyakit Dalam',
                    'kategori' => 'kegiatan',
                    'file_path' => 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Penyuluhan pola hidup sehat dan pencegahan dini penyakit kardiovaskular bersama dokter spesialis.',
                    'urutan' => 2,
                ],
            ];

            foreach ($galeriData as $data) {
                \App\Models\GaleriAset::updateOrCreate(
                    ['judul' => $data['judul']],
                    [
                        'kategori' => $data['kategori'],
                        'file_path' => $data['file_path'],
                        'deskripsi' => $data['deskripsi'],
                        'urutan' => $data['urutan'],
                        'is_active' => true,
                    ]
                );
            }
        });
    }
}
