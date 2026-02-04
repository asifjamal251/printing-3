<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/cities.json");
        $data = json_decode($json, true); // Decode JSON as an associative array

        $uniqueCities = collect($data)->unique('name');

        foreach ($uniqueCities as $city) {
            City::updateOrCreate(
                ['name' => $city['name']], 
                [
                    'state_id' => $city['state_id'],
                    'created_at' => $city['created_at'],
                    'updated_at' => $city['updated_at'],
                ]
            );
        }
    }
}
