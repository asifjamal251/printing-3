@php
    $client = request()->get('client');
    $type = request()->get('type');
@endphp
{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}


<div class="row">

    <div class="col-md-4 col-sm-12">
        <div class="m-0 form-group{{ $errors->has('item_client') ? ' has-error' : '' }}">
            {{ html()->label('Choose Client', 'item_client') }}

            @if($client)
                {{ html()->select(
                    'item_client',
                    \App\Models\Client::where('id', $client)->pluck('company_name', 'id'),
                    $client
                )->id('item_client')->placeholder('Choose Client')->class('client form-control')->attribute('readonly') }}
            @else
                {{ html()->select('item_client', [])->id('item_client')->placeholder('Choose Client')->class('client form-control') }}
            @endif

            <small class="text-danger">{{ $errors->first('item_client') }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has('item_type') ? ' has-error' : '' }}">
            {{ html()->label('Item Type', 'item_type') }}
            {{ html()->select('item_type', ['Label' => 'Label', 'Cone' => 'Cone'], $type)
                ->class('form-control js-choice')
                ->placeholder('Select Item Type') }}
            <small class="text-danger">{{ $errors->first('Item Type') }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("item_name") ? ' has-error' : '' }}">
            {{ html()->label('Item Name', "item_name") }}
            {{ html()->text("item_name")->class('form-control')->placeholder('Item Name') }}
            <small class="text-danger">{{ $errors->first("item_name") }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("item_size") ? ' has-error' : '' }}">
            {{ html()->label('Item Size', "item_size") }}
            {{ html()->text("item_size")->class('form-control')->placeholder('Item Size') }}
            <small class="text-danger">{{ $errors->first("item_size") }}</small>
        </div> 
    </div>


    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("paper_type") ? ' has-error' : '' }}">
            {{ html()->label('Paper Type', "paper_type") }}
            {{ html()->select("paper_type", App\Models\PaperType::orderBy('type', 'asc')->where('status_id', 14)->pluck('type', 'id'))->class('js-choice form-control')->placeholder('Paper Type') }}
            <small class="text-danger">{{ $errors->first("paper_type") }}</small>
        </div> 
    </div>

    

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("gsm") ? ' has-error' : '' }}">
            {{ html()->label('GSM', "gsm") }}
            {{ html()->text("gsm")->class('form-control')->placeholder('GSM') }}
            <small class="text-danger">{{ $errors->first("gsm") }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("coating_type") ? ' has-error' : '' }}">
            {{ html()->label('Coating Type', "coating_type") }}
            {{ html()->select("coating_type", App\Models\CoatingType::orderBy('type', 'asc')->where('status_id', 14)->pluck('type', 'id'))->class('form-control js-choice')->placeholder('Coating Type') }}
            <small class="text-danger">{{ $errors->first("coating_type") }}</small>
        </div> 
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has('leafing') ? ' has-error' : '' }}">
            {{ html()->label('Leafing', 'leafing') }}
            {{ html()->select('leafing', ['Yes' => 'Yes', 'No' => 'No'])
                ->class('form-control js-choice')
                ->placeholder('Select Leafing Option') }}
            <small class="text-danger">{{ $errors->first('leafing') }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("artwork_code") ? ' has-error' : '' }}">
            {{ html()->label('Artwork Code', "artwork_code") }}
            {{ html()->text("artwork_code")->class('form-control')->placeholder('Artwork Code') }}
            <small class="text-danger">{{ $errors->first("artwork_code") }}</small>
        </div> 
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has("color") ? ' has-error' : '' }}">
            {{ html()->label('Color', "color") }}
            {{ html()->text("color")->class('form-control')->placeholder('Color') }}
            <small class="text-danger">{{ $errors->first("color") }}</small>
        </div> 
    </div>


     <div class="col-md-4 col-sm-12">
        <div class="form-group{{ $errors->has('dye') ? ' has-error' : '' }}">
            {{ html()->label('Dye', 'dye') }}
            {{ html()->select('dye', ['Offline' => 'Offline', 'Online' => 'Online'])
                ->class('form-control js-choice')
                ->placeholder('Select Dye Option') }}
            <small class="text-danger">{{ $errors->first('dye') }}</small>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="mt-4 form-group">
            {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>
</div>
{{ html()->form()->close() }}
