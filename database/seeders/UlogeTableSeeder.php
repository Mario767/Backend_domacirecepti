<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Uloga;

class UlogeTableSeeder extends Seeder
{
    public function run()
    {
        Uloga::create(['naziv_uloge' => 'korisnik']);
        Uloga::create(['naziv_uloge' => 'admin']);
        Uloga::create(['naziv_uloge' => 'super_admin']);
    }
}
