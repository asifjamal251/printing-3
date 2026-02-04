<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        DB::table('modules')->insert([
            array('id' => '1','name' => 'Paper Cutting','model_name' => 'PaperCutting','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '2','name' => 'Printing','model_name' => 'Printing','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '3','name' => 'Coating','model_name' => 'Coating','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '4','name' => 'Lamination','model_name' => 'Lamination','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '5','name' => 'Leafing','model_name' => 'Leafing','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '6','name' => 'Embossing','model_name' => 'Embossing','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '7','name' => 'Spot UV','model_name' => 'SpotUV','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '8','name' => 'Dye Cutting','model_name' => 'DyeCutting','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01'),
          array('id' => '9','name' => 'Pasting','model_name' => 'Pasting','status_id' => '14','created_at' => '2026-01-03 18:31:57','updated_at' => '2026-01-03 18:32:01')
        ]);
    }
}
