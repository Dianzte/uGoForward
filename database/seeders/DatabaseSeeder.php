<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        
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
