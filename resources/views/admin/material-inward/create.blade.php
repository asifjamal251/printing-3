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

{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card border border-success mb-5">
    <div class="card-body">
        <div class="row ">

            {{ html()->hidden('order_id', $order->id) }}
            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('vendor') ? ' has-error' : '' }}">
                    {{ html()->label('Vendor', 'vendor') }}
                    {{ html()->select('vendor', App\Models\Vendor::where('id', $order->vendor_id)->pluck('company_name', 'id'), $order->vendor_id)->id('vendors')->placeholder('Choose Vendor')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('vendor') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has('bill_no') ? ' has-error' : '' }}">
                    {{ html()->label('Bill No.', 'bill_no') }}
                    {{ html()->text('bill_no')->class('form-control')->placeholder('Bill No.') }}
                    <small class="text-danger">{{ $errors->first('bill_no') }}</small>
                </div>
            </div>


            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('bill_date') ? ' has-error' : '' }}">
                    {{ html()->label('Bill Date', 'bill_date') }}
                    {{ html()->text('bill_date')->id('bill_date')->placeholder('Bill Date')->class('dateSelector form-control') }}
                    <small class="text-danger">{{ $errors->first('bill_date') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', 'remarks') }}
                    {{ html()->text('remarks')->class('form-control')->placeholder('Remarks') }}
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>







<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @foreach(old('kt_docs_repeater_advanced', $order->items ?? [[]]) as $index => $item)

                 @php
                    $product = App\Models\Product::where('id', $item->product_id)->with(['productType', 'category'])->first();
                @endphp

               
            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card border border-secondary">

                     {{ html()->hidden("kt_docs_repeater_advanced[$index][order_item_id]", old("kt_docs_repeater_advanced.$index.order_item_id", $item['id'] ?? '')) }}


                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">
                            
                            <div class="w-100 form-group{{$errors->has('kt_docs_repeater_advanced.'.$loop->index.'.product') ? ' has-error' : '' }}">
                                <label>Choose Product </label>
                                <select name="product" class="form-control form-select" data-kt-repeater="select2" data-placeholder="Select an option">

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
                                    {{ html()->text("kt_docs_repeater_advanced[$index][quantity]", old("kt_docs_repeater_advanced.$index.quantity", $item['quantity'] ?? ''))->class('form-control ktquantity')->placeholder('Quantity') }}
                                   
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity") }}</small>
                            </div>


                            <div class="w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_per_packet") ? ' has-error' : '' }}">
                                {{ html()->label('Item/Packet', "kt_docs_repeater_advanced[$index][item_per_packet]") }}
                                {{ html()->select("kt_docs_repeater_advanced[$index][item_per_packet]", App\Models\ProductAttribute::where('product_id', $product->id)->pluck('item_per_packet', 'id'), old("kt_docs_repeater_advanced.$index.item_per_packet", $item['product_attribute_id'] ?? ''))->class('productAttribute form-control')->placeholder('Item/Packet') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.item_per_packet") }}</small>
                            </div> 


                            <div class="w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.weight_per_piece") ? ' has-error' : '' }}">
                                {{ html()->label('WT/PC/PKT', "kt_docs_repeater_advanced[$index][weight_per_piece]") }}
                                    {{ html()->text("kt_docs_repeater_advanced[$index][weight_per_piece]", old("kt_docs_repeater_advanced.$index.weight_per_piece", App\Models\ProductAttribute::where('product_id', $product->id)->value('weight_per_piece') ?? ''))->class('form-control')->attribute('readonly')->placeholder('WT/PC/PKT') }}
                                   
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.weight_per_piece") }}</small>
                            </div>




                        </div>


                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">
                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.total_weight") ? ' has-error' : '' }}">
                                {{ html()->label('Total WT', "kt_docs_repeater_advanced[$index][total_weight]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][total_weight]",  old("kt_docs_repeater_advanced.$index.total_weight", $item['total_weight'] ?? ''))->class('form-control')->attribute('readonly')->placeholder('Total WT') }}
                                    <span class="input-group-text unit">KG</span>
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.total_weight") }}</small>
                            </div>


                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.rate") ? ' has-error' : '' }}">
                                {{ html()->label('Rate', "kt_docs_repeater_advanced[$index][rate]") }} 

                                @can('rate_product')
                                    <a  model-size="modal-xl" data-title="View Product Rate" data-url="{{route('admin.product.rate')}}?product_id={{$product->id}}" href="javascript:void(0);"  class="product-rate create float-end text-end text-decoration-underline ps-2" style="width:75px;">View Rate</a>
                                @endcan

                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    {{ html()->text("kt_docs_repeater_advanced[$index][rate]", old("kt_docs_repeater_advanced.$index.rate", $item['rate'] ?? ''))->class('form-control ktrate')->placeholder('Rate') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.rate") }}</small>
                            </div>

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.gst") ? ' has-error' : '' }}">
                                {{ html()->label('GST', "kt_docs_repeater_advanced[$index][gst]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][gst]", old("kt_docs_repeater_advanced.$index.gst", $item['gst'] ?? ''))->class('form-control ktgst')->attribute('readonly')->placeholder('GST') }}
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.gst") }}</small>
                            </div>

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.amount") ? ' has-error' : '' }}">
                                {{ html()->label('Amount', "kt_docs_repeater_advanced[$index][amount]") }}
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    {{ html()->text("kt_docs_repeater_advanced[$index][amount]", old("kt_docs_repeater_advanced.$index.amount", $item['amount'] ?? ''))->class('productAttribute form-control')->attribute('readonly')->placeholder('Amount') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.amount") }}</small>
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

   let rowCounter = 0;

   $('#kt_docs_repeater_advanced').repeater({
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');

        $row.slideDown('fast', function () {
            $row.find('input[name*="[packet_weight]"]').first().focus().addClass('ojkk');
        });
        getProduct('.getProduct', false);
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    },
    //isFirstItemUndeletable: true
});




   $(document).ready(function () {

    if ($('#vendor').length > 0 || $('.vendor').length > 0) {
        getVendor('.vendor', false);
    }

    getProduct('.getProduct', false);
});
</script>
@endpush