<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenusTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('menus')->insert([
            array('slug' => 'access_control','name' => 'Access Control','icon' => 'mdi mdi-tools','parent' => 'control_panel','grand' => NULL,'ordering' => '1','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'account','name' => 'Account','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => '7','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'admin','name' => 'Admin','icon' => 'mdi mdi-account-lock','parent' => 'master','grand' => NULL,'ordering' => '6','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'app_setting','name' => 'App Setting','icon' => 'bx bx-cog','parent' => 'control_panel','grand' => NULL,'ordering' => '0','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'billing','name' => 'Billing','icon' => NULL,'parent' => 'account','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'bread','name' => 'Bread','icon' => 'ft-target','parent' => NULL,'grand' => 'access_control','ordering' => '1','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'carton_rate','name' => 'Carton Rate','icon' => NULL,'parent' => 'prepress','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'category','name' => 'Category','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '10','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'city','name' => 'City','icon' => 'bx bx-cog','parent' => 'location','grand' => NULL,'ordering' => '0','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'client','name' => 'Client','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '3','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'coating','name' => 'Coating','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '2','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'control_panel','name' => 'Control Panel','icon' => 'mdi mdi-tools','parent' => NULL,'grand' => NULL,'ordering' => '11','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'country','name' => 'Country','icon' => 'bx bx-cog','parent' => 'location','grand' => NULL,'ordering' => '2','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'dashboard','name' => 'Dashboard','icon' => 'bx bx-home-circle','parent' => NULL,'grand' => NULL,'ordering' => '2','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'department','name' => 'Department','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => '8','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'district','name' => 'District','icon' => 'bx bx-cog','parent' => 'location','grand' => NULL,'ordering' => '3','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'dye','name' => 'Die','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '9','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'dye_cutting','name' => 'Dye Cutting','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '7','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'dye_lock_type','name' => 'Dye Lock Type','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => '9','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'embossing','name' => 'Embossing','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '4','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'firm','name' => 'Firm','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '5','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'item','name' => 'Item','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'item_for_billing','name' => 'Item For Billing','icon' => NULL,'parent' => 'account','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'job_card','name' => 'Job Card','icon' => NULL,'parent' => 'prepress','grand' => NULL,'ordering' => '4','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'lamination','name' => 'Lamination','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '5','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'leafing','name' => 'Leafing','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '3','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'location','name' => 'Location','icon' => 'mdi mdi-google-maps','parent' => NULL,'grand' => NULL,'ordering' => '10','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'master','name' => 'Master','icon' => 'ri-apps-2-line','parent' => NULL,'grand' => NULL,'ordering' => '3','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'material_inward','name' => 'Material Inward','icon' => NULL,'parent' => 'transactions','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'material_issue','name' => 'Material Issue','icon' => NULL,'parent' => 'transactions','grand' => NULL,'ordering' => '2','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'material_order','name' => 'Material Order','icon' => NULL,'parent' => 'transactions','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'media','name' => 'Media','icon' => 'bx bx-folder','parent' => 'master','grand' => NULL,'ordering' => '12','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'menu','name' => 'Menu','icon' => NULL,'parent' => NULL,'grand' => 'access_control','ordering' => '0','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'operator','name' => 'Operator','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '2','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'order_sheet','name' => 'Order Sheet','icon' => NULL,'parent' => 'prepress','grand' => NULL,'ordering' => '2','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'paper_cutting','name' => 'Paper Cutting','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'pasting','name' => 'Pasting','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '8','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'prepress','name' => 'Prepress','icon' => 'bx bx-customize','parent' => NULL,'grand' => NULL,'ordering' => '4','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'printing','name' => 'Printing','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'processing','name' => 'Processing','icon' => NULL,'parent' => 'prepress','grand' => NULL,'ordering' => '3','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'product','name' => 'Product','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'product_type','name' => 'Product Type','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '11','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'production','name' => 'Production','icon' => 'mdi mdi-alpha-p-circle','parent' => NULL,'grand' => NULL,'ordering' => '5','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'purchase_order','name' => 'Purchase Order','icon' => NULL,'parent' => 'prepress','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'reel_conversion','name' => 'Reel Conversion','icon' => NULL,'parent' => 'reels','grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'reel_inward','name' => 'Reel Inward','icon' => NULL,'parent' => 'reels','grand' => NULL,'ordering' => '2','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'reel_job_card','name' => 'Reel Job Card','icon' => NULL,'parent' => 'reels','grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'reels','name' => 'Reels','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => '0','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'role','name' => 'Role','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '7','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'spot_uv','name' => 'Spot UV','icon' => NULL,'parent' => 'production','grand' => NULL,'ordering' => '6','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'state','name' => 'State','icon' => 'bx bx-cog','parent' => 'location','grand' => NULL,'ordering' => '1','status' => '1','created_at' => '2025-09-04 02:39:33','updated_at' => '2025-09-04 02:39:33'),
  array('slug' => 'status','name' => 'Status','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '8','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'store','name' => 'Store','icon' => NULL,'parent' => NULL,'grand' => NULL,'ordering' => '1','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'transactions','name' => 'Transactions','icon' => 'bx bx-transfer-alt','parent' => NULL,'grand' => NULL,'ordering' => '6','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'vendor','name' => 'Vendor','icon' => NULL,'parent' => 'master','grand' => NULL,'ordering' => '4','status' => '1','created_at' => NULL,'updated_at' => NULL),
  array('slug' => 'warehouse','name' => 'Warehouse','icon' => NULL,'parent' => 'transactions','grand' => NULL,'ordering' => '3','status' => '1','created_at' => NULL,'updated_at' => NULL)
        ]);
}
}
