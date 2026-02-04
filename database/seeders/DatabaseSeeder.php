<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            AdminsTableSeeder::class,
            MenusTableSeeder::class,
            PermissionsTableSeeder::class,
            RolePermissionsTableSeeder::class,
            AppSettingsTableSeeder::class,
            StatusesTableSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            UnitSeeder::class,
            OtherCoatingTypeSeeder::class,
            CoatingTypeSeeder::class,
            MediaSeeder::class,
            ModuleSeeder::class,
        ]);
    }
}
