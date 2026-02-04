
{{ html()->form('PUT', route('admin.' . request()->segment(2) . '.update', $product->id))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}
<div class="card">
    <div class="card-body">
        <div class="d-md-flex flex-wrape gap-3 default-bg">

            <div class="w-100 form-group{{ $errors->has('product_type') ? ' has-error' : '' }}">
                {{ html()->label('Product Type', 'product_type') }} <span class="text-danger">*</span>
                {{ html()->select('product_type', App\Models\ProductType::orderBy('name', 'asc')->pluck('name', 'id'), $product->product_type_id)->class('form-control js-choice')->placeholder('Product Type') }}
                <small class="text-danger">{{ $errors->first('product_type') }}</small>
            </div>

            <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {{ html()->label('Name', 'name') }} <span class="text-danger">*</span>
                {{ html()->text('name', $product->name)
                ->class('form-control')
                ->placeholder('Name')
                ->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>

            {{-- <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has('name_other') ? ' has-error' : '' }}">
                {{ html()->label('Name Other', 'name_other') }}
                {{ html()->text('name_other', $product->name_other)
                ->class('form-control')
                ->placeholder('Name Other')
                ->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('name_other') }}</small>
            </div> --}}

                <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has('stores') ? ' has-error' : '' }}">
                    {{ html()->label('Stores')->for('stores') }}
                    {{ html()->select('stores', App\Models\Store::orderBy('name', 'asc')->pluck('name', 'id'), $product->store_id)->class('form-control js-choice')->id('stores')->placeholder('Choose Stores') }}
                    <small class="text-danger">{{ $errors->first('stores') }}</small>
                </div>
          

            <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                {{ html()->label('Category', 'category') }} <span class="text-danger">*</span>
                {{ html()->select('category', App\Models\Category::orderBy('name', 'asc')->pluck('name', 'id'), $product->category_id)->class('form-control js-choice')->placeholder('Category') }}
                <small class="text-danger">{{ $errors->first('category') }}</small>
            </div>


        </div>

        <div class="d-md-flex flex-wrape gap-3 default-bg pt-0">



            <div class="w-md-75 mb-md-0 mb-sm-2 form-group{{ $errors->has('unit') ? ' has-error' : '' }}">
                {{ html()->label('Unit', 'Unit') }} <span class="text-danger">*</span>
                {{ html()->select('unit', App\Models\Unit::orderBy('name', 'asc')->pluck('name', 'id'), $product->unit_id)->class('form-control js-choice')->placeholder('Unit') }}
                <small class="text-danger">{{ $errors->first('unit') }}</small>
            </div>

            <div class="w-75 mb-md-0 mb-sm-2 form-group{{ $errors->has('hsn') ? ' has-error' : '' }}">
                {{ html()->label('HSN', 'hsn') }}
                {{ html()->text('hsn', $product->hsn)
                ->class('form-control')
                ->placeholder('HSN')
                ->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('hsn') }}</small>
            </div>


            <div class="w-50 m-0 form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
                {{ html()->label('GST', 'gst') }} <span class="text-danger">*</span>
                <div class="input-group">
                    {{ html()->text('gst', $product->gst)
                    ->class('form-control')
                    ->placeholder('GST')
                    ->attribute('autocomplete', 'off') }}
                    <span class="input-group-text unit">%</span>
                </div>
                <small class="text-danger">{{ $errors->first('gst') }}</small>
            </div>



            <div class="w-75 mb-md-0 mb-sm-2 form-group{{ $errors->has('gsm') ? ' has-error' : '' }}">
                {{ html()->label('GSM', 'gsm') }}
                {{ html()->text('gsm', $product->gsm)
                ->class('form-control')
                ->placeholder('GSM')
                ->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('gsm') }}</small>
            </div>


            <div class="w-75  mb-md-0 mb-sm-2 form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                {{ html()->label('Choose Logo', "file") }}
                <div class="media-area media-flex gap-1" file-name="file">

                   <a class="form-control text-secondary select-mediatype" href="javascript:void(0);" mediatype="single" onclick="loadMediaFiles($(this))">
                    Select Image
                </a>
                <div class="media-file-value d-none">
                    @if($product->mediaFile)
                    <input type="hidden" name="file[]" value="{{$product->media_id}}" class="fileid{{$product->media_id}}">
                    @endif
                </div>
                <div class="media-file" style="width:50px;">
                    @if($product->media_id)
                    <div class="file-container d-inline-block fileid{{$product->media_id}}">
                        <span data-id="{{$product->media_id}}" class="remove-file">✕</span>
                        <img class="w-100 d-block img-thumbnail" src="{{asset($product->mediaFile->file)}}" alt="{{$product->title}}">
                    </div>
                    @endif
                </div>

            </div>
            <small class="text-danger">{{ $errors->first('file') }}</small>
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
            @foreach(old('kt_docs_repeater_advanced', $product->attributes ?? [[]]) as $index => $item)
            <div class="card">
                <div class="card-body">
                    <div data-repeater-item class="repeater-row row-{{$index}} default-bg  mb-3">
                        <div class="custom-row gap-3 stock-error d-md-flex flex-wrape justify-content-between flex-sm-wrape">


                          {{ html()->hidden("kt_docs_repeater_advanced[$index][id]",  $item['id'])->class('form-control')->placeholder('Weight/Piece') }}


                          <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.weight_per_piece") ? ' has-error' : '' }}">
                            {{ html()->label('Weight/PC/PKT', "kt_docs_repeater_advanced[$index][weight_per_piece]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][weight_per_piece]",  $item['weight_per_piece'])->attribute('readonly')->class('form-control')->placeholder('Weight/PC/PKT') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.weight_per_piece") }}</small>
                        </div>




                        <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_per_packet") ? ' has-error' : '' }}">
                            {{ html()->label('Item/PC/PKT', "kt_docs_repeater_advanced[$index][item_per_packet]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][item_per_packet]",  $item['item_per_packet'])->attribute('readonly')->class('form-control')->placeholder('Item/PC/PKT') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.item_per_packet") }}</small>
                        </div> 


                        <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.opening_stock") ? ' has-error' : '' }}">
                            {{ html()->label('Opening Stock', "kt_docs_repeater_advanced[$index][opening_stock]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][opening_stock]", old("kt_docs_repeater_advanced.$index.opening_stock", $item['stock']['opening_stock'] ?? ''))->class('form-control')->placeholder('Opening Stock')->attribute('readonly', 'readonly') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.opening_stock") }}</small>
                        </div>

                        {{-- In Hand Quantity --}}
                        <div class="w-100 mb-md-0 mb-sm-2 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.in_hand_quantity") ? ' has-error' : '' }}">
                            {{ html()->label('In Hand Quantity', "kt_docs_repeater_advanced[$index][in_hand_quantity]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][in_hand_quantity]", old("kt_docs_repeater_advanced.$index.in_hand_quantity", $item['stock']['in_hand_quantity'] ?? ''))->class('form-control')->placeholder('In Hand Quantity') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.in_hand_quantity") }}</small>
                        </div>


                        <div class="w-100 mb-md-0 mb-sm-2 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.location") ? ' has-error' : '' }}">
                            {{ html()->label('Location', "kt_docs_repeater_advanced[$index][location]") }}
                            {{ html()->text("kt_docs_repeater_advanced[$index][location]",  $item['location'])->class('form-control focus')->placeholder('Location') }}
                            <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.location") }}</small>
                        </div>



                        <div class="m-0 form-group remove-item d-none" style="width:44px;">
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
        $row.find('.remove-item').removeClass('d-none');
        $row.find('.form-control').removeAttr('readonly');

        $row.slideDown('fast', function () {
            $row.find('input[name*="[weight_per_piece]"]').first().focus();
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});

</script>
