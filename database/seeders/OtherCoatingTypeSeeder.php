<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtherCoatingTypeSeeder extends Seeder
{
    public function run()
    {
        $coatings = [
           array('id' => '1','name' => 'Metallic','category' => 'Metallic','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
           array('id' => '2','name' => 'Spot UV','category' => 'Spot UV','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
           array('id' => '3','name' => 'Spot UV + Metallic','category' => 'Spot UV + Metallic','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
           array('id' => '4','name' => 'None','category' => 'None','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09')
       ];

       DB::table('other_coating_types')->insert($coatings);
   }
}
