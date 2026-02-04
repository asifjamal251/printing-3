<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClientSaleLedgerExport;
use App\Exports\ClientsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Client\ClientCollection;
use App\Imports\ClientsImport;
use App\Models\City;
use App\Models\Client;
use App\Models\ProductLedger;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {
            $datas = Client::orderBy('company_name','asc')
            ->with(['media', 'city']);

            $name = request()->input('name');
            if ($name) {
                $datas->where('company_name', 'like', '%'.$name.'%');
            }

            $email = request()->input('email');
            if ($email) {
                $datas->where('email', 'like', '%'.$email.'%');
            }

            $contact_no = request()->input('contact_no');
            if ($contact_no) {
                $datas->where('contact_no', 'like', '%'.$contact_no.'%');
            }

            $gst = request()->input('gst');
            if ($gst) {
                $datas->where('gst', 'like', '%'.$gst.'%');
            }


            $city = $request->input('city');
            if ($city) {
                $datas->where('city_id', $city);
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new ClientCollection($datas));

        }
        return view('admin.client.list');
    }



    public function create(Request $request ){
        return view('admin.client.create');
    }


    public function store(Request $request){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email|max:91',
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'state' => 'nullable',
            'city' => 'nullable',
            'cc_emails' => 'nullable',
        ]);

        $emails = $this->parseCcEmails($request->cc_emails);

        $client = Client::create([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'password' => Hash::make(123456),
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'gst' => $request->gst,
            'cc_emails' => $request->cc_emails,
            'status_id' => $request->status,
        ]);

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Client Saved Successfully', 'call_back' => '', 'table_refresh' => true, 'model_id' => 'dataSave']);
    }



    public function update(Request $request, $id){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:91|unique:clients,email,' . $id,
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'state' => 'nullable',
            'city' => 'nullable',
        ]);

        //$emails = $this->parseCcEmails($request->cc_emails);

        $client = Client::findOrFail($id);

        $client->update([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            //'password' => $request->filled(123456) ? Hash::make($request->password) : $client->password,
            'media_id' => $request->media_id,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'gst' => $request->gst,
            'status_id' => $request->status,
            //'cc_emails' => !empty($emails) ? json_encode($emails) : null,
            'cc_emails' => $request->cc_emails,
        ]);

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Client Saved Successfully', 'call_back' => '', 'table_refresh' => true, 'model_id' => 'dataSave']);
    }


    public function edit($id){
        $client = Client::find($id);
        return view('admin.client.edit', compact('client'));
    }


    public function show(Request $request, $id){
        $client = Client::withSum('sales', 'total_amount')->findOrFail($id);

        if ($request->wantsJson()) {
            $saleIds = Sale::where('ship_to', $id)->pluck('id');

            $datas = ProductLedger::orderBy('product_ledgers.id', 'desc')
                ->where('product_ledgers.source_type', 'Sale')
                ->whereIn('product_ledgers.source_id', $saleIds)
                ->leftJoin('sales', 'product_ledgers.source_id', '=', 'sales.id')
                ->select('product_ledgers.*');

            $search = $request->search['value'] ?? null;
            if ($search) {
                $datas->where(function ($query) use ($search) {
                    $query->where('sales.so_number', 'like', "%$search%")
                          ->orWhere('sales_invoices.invoice_number', 'like', "%$search%");
                });
            }

            $recordsTotal = $datas->count();
            $records = $datas->with(['product.paperType', 'productAttribute'])
                             ->limit($request->length)
                             ->offset($request->start)
                             ->get();

            $saleMap = Sale::whereIn('id', $records->pluck('source_id'))->get()->keyBy('id');

            $saleItemMap = SaleItem::whereIn('sale_id', $records->pluck('source_id'))->get()->groupBy(fn ($item) => $item->sale_id . '-' . $item->product_id . '-' . $item->product_attribute_id);

            $result['data'] = [];
            $sn = $request->start;
            foreach ($records as $data) {
                $sale = $saleMap[$data->source_id] ?? null;
                if (!$sale) continue;

                $key = $data->source_id . '-' . $data->product_id . '-' . $data->product_attribute_id;
                $saleItem = $saleItemMap[$key][0] ?? null;

                $result['data'][] = [
                    'sn' => ++$sn,
                    'id' => $data->id,
                    'godown' => $sale->godown->display_name,
                    'mill' => $data->product->paperType->mill ?? 'N/A',
                    'so_no' => $sale->order_no,
                    'invoice_no' => $sale->invoice_no ?? 'N/A',
                    'quality' => $data->product->paperType->name ?? 'N/A',
                    'length_cm' => $data->product->length_cm,
                    'length_inch' => $data->product->length_inch,
                    'width_cm' => $data->product->width_cm,
                    'width_inch' => $data->product->width_inch,
                    'gsm' => $data->product->gsm,
                    'pkt_wt' => $data->productAttribute->weight_per_packet,
                    'pkt' => $data->new_quantity,
                    'so_date' => optional($sale->so_date)->format('d F, Y'),
                    'rate' => $saleItem?->rate ?? 0,
                    'weight' => number_format($data->new_quantity * $data->productAttribute->weight_per_packet, 2),
                ];
            }

            return [
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'data' => $result['data'],
            ];
        }

        return view('admin.client.view', compact('client'));
    }

    public function exportLedger($id){
        return Excel::download(new ClientSaleLedgerExport($id), 'client_ledger.xlsx');
    }


    public function parseCcEmails($raw): array{
        $raw = preg_replace('/[\s;]+/', ',', $raw); // convert spaces and semicolons to commas
        $emails = array_map('trim', explode(',', $raw)); // split and trim
        return array_filter($emails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }


    public function importCreate(){
        return view('admin.client.import');
    }

    public function importStore(Request $request): JsonResponse{
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new ClientsImport();
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $import->errors,
                'validation_errors' => $import->errors,
                'call_back' => '',
                'table_refresh' => false,
                'model_id' => 'dataSave',
            ]);
        }

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Clients imported successfully.',
            'call_back' => '',
            'table_refresh' => true,
            'model_id' => 'dataSave',
        ]);
    }

    public function exportClients(){
        return Excel::download(new ClientsExport, 'clients_export.xlsx');
    }


}
