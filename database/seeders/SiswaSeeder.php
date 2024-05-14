<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 10000; $i++) {
     
            $imageName = $faker->image(storage_path('app/public/images'), 400, 300, null, false);

            Siswa::create([
                'gambar' => $imageName,
                'no_absen' => $faker->unique()->randomNumber(3),
                'nama' => $faker->name,
                'kelas' => $faker->randomElement(['X', 'XI', 'XII']),
                'jurusan' => $faker->randomElement(['RPL', 'Kuliner', 'DKV', 'TKRO', 'TKP']),
            ]);
        }
    }
}
    