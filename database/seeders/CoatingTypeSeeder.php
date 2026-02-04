<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoatingTypeSeeder extends Seeder
{
    public function run()
    {
        $coatings = [
            array('id' => '1','name' => 'Gloss Lamination','category' => 'Gloss Lamination','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '2','name' => 'Matt Lamination','category' => 'Matt Lamination','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '3','name' => 'Velvet Lamination','category' => 'Velvet Lamination','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '4','name' => 'Drip off','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '5','name' => 'Drip off + UV','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '6','name' => 'Full UV','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '7','name' => 'Aqueous Varnish','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '8','name' => 'Matt Varnish','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '9','name' => 'Double Aqueous Varnish','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '10','name' => 'UV+Aqueous Varnish','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '11','name' => 'Velvet Varnish','category' => 'Chemical Coating','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09'),
            array('id' => '12','name' => 'None','category' => 'None','status_id' => '14','created_at' => '2026-01-02 15:02:09','updated_at' => '2026-01-02 15:02:09')
        ];

        DB::table('coating_types')->insert($coatings);
    }
}
