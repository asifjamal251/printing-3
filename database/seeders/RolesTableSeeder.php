<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'root', 'display_name' => 'Super Admin'],
            ['id' => 2, 'name' => 'admin', 'display_name' => 'Admin'],
            ['id' => 3, 'name' => 'designer', 'display_name' => 'Designer'],
            ['id' => 4, 'name' => 'paper_cutting', 'display_name' => 'Paper Cutting'],
            ['id' => 5, 'name' => 'printing', 'display_name' => 'Printing'],
            ['id' => 6, 'name' => 'coating', 'display_name' => 'Coating'],
            ['id' => 7, 'name' => 'leafing', 'display_name' => 'Leafing'],
            ['id' => 8, 'name' => 'embossing', 'display_name' => 'Embossing'],
            ['id' => 9, 'name' => 'lamination', 'display_name' => 'Lamination'],
            ['id' => 10, 'name' => 'spot_uv', 'display_name' => 'Spot UV'],
            ['id' => 11, 'name' => 'dye_cutting', 'display_name' => 'Dye Cutting'],
            ['id' => 12, 'name' => 'pasting', 'display_name' => 'Pasting'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']], // check existing by ID
                [
                    'name' => $role['name'],
                    'display_name' => $role['display_name'],
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}