<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carton;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Cylinder;
use App\Models\District;
use App\Models\Dye;
use App\Models\Firm;
use App\Models\Foil;
use App\Models\Item;
use App\Models\JobCardItem;
use App\Models\MaterialOrderItem;
use App\Models\PaperQuality;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Role;
use App\Models\State;
use App\Models\Stock;
use App\Models\Vendor;
use Illuminate\Http\Request;

class CommonController extends Controller{

    public function clientList(Request $request){
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 15;

            $offset = ($page - 1) * $resultCount;

            $name = Client::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
            ->orderBy('created_at', 'asc')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id, company_name as text')
            ->get();

            $count = Count(Client::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
                ->orderBy('created_at', 'asc')
                ->selectRaw('id, company_name as text')
                ->get());

            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
              "results" => $name,
              "pagination" => array(
                  "more" => $morePages
              )
          );

            return response()->json($results);
        }
        return response()->json('oops');
    }






public function clientListCartonRate(Request $request)
{
    if (!$request->ajax()) {
        return response()->json(['message' => 'Invalid request'], 400);
    }

    $page = max((int)$request->page, 1);
    $resultCount = 15;
    $offset = ($page - 1) * $resultCount;

    $term = $request->term ?? '';
    $statusId = (int) ($request->status_id ?? 1);
    $type = $request->type ?? 'mkdt';

    $clientIdsQuery = JobCardItem::query()
        ->join('items', 'items.id', '=', 'job_card_items.item_id')
        ->where('job_card_items.status_id', $statusId);

    if ($type === 'mfg') {
        $clientIdsQuery->whereNotNull('items.mfg_by')
            ->select('items.mfg_by as client_id');
    } else {
        $clientIdsQuery->whereNotNull('items.mkdt_by')
            ->select('items.mkdt_by as client_id');
    }

    $clientIds = $clientIdsQuery->distinct()->pluck('client_id')->filter()->toArray();

    $query = Client::query()
        ->whereIn('id', $clientIds)
        ->where('company_name', 'LIKE', '%' . $term . '%')
        ->orderBy('company_name', 'asc');

    $count = (clone $query)->count();

    $clients = $query->skip($offset)
        ->take($resultCount)
        ->get(['id', 'company_name as text']);

    $endCount = $offset + $resultCount;
    $morePages = $count > $endCount;

    return response()->json([
        "results" => $clients,
        "pagination" => ["more" => $morePages]
    ]);
}

    public function vendorList(Request $request){
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 15;

            $offset = ($page - 1) * $resultCount;

            $name = Vendor::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
            ->orderBy('company_name', 'asc')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id, company_name as text')
            ->get();

            $count = Count(Vendor::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
                ->orderBy('company_name', 'asc')
                ->selectRaw('id, company_name as text')
                ->get());

            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
              "results" => $name,
              "pagination" => array(
                  "more" => $morePages
              )
          );

            return response()->json($results);
        }
        return response()->json('oops');
    }


    public function firmList(Request $request){
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 15;

            $offset = ($page - 1) * $resultCount;

            $name = Firm::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
            ->orderBy('company_name', 'asc')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id, company_name as text')
            ->get();

            $count = Count(Firm::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
                ->orderBy('company_name', 'asc')
                ->selectRaw('id, company_name as text')
                ->get());

            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
              "results" => $name,
              "pagination" => array(
                  "more" => $morePages
              )
          );

            return response()->json($results);
        }
        return response()->json('oops');
    }



    public function countryList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = Country::where('name', 'LIKE', '%' . $term . '%')
        ->orderBy('name', 'asc');

        $countries = $query->clone()
        ->selectRaw('id, name as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $countries,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }



    public function stateList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = State::where('name', 'LIKE', '%' . $term . '%')
        ->orderBy('name', 'asc');

        $states = $query->clone()
        ->selectRaw('id, name as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $states,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }



    public function districtList(Request $request, $stateID){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = District::where('name', 'LIKE', '%' . $term . '%')
        ->where('state_id', $stateID)
        ->orderBy('name', 'asc');

        $districts = $query->clone()
        ->selectRaw('id, name as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $districts,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }


    public function cityList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = City::where('name', 'LIKE', '%' . $term . '%')
        ->where('state_id', $request->state_id)
        ->orderBy('name', 'asc');

        $cities = $query->clone()
        ->selectRaw('id, name as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $cities,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }


    public function pincodeList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = City::where('pin_code', 'LIKE', '%' . $term . '%')
        ->orWhere('name', 'LIKE', '%' . $term . '%')
        ->orderBy('pin_code', 'asc');

        $cities = $query->clone()
        ->selectRaw('id, CONCAT(pin_code, " - ", name) as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $cities,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }


    public function getLocationByPincode($id){
        $city = City::where('id', $id)->with('district.state')->first();

        if ($city) {
            return response()->json([
                'state' => $city->district->state->name ?? null,
                'district' => $city->district->name ?? null,
                'city' => $city->name
            ]);
        }

        return response()->json(['message' => 'Pincode not found'], 404);
    }

    public function qualityList(Request $request, $vendorId = null){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = PaperQuality::where('quality', 'LIKE', '%' . $term . '%')
        ->orWhere('code', 'LIKE', '%' . $term . '%')
        ->orderBy('quality', 'asc');

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        $quality = $query->clone()
        ->selectRaw('id, quality as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $quality,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }



    public function roleList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = $request->term ?? '';

        $query = Role::where('name', 'LIKE', '%' . $term . '%')
        ->orderBy('name', 'asc');

        $role = $query->clone()
        ->selectRaw('id, name as text')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        
        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $role,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }




    public function itemList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = trim($request->term ?? '');

        $query = Item::query()
        ->when($term, function ($q) use ($term) {
            $q->where('item_name', 'LIKE', '%' . $term . '%');
        })
        ->when($request->filled('client'), function ($q) use ($request) {
            $q->where('client_id', $request->client);
        });

        $results = (clone $query)
        ->selectRaw("id, CONCAT(item_name, COALESCE(CONCAT(' (', item_size, ')'), '')) as text")
        ->orderBy('item_name', 'asc')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $results,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }



    public function cylinderList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page = max((int) $request->page, 1);
        $resultCount = 5;
        $term = trim($request->term ?? '');

        $query = Cylinder::query()
        ->when($term, function ($q) use ($term) {
            $q->where('cylinder_size', 'LIKE', '%' . $term . '%')
            ->where('item_id', $request->item);
        });

        $results = (clone $query)
        ->selectRaw("id, CONCAT(cylinder_size, COALESCE(CONCAT(' (', colour, ')'), '')) as text")
        ->orderBy('cylinder_size', 'asc')
        ->skip(($page - 1) * $resultCount)
        ->take($resultCount)
        ->get();

        $totalCount = $query->count();
        $morePages = ($page * $resultCount) < $totalCount;

        return response()->json([
            "results" => $results,
            "pagination" => [
                "more" => $morePages
            ]
        ]);
    }

    
    public function itemDetails($id){
        $item = Item::with('cylinders', 'latestRate')->findOrFail($id);

        return response()->json([
            'item' => $item,
            'rate' => $item->latestRate,
            'cylinders' => $item->cylinders->map(function ($cylinder) {
                return [
                    'id'            => $cylinder->id,
                    'cylinder_size' => $cylinder->cylinder_size,
                    'colour'        => $cylinder->colour,
                    'location'      => $cylinder->location,
                ];
            }),
        ]);
    }



    public function productList(Request $request)
{
    if ($request->ajax()) {

        $page   = $request->input('page', 1);
        $limit  = 5;
        $offset = ($page - 1) * $limit;
        $term   = trim($request->input('term', ''));

        // Split search term into words
        $keywords = array_filter(explode(' ', $term));

        $query = Product::with('productType')
            ->when($keywords, function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($sub) use ($word) {
                        $sub->where('name', 'LIKE', "%{$word}%")
                            ->orWhere('name_other', 'LIKE', "%{$word}%")
                            ->orWhere('gsm', 'LIKE', "%{$word}%")
                            ->orWhereHas('productType', function ($pt) use ($word) {
                                $pt->where('name', 'LIKE', "%{$word}%");
                            });
                    });
                }
            })
            ->orderBy('created_at', 'asc');

        $count = $query->count();

        $products = $query->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id'   => $product->id,
                    'text' => trim($product->fullname), // e.g. Name | GSM | Type
                ];
            });

        return response()->json([
            'results' => $products,
            'pagination' => [
                'more' => $count > ($offset + $limit)
            ]
        ]);
    }

    return response()->json('oops');
}


        public function productAttributeList(Request $request){
            if (!$request->ajax()) {
                return response()->json(['message' => 'Invalid request'], 400);
            }

            $page = max((int) $request->page, 1);
            $resultCount = 5;
            $term = $request->term ?? '';

            $query = ProductAttribute::where('item_per_packet', 'LIKE', '%' . $term . '%')
            ->where('product_id', $request->product_id)
            ->orderBy('item_per_packet', 'asc');

            $role = $query->clone()
            ->selectRaw('id, item_per_packet as text')
            ->skip(($page - 1) * $resultCount)
            ->take($resultCount)
            ->get();


            $totalCount = $query->count();
            $morePages = ($page * $resultCount) < $totalCount;

            return response()->json([
                "results" => $role,
                "pagination" => [
                    "more" => $morePages
                ]
            ]);
        }


        public function productStock(Request $request){
           $stock = Stock::where('product_id', $request->id)->sum('quantity');
           return response()->json([
                "datas" => $stock,
            ]);
        }


        public function attriSingle(Request $request){
            $productAttr = ProductAttribute::where('id', $request->id)->with(['stock', 'product' => function($query){
                $query->with('unit');
            }])->first();
            return response()->json([
                "datas" => $productAttr,
            ]);
        }

        public function productSingle(Request $request){
            $product = Product::where('id', $request->id)->with(['unit'])->first();
            return response()->json([
                "datas" => $product,
            ]);
        }

        public function productMORate(Request $request){
            $moItem = MaterialOrderItem::where('product_id', $request->id)->first();
            return response()->json([
                "datas" => $moItem,
            ]);
        }



    public function dyeList(Request $request){
    if ($request->ajax()) {

        $page   = $request->page ?? 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $term   = trim($request->term ?? '');

        // Split input into tokens (space OR x)
        $tokens = preg_split('/[\sxX]+/', $term);

        $query = Dye::with(['dyeDetails.dyeLockType'])
            ->when($term, function ($q) use ($term, $tokens) {

                $q->where(function ($main) use ($term, $tokens) {

                    /* 🔹 Text match (dye number) */
                    $main->where('dye_number', 'LIKE', "%{$term}%");

                    /* 🔹 Dimension & numeric matching */
                    $main->orWhereHas('dyeDetails', function ($sub) use ($tokens) {

                        foreach ($tokens as $value) {

                            if (is_numeric($value)) {
                                $num = (float) $value;
                                $min = $num - 0.05;
                                $max = $num + 0.05;

                                $sub->where(function ($d) use ($min, $max) {
                                    $d->whereBetween('length', [$min, $max])
                                      ->orWhereBetween('width',  [$min, $max])
                                      ->orWhereBetween('height', [$min, $max]);
                                });
                            }
                        }
                    });

                    /* 🔹 Lock type search */
                    $main->orWhereHas('dyeDetails.dyeLockType', function ($lock) use ($term) {
                        $lock->where('type', 'LIKE', "%{$term}%");
                    });
                });
            })
            ->orderBy('dye_number', 'asc');

        $count = $query->count();

        $dyes = $query->skip($offset)->take($limit)->get();

        $results = $dyes->map(function ($dye) {

            $sizes = $dye->dyeDetails
                ->map(fn ($d) => $d->carton_size)
                ->filter()
                ->unique()
                ->implode(', ');

            $lockType = $dye->dyeDetails
                ->pluck('dyeLockType.type')
                ->filter()
                ->unique()
                ->implode(', ');

            return [
                'id'   => $dye->id,
                'text' => "{$dye->dye_number} ({$sizes})" . ($lockType ? " - {$lockType}" : ""),
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $count > ($offset + $limit)
            ]
        ]);
    }

    return response()->json('oops');
}

        public function dyeSingle(Request $request){
            $dye = Dye::where('id', $request->id)->with(['items'])->first();
            return response()->json([
                "datas" => $dye,
            ]);
        }


    }
