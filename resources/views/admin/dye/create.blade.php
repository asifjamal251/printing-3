
{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">


                



                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        {{ html()->label('Type', 'type') }}  <span class="text-danger">*</span>
                        {{ html()->select('type', ['Mix' => 'Mix', 'Separate' => 'Separate'])
                        ->class('form-control js-choice')
                        ->placeholder('Select Type') }}
                        <small class="text-danger">{{ $errors->first('type') }}</small>
                    </div>
                </div>

                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has("dye_number") ? ' has-error' : '' }}">
                        {{ html()->label('Dye Number', "dye_number") }}
                        {{ html()->text("dye_number")->class('form-control')->placeholder('Dye Number') }}
                        <small class="text-danger">{{ $errors->first("dye_number") }}</small>
                    </div> 
                </div>

                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has("sheet_size") ? ' has-error' : '' }}">
                        {{ html()->label('Sheet Size', "sheet_size") }}  <span class="text-danger">*</span>
                        {{ html()->text("sheet_size")->class('form-control')->placeholder('Sheet Size') }}
                        <small class="text-danger">{{ $errors->first("sheet_size") }}</small>
                    </div> 
                </div>

                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('dye_type') ? ' has-error' : '' }}">
                        {{ html()->label('Dye Type', 'dye_type') }}  <span class="text-danger">*</span>
                        {{ html()->select('dye_type', ['Manual' => 'Manual', 'Automatic' => 'Automatic'])
                        ->class('form-control js-choice')
                        ->placeholder('Select Dye Type') }}
                        <small class="text-danger">{{ $errors->first('dye_type') }}</small>
                    </div>
                </div>
                

            </div>
        </div>
    </div>


    <span class="w-100 d-inline-block" style="position: relative;">
        <small class="fs-10 text-danger" style="position: absolute;left: 0;right: 0;display: inline-table;top: 0; bottom: 0;margin: auto;background: #f3f3f9;">
            Die Details
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


                               <div class="w-100 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.dye_lock_type") ? ' has-error' : '' }}">
                                {{ html()->label('Dye Lock Type', "kt_docs_repeater_advanced[$index][dye_lock_type]") }} <span class="text-danger">*</span>
                                {{ html()->select("kt_docs_repeater_advanced[$index][dye_lock_type]", App\Models\DyeLockType::orderBy('type', 'asc')->pluck('type', 'id'))->class('form-control js-choice')->placeholder('Dye Lock Type') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.dye_lock_type") }}</small>
                            </div>




                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.length") ? ' has-error' : '' }}">
                                {{ html()->label('Length', "kt_docs_repeater_advanced[$index][length]") }} <span class="text-danger">*</span>
                                {{ html()->text("kt_docs_repeater_advanced[$index][length]")->class('form-control')->placeholder('Length') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.length") }}</small>
                            </div> 





                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.width") ? ' has-error' : '' }}">
                                {{ html()->label('Width', "kt_docs_repeater_advanced[$index][width]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][width]")->class('form-control')->placeholder('Width') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.width") }}</small>
                            </div>



                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.height") ? ' has-error' : '' }}">
                                {{ html()->label('Height', "kt_docs_repeater_advanced[$index][height]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][height]")->class('form-control')->placeholder('Height') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.height") }}</small>
                            </div>

                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.tuckin_flap") ? ' has-error' : '' }}">
                                {{ html()->label('Tuckin Flap', "kt_docs_repeater_advanced[$index][tuckin_flap]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][tuckin_flap]")->class('form-control')->placeholder('Tuckin Flap') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.tuckin_flap") }}</small>
                            </div>


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.pasting_flap") ? ' has-error' : '' }}">
                                {{ html()->label('Pasting Flap', "kt_docs_repeater_advanced[$index][pasting_flap]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][pasting_flap]")->class('form-control')->placeholder('Pasting Flap') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.pasting_flap") }}</small>
                            </div>


                            <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.ups") ? ' has-error' : '' }}">
                                {{ html()->label('UPS', "kt_docs_repeater_advanced[$index][ups]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][ups]")->class('form-control')->placeholder('UPS') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.ups") }}</small>
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


    <div class="col-md-4 col-sm-12">
        <div class="mt-4 form-group">
            {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>

    {{ html()->form()->close() }}



    <script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
    <script type="text/javascript">

     var rowCounter = 0;

     $('#kt_docs_repeater_advanced').repeater({
        show: function () {
            var $row = $(this);
            $row.addClass('row-' + rowCounter);
            rowCounter++;

            $row.find('small.text-danger').html('');
            $row.find('.form-group').removeClass('has-error');


            $row.find(".js-choice").each(function() {
                new Choices($(this)[0], { allowHTML: true });
            });
            

            $row.slideDown('fast', function () {
                $row.find('input[name*="[packet_weight]"]').first().focus().addClass('ojkk');
            });
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
</script>
