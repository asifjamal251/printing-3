@php
$poItems = session('po_item', []);
@endphp

@if (!empty($poItems))
@php
$firstPoItem = reset($poItems);
$item = App\Models\Item::find($firstPoItem['po_item_id']);
@endphp

{{ html()->form('POST', route('admin.' . request()->segment(2) . '.po.store'))
->attribute('enctype', 'multipart/form-data')
->id('store')
->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                    {{ html()->label('Choose Client', 'client') }} <span class="text-danger">*</span>
                    {{ html()->select('client',[])
                    ->id('client')
                    ->placeholder('Choose Client')
                    ->class('client form-control') }}
                    <small class="text-danger">{{ $errors->first('client') }}</small>
                </div>
            </div>



            <div class="col-md-4 col-sm-12">
                <div class="form-group{{ $errors->has('po_number') ? ' has-error' : '' }}">
                    {{ html()->label('PO Number', 'po_number') }} <span class="text-danger">*</span>
                    {{ html()->text('po_number')->class('form-control')->placeholder('PO Number') }}
                    <small class="text-danger">{{ $errors->first('po_number') }}</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('po_date') ? ' has-error' : '' }}">
                    {{ html()->label('PO Date', 'po_date') }} <span class="text-danger">*</span>
                    {{ html()->text('po_date')->class('form-control dateSelector')->placeholder('PO Date') }}
                    <small class="text-danger">{{ $errors->first('po_date') }}</small>
                </div> 
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', 'remarks') }}
                    {{ html()->text('remarks')->class('form-control')->placeholder('Remarks') }}
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                </div> 
            </div>

        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')
        ->type('button')
        ->class('btn btn-success bg-gradient')
        ->attribute('onclick', 'store(this)') }}
    </div>
</div>

{{ html()->form()->close() }}
@else
<div class="card">
    <div class="card-body">
        <div class="alert alert-warning text-center my-4">
            <strong>No data available for generating Purchase Order.</strong>
        </div>
    </div>
</div>
@endif
