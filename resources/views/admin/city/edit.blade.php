{!! html()->form('PUT', route('admin.city.update', $city->id))->id('updateForm')->attribute('files', true)->open() !!}
    
    <div class="row">
        <div class="col-md-5 col-sm-12">
            <div class="m-0 form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                {{ html()->label('State', 'state') }}
                {{ html()->select('state', App\Models\State::where('id', $city->state_id)->pluck('name', 'id'), $city->state_id)->class('form-control state')->placeholder('Choose State') }}
                <small class="text-danger">{{ $errors->first('State') }}</small>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">
            <div class="m-0 form-group{{ $errors->has('city_name') ? ' has-error' : '' }}">
                {{ html()->label('City Name', 'city_name') }}
                {{ html()->text('city_name', $city->name)->class('form-control')->placeholder('City Name') }}
                <small class="text-danger">{{ $errors->first('city_name') }}</small>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            {{ html()->button('Save Details')->type('button')->class('btn-label-adjust btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>

{{ html()->form()->close() }}