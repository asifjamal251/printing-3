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
        <div class="d-flex gap-3">



            <div class="w-75">
                <div class="m-0 form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                    {{ html()->label('Choose Client', 'client') }} <span class="text-danger">*</span>
                    {{ html()->select('client', [])->id('client')->placeholder('Choose Client')->class('client form-control client') }}
                    <small class="text-danger">{{ $errors->first('client') }}</small>
                </div>
            </div>

            <div class="w-75">
                <div class="m-0 form-group{{ $errors->has('po_number') ? ' has-error' : '' }}">
                    {{ html()->label('PO Number', 'po_number') }} <span class="text-danger">*</span>
                    {{ html()->text('po_number')->class('form-control')->placeholder('PO Number') }}
                    <small class="text-danger">{{ $errors->first('po_number') }}</small>
                </div>
            </div>


            <div class="w-75">
                <div class="m-0 form-group{{ $errors->has('po_date') ? ' has-error' : '' }}">
                    {{ html()->label('PO Date', 'po_date') }} <span class="text-danger">*</span>
                    {{ html()->text('po_date')->class('form-control dateSelector')->placeholder('PO Date') }}
                    <small class="text-danger">{{ $errors->first('po_date') }}</small>
                </div>
            </div>


            <div class="w-100">
                <div class="m-0 form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
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
            @foreach(old('kt_docs_repeater_advanced', [[]]) as $index => $item)

            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape mb-3">

                            <div class="w-75 m-0 form-group{{$errors->has('kt_docs_repeater_advanced.'.$loop->index.'.carton') ? ' has-error' : '' }}">
                                <label>Choose Item <span class="text-danger">*</span> </label>  <a  model-size="modal-xl" data-title="Create New Item" data-url="{{route('admin.item.create')}}" href="javascript:void(0);"  class="product-rate create float-end text-end text-decoration-underline ps-2" style="width:150px;">New Item</a>
                                <select name="item" class="form-control form-select getItem" data-kt-repeater="select2" data-placeholder="Select an option">

                                    @if(old('kt_docs_repeater_advanced.'.$loop->index.'.item'))
                                    <option selected="selected" value="{{$item['item_id']}}">{{App\Models\Item::where('id', $item['item'])->value('item_name')}}</option>
                                    @else
                                    <option value="">Choose Item</option>
                                    @endif
                                </select>
                                <small class="text-danger validate kt_docs_repeater_advanced.{{ $loop->index }}.item">
                                    {{ $errors->first('kt_docs_repeater_advanced.'.$loop->index.'.item') }}
                                </small>
                            </div>


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.quantity") ? ' has-error' : '' }}">
                                {{ html()->label('Quantity', "kt_docs_repeater_advanced[$index][quantity]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][quantity]")->class('form-control')->placeholder('Quantity') }}

                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity") }}</small>
                            </div>




                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_size") ? ' has-error' : '' }}">
                                {{ html()->label('Item Size', "kt_docs_repeater_advanced[$index][item_size]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][item_size]")->class('form-control')->placeholder('Item Size')->attribute('readonly') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.item_size") }}</small>
                            </div> 


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.colour") ? ' has-error' : '' }}">
                                {{ html()->label('Colour', "kt_docs_repeater_advanced[$index][colour]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][colour]")->class('form-control')->placeholder('Colour')->attribute('readonly') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.colour") }}</small>
                            </div>

                        </div>
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">

                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.coating") ? ' has-error' : '' }}">
                                {{ html()->label('Coating', "kt_docs_repeater_advanced[$index][coating]") }} <span class="text-danger">*</span>
                                {{ html()->select("kt_docs_repeater_advanced[$index][coating]", ['Yes' => 'Yes', 'No' => 'No'])
                                ->class('form-control js-choice')
                                ->placeholder('Select Coating') }}

                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.coating") }}</small>
                            </div>


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.artwork_code") ? ' has-error' : '' }}">
                                {{ html()->label('Artwork Code', "kt_docs_repeater_advanced[$index][artwork_code]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][artwork_code]")->class('form-control')->placeholder('Artwork Code')->attribute('readonly') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.artwork_code") }}</small>
                            </div>


                            <div class="w-40 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.rate") ? ' has-error' : '' }}">
                                {{ html()->label('Rate', "kt_docs_repeater_advanced[$index][rate]") }} <span class="text-danger">*</span>
                                <div class="input-group"> 
                                    <span class="input-group-text">₹</span>
                                    {{ html()->text("kt_docs_repeater_advanced[$index][rate]")->class('form-control numOnly')->placeholder('Rate') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.rate") }}</small>
                            </div>

                            <div class="w-40 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.gst_percentage") ? ' has-error' : '' }}">
                                {{ html()->label('GST %', "kt_docs_repeater_advanced[$index][gst_percentage]") }} <span class="text-danger">*</span>
                                <div class="input-group"> 
                                    {{ html()->text("kt_docs_repeater_advanced[$index][gst_percentage]")->class('form-control numOnly')->placeholder('GST Percentage') }}
                                     <span class="input-group-text">%</span>
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.gst_percentage") }}</small>
                            </div>


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.remarks") ? ' has-error' : '' }}">
                                {{ html()->label('Remarks', "kt_docs_repeater_advanced[$index][remarks]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][remarks]")->class('form-control')->placeholder('Remarks') }}
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
<div class="mb-0 form-group" style="margin-top:22px; min-width:110px;">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>




{{ html()->form()->close() }}
@endsection


@push('scripts')

<script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">

   let rowCounter = 0;

   $('#kt_docs_repeater_advanced').repeater({
    show: function () {
        var client = $('#client').val();
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;
        godownId = $('#godown').val();

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');

        $row.slideDown('fast', function () {
            $row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
        getItem('.getItem', false, 'Choose Item');
         let baseUrl = "{{ route('admin.item.create') }}";
        $('.create').attr('data-url', baseUrl + '?client=' + client + '&type=' + type);
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});




   $(document).ready(function () {

    if ($('#client').length > 0) {
        getClient('#client', false, 'Choose Client');
    }


    $('body').on('change', '#client, #type', function () {
        var client = $('#client').val();
        var type = $('#type').val();
        
        getItem('.getItem', false, 'Choose Item');

        let baseUrl = "{{ route('admin.item.create') }}";
        $('.create').attr('data-url', baseUrl + '?client=' + client + '&type=' + type);
    });


    
});
</script>
@endpush
