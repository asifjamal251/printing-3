<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ProductCollection;
use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\FoilShade;
use App\Models\MaterialInwardItem;
use App\Models\MaterialOrderItem;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImport;
use App\Models\ProductLedger;
use App\Models\ProductType;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller{
    public function index(Request $request){
        
        if($request->ajax()) {

            $admin = auth('admin')->user();

            if ($request->type === 'category') {
                $categories = Category::orderBy('ordering', 'asc')->get();
                $cat = array();
                foreach ($categories as $cat2) {
                    $cat[] = ['id' => $cat2->id, 'text' => $cat2->name, 'a_attr' => ['href' => route('admin.product.index', 'category=' . $cat2->id)], 'parent' => ($cat2->parent) ? $cat2->parent : '#'];
                }
                return response()->json($cat);
            }

           

            if ($request->type === 'product_type') {
                $product_types = ProductType::orderBy('name', 'asc')->get();
                $paperTypes = array();
                foreach ($product_types as $type) {
                    $paperTypes[] = ['id' => $type->id, 'text' => $type->product_type, 'a_attr' => ['href' => route('admin.product.index', 'product_type=' . $type->id)]];
                }
                return response()->json($paperTypes);
            }



            if ($request->type === 'store') {
                $stores = Store::orderBy('name', 'asc')->get();
                $stock_stores = array();
                foreach ($stores as $store) {
                    $stock_stores[] = ['id' => $store->id, 'text' => $store->name, 'a_attr' => ['href' => route('admin.product.index', 'store=' . $store->id)]];
                }
                return response()->json($stock_stores);
            }



            $datas = Product::with(['attributes.stock', 'unit', 'category', 'stocks.productAttribute']);

            if ($admin->stores()->exists()) {
        $datas->whereIn(
            'store_id',
            $admin->stores()->pluck('stores.id')
        );
    }
           if ($request->category != '') {
                $category = Category::find($request->category);
                if ($category) {
                    $categoryIds = [$category->id];
                    $categoryIds = array_merge($categoryIds, $category->allChildrenIds());

                    $datas->whereIn('category_id', $categoryIds);
                }
            }
            
            if ($request->paper_type != '') {
                $paperTypes = explode(',', $request->paper_type);
                $datas->whereHas('paperType', function ($q) use ($paperTypes) {
                    $q->whereIn('paper_type_id', $paperTypes);
                });
            }
            
            if ($request->product_type != '') {
                $productType = explode(',', $request->product_type);
                $datas->whereHas('productType', function ($q) use ($productType) {
                    $q->whereIn('product_type_id', $productType);
                });
            }

            $name = request()->input('name');
            if ($name) {
                $datas->where('name', 'like', '%'.$name.'%');
                $datas->orWhere('name_other', 'like', '%'.$name.'%');
            }

            $gsm = request()->input('gsm');
            if ($gsm) {
                $datas->whereIn('gsm', $gsm);
            }

            $totaldata = $datas->count();
            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new ProductCollection($datas));
        }

        $gsmOptions = Product::pluck('gsm')->filter()->unique()->sort()->values()->mapWithKeys(fn($gsm) => [$gsm => $gsm]);
        return view('admin.product.list', compact('gsmOptions')); 
    }



    public function create(){
       return view('admin.product.create');
    }

    public function createOther(){
       return view('admin.product.create-other');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'gst' => 'required',
            'category' => 'required',
            'product_type' => 'required',
            'unit' => 'required',
            'hsn' => 'nullable|string|max:20',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query
                        ->where('name', $request->name)
                        ->where('gsm', $request->gsm)
                        ->where('product_type_id', $request->product_type);
                }),
            ],
            'name_other' => 'nullable|string|max:255|unique:products,name_other',
            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.opening_stock' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.in_hand_quantity' => 'required|numeric|min:0',
        ],
        [
            'kt_docs_repeater_advanced.required' => 'At least one stock entry is required.',
            'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Item per packet is required.',
            'kt_docs_repeater_advanced.*.item_per_packet.numeric' => 'Item per packet must be a number.',
            'kt_docs_repeater_advanced.*.item_per_packet.min' => 'Item per packet must be at least 1.',
            'kt_docs_repeater_advanced.*.weight_per_piece.required' => 'Weight per piece is required.',
            'kt_docs_repeater_advanced.*.weight_per_piece.numeric' => 'Weight per piece must be a number.',
            'kt_docs_repeater_advanced.*.weight_per_piece.min' => 'Weight per piece must be at least 0.',
            'kt_docs_repeater_advanced.*.opening_stock.required' => 'Opening stock is required.',
            'kt_docs_repeater_advanced.*.opening_stock.numeric' => 'Opening stock must be a number.',
            'kt_docs_repeater_advanced.*.opening_stock.min' => 'Opening stock must be at least 0.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.required' => 'In hand quantity is required.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.numeric' => 'In hand quantity must be a number.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.min' => 'In hand quantity must be at least 0.',
        ]);

    
       DB::beginTransaction();

       if($request->input('file')){
            foreach($request->file as $file){
                $media = $file;
            } 
        }
        else{
            $media = NULL;
        } 

        if ($request->input('gsm')) {
            $name = strtoupper(str_replace(' ', '', $request->input('name')));
            $name_other = strtoupper(str_replace(' ', '', $request->input('name_other')));
        } else{
            $name = $request->input('name');
            $name_other = $request->input('name_other');
        }

        try {
            $product = Product::create([
                'product_type_id' => $request->input('product_type'),
                'category_id' => $request->input('category'),
                'store_id' => $request->input('stores'),
                'unit_id' => $request->input('unit'),
                'name' => $name,
                'name_other' => $name_other,
                'hsn' => $request->input('hsn'),
                'gsm' => $request->input('gsm'),
                'gst' => $request->input('gst'),
                'media_id' => $media,
                'status_id' => $request->input('status_id', 14),
            ]);

            foreach ($request->input('kt_docs_repeater_advanced') as $attribute) {
                $product_attribute = ProductAttribute::create([
                    'product_id' => $product->id,
                    'location' => $attribute['location'],
                    'item_per_packet' => $attribute['item_per_packet'],
                    'weight_per_piece' => $attribute['weight_per_piece'],
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'product_attribute_id' => $product_attribute->id,
                    'opening_stock' => $attribute['opening_stock'],
                    'quantity' => $attribute['opening_stock'],
                    'in_hand_quantity' => $attribute['in_hand_quantity'],
                ]);

                ProductLedger::create([
                    'product_id' => $product->id,
                    'product_attribute_id' => $product_attribute->id,
                    
                    'reference_no' => 'New Product Added',
                    'type' => 'in',
                    'current_quantity' => $attribute['opening_stock'],
                    'source_type' => 'Initial',
                    'source_id' => null,
                    'note' => 'Initial stock for product',
                    'created_by' => auth('admin')->user()->id, 
                ]);
            }

            DB::commit();

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Product Saved successfully',
                'table_refresh'  => true,
                'call_back' => '',
                'model_id'       => 'dataSave',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Product Not Saved'. $e,
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => '',
                'debug' => $e->getMessage()
            ]);
        }

    }


    function edit($id){
        $product = Product::where('id', $id)
            ->with(['attributes.stock', 'unit', 'category', 'stocks.productAttribute'])
            ->first();

        $attributes = $product->attributes->map(function ($attribute) {
            $attributeArray = $attribute->toArray();
            $attributeArray['opening_stock'] = optional($attribute->stock)->opening_stock ?? 0;
            $attributeArray['in_hand_quantity'] = optional($attribute->stock)->in_hand_quantity ?? 0;
            return $attributeArray;
        });  

       // dd($attributes);

        return view('admin.product.edit', [
            'product' => $product,
            'attributes' => $attributes
        ]);
    }



    public function update(Request $request, $id){
        $validated = $request->validate([
            'gst' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'hsn' => 'nullable|string|max:20',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')
                    ->ignore($id)
                    ->whereNull('deleted_at')
                    ->where(function ($query) use ($request) {
                        $query
                            ->where('name', $request->name)
                            ->where('gsm', $request->gsm)
                            ->where('product_type_id', $request->product_type);
                    }),
            ],
            'name_other' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'name_other')->ignore($id)->whereNull('deleted_at'),
            ],
            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.item_per_packet' => 'required|numeric|min:1',
            'kt_docs_repeater_advanced.*.weight_per_piece' => 'required|numeric|min:0',
            'kt_docs_repeater_advanced.*.in_hand_quantity' => 'required|numeric|min:0',
        ],
        [
            'kt_docs_repeater_advanced.required' => 'At least one stock entry is required.',
            'kt_docs_repeater_advanced.*.item_per_packet.required' => 'Item per packet is required.',
            'kt_docs_repeater_advanced.*.item_per_packet.numeric' => 'Item per packet must be a number.',
            'kt_docs_repeater_advanced.*.item_per_packet.min' => 'Item per packet must be at least 1.',
            'kt_docs_repeater_advanced.*.weight_per_piece.required' => 'Weight per piece is required.',
            'kt_docs_repeater_advanced.*.weight_per_piece.numeric' => 'Weight per piece must be a number.',
            'kt_docs_repeater_advanced.*.weight_per_piece.min' => 'Weight per piece must be at least 0.',
            'kt_docs_repeater_advanced.*.opening_stock.required' => 'Opening stock is required.',
            'kt_docs_repeater_advanced.*.opening_stock.numeric' => 'Opening stock must be a number.',
            'kt_docs_repeater_advanced.*.opening_stock.min' => 'Opening stock must be at least 0.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.required' => 'In hand quantity is required.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.numeric' => 'In hand quantity must be a number.',
            'kt_docs_repeater_advanced.*.in_hand_quantity.min' => 'In hand quantity must be at least 0.',
        ]);

        DB::beginTransaction();


        try {
            $product = Product::findOrFail($id);

            if($request->input('file')){
                foreach($request->file as $file){
                    $media = $file;
                } 
            }
            else{
                $media = NULL;
            } 

            if ($request->input('gsm')) {
                $name = strtoupper(str_replace(' ', '', $request->input('name')));
                $name_other = strtoupper(str_replace(' ', '', $request->input('name_other')));
            } else{
                $name = $request->input('name');
                $name_other = $request->input('name_other');
            }


            $product->update([
                'product_type_id' => $request->input('product_type'),
                'category_id' => $request->input('category'),
                'store_id' => $request->input('stores'),
                'unit_id' => $request->input('unit'),
                'name' => $name,
                'name_other' => $name_other,
                'hsn' => $request->input('hsn'),
                'gsm' => $request->input('gsm'),
                'gst' => $request->input('gst'),
                'media_id' => $media,
                'status_id' => $request->input('status_id', 14),
            ]);

            foreach ($request->input('kt_docs_repeater_advanced') as $attribute) {
                //dd($attribute);
                $existingAttribute = ProductAttribute::where('product_id', $product->id)
                                                     ->where('id', $attribute['id'])
                                                     ->first();

                if ($existingAttribute) {
                    $existingAttribute->update([
                        'location' => $attribute['location'],
                    ]);

                    $stock = Stock::where('product_attribute_id', $existingAttribute->id)->first();
                    if ($stock) {
                        $stock->update([
                            //'opening_stock' => $attribute['opening_stock'],
                            //'quantity' => $attribute['opening_stock'],
                            'in_hand_quantity' => $attribute['in_hand_quantity'],
                        ]);
                    }

                    // ProductLedger::create([
                    //     'product_id' => $product->id,
                    //     
                    //     'reference_no' => 'Product Updated',
                    //     'type' => 'in',
                    //     'quantity' => $attribute['opening_stock'],
                    //     'source_type' => 'Update',
                    //     'source_id' => $existingAttribute->id,
                    //     'note' => 'Updated stock for product',
                    // ]);
                } else {
                    $product_attribute = ProductAttribute::create([
                        'product_id' => $product->id,
                        'location' => $attribute['location'],
                        'item_per_packet' => $attribute['item_per_packet'],
                        'weight_per_piece' => $attribute['weight_per_piece'],
                    ]);

                    $stock = Stock::create([
                        'product_id' => $product->id,
                        'product_attribute_id' => $product_attribute->id,
                        'quantity' => $attribute['opening_stock'],
                        'in_hand_quantity' => $attribute['in_hand_quantity'],
                    ]);

                    ProductLedger::create([
                        'product_id' => $product->id,
                        'product_attribute_id' => $product_attribute->id,
                        
                        'reference_no' => 'Product Updated',
                        'type' => 'in',
                        'current_quantity' => $attribute['opening_stock'],
                        'source_type' => 'Initial',
                        'source_id' => null,
                        'note' => 'update product stock for product',
                        'created_by' => auth('admin')->user()->id, 
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Product Updated Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Product Not Updated'. $e,
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => '',
                'debug' => $e->getMessage()
            ]);
        }
    }


    public function show(Request $request, $id){
        $product = Product::where('id', $id)
            ->with(['attributes.stock', 'unit', 'category', 'stocks.productAttribute'])
            ->first();

        if ($request->ajax()) {
            $datas =  ProductLedger::orderBy('id', 'desc')->where('product_id', $id)->with(['product', 'attribute']);
            $totaldata = $datas->count();
            $result["length"]= $request->length;
            $result["recordsTotal"]= $totaldata;
            $result["recordsFiltered"]= $datas->count();
            $totaldata = $datas->count();

            $attributes = $request->input('attributes');
            if ($attributes) {
                $datas->where('product_attribute_id', $attributes);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $records = $datas->limit($request->length)->offset($request->start)->get();

            $result['data'] = [];
            foreach ($records as $data) {

                if ($data->source_type === 'Material Inward') {
                    $reference_link = route('admin.material-inward.show', $data->source_id);
                } elseif ($data->source_type === \App\Models\Issue::class) {
                    $reference_link = route('admin.issue.show', $data->source_id);
                } elseif ($data->source_type === 'JobCard') {
                    $reference_link = route('admin.job-card.show', $data->source_id);
                } else {
                    $reference_link = null;
                }

                $result['data'][] =[
                    'sn' => ++$request->start,
                    'id' => $data->id,
                    'item' => $data->attribute->item_per_packet,
                    'weight' => number_format($data->attribute->weight_per_piece, 1, '.', ''),
                    'date' => $data->created_at->format('d F, Y'),
                    'type' => $data->type,
                    'reference_no' => $reference_link ? '<a href="'.$reference_link.'" target="_blank">'.$data->reference_no.'</a>' : $data->reference_no,
                    'old_stock' => $data->old_quantity,
                    'new_stock' => $data->type == 'in' ? '<span class="text-success">+'.$data->new_quantity.'</span>':'<span class="text-danger">-'.$data->new_quantity.'</span>',
                    'current_stock' => $data->current_quantity,
                    'total_wt' => number_format(($data->current_quantity / $data->attribute->item_per_packet) * $data->attribute->weight_per_piece, 2, '.', ''),
                    'note' => $data->note,
                    'created_by' => $data->createdBy->name,
                ];
            }
            return $result;
        }

        return view('admin.product.view', compact('product'));
    }


    public function rate(Request $request){
        $rates = collect();
        if (!empty($request->product_id)) {
            $rates = MaterialInwardItem::orderBy('created_at', 'desc')
                ->where('product_id', $request->product_id)
                ->with([
                    'product',
                    'materialOrder.vendor'
                ])->latest()->paginate(20);
        }
        
        return view('admin.product.rate', compact('rates'));
    }


    public function importCreate(){
    return view('admin.product.import');
}


public function importStore(Request $request){
    $validated = $request->validate([
        'file' => 'required',
    ]);

    $file = $request->file('file');

    ProductImport::where('create_by', auth('admin')->user()->id)->delete();

    $saveData = Excel::import(new ProductsImport(auth('admin')->user()), $file);

    return response()->json(['class' => 'bg-warning', 'error' => false, 'message' => 'Please Check and Confirm', 'call_back' => route('admin.product.import.show'), 'table_referesh' => true, 'model_id' => '']);
}

    public function importShow(){
        $products = ProductImport::where('create_by', auth('admin')->user()->id)->get();
        return view('admin.product.import-show', compact('products'));
    }

    public function importUpdate(Request $request){
        DB::beginTransaction();

        try {
            $productImports = ProductImport::all();
            foreach ($productImports as $import) {
                $name_cm = strtoupper(str_replace(' ', '', $import->name_cm));
                $name_inch = strtoupper(str_replace(' ', '', $import->name_inch));

                $category = Category::firstOrCreate(['name' => $import->mill]);
                $productType = ProductType::firstOrCreate(['name' => $import->product_type]);
                $unit = Unit::firstOrCreate(['name' => $import->unit]);

                $product = Product::firstOrNew([
                    'name' => $name_inch,
                    'gsm' => $import->gsm,
                    'product_type_id' => $productType->id,
                    'category_id' => $category->id,
                ]);
                $product->name_other = $name_cm;
                $product->category_id = $category->id;
                $product->unit_id = $unit->id;
                $product->hsn = $import->hsn;
                $product->status_id = 14;
                $product->save();

                $productAttribute = ProductAttribute::firstOrNew([
                    'product_id' => $product->id,
                    'item_per_packet' => $import->sheet_per_packet,
                    'weight_per_piece' => number_format($import->weight_per_packet, 1, '.', ''),
                ]);
                $productAttribute->save();

                $stock = Stock::firstOrNew([
                    'product_id' => $product->id,
                    'product_attribute_id' => $productAttribute->id,
                ]);
                $stock->opening_stock = $import->opening_stock;
                $stock->quantity += $import->quantity;
                $stock->in_hand_quantity = $import->in_hand_quantity;
                $stock->save();

                ProductLedger::create([
                    'product_id' => $product->id,
                    'product_attribute_id' => $productAttribute->id,
                    'reference_no' => 'Imported Product',
                    'type' => 'in',
                    'new_quantity' => $import->opening_stock,
                    'current_quantity' => $import->opening_stock,
                    'source_type' => 'Import',
                    'source_id' => null,
                    'note' => 'Initial stock from import',
                    'created_by' => $import->create_by ?? auth('admin')->id(),
                ]);
            }
            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'All products imported successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => 'Import Failed: ' . $e->getMessage(),
                'debug' => $e->getTraceAsString(),
            ]);
        }
    }

}
