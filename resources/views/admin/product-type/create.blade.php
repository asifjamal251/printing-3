{{ html()->form('POST', route('admin.product-type.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('product_type') ? ' has-error' : '' }}">
                    {{ html()->label('Product Type', 'product_type') }}
                    {{ html()->text('product_type')->class('form-control')->placeholder('Product Type') }}
                    <small class="text-danger">{{ $errors->first('product_type') }}</small>
                </div>

                <div class="m-0 form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    {{ html()->label('Type', 'type') }}
                    {{ html()->select('type', ['Paper' => 'Paper', 'Chemical' => 'Chemical', 'Other' => 'Other'])->class('form-control js-choice')->placeholder('Choose Type') }}
                    <small class="text-danger">{{ $errors->first('type') }}</small>
                </div>
            </div>






        </div>
    </div>
</div>


{{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}

{{ html()->form()->close() }}
