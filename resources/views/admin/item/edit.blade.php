
{{ html()->form('Put', route('admin.' . request()->segment(2) . '.update', $item->id))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}
<div class="card">
    <div class="card-body">
        <div class="row">
            @can('mfg_mkdt_item')
            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('mfg_by') ? ' has-error' : '' }}">
                    {{ html()->label('Choose MFG By', 'mfg_by') }}  <span class="text-danger">*</span>
                    {{ html()->select('mfg_by', App\Models\Client::where('id', $item->mfg_by)->pluck('company_name', 'id'), $item->mfg_by)->id('mfg_by')->placeholder('Choose MFG By')->class('mfg_by form-control') }}
                    <small class="text-danger">{{ $errors->first('mfg_by') }}</small>
                </div>
            </div>
            

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('mkdt_by') ? ' has-error' : '' }}">
                    {{ html()->label('Choose MKDT By', 'mkdt_by') }}  <span class="text-danger">*</span>
                    {{ html()->select('mkdt_by', App\Models\Client::where('id', $item->mkdt_by)->pluck('company_name', 'id'), $item->mkdt_by)->id('mkdt_by')->placeholder('Choose MKDT By')->class('mkdt_by form-control') }}
                    <small class="text-danger">{{ $errors->first('mkdt_by') }}</small>
                </div>
            </div>
            @else

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                    {{ html()->label('Choose Client', 'client') }}  <span class="text-danger">*</span>
                    {{ html()->select('client', App\Models\Client::where('id', $item->mkdt_by)->pluck('company_name', 'id'), $item->mkdt_by)->id('client')->placeholder('Choose Client')->class('client form-control') }}
                    <small class="text-danger">{{ $errors->first('client') }}</small>
                </div>
            </div>

            @endcan


            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has("item_name") ? ' has-error' : '' }}">
                    {{ html()->label('Item Name', "item_name") }}  <span class="text-danger">*</span>
                    {{ html()->text("item_name", $item->item_name)->class('form-control')->placeholder('Item Name') }}
                    <small class="text-danger">{{ $errors->first("item_name") }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has("item_size") ? ' has-error' : '' }}">
                    {{ html()->label('Item Size', "item_size") }}  <span class="text-danger">*</span>
                    {{ html()->text("item_size", $item->item_size)->class('form-control')->placeholder('Item Size') }}
                    <small class="text-danger">{{ $errors->first("item_size") }}</small>
                </div> 
            </div>



            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has("colour") ? ' has-error' : '' }}">
                    {{ html()->label('Colour', "colour") }}  <span class="text-danger">*</span>
                    {{ html()->text("colour", $item->colour)->class('form-control')->placeholder('Colour') }}
                    <small class="text-danger">{{ $errors->first("colour") }}</small>
                </div> 
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has("product_type") ? ' has-error' : '' }}">
                    {{ html()->label('Product Type', "product_type") }}  <span class="text-danger">*</span>
                    {{ html()->select("product_type", App\Models\ProductType::orderBy('name', 'asc')->pluck('name', 'id'), $item->product_type_id)->id('ItemTypeChange')->class('js-choice form-control')->placeholder('Product Type') }}
                    <small class="text-danger">{{ $errors->first("product_type") }}</small>
                </div> 
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has("gsm") ? ' has-error' : '' }}">
                    {{ html()->label('GSM', "gsm") }}  <span class="text-danger">*</span>
                    {{ html()->text("gsm", $item->gsm)->class('form-control')->placeholder('GSM') }}
                    <small class="text-danger">{{ $errors->first("gsm") }}</small>
                </div> 
            </div>



            <div class="col-md-3 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('coating') ? ' has-error' : '' }}">
                    {{ html()->label('Coating', 'coating') }} <span class="text-danger">*</span>
                    {{ html()->select('coating', App\Models\CoatingType::orderBy('name', 'asc')->pluck('name', 'id'), $item->coating_type_id)
                    ->class('form-control js-choice')
                    ->placeholder('Select Coating') }}
                    <small class="text-danger">{{ $errors->first('coating') }}</small>
                </div>
            </div>


            <div class="col-md-3 col-sm-12">
                <div class="form-group{{ $errors->has('other_coating') ? ' has-error' : '' }}">
                    {{ html()->label('Other Coating', 'other_coating') }} <span class="text-danger">*</span>
                    {{ html()->select('other_coating', App\Models\OtherCoatingType::orderBy('name', 'asc')->pluck('name', 'id'), $item->other_coating_type_id)->class('form-control js-choice')->placeholder('Select Other Coating') }}
                        <small class="text-danger">{{ $errors->first('other_coating') }}</small>
                    </div>
                </div>



             

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group{{ $errors->has('embossing') ? ' has-error' : '' }}">
                            {{ html()->label('Embossing', 'embossing') }} <span class="text-danger">*</span>
                            {{ html()->select('embossing', ['Yes' => 'Yes', 'No' => 'No'], $item->embossing)
                            ->class('form-control js-choice')
                            ->placeholder('Select Embossing') }}
                            <small class="text-danger">{{ $errors->first('embossing') }}</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group{{ $errors->has('leafing') ? ' has-error' : '' }}">
                            {{ html()->label('Leafing', 'leafing') }} <span class="text-danger">*</span>
                            {{ html()->select('leafing', ['Yes' => 'Yes', 'No' => 'No'], $item->leafing)
                            ->class('form-control js-choice')
                            ->placeholder('Select Leafing') }}
                            <small class="text-danger">{{ $errors->first('leafing') }}</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group{{ $errors->has('back_print') ? ' has-error' : '' }}">
                            {{ html()->label('Back Print', 'back_print') }} <span class="text-danger">*</span>
                            {{ html()->select('back_print', ['Yes' => 'Yes', 'No' => 'No'], $item->back_print)
                            ->class('form-control js-choice')
                            ->placeholder('Select Back Print') }}
                            <small class="text-danger">{{ $errors->first('back_print') }}</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="m-0 form-group{{ $errors->has('braille') ? ' has-error' : '' }}">
                            {{ html()->label('Brail', 'braille') }} <span class="text-danger">*</span>
                            {{ html()->select('braille', ['Yes' => 'Yes', 'No' => 'No'], $item->braille)
                            ->class('form-control js-choice')
                            ->placeholder('Select Brail') }}
                            <small class="text-danger">{{ $errors->first('braille') }}</small>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <div class="m-0 form-group{{ $errors->has('artwork_code') ? ' has-error' : '' }}">
                            {{ html()->label('Artwork Code', 'artwork_code') }} 
                            {{ html()->text('artwork_code', $item->artwork_code)
                            ->class('form-control')
                            ->placeholder('Enter Artwork Code') }}
                            <small class="text-danger">{{ $errors->first('artwork_code') }}</small>
                        </div>
                    </div>


                    <div class="col-md-3 col-sm-12">
                        <div class="m-0 form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            {{ html()->label('Status', 'status') }} <span class="text-danger">*</span>
                            {{ html()->select('status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [15,14])->pluck('name', 'id'), $item->status_id)->class('form-control js-choice')->placeholder('Choose Status') }}
                            <small class="text-danger">{{ $errors->first('status') }}</small>
                        </div>
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


     $('#kt_docs_repeater_advanced').repeater({
        show: function () {
            var $row = $(this);
  

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
