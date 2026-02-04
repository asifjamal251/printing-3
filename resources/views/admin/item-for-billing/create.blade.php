@php
    $billingClients = \App\Models\Client::whereIn('id', [$mkdt_by, $mfg_by])
        ->orderBy('company_name', 'asc')
        ->pluck('company_name', 'id');
@endphp
{{ html()->form('POST', route('admin.billing.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('bill_from') ? ' has-error' : '' }}">
                    {{ html()->label('Bill From', 'bill_from') }}
                    {{ html()->select('bill_from', App\Models\Firm::orderBy('company_name', 'asc')->pluck('company_name', 'id'))->class('form-control js-choice')->placeholder('Bill From') }}
                    <small class="text-danger">{{ $errors->first('bill_from') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('bill_to') ? ' has-error' : '' }}">
                    {{ html()->label('Bill To', 'bill_to') }}
                    {{ html()->select('bill_to', $billingClients)->class('form-control js-choice')->placeholder('Bill To') }}
                    <small class="text-danger">{{ $errors->first('bill_to') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('ship_to') ? ' has-error' : '' }}">
                    {{ html()->label('Ship To', 'ship_to') }}
                    {{ html()->select('ship_to', $billingClients)->class('form-control js-choice')->placeholder('Ship To') }}
                    <small class="text-danger">{{ $errors->first('ship_to') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('bill_date') ? ' has-error' : '' }}">
                    {{ html()->label('Bill Date', 'bill_date') }}
                    {{ html()->text('bill_date')->class('form-control dateSelector')->placeholder('Bill Date') }}
                    <small class="text-danger">{{ $errors->first('bill_date') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('vehicle_no') ? ' has-error' : '' }}">
                    {{ html()->label('Vehicle No.', 'vehicle_no') }}
                    {{ html()->text('vehicle_no')->class('form-control')->placeholder('Vehicle No.') }}
                    <small class="text-danger">{{ $errors->first('vehicle_no') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('transporter_name') ? ' has-error' : '' }}">
                    {{ html()->label('Transporter Name', 'transporter_name') }}
                    {{ html()->text('transporter_name')->class('form-control')->placeholder('Transporter Name') }}
                    <small class="text-danger">{{ $errors->first('transporter_name') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="m-0 form-group">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>
{{ html()->form()->close() }}


