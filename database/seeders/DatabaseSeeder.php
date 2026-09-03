<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('imagenes'); 

        Storage::disk('public')->makeDirectory('imagenes');

        $this->call(UserSeeder::class);
        $this->call(UniversidadSeeder::class);
        $this->call(CarreraSeeder::class);
        $this->call(CondicionSeeder::class);
        $this->call(AyudaSeeder::class);
        $this->call(ImagenSeeder::class);
        $this->call(CategoriasForoSeeder::class);
        $this->call(ChatRoomSeeder::class);

        Beca::factory(5)->create();

    }
}
