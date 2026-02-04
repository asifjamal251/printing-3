@php
    $poItems = session('po_item', []);
    $poData  = $poItems[$item->id] ?? null;
@endphp


{{ html()->form('POST', route('admin.' . request()->segment(2) . '.add.to.po.store', $item->id))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">


            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has("quantity") ? ' has-error' : '' }}">
                    <span class="d-flex justify-content-between"><apan>{{ html()->label('Quantity', "quantity") }}  <span class="text-danger">*</span></apan>  <span class="text-dark"> In Warehouse: <span class="text-success">{{$item?->itemStock?->total_quantity??0}}</span></span></span>
                    {{ html()->text("quantity", $poData['quantity'] ?? '')->class('form-control')->placeholder('Quantity') }}
                    <small class="text-danger">{{ $errors->first("quantity") }}</small>
                </div> 
            </div>


            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has("rate") ? ' has-error' : '' }}">
                    {{ html()->label('Rate', "rate") }}  <span class="text-danger">*</span>
                    <div class="input-group"> 
                        <span class="input-group-text bg-white">₹</span>
                        {{ html()->text("rate", $poData['rate'] ?? '')->class('form-control')->placeholder('Rate') }}
                    </div>
                    <small class="text-danger">{{ $errors->first("rate") }}</small>
                </div> 
            </div>


            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has("gst_percentage") ? ' has-error' : '' }}">
                    {{ html()->label('GST %', "gst_percentage") }}  <span class="text-danger">*</span>
                    <div class="input-group"> 
                        {{ html()->text("gst_percentage", $poData['gst_percentage'] ?? 5)->class('form-control')->placeholder('GST %') }}
                        <span class="input-group-text bg-white">%</span>
                    </div>
                    <small class="text-danger">{{ $errors->first("gst_percentage") }}</small>
                </div> 
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has("batch") ? ' has-error' : '' }}">
                    {{ html()->label('Batch', "batch") }}
                    {{ html()->select("batch", ['Yes' => 'Yes', 'No' => 'No'] ,$poData['batch'] ?? 'No')->class('js-choice form-control')->placeholder('Batch') }}
                    <small class="text-danger">{{ $errors->first("batch") }}</small>
                </div> 
            </div>


            <div class="col-md-12 col-sm-12">
                <div class="m-0 form-group{{ $errors->has("remarks") ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', "remarks") }}
                    {{ html()->text("remarks", $poData['remarks'] ?? '')->class('form-control')->placeholder('Remarks') }}
                    <small class="text-danger">{{ $errors->first("remarks") }}</small>
                </div> 
            </div>


            



            </div>
        </div>
    </div>



    <div class="col-md-4 col-sm-12">
        <div class="mt-4 form-group">
             {{ html()->button($poData ? 'Remove Item' : 'Save Details')
            ->type('button')
            ->class('btn ' . ($poData ? 'btn-danger' : 'btn-success') . ' bg-gradient')
            ->attribute('onclick', 'store(this)') }}
        </div>
    </div>

    {{ html()->form()->close() }}



    <script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
    <script type="text/javascript">

    </script>
