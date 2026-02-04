@extends('admin.layouts.master')
@push('links')
<style type="text/css">

</style>
@endpush




@section('main')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

        </div>
    </div>
</div>
<!-- end page title -->

{{ html()->form('Put', route('admin.' . request()->segment(2) . '.update', $material_issue->id))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card border border-success mb-5">
    <div class="card-body">
        <div class="row">

            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                    {{ html()->label('Department', 'department') }}
                    {{ html()->select('department', App\Models\Department::orderBy('name', 'asc')->pluck('name', 'id'), $material_issue->department_id)->class('js-choice form-control')->placeholder('Select Department') }}
                    <small class="text-danger">{{ $errors->first('department') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('material_issue_date') ? ' has-error' : '' }}">
                    {{ html()->label('Material Issue Date', 'material_issue_date') }}
                    {{ html()->text('material_issue_date', $material_issue->material_issue_date)->id('material_issue_date')->placeholder('Material Issue Date')->class('dateSelector form-control') }}
                    <small class="text-danger">{{ $errors->first('material_issue_date') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', 'remarks') }}
                    {{ html()->text('remarks', $material_issue->remarks)->class('form-control')->placeholder('Remarks') }}
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                </div>
            </div>


        </div>
    </div>
</div>








<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
           @foreach(old('kt_docs_repeater_advanced', $material_issue->items ?? [[]]) as $index => $item)

                 @php
                    $product = App\Models\Product::where('id', $item->product_id)->with(['productType', 'category'])->first();
                @endphp

                {{ html()->hidden("kt_docs_repeater_advanced[$index][item_id]", old("kt_docs_repeater_advanced.$index.item_id", $item['id'] ?? '')) }}
            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">
                            <div class="w-100 form-group{{$errors->has('kt_docs_repeater_advanced.'.$loop->index.'.product') ? ' has-error' : '' }}">
                                <label>Choose Product </label>
                                <select name="product" class="form-control form-select getProduct" data-kt-repeater="select2" data-placeholder="Select an option">

                                    @if($item->product_id)
                                    <option selected="selected" value="{{$item->product_id}}">{{ $product->fullname }}</option>
                                    @else
                                    <option value="">Choose Product</option>
                                    @endif
                                </select>
                                <small class="text-danger validate kt_docs_repeater_advanced.{{ $loop->index }}.product">
                                    {{ $errors->first('kt_docs_repeater_advanced.'.$loop->index.'.product') }}
                                </small>
                            </div>


                            <div class="w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.quantity") ? ' has-error' : '' }}">
                                {{ html()->label('Quantity', "kt_docs_repeater_advanced[$index][quantity]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][quantity]", $item->quantity)->class('form-control ktquantity')->placeholder('Quantity') }}
                                   <span class="input-group-text unit">Unit</span>
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity") }}</small>
                            </div>


                            <div class="w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_per_packet") ? ' has-error' : '' }}">
                                {{ html()->label('Item/Packet', "kt_docs_repeater_advanced[$index][item_per_packet]") }}
                                {{ html()->select("kt_docs_repeater_advanced[$index][item_per_packet]",  App\Models\ProductAttribute::where('product_id', $product?->id)->pluck('item_per_packet', 'id'), old("kt_docs_repeater_advanced.$index.item_per_packet", $item['product_attribute_id'] ?? ''))->class('productAttribute form-control')->placeholder('Item/Packet') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.item_per_packet") }}</small>
                            </div> 


                            <div class="w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.weight_per_piece") ? ' has-error' : '' }}">
                                {{ html()->label('WT/PC/PKT', "kt_docs_repeater_advanced[$index][weight_per_piece]") }}
                                    {{ html()->text("kt_docs_repeater_advanced[$index][weight_per_piece]", App\Models\ProductAttribute::where('product_id', $product?->id)->value('weight_per_piece') ?? '')->class('form-control')->attribute('readonly')->placeholder('WT/PC/PKT') }}
                                   
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.weight_per_piece") }}</small>
                            </div>




                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.total_weight") ? ' has-error' : '' }}">
                                {{ html()->label('Total WT', "kt_docs_repeater_advanced[$index][total_weight]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][total_weight]", old("kt_docs_repeater_advanced.$index.total_weight", $item['weight'] ?? ''))->class('form-control')->attribute('readonly')->placeholder('Total WT') }}
                                    <span class="input-group-text unit">Unit</span>
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.total_weight") }}</small>
                            </div>


                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.remarks") ? ' has-error' : '' }}">
                                {{ html()->label('Remarks', "kt_docs_repeater_advanced[$index][remarks]") }}
                              
                                    {{ html()->text("kt_docs_repeater_advanced[$index][remarks]", $item['remarks'] ?? '')->class('form-control')->placeholder('Remarks') }}
                                    
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.remarks") }}</small>
                            </div>




                            <div class="m-0 form-group remove-item" style="width:44px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" class="btn-labels btn btn-danger" style="margin-top: 23px;">
                                        <i class="label-icon ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="form-group m-0">
                <button data-repeater-create type="button" class="btn-label btn btn-warning text-end btn-sm">
                    <i class="label-icon align-middle fs-16 me-2 bx bx-plus-circle"></i> Add New Row
                </button>
            </div>
        </div>
    </div>

</div>




<div class="col-md-12 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}
@endsection


@push('scripts')

<script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">



   $('#kt_docs_repeater_advanced').repeater({
    show: function () {
        var $row = $(this);


        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');

        $row.slideDown('fast', function () {
            $row.find('input[name*="[packet_weight]"]').first().focus().addClass('ojkk');
        });
        getProduct('.getProduct', false);
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});




   $(document).ready(function () {

    if ($('#vendor').length > 0 || $('.vendor').length > 0) {
        getVendor('.vendor', false);
    }

    getProduct('.getProduct', false);
});
</script>
@endpush
