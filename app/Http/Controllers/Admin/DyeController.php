<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Dye\DyeCollection;
use App\Imports\DyeImport;
use App\Models\Dye;
use App\Models\DyeLockType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class DyeController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            if ($request->type === 'dye_lock_type') {
                $categories = DyeLockType::where('status_id', 14)->get();
                $cat = array();
                foreach ($categories as $cat2) {
                    $cat[] = ['id' => $cat2->id, 'text' => $cat2->type, 'a_attr' => ['href' => route('admin.dye.index', 'dye_lock_type=' . $cat2->id)], 'parent' => ($cat2->parent) ? $cat2->parent : '#'];
                }
                return response()->json($cat);
            }

            $datas = Dye::orderBy('dye_number', 'asc')->with(['items']);


            $dye_type = request()->input('dye_type');
            if ($dye_type) {
                $datas->where('dye_type', $dye_type);
            }

            $type = request()->input('type');
            if ($type) {
                $datas->where('type', $type);
            }


            $lock_type = request()->input('lock_type');
            if ($lock_type) {
                $datas->whereHas('dyeDetails', function ($q) use ($lock_type) {
                    $q->where('dye_lock_type_id', $lock_type);
                });
            }


            $dye_number = request()->input('dye_number');
            if ($dye_number) {
                $datas->where('dye_number', 'like', '%'.$dye_number.'%');
            }

            $sheet_size = request()->input('sheet_size');
            if ($sheet_size) {
                $datas->where('sheet_size', 'like', '%'.$sheet_size.'%');
            }



            $search = trim($request->input('search'));



            if ($search !== '') {

                $normalized = strtolower(str_replace(' ', '', $search));
                $parts = array_values(array_filter(explode('*', $normalized)));

                $datas->where(function ($q) use ($search, $parts) {

                    if (count($parts) === 1 && is_numeric($parts[0])) {

                        $q->where('dye_number', 'like', "%{$search}%")
                        ->orWhereHas('dyeDetails', function ($sub) use ($parts) {
                          $sub->where('length', 'like', "{$parts[0]}%");
                      });
                    }

                    elseif (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {

                        [$a, $b] = $parts;

                        $q->whereHas('dyeDetails', function ($sub) use ($a, $b) {
                            $sub->where(function ($w) use ($a, $b) {
                                $w->where(function ($x) use ($a, $b) {
                        // length x width
                                    $x->where('length', 'like', "{$a}%")
                                    ->where('width',  'like', "{$b}%");
                                })
                                ->orWhere(function ($x) use ($a, $b) {
                        // width x height
                                    $x->where('width',  'like', "{$a}%")
                                    ->where('height', 'like', "{$b}%");
                                });
                            });
                        });
                    }


                    elseif (count($parts) >= 3 && is_numeric($parts[0]) && is_numeric($parts[1]) && is_numeric($parts[2])) {

                        [$l, $w, $h] = $parts;

                        $q->whereHas('dyeDetails', function ($sub) use ($l, $w, $h) {
                            $sub->where('length', 'like', "{$l}%")
                            ->where('width',  'like', "{$w}%")
                            ->where('height', 'like', "{$h}%");
                        });
                    }


                    else {
                        $q->where('dye_number', 'like', "%{$search}%");
                    }
                });
            }


            $totaldata = $datas->count();
            $request->merge(['recordsTotal' => $totaldata, 'length' => $request->length]);

            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new DyeCollection($datas));
        }
        return view('admin.dye.list');
    }


    public function create()
    {
        return view('admin.dye.create');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'       => 'required|in:Mix,Separate',
            'dye_number' => 'nullable|string|max:255',
            'sheet_size' => 'required|string|max:255',
            'dye_type'   => 'required|in:Manual,Automatic',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.dye_lock_type' => 'required|exists:dye_lock_types,id',
            'kt_docs_repeater_advanced.*.length'        => 'required|string|max:255',
            'kt_docs_repeater_advanced.*.width'         => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.height'        => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.pasting_flap'  => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.tuckin_flap'   => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.ups'           => 'nullable|string|max:255',
        ], [
            'kt_docs_repeater_advanced.required' => 'At least one lock type entry is required.',
            'kt_docs_repeater_advanced.*.dye_lock_type.required' => 'Dye Lock Type is required.',
            'kt_docs_repeater_advanced.*.length.required'        => 'Length is required.',
        ]);

        DB::beginTransaction();

        try {
        // Create main Dye record
            $dye = Dye::create([
                'type'        => $validated['type'],
                'dye_number'  => $validated['dye_number'] ?? null,
                'sheet_size'  => $validated['sheet_size'],
                'dye_type'    => $validated['dye_type'],
            'status_id'   => 14, // default status
        ]);

        // Create Dye Lock details (repeater data)
            foreach ($validated['kt_docs_repeater_advanced'] as $lock) {
                $dye->items()->create([
                    'dye_lock_type_id' => $lock['dye_lock_type'],
                    'length'           => $lock['length'],
                    'width'            => $lock['width'] ?? null,
                    'height'           => $lock['height'] ?? null,
                    'pasting_flap'     => $lock['pasting_flap'] ?? null,
                    'tuckin_flap'      => $lock['tuckin_flap'] ?? null,
                    'ups'              => $lock['ups'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'class'          => 'bg-success',
                'error'          => false,
                'message'        => 'Dye created successfully',
                'table_refresh'  => true,
                'call_back' => '',
                'model_id'       => 'dataSave',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'class'          => 'bg-danger',
                'error'          => true,
                'message'        => 'Something went wrong! ' . $e->getMessage(),
                'call_back'      => '',
                'table_referesh' => false,
                'model_id'       => '',
            ]);
        }
    }


    public function edit($id){
        $die = Dye::findOrFail($id);
        return view('admin.dye.edit', compact('die'));
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type'       => 'required|in:Mix,Separate',
            'dye_number' => 'nullable|string|max:255',
            'sheet_size' => 'required|string|max:255',
            'dye_type'   => 'required|in:Manual,Automatic',

            'kt_docs_repeater_advanced' => 'required|array|min:1',
            'kt_docs_repeater_advanced.*.id' => 'nullable|exists:dye_items,id',
            'kt_docs_repeater_advanced.*.dye_lock_type' => 'required|exists:dye_lock_types,id',
            'kt_docs_repeater_advanced.*.length'        => 'required|string|max:255',
            'kt_docs_repeater_advanced.*.width'         => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.height'        => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.pasting_flap'  => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.tuckin_flap'   => 'nullable|string|max:255',
            'kt_docs_repeater_advanced.*.ups'           => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {

                $dye = Dye::findOrFail($id);

                $dye->update([
                    'type'       => $validated['type'],
                    'dye_number' => $validated['dye_number'] ?? null,
                    'sheet_size' => $validated['sheet_size'],
                    'dye_type'   => $validated['dye_type'],
                    'status_id'  => 14,
                ]);

                $incomingIds = collect($validated['kt_docs_repeater_advanced'])
                ->pluck('id')
                ->filter()
                ->toArray();

                $dye->items()
                ->whereNotIn('id', $incomingIds)
                ->delete();

                foreach ($validated['kt_docs_repeater_advanced'] as $lock) {

                    $data = [
                        'dye_lock_type_id' => $lock['dye_lock_type'],
                        'length'           => $lock['length'],
                        'width'            => $lock['width'] ?? null,
                        'height'           => $lock['height'] ?? null,
                        'pasting_flap'     => $lock['pasting_flap'] ?? null,
                        'tuckin_flap'      => $lock['tuckin_flap'] ?? null,
                        'ups'              => $lock['ups'] ?? null,
                    ];

                    if (!empty($lock['id'])) {
                        $dye->items()->where('id', $lock['id'])->update($data);
                    } else {
                        $dye->items()->create($data);
                    }
                }
            });

            return response()->json([
                'class'         => 'bg-success',
                'error'         => false,
                'message'       => 'Dye updated successfully',
                'table_refresh' => true,
                'model_id'      => 'dataSave',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'class'   => 'bg-danger',
                'error'   => true,
                'message' => $e->getMessage(),
            ]);
        }
    }



    public function importCreate(){
        return view('admin.dye.import');
    }

    public function importStore(Request $request): JsonResponse{
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new DyeImport();
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
            'message' => 'Dyes imported successfully.',
            'call_back' => '',
            'table_refresh' => true,
            'model_id' => 'dataSave',
        ]);
    }


    

}
