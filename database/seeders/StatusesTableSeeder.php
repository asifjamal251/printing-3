<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            array('id' => '1','name' => 'Pending','status_badge' => '<span class="badge bg-warning">Pending</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '2','name' => 'In Progress','status_badge' => '<span class="badge bg-info">In Progress</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '3','name' => 'Completed','status_badge' => '<span class="badge bg-success">Completed</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '4','name' => 'Failed','status_badge' => '<span class="badge bg-danger">Failed</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '5','name' => 'Canceled','status_badge' => '<span class="badge bg-secondary">Canceled</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '6','name' => 'Approved','status_badge' => '<span class="badge bg-success">Approved</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '7','name' => 'Rejected','status_badge' => '<span class="badge bg-danger">Rejected</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '8','name' => 'On Hold','status_badge' => '<span class="badge bg-warning">On Hold</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '9','name' => 'Processing','status_badge' => '<span class="badge bg-info">Processing</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '10','name' => 'Shipped','status_badge' => '<span class="badge bg-primary">Shipped</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '11','name' => 'Delivered','status_badge' => '<span class="badge bg-success">Delivered</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '12','name' => 'Returned','status_badge' => '<span class="badge bg-danger">Returned</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '13','name' => 'Refunded','status_badge' => '<span class="badge bg-success">Refunded</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '14','name' => 'Active','status_badge' => '<span class="badge bg-success">Active</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '15','name' => 'Inactive','status_badge' => '<span class="badge bg-warning">Inactive</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '16','name' => 'Draft','status_badge' => '<span class="badge bg-secondary">Draft</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '17','name' => 'Expired','status_badge' => '<span class="badge bg-dark">Expired</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '18','name' => 'Partially Paid','status_badge' => '<span class="badge bg-info">Partially Paid</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '19','name' => 'Paid','status_badge' => '<span class="badge bg-success">Paid</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '20','name' => 'Partially Approved','status_badge' => '<span class="badge bg-success">Partially Approved</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '21','name' => 'On Order Sheet','status_badge' => '<span class="badge bg-success">On Order Sheet</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '22','name' => 'On Processing','status_badge' => '<span class="badge bg-secondary">On Processing</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '23','name' => 'Details Added','status_badge' => '<span class="badge" style="background-color:#e93f3f; color:#ffffff;">Details Added</span>','background_colour' => '#e93f3f','text_colour' => '#ffffff','created_at' => '2025-09-04 08:09:33','updated_at' => '2026-01-30 20:36:44'),
  array('id' => '24','name' => 'Job Card','status_badge' => '<span class="badge bg-dark">Job Card</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '25','name' => 'On Paper Cutting','status_badge' => '<span class="badge" style="background-color:#531dd3; color:#ffffff;">On Paper Cutting</span>','background_colour' => '#531dd3','text_colour' => '#ffffff','created_at' => '2025-09-04 08:09:33','updated_at' => '2026-01-30 20:37:32'),
  array('id' => '26','name' => 'On Printing','status_badge' => '<span class="badge" style="background-color:#2fa404; color:#ffffff;">On Printing</span>','background_colour' => '#2fa404','text_colour' => '#ffffff','created_at' => '2025-09-04 08:09:33','updated_at' => '2026-01-30 20:38:09'),
  array('id' => '27','name' => 'On Coating','status_badge' => '<span class="badge bg-dark">On Coating</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '28','name' => 'On Leafing','status_badge' => '<span class="badge bg-primary">On Leafing</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '29','name' => 'On Embossing','status_badge' => '<span class="badge bg-dark">On Embossing</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '30','name' => 'On Lamination','status_badge' => '<span class="badge bg-info">On Lamination</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '31','name' => 'On Spot UV','status_badge' => '<span class="badge bg-info">On Spot UV</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '32','name' => 'On Dye Cutting','status_badge' => '<span class="badge bg-info">On Dye Cutting</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '33','name' => 'On Pasting','status_badge' => '<span class="badge bg-success">On Pasting</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '34','name' => 'In Warehouse','status_badge' => '<span class="badge bg-success">In Warehouse</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33'),
  array('id' => '35','name' => 'On Ready For Billing','status_badge' => '<span class="badge bg-success"> On Ready For Billing</span>','background_colour' => NULL,'text_colour' => NULL,'created_at' => '2025-09-04 08:09:33','updated_at' => '2025-09-04 08:09:33')
        ]);
}
}
