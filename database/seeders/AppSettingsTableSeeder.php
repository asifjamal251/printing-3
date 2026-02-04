<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_settings')->insert([
            [
                'id' => 1,
                'app_name' => 'SKG Graphicss',
                'app_description' => 'Offset Printing Press',
                'logo' => 1,
                'favicon' => 2,
                'email' => 'info@girdharprinting.com',
                'mobile_number' => '+91 9315647380',
                'country_id' => 72,
                'state_id' => 5,
                'district_id' => 164,
                'city_id' => 18809,
                'pincode' => 845412,
                'address' => 'Chandigarh',
                'created_at' => Carbon::parse('2022-06-26 15:46:11'),
                'updated_at' => Carbon::parse('2024-11-03 08:52:29')
            ],
        ]);
    }
}
