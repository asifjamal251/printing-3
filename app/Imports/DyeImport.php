<?php

namespace App\Imports;

use App\Models\Dye;
use App\Models\DyeDetail;
use App\Models\DyeLockType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DyeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public $errors = [];

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $rows)
    {
        $existingLockTypes = DyeLockType::pluck('id', 'type')->toArray();

        $existingDyes = Dye::select('id', 'dye_number', 'type', 'dye_type')
            ->get()
            ->keyBy(fn($d) => $d->dye_number.'_'.$d->type.'_'.$d->dye_type);

        $newLockTypes = [];
        $newDyes = [];
        $dyeDetails = [];

        foreach ($rows as $row) {

            $validator = Validator::make($row->toArray(), [
                'type' => 'required|in:Mix,Separate',
                'dye_number' => 'required',
                'sheet_size' => 'required',
                'dye_type' => 'required|in:Manual,Automatic',
                'length' => 'required',
                'width' => 'required',
                'height' => 'required',
                'ups' => 'required',
                'dye_lock_type' => 'nullable',
            ]);

            // if ($validator->fails()) {
            //     $this->errors[] = [
            //         'row' => $row['__rowNum__'] + 1,
            //         'errors' => $validator->errors()->all()
            //     ];
            //     continue;
            // }

            if (!empty($row['dye_lock_type'])) {
                $lt = trim($row['dye_lock_type']);

                if (!isset($existingLockTypes[$lt])) {
                    $newLockTypes[$lt] = ['type' => $lt];
                }
            }

            $dyeKey = $row['dye_number'].'_'.$row['type'].'_'.$row['dye_type'];

            if (!isset($existingDyes[$dyeKey])) {
                $newDyes[$dyeKey] = [
                    'dye_number' => $row['dye_number'],
                    'type'       => $row['type'],
                    'dye_type'   => $row['dye_type'],
                    'sheet_size' => $row['sheet_size'],
                    'status_id'  => 14,
                ];
            }
        }

        if (!empty($newLockTypes)) {
            DyeLockType::insert(array_values($newLockTypes));
            $existingLockTypes = DyeLockType::pluck('id', 'type')->toArray();
        }

        if (!empty($newDyes)) {
            Dye::insert(array_values($newDyes));
            $existingDyes = Dye::select('id','dye_number','type','dye_type')
                ->get()
                ->keyBy(fn($d) => $d->dye_number.'_'.$d->type.'_'.$d->dye_type);
        }

        foreach ($rows as $row) {

            $lockTypeId = null;
            if (!empty($row['dye_lock_type'])) {
                $lockTypeId = $existingLockTypes[trim($row['dye_lock_type'])] ?? null;
            }

            $dyeKey = $row['dye_number'].'_'.$row['type'].'_'.$row['dye_type'];
            $dyeId  = $existingDyes[$dyeKey]->id;

            $dyeDetails[] = [
                'dye_id'           => $dyeId,
                'dye_lock_type_id' => $lockTypeId,
                'length'           => $row['length'],
                'width'            => $row['width'],
                'height'           => $row['height'],
                'tuckin_flap'      => $row['tuckin_flap'],
                'pasting_flap'     => $row['pasting_flap'],
                'ups'              => $row['ups'],
            ];
        }

        if (!empty($dyeDetails)) {
            DyeDetail::insert($dyeDetails);
        }
    }
}