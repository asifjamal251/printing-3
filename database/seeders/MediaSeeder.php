<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $medias = [
          array('id' => '1','file' => 'storage/media/1675870540-logo.png','icon' => 'storage/media/1675870540-logo_icon.png','name' => '1675870540_logo','slug' => '1675870540-logo','type' => 'png','original_name' => '1675870540_logo.png','size' => '8.33 KB','handle' => '1675870540-logo','created_at' => '2025-12-31 12:33:53','updated_at' => '2025-12-31 12:33:53'),
          array('id' => '2','file' => 'storage/media/1675870540-favicon.png','icon' => 'storage/media/1675870540-favicon_icon.png','name' => '1675870540_favicon','slug' => '1675870540-favicon','type' => 'png','original_name' => '1675870540_favicon.png','size' => '2.66 KB','handle' => '1675870540-favicon','created_at' => '2025-12-31 12:34:45','updated_at' => '2025-12-31 12:34:45')
      ];

      DB::table('medias')->insert($medias);
  }
}
