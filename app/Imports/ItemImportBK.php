<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Dye;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\PrintingMachine;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemImport implements 
    ToModel, 
    WithHeadingRow, 
    WithChunkReading, 
    WithValidation, 
    SkipsOnFailure
{
    use SkipsFailures;

    public $errors = [];

    protected $clientCache = [];
    protected $productTypeCache = [];
    protected $machineCache = [];
    protected $dyeCache = [];

    public function __construct()
    {
        DB::disableQueryLog();
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
    }

    public function chunkSize(): int
    {
        return 500; // recommended
    }

    public function model(array $data)
    {
        try {
            $data = collect($data)->map(fn($v) => is_string($v) ? trim($v) : $v)->toArray();

            $mkdtById          = $this->resolveClientFuzzy($data['mkdt_by']);
            $mfgById           = $this->resolveClientFuzzy($data['mfg_by']);
            $productTypeId     = $this->resolveProductType($data['product_type']);
            $dyeId             = $this->resolveDye($data['dye'] ?? null);
            $printingMachineId = $this->resolvePrintingMachine($data['printing_machine'] ?? null);

            $item = Item::updateOrCreate(
                [
                    'item_name' => $data['item_name'],
                    'item_size' => $data['item_size'],
                    'mkdt_by'   => $mkdtById,
                    'mfg_by'    => $mfgById,
                ],
                [
                    'product_type_id' => $productTypeId,
                    'colour'          => $data['colour'] ?? null,
                    'gsm'             => $data['gsm'] ?? null,
                    'coating'         => $data['coating'] ?? 'None',
                    'other_coating'   => $data['other_coating'] ?? 'None',
                    'embossing'       => $data['embossing'] ?? null,
                    'leafing'         => $data['leafing'] ?? null,
                    'back_print'      => $data['back_print'] ?? null,
                    'brail_embossing' => $data['brail_embossing'] ?? null,
                    'artwork_code'    => $data['artwork_code'] ?? null,
                    'status_id'       => 14,
                ]
            );

            return new ItemProcessDetail([
                'item_id'         => $item->id,
                'purchase_order_id'       => $this->nullableInt($data['purchase_order_id'] ?? null),
                'purchase_order_item_id'  => $this->nullableInt($data['purchase_order_item_id'] ?? null),

                'product_type_id' => $productTypeId,
                'dye_id'          => $dyeId,
                'printing_machine_id' => $printingMachineId,

                'batch'           => $data['batch'] ?? null,
                'colour'          => $data['colour'] ?? null,
                'gsm'             => $data['gsm'] ?? null,
                'coating'         => $data['coating'] ?? 'None',
                'other_coating'   => $data['other_coating'] ?? 'None',
                'embossing'       => $data['embossing'] ?? null,
                'leafing'         => $data['leafing'] ?? null,
                'back_print'      => $data['back_print'] ?? null,
                'brail_embossing' => $data['brail_embossing'] ?? null,
                'artwork_code'    => $data['artwork_code'] ?? null,
                'job_type'        => $data['job_type'] ?? null,

                'sheet_size'      => $data['sheet_size'] ?? null,
                'number_of_sheet' => $data['number_of_sheet'] ?? null,
                'set_number'      => $data['set_number'] ?? null,
                'ups'             => $data['ups'] ?? null,
                'board_size'      => $data['board_size'] ?? null,
                'divide'          => $data['divide'] ?? null,

                'quantity'       => $this->nullableInt($data['quantity'] ?? 0),
                'rate'           => $data['rate'] ?? 0,
                'gst_percentage' => $data['gst_percentage'] ?? 18,
                'status_id'      => 3,
            ]);

        } catch (\Throwable $e) {
            $this->errors[] = [
                'row' => $data,
                'error' => $e->getMessage(),
            ];
        }

        return null;
    }

    public function rules(): array
    {
        return [
            'item_name'      => 'required|string|max:255',
            'item_size'      => 'required|string|max:255',
            'mkdt_by'        => 'required',
            'mfg_by'         => 'required',
            'product_type'   => 'required',
            'embossing'      => 'nullable|in:Yes,No',
            'leafing'        => 'nullable|in:Yes,No',
            'back_print'     => 'nullable|in:Yes,No',
            'brail_embossing'=> 'nullable|in:Yes,No',
            'quantity'       => 'nullable|numeric|min:0',
            'rate'           => 'nullable|numeric|min:0',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
        ];
    }

    // ----------------------------------------------------
    //               RESOLVER FUNCTIONS
    // ----------------------------------------------------

    protected function resolveClientFuzzy($value)
    {
        if (!$value) return null;

        if (isset($this->clientCache[$value])) 
            return $this->clientCache[$value];

        if (is_numeric($value)) {
            $client = Client::find($value);
            return $this->clientCache[$value] = $client?->id;
        }

        $client = Client::where('gst', $value)->first();
        if ($client) return $this->clientCache[$value] = $client->id;

        $client = Client::where('company_name', 'LIKE', "%$value%")->first();
        return $this->clientCache[$value] = $client?->id;
    }

    protected function resolveProductType($value)
    {
        if (!$value) return null;

        if (isset($this->productTypeCache[$value]))
            return $this->productTypeCache[$value];

        if (is_numeric($value)) {
            $pt = ProductType::find($value);
            return $this->productTypeCache[$value] = $pt?->id;
        }

        $pt = ProductType::firstOrCreate(['name' => $value]);
        return $this->productTypeCache[$value] = $pt->id;
    }

    protected function resolvePrintingMachine($value)
    {
        if (!$value) return null;

        $key = strtolower(trim($value));

        if (isset($this->machineCache[$key]))
            return $this->machineCache[$key];

        $pm = PrintingMachine::whereRaw('LOWER(name)=?', [$key])->first();
        if ($pm) return $this->machineCache[$key] = $pm->id;

        $pm = PrintingMachine::firstOrCreate(['name' => $value]);
        return $this->machineCache[$key] = $pm->id;
    }

    protected function resolveDye($value)
    {
        if (!$value) return null;

        if (isset($this->dyeCache[$value]))
            return $this->dyeCache[$value];

        if (is_numeric($value)) {
            $dye = Dye::find($value);
            return $this->dyeCache[$value] = $dye?->id;
        }

        return $this->dyeCache[$value] = null;
    }

    protected function nullableInt($value)
    {
        return is_numeric($value) ? (int) $value : null;
    }
}