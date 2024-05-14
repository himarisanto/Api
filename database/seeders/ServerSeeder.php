<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Server;
use Illuminate\Support\Str;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        for ($i = 0; $i < 26; $i++) {
            Server::create([
                'nama_koneksi' => 'Server ' . ($i + 1),
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => 3306,
                'username' => 'root',
                'password' => Str::random(10),
                'note' => 'Dummy server ' . ($i + 1),
            ]);
        }
    }
}
