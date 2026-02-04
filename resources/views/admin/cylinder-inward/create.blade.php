@php
$client = request()->get('client');
@endphp

{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

                <div class="col-md-4 col-sm-12">
                    <div class="form-group{{ $errors->has("bill_no") ? ' has-error' : '' }}">
                        {{ html()->label('Bill No.', "bill_no") }}
                        {{ html()->text("bill_no")->class('form-control')->placeholder('Bill No.') }}
                        <small class="text-danger">{{ $errors->first("bill_no") }}</small>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="form-group{{ $errors->has("bill_date") ? ' has-error' : '' }}">
                        {{ html()->label('Bill Date', "bill_date") }}
                        {{ html()->text("bill_date")->class('form-control dateSelector')->placeholder('Bill Date') }}
                        <small class="text-danger">{{ $errors->first("bill_date") }}</small>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('vendor') ? ' has-error' : '' }}">
                        {{ html()->label('Choose Vendor', 'vendor') }}
                        {{ html()->select('vendor', [])->id('vendor')->placeholder('Choose Vendor')->class('vendor form-control') }}
                        <small class="text-danger">{{ $errors->first('vendor') }}</small>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                        {{ html()->label('Choose Client', 'client') }}
                        {{ html()->select('client', [])->id('client')->placeholder('Choose Client')->class('client form-control') }}
                        <small class="text-danger">{{ $errors->first('client') }}</small>
                    </div>
                </div>


                <div class="col-md-4 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('item') ? ' has-error' : '' }}">
                        {{ html()->label('Choose Item', 'item') }}
                        {{ html()->select('item', [])->id('item')->placeholder('Choose Item')->class('getItem form-control') }}
                        <small class="text-danger">{{ $errors->first('item') }}</small>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
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
                @foreach(old('kt_docs_repeater_advanced', [[]]) as $index => $item)
                <div data-repeater-item class="repeater-row row-{{$index}} default-bg mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">


                            <div class="w-75 m-0 form-group{{$errors->has('kt_docs_repeater_advanced.'.$loop->index.'.cylinder') ? ' has-error' : '' }}">
                                <label>Choose Cylinder </label> 
                                <select name="cylinder" class="form-control form-select getCylinder" data-kt-repeater="select2" data-placeholder="Select an option">
                                    <option value="">Choose item</option>
                                </select>
                                <small class="text-danger validate kt_docs_repeater_advanced.{{ $loop->index }}.item">
                                    {{ $errors->first('kt_docs_repeater_advanced.'.$loop->index.'.item') }}
                                </small>
                            </div>



                            <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.type") ? ' has-error' : '' }}">
                                {{ html()->label('Type', "kt_docs_repeater_advanced[$index][type]") }} 
                                {{ html()->select("kt_docs_repeater_advanced[$index][type]", ['New' => 'New', 'Repaired' => 'Repaired'])->class('form-control js-choice')->placeholder('Type') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.type") }}</small>
                            </div>


                            <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.location") ? ' has-error' : '' }}">
                                {{ html()->label('Location', "kt_docs_repeater_advanced[$index][location]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][location]")->class('form-control')->placeholder('Location') }}
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

    <div class="col-md-4 col-sm-12">
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
             getCylinder('.getCylinder', true);
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
</script>

