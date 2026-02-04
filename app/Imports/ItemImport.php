<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\CoatingType;
use App\Models\Dye;
use App\Models\Item;
use App\Models\ItemProcessDetail;
use App\Models\OtherCoatingType;
use App\Models\PrintingMachine;
use App\Models\ProductType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
    protected $coatingTypeCache = [];
    protected $otherCoatingTypeCache = [];

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

            $date = Carbon::createFromFormat('d.m.Y', trim($data['date']))->startOfDay();
            $hour = rand(9, 10);
            $minute = rand(0, 59);
            $second = rand(0, 59);
            $timestamp = $date->setTime($hour, $minute, $second);

            $coatingTypeId      = $this->resolveCoatingType($data['coating'] ?? 'None');
            $otherCoatingTypeId = $this->resolveOtherCoatingType($data['other_coating'] ?? 'None');

            $item = Item::where(
                [
                    'item_name' => $data['item_name'],
                    'item_size' => $data['item_size'],
                    'mkdt_by'   => $mkdtById,
                    'mfg_by'    => $mfgById,
                ])->first();

                $item->update([
                   
                    'back_print'  => $data['back_print'] ?? null,
                    'braille' => $data['braille'] ?? null,
                ]);

            


            return  $item_details = ItemProcessDetail::where([
                'item_id'         => $item->id,
                'colour'          => $data['colour'],
                'gsm'             => $data['gsm'],
                'coating_type_id' => $coatingTypeId,
                'other_coating_type_id'   => $otherCoatingTypeId,
                'embossing'       => $data['embossing'],
                'leafing'         => $data['leafing'],
                'set_number'      => $data['set_number'],
            ])->first();

            $item_details->update([
                'dye_id'          => $dyeId,
                'back_print'      => $data['back_print'] ?? null,
                'braille' => $data['braille'] ?? null,
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
            'embossing'      => 'required|in:Yes,No',
            'leafing'        => 'required|in:Yes,No',
            'back_print'     => 'required|in:Yes,No',
            'braille'        => 'required|in:Yes,No',
            'quantity'       => 'required|numeric|min:0',
            'rate'           => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
        ];
    }

    // ----------------------------------------------------
    //               RESOLVER FUNCTIONS
    // ----------------------------------------------------


// protected function resolveClientFuzzy($value)
// {
//     if (!$value) return null;

//     $value = trim($value);

//     if (isset($this->clientCache[$value])) {
//         return $this->clientCache[$value];
//     }

//     $client = Client::whereRaw('LOWER(company_name) = ?', [strtolower($value)])->first();

//     if ($client) {
//         return $this->clientCache[$value] = $client->id;
//     }

//     $client = Client::create([
//         'company_name' => $value,
//         'email'        => strtolower(str_replace(' ', '', $value)) . '@example.com',
//         'state_id'     => 28,
//         'city_id'      => 133,
//         'gst'          => 'ABCDE1234F',
//         'pincode'      => '160059',
//         'password'     => bcrypt(time()),
//         'contact_no'   => '1234567890',
//         'status_id'    => 1,
//     ]);

//     return $this->clientCache[$value] = $client->id;
// }
    protected function resolveClientFuzzy($value)
    {
        if (!$value) return null;

        if (isset($this->clientCache[$value])) 
            return $this->clientCache[$value];

        // if (is_numeric($value)) {
        //     $client = Client::find($value);
        //     return $this->clientCache[$value] = $client?->id;
        // }

        // $client = Client::where('gst', $value)->first();
        // if ($client) return $this->clientCache[$value] = $client->id;

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
            $dye = Dye::where('dye_number', $value);
            return $this->dyeCache[$value] = $dye?->id;
        }

        return $this->dyeCache[$value] = null;
    }

    protected function nullableInt($value)
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function convertDate($value)
    {
        $date = \DateTime::createFromFormat('d.m.y', trim($value));
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }


    protected function resolveCoatingType($value){
        if (!$value) return null;

        $key = strtolower(trim($value));

        if (isset($this->coatingTypeCache[$key])) {
            return $this->coatingTypeCache[$key];
        }

        $type = CoatingType::whereRaw('LOWER(name) = ?', [$key])->first();

        if (!$type) {
            $type = CoatingType::create([
                'name' => trim($value),
            ]);
        }

        return $this->coatingTypeCache[$key] = $type->id;
    }

    protected function resolveOtherCoatingType($value){
        if (!$value) return null;

        $key = strtolower(trim($value));

        if (isset($this->otherCoatingTypeCache[$key])) {
            return $this->otherCoatingTypeCache[$key];
        }

        $type = OtherCoatingType::whereRaw('LOWER(name) = ?', [$key])->first();

        if (!$type) {
            $type = OtherCoatingType::create([
                'name' => trim($value),
            ]);
        }

        return $this->otherCoatingTypeCache[$key] = $type->id;
    }
}