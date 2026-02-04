<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['id' => 1, 'name' => 'Numbers', 'code' => 'Nos', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Sheets', 'code' => 'Sheets', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Kilogram', 'code' => 'KG', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Gram', 'code' => 'Gm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'name' => 'Milligram', 'code' => 'Mg', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'name' => 'Ton', 'code' => 'Ton', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'name' => 'Liter', 'code' => 'Ltr', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'name' => 'Milliliter', 'code' => 'Ml', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'name' => 'Meter', 'code' => 'Mtr', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'name' => 'Centimeter', 'code' => 'Cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'name' => 'Millimeter', 'code' => 'Mm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 12, 'name' => 'Inch', 'code' => 'In', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 13, 'name' => 'Feet', 'code' => 'Ft', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 14, 'name' => 'Square Meter', 'code' => 'Sq.Mtr', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 15, 'name' => 'Square Feet', 'code' => 'Sq.Ft', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 16, 'name' => 'Roll', 'code' => 'Roll', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 17, 'name' => 'Printing Plates', 'code' => 'PP', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 18, 'name' => 'Packet', 'code' => 'Pkt', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 19, 'name' => 'Box', 'code' => 'Box', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 20, 'name' => 'Container', 'code' => 'Container', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('units')->insert($units);
    }
}
