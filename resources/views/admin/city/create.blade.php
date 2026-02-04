{{ html()->form('POST', route('admin.city.store'))->attribute('enctype', 'multipart/form-data')->id('storeForm')->open() }}
<div class="row">
    <div class="col-md-5 col-sm-12">
        <div class="m-0 form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            {{ html()->label('State', 'state') }}
            {{ html()->select('state', [])->class('form-control state')->placeholder('Choose State') }}
            <small class="text-danger">{{ $errors->first('State') }}</small>
        </div>
    </div>
    <div class="col-md-5 col-sm-12">
        <div class="m-0 form-group{{ $errors->has('city_name') ? ' has-error' : '' }}">
            {{ html()->label('City Name', 'city_name') }}
            {{ html()->text('city_name')->class('form-control')->placeholder('City Name') }}
            <small class="text-danger">{{ $errors->first('city_name') }}</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        {{ html()->button('Save Details')->type('button')->class('btn-label-adjust btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>

{{ html()->form()->close() }}