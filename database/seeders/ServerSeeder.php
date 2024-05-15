<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use Faker\Factory as Faker;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Server::create([
                'nama_koneksi' => 'Server ' . ($i + 1),
                'driver' => $faker->randomElement(['mysql', 'pgsql', 'sqlite', 'sqlsrv']),
                'host' => $faker->ipv4,
                'port' => $faker->numberBetween(1024, 65535),
                'username' => $faker->userName,
                'password' => $faker->password,
                'note' => $faker->sentence,
            ]);
        }
    }
}
