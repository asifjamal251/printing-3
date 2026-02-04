{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    {{ html()->label('Company Name', 'company_name') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->text('company_name')->class('form-control')->placeholder('Company Name') }}
                    <small class="text-danger">{{ $errors->first('company_name') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ html()->label('Email Address', 'email') }}<span class="star-mandatory text-danger"> *</span>
                    {{ html()->email('email')->class('form-control')->placeholder('eg: foo@bar.com') }}
                    <small class="text-danger">{{ $errors->first('email') }}</small>
                </div>
            </div>



            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
                    {{ html()->label('Contact No.', 'contact_no') }}
                    {{ html()->text('contact_no')->class('form-control')->id('mobile_no')->placeholder('Contact No.') }}
                    <small class="text-danger">{{ $errors->first('contact_no') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
                    {{ html()->label('GST', 'gst') }}
                    {{ html()->text('gst')->class('form-control')->placeholder('GST') }}
                    <small class="text-danger">{{ $errors->first('gst') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                    {{ html()->label('State', 'state') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->select('state', [])->id('state')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('state') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                    {{ html()->label('City', 'city') }}<span class="star-mandatory text-danger"> *</span>
                    {{ html()->select('city', [])->class('form-control')->id('city')}}
                    <small class="text-danger">{{ $errors->first('city') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status')->for('status') }}
                    {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 14)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
                    {{ html()->label('Pincode', 'pincode') }}
                    {{ html()->text('pincode')->class('form-control')->placeholder('Pincode') }}
                    <small class="text-danger">{{ $errors->first('pincode') }}</small>
                </div>
            </div>



            <div class="col-md-6 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                    {{ html()->label('Address', 'address') }}
                    {{ html()->textarea('address')->class('form-control')->placeholder('Address')->rows(2) }}
                    <small class="text-danger">{{ $errors->first('address') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('cc_emails') ? ' has-error' : '' }}">
                    {{ html()->label('CC Emails', 'cc_emails') }}
                    {{ html()->textarea('cc_emails')->class('form-control')->placeholder('CC Emails')->rows(2) }}
                    <small class="text-danger">{{ $errors->first('cc_emails') }}</small>
                </div>
            </div>

        </div>


    </div>
</div>

<div class="col-md-12 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}
