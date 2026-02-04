<?php 

use App\Models\Admin;
use App\Models\Client;
use App\Models\Dye;
use App\Models\Godown;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\PurchaseOrder;
use Carbon\Carbon;





if(!function_exists('get_app_setting')){
    function get_app_setting($setting_type){
        $setting = App\Models\AppSetting::with(['siteLogo','siteFavicon'])->latest()->first();
        if($setting[$setting_type]){

            if($setting_type == 'logo' && $setting->siteLogo){
                return $setting->siteLogo->file;
            }
            if($setting_type == 'favicon' && $setting->siteFavicon){
                return $setting->siteFavicon->file;
            }

            return $setting[$setting_type];
        }
        
    }
}


if (!function_exists('status')) {

    function status($id){
        $weight = App\Models\Status::where(['id'=> $id])->first();
        if($weight){
            return $weight->status_badge;
        }
        return null;
    }
}




if (!function_exists('getFile')) {
    function getFile($file, $id) {
        if (!empty($file)) {
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                return "<a class='glightbox' data-gallery='{$id}' href='".asset($file)."'>
                            <img class='img-fluid' style='width:40px; height:40px; object-fit:cover;' src='".asset($file)."'/>
                        </a>";
            } elseif (strtolower($fileExtension) === 'pdf') {
                return "<a class='glightbox' data-gallery='{$id}' href='".asset($file)."'>
                            <img class='img-fluid' style='width:40px;' src='".asset('icons/pdf.png')."'/>
                        </a>";
            }
        }
        return 'N/A';
    }
}



if (!function_exists('getAdmin')) {
    function getAdmin($get_detail) {
        $admin = \Auth::guard('admin')->user();
        if (!$admin) {
            return "No admin is currently logged in";
        }
        if (!in_array($get_detail, ['password', 'role', 'role_id'])) {
            if (isset($admin[$get_detail])) {
                return $admin[$get_detail];
            }
        }
        if ($get_detail == 'role') {
            $admin = $admin->load('role'); 
            if ($admin->role) {
                return $admin->role->display_name;
            }
            return "Role not found";
        }

        if ($get_detail == 'role_id') {
            return $admin->role_id ?? "Role ID not found";
        }

        
    }
}



if (!function_exists('numberToWords')) {
    function numberToWords($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
                       '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                       '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                       '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                       '13' => 'Thirteen', '14' => 'Fourteen',
                       '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                       '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
                       '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                       '60' => 'Sixty', '70' => 'Seventy',
                       '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? " and " . $words[$point / 10] . " " .
                  $words[$point = $point % 10] . " Paise" : '';
        return $result . "Rupees" . $points;
    }
}

if (!function_exists('databaseDate')) {
    function databaseDate($date) {
        $formats = [
            'd F, Y',
            'Y-m-d',
            'm/d/Y',
            'd/m/Y',
            'F j, Y',
            'j F, Y',
            'd M, Y',
            'm-d-Y',
            'Y/m/d',
            'M d, Y',
            'd.m.Y',
            'Y.m.d',
        ];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        return Carbon::now()->format('Y-m-d');
    }
}

if (!function_exists('age')) {
    function age($date) {
        $now = Carbon::now();
        $diff = $date->diff($now);

        if ($diff->m > 0) {
            // Display months
            $months = $diff->m + ($diff->y * 12);
            if ($diff->d > 0) {
                return $months . '.' . $diff->d . ' months ago';
            } else {
                return $months . ' months ago';
            }
        } else {
            // Display days
            $days = $diff->d;
            if ($days == 0) {
                return 'Today';
            } else {
                return $days . ' days ago';
            }
        }
    }
}


if (!function_exists('tablePOItems')) {
    function tablePOItems($poId)
    {
        $purchaseOrder = PurchaseOrder::with('items')->find($poId);

        if (!$purchaseOrder || $purchaseOrder->items->isEmpty()) {
            return '';
        }

        $items = $purchaseOrder->items;
        $accordionHtml = '<div class="accordion simple-accordion" id="accordion-'.$purchaseOrder->id.'">';
        $accordionHtml .= '<div class="accordion-item">';

        $first = $items->first();
        $hasMore = $items->count() > 1;

        // First row (always visible)
        $accordionHtml .= '<div class="d-flex gap-2 ps-1 accordion-row border-none accordion-toggle'.($hasMore ? '' : ' no-toggle').'" ';
        if ($hasMore) {
            $accordionHtml .= 'data-bs-toggle="collapse" data-bs-target="#collapse-'.$purchaseOrder->id.'" aria-expanded="false" aria-controls="collapse-'.$purchaseOrder->id.'"';
        }
        $accordionHtml .= '>';
        $accordionHtml .= '<span><strong>Item:</strong> '.$first->item_name;

        if ($hasMore) {
            $accordionHtml .= '<span class="accordion-icon float-end">&#9662;</span>'; // ▼ icon
        }
        $accordionHtml .= '</div>';

        // Collapsible other items
        if ($hasMore) {
            $accordionHtml .= '<div id="collapse-'.$purchaseOrder->id.'" class="accordion-collapse collapse">';
            $lastIndex = $items->count() - 1;
            foreach ($items->skip(1)->values() as $i => $item) {
                $isLast = $i === $lastIndex - 1;
                $accordionHtml .= '<div class="ps-1 accordion-row'.($isLast ? ' last-row' : '').'"><span><strong>Item:</strong> '.$item->item_name. '</div>';
            }
            $accordionHtml .= '</div>';
        }

        $accordionHtml .= '</div></div>';

        return $accordionHtml;
    }
}


if (!function_exists('tableJobCardItems')) {
    function tableJobCardItems($jobCardId)
    {
        $jobCard = JobCard::with('items')->find($jobCardId);

        if (!$jobCard || $jobCard->items->isEmpty()) {
            return '';
        }

        $items = $jobCard->items;
        $accordionHtml = '<div class="accordion simple-accordion" id="accordion-'.$jobCardId.'">';
        $accordionHtml .= '<div class="accordion-item">';

        $first = $items->first();
        $hasMore = $items->count() > 1;

        // First row (always visible)
        $accordionHtml .= '<div class="d-flex gap-2 ps-1 accordion-row border-none accordion-toggle'.($hasMore ? '' : ' no-toggle').'" ';
        if ($hasMore) {
            $accordionHtml .= 'data-bs-toggle="collapse" data-bs-target="#collapse-'.$jobCardId.'" aria-expanded="false" aria-controls="collapse-'.$jobCardId.'"';
        }
        $accordionHtml .= '>';
        $accordionHtml .= '<span>'.$first?->itemProcessDetail?->item?->item_name.' </span>';

        if ($hasMore) {
            $accordionHtml .= '<span class="accordion-icon float-end">&#9662;</span>'; // ▼ icon
        }
        $accordionHtml .= '</div>';

        // Collapsible other items
        if ($hasMore) {
            $accordionHtml .= '<div id="collapse-'.$jobCardId.'" class="accordion-collapse collapse">';
            $lastIndex = $items->count() - 1;
            foreach ($items->skip(1)->values() as $i => $job_card_item) {
                $isLast = $i === $lastIndex - 1;
                $accordionHtml .= '<div class="ps-1 accordion-row'.($isLast ? ' last-row' : '').'"><span>'.$job_card_item?->itemProcessDetail?->item?->item_name.' </span></div>';
            }
            $accordionHtml .= '</div>';
        }

        $accordionHtml .= '</div></div>';

        return $accordionHtml;
    }
}




if (!function_exists('tableJobCardClient')) {
    function tableJobCardClient($jobCardId)
    {
        $rows = DB::table('job_card_items as jci')
            ->join('purchase_order_items as poi', 'poi.id', '=', 'jci.purchase_order_item_id')
            ->join('items as i', 'i.id', '=', 'poi.item_id')
            ->where('jci.job_card_id', $jobCardId)
            ->select('i.mkdt_by', 'i.mfg_by')
            ->get();

        if ($rows->isEmpty()) {
            return '';
        }

        $mkdtByIds = $rows->pluck('mkdt_by')->filter()->unique();
        $mfgByIds  = $rows->pluck('mfg_by')->filter()->unique();

        $mkdtByClients = Client::whereIn('id', $mkdtByIds)
            ->pluck('company_name')
            ->toArray();

        $mfgByClients = Client::whereIn('id', $mfgByIds)
            ->pluck('company_name')
            ->toArray();

        if (empty($mkdtByClients) && empty($mfgByClients)) {
            return '';
        }

        $html = '<div class="jobcard-client-info">';

        if (!empty($mfgByClients)) {
            $html .= '<strong>MFG By:</strong> ' . implode(', ', $mfgByClients);
        }

        if (!empty($mkdtByClients)) {
            if (!empty($mfgByClients)) {
                $html .= ' | ';
            }
            $html .= '<strong>MKDT By:</strong> ' . implode(', ', $mkdtByClients);
        }

        $html .= '</div>';

        return $html;
    }
}



if (!function_exists('dyeDetails')) {
    function dyeDetails($dyeId)
    {
        $dye = Dye::with('items')->find($dyeId);

        if (!$dye || $dye->items->isEmpty()) {
            return '';
        }

        $items = $dye->items;

        $accordionHtml = '<div class="accordion simple-accordion" id="accordion-'.$dye->id.'">';
        $accordionHtml .= '<div class="accordion-item">';

        $first = $items->first();
        $hasMore = $items->count() > 1;

        // ------------------------------------
        // FIRST ROW (HORIZONTAL, ALWAYS VISIBLE)
        // ------------------------------------
        $accordionHtml .= '<div class="accordion-row border-none accordion-toggle'.($hasMore ? '' : ' no-toggle').'" ';

        if ($hasMore) {
            $accordionHtml .= 'data-bs-toggle="collapse" data-bs-target="#collapse-'.$dye->id.'" aria-expanded="false" aria-controls="collapse-'.$dye->id.'"';
        }

        $accordionHtml .= '>';

        // Horizontal UL for first row
        $accordionHtml .= '
            <ul class="list-group list-group-horizontal-md justify-content-center p-0 m-0">
                <li class="px-2 py-1 list-group-item flex-fill"><strong>Carton:</strong> '.$first?->carton_size.'</li>
                <li class="px-2 py-1 list-group-item flex-fill"><strong>Lock Type:</strong> '.$first?->dyeLockType?->type.'</li>
                <li class="px-2 py-1 list-group-item flex-fill"><strong>UPS:</strong> '.($first?->ups ?? '-').'</li>
                <li class="px-2 py-1 list-group-item flex-fill"><strong>Pasting Flat:</strong> '.($first?->pasting_flap ?? '-').'</li>
                <li class="px-2 py-1 list-group-item flex-fill"><strong>Tuckin Flap:</strong> '.($first?->tuckin_flap ?? '-').'</li>
            </ul>
        ';

        if ($hasMore) {
            $accordionHtml .= '<span class="accordion-icon float-end" style="position:absolute; right:10px; top:10px;">&#9662;</span>';
        }

        $accordionHtml .= '</div>';

        // ------------------------------------
        // COLLAPSIBLE OTHER ITEMS (HORIZONTAL)
        // ------------------------------------
        if ($hasMore) {

            $accordionHtml .= '<div id="collapse-'.$dye->id.'" class="accordion-collapse collapse">';

            $lastIndex = $items->count() - 1;

            foreach ($items->skip(1)->values() as $i => $item) {

                $isLast = $i === $lastIndex - 1;

                $accordionHtml .= '
                <div class="accordion-row'.($isLast ? ' last-row' : '').'">
                    <ul class="list-group list-group-horizontal-md justify-content-center p-0 m-0">
                        <li class="px-2 py-1 list-group-item flex-fill"><strong>Carton:</strong> '.$item->carton_size.'</li>
                        <li class="px-2 py-1 list-group-item flex-fill"><strong>Lock Type:</strong> '.$item->dyeLockType->type.'</li>
                        <li class="px-2 py-1 list-group-item flex-fill"><strong>UPS:</strong> '.($item->ups ?? '-').'</li>
                        <li class="px-2 py-1 list-group-item flex-fill"><strong>Pasting Flat:</strong> '.($item->pasting_flap ?? '-').'</li>
                        <li class="px-2 py-1 list-group-item flex-fill"><strong>Tuckin Flap:</strong> '.($item->tuckin_flap ?? '-').'</li>
                    </ul>
                </div>';
            }

            $accordionHtml .= '</div>';
        }

        $accordionHtml .= '</div></div>';

        return $accordionHtml;
    }
}

