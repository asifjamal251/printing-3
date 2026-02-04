<?php
namespace App\Imports;

use App\Models\ProductImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    protected $user;
    private $serialNumber = 0;

    public function __construct($user){
        $this->user = $user;
    }

    
    public function model(array $row)
    {

        return new ProductImport([
            'create_by'          => $this->user->id,
            'product_type'      => $row['product_type'] ?? null,
            'mill'               => $row['mill'] ?? null,
            'sheet_per_packet'   => $row['sheet_per_packet'] ?? null,
            'weight_per_packet'  => $row['weight_per_packet'] ?? null,
            'name_cm'            => $row['name_cm'],
            'name_inch'          => $row['name_inch'] ?? null,
            'hsn'                => $row['hsn'] ?? null,
            'gsm'                => $row['gsm'] ?? null,
            'opening_stock'      => $row['opening_stock'] ?? 0,
            'quantity'           => $row['opening_stock'] ?? 0,
            'in_hand_quantity'   => $row['in_hand_quantity'] ?? 0,
            'location'           => $row['location'] ?? null,
            'unit'               => $row['unit'] ?? null,
        ]);
    }
}
