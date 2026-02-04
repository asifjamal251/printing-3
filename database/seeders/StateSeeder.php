<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = \Illuminate\Support\Facades\File::get("database/data/states.json");
        $data = json_decode($json);
        foreach ($data as $state) {
            State::updateOrCreate([
                'id' => $state->id,
                'name' => $state->name,
                'short_name' => $state->short_name,
                'created_at' => $state->created_at,
                'updated_at' => $state->updated_at
            ]);
        }
    }
}
