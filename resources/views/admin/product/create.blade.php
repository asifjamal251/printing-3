
{{ html()->form('POST', route('admin.product.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-sm-12 col-md-3">
                <div class="form-group{{ $errors->has('product_type') ? ' has-error' : '' }}">
                    {{ html()->label('Product Type', 'product_type') }} <span class="text-danger">*</span>
                    {{ html()->select('product_type', App\Models\ProductType::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control js-choice')->placeholder('Product Type') }}
                    <small class="text-danger">{{ $errors->first('product_type') }}</small>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }} <span class="text-danger">*</span>
                    {{ html()->text('name')
                    ->class('form-control')
                    ->placeholder('Name')
                    ->attribute('autocomplete', 'off')}}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            {{-- <div class="col-sm-12 col-md-3">
                <div class="form-group{{ $errors->has('name_other') ? ' has-error' : '' }}">
                    {{ html()->label('Name Other', 'name_other') }}
                    {{ html()->text('name_other')
                    ->class('form-control')
                    ->placeholder('Name Other')
                    ->attribute('autocomplete', 'off')}}
                    <small class="text-danger">{{ $errors->first('name_other') }}</small>
                </div>
            </div> --}}
            <div class="col-sm-12 col-md-3">
            <div class="m-0 form-group{{ $errors->has('stores') ? ' has-error' : '' }}">
                    {{ html()->label('Stores')->for('stores') }}
                    {{ html()->select('stores', App\Models\Store::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control js-choice')->id('stores')->placeholder('Choose Stores') }}
                    <small class="text-danger">{{ $errors->first('stores') }}</small>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                    {{ html()->label('Category', 'category') }} <span class="text-danger">*</span>
                    {{ html()->select('category', App\Models\Category::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control js-choice')->placeholder('Category') }}
                    <small class="text-danger">{{ $errors->first('category') }}</small>
                </div>
            </div>


            <div class="col-sm-12 col-md-2">
                <div class="m-0 form-group{{ $errors->has('unit') ? ' has-error' : '' }}">
                    {{ html()->label('Unit', 'unit') }} <span class="text-danger">*</span>
                    {{ html()->select('unit', App\Models\Unit::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control js-choice')->placeholder('Unit') }}
                    <small class="text-danger">{{ $errors->first('unit') }}</small>
                </div>

            </div>


            <div class="col-sm-12 col-md-3">
                <div class="mb-0 form-group{{ $errors->has('hsn') ? ' has-error' : '' }}">
                    {{ html()->label('HSN', 'hsn') }} 
                    {{ html()->text('hsn')
                    ->class('form-control')
                    ->placeholder('HSN')
                    ->attribute('autocomplete', 'off') }}
                    <small class="text-danger">{{ $errors->first('hsn') }}</small>
                </div>
            </div>


            <div class="col-sm-12 col-md-2">
                <div class="m-0 form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
                    {{ html()->label('GST', 'gst') }} <span class="text-danger">*</span>
                    <div class="input-group">
                        {{ html()->text('gst')
                        ->class('form-control')
                        ->placeholder('GST')
                        ->attribute('autocomplete', 'off') }}
                        <span class="input-group-text unit">%</span>
                    </div>
                    <small class="text-danger">{{ $errors->first('gst') }}</small>
                </div>
            </div>

            <div class="col-sm-12 col-md-2">

                <div class="m-0 form-group{{ $errors->has('gsm') ? ' has-error' : '' }}">
                    {{ html()->label('GSM', 'gsm') }}
                    {{ html()->text('gsm')
                    ->class('form-control')
                    ->placeholder('GSM')
                    ->attribute('autocomplete', 'off') }}
                    <small class="text-danger">{{ $errors->first('gsm') }}</small>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="m-0 form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                    {{ html()->label('Choose Logo', "file") }}
                    <div class="media-area media-flex gap-1" file-name="file">

                     <a class="form-control text-secondary select-mediatype" href="javascript:void(0);" mediatype="single" onclick="loadMediaFiles($(this))">
                        Select Image
                    </a>
                </div>
                <small class="text-danger">{{ $errors->first('file') }}</small>
            </div>
        </div>


    </div>
</div>
</div>



<span class="w-100 d-inline-block" style="position: relative;">
    <small class="fs-10 text-danger" style="position: absolute;left: 0;right: 0;display: inline-table;top: 0; bottom: 0;margin: auto;background: #f3f3f9;">
        <i class="ri-alert-line"></i>
        Below Data Can not Be Changed
    </small>
    <hr class="border-dark" style="opacity: 1; border-style: dashed;">



</span>

<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @foreach(old('kt_docs_repeater_advanced', [[]]) as $index => $item)
            <div data-repeater-item class="repeater-row row-{{$index}} default-bg mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">


                         <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.weight_per_piece") ? ' has-error' : '' }}">
                            {{ html()->label('Weight/PC/PKT', "kt_docs_repeater_advanced[$index][weight_per_piece]") }} <span class="text-danger">*</span>
                            {{ html()->text("kt_docs_repeater_advanced[$index][weight_per_piece]")->class('form-control')->placeholder('Weight/PC/PKT') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.weight_per_piece") }}</small>
                        </div>




                        <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_per_packet") ? ' has-error' : '' }}">
                            {{ html()->label('Item/PC/PKT', "kt_docs_repeater_advanced[$index][item_per_packet]") }} <span class="text-danger">*</span>
                            {{ html()->text("kt_docs_repeater_advanced[$index][item_per_packet]")->class('form-control')->placeholder('Item/PC/PKT') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.item_per_packet") }}</small>
                        </div> 





                        <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.opening_stock") ? ' has-error' : '' }}">
                            {{ html()->label('Opening Stock', "kt_docs_repeater_advanced[$index][opening_stock]") }} <span class="text-danger">*</span>
                            {{ html()->text("kt_docs_repeater_advanced[$index][opening_stock]")->class('form-control')->placeholder('Opening Stock') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.opening_stock") }}</small>
                        </div>


                        <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.in_hand_quantity") ? ' has-error' : '' }}">
                            {{ html()->label('In Hand Quantity', "kt_docs_repeater_advanced[$index][in_hand_quantity]") }} <span class="text-danger">*</span>
                            {{ html()->text("kt_docs_repeater_advanced[$index][in_hand_quantity]")->class('form-control')->placeholder('In Hand Quantity') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.in_hand_quantity") }}</small>
                        </div>


                        <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.location") ? ' has-error' : '' }}">
                            {{ html()->label('Location', "kt_docs_repeater_advanced[$index][location]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][location]")->class('form-control focus')->placeholder('Location') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.location") }}</small>
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
<div class="col-md-6 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}



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
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
</script>
