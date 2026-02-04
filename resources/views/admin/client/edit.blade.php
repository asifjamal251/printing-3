{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $client->id))->attribute('files', true)->open() !!}

<div class="row">

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
            {{ html()->label('Company Name', 'company_name') }} <span class="star-mandatory text-danger"> *</span>
            {{ html()->text('company_name', $client->company_name)->class('form-control')->placeholder('Company Name') }}
            <small class="text-danger">{{ $errors->first('company_name') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            {{ html()->label('Email Address', 'email') }}
            {{ html()->email('email', $client->email)->class('form-control')->placeholder('eg: foo@bar.com') }}
            <small class="text-danger">{{ $errors->first('email') }}</small>
        </div>
    </div>



    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
            {{ html()->label('Contact No.', 'contact_no') }}
            {{ html()->text('contact_no', $client->contact_no)->class('form-control')->id('mobile_no')->placeholder('Contact No.') }}
            <small class="text-danger">{{ $errors->first('contact_no') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
            {{ html()->label('GST', 'gst') }}
            {{ html()->text('gst', $client->gst)->class('form-control')->placeholder('GST') }}
            <small class="text-danger">{{ $errors->first('gst') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            {{ html()->label('State', 'state') }}
            {{ html()->select('state', App\Models\State::where('id', $client->state_id)->pluck('name', 'id'), $client->state_id)->id('state')->class('form-control') }}
            <small class="text-danger">{{ $errors->first('state') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            {{ html()->label('City', 'city') }}
            {{ html()->select('city', App\Models\City::where('id', $client->city_id)->pluck('name', 'id'), $client->city_id)->class('form-control')->placeholder('City') }}
            <small class="text-danger">{{ $errors->first('city') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            {{ html()->label('Status')->for('status') }} <span class="star-mandatory text-danger"> *</span>
            {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $client->status_id)->class('form-control js-choice')->id('status')->placeholder('Status') }}
            <small class="text-danger">{{ $errors->first('status') }}</small>
        </div>
    </div>


    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
            {{ html()->label('Pincode', 'pincode') }}
            {{ html()->text('pincode', $client->pincode)->class('form-control')->placeholder('Pincode') }}
            <small class="text-danger">{{ $errors->first('pincode') }}</small>
        </div>
    </div>

      

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            {{ html()->label('Address', 'address') }}
            {{ html()->textarea('address', $client->address)->class('form-control')->placeholder('Address')->rows(3) }}
            <small class="text-danger">{{ $errors->first('address') }}</small>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group{{ $errors->has('cc_emails') ? ' has-error' : '' }}">
            {{ html()->label('CC Emails', 'cc_emails') }}
            {{ html()->textarea('cc_emails', $client->cc_emails)->class('form-control')->placeholder('CC Emails')->rows(3) }}
            <small class="text-danger">{{ $errors->first('cc_emails') }}</small>
        </div>
    </div>


    <div class="col-md-12 col-sm-12">
        <div class="mt-4 form-group">
            {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>

</div>
{!! html()->form()->close() !!}


