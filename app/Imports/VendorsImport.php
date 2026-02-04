<?php

namespace App\Imports;

use App\Models\Vendor;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorsImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowIndex = $index + 2; // Excel row number (including heading row)

            $validator = Validator::make($row->toArray(), [
                'email' => 'required|email|unique:Vendors,email',
                'state' => 'required',
                'city' => 'required',
                'company_name' => 'nullable|string',
                'contact_no' => 'nullable',
                'password' => 'nullable',
                'gst' => 'nullable|string',
                'pincode' => 'nullable|max:6',
                'address' => 'nullable',
                'cc_emails' => 'nullable',
            ]);

            // if ($validator->fails()) {
            //     $this->errors[] = [
            //         'row' => $rowIndex,
            //         'errors' => $validator->errors()->all(),
            //     ];
            //     continue;
            // }

            $state = State::where('name', $row['state'])->first();
            $city = City::where('name', $row['city'])->first();

            // if (!$state || !$city) {
            //     $this->errors[] = [
            //         'row' => $rowIndex,
            //         'errors' => [
            //             !$state ? "State '{$row['state']}' not found." : '',
            //             !$city ? "City '{$row['city']}' not found." : '',
            //         ],
            //     ];
            //     continue;
            // }

            Vendor::create([
                'state_id' => $state->id??6,
                'city_id' => $city->id??246,
                'company_name' => $row['company_name'],
                'email' => 'testcompany'.$rowIndex.'@example.com',
                'contact_no' => $row['contact_no'],
                'gst' => $row['gst'],
                'pincode' => $row['pincode'],
                'address' => $row['address'],
                'cc_emails' => $row['cc_emails'],
                'status_id' => 14,
            ]);
        }
    }
}