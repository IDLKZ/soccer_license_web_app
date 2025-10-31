<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SeasonSeeder::class,
            LeagueSeeder::class,
            ClubTypeSeeder::class,
            ClubSeeder::class,
            LicenceSeeder::class,
            CategoryDocumentSeeder::class,
            DocumentSeeder::class,
            LicenceRequirementSeeder::class,
            ApplicationStatusCategorySeeder::class,
            ApplicationStatusSeeder::class,
        ]);
    }
}
