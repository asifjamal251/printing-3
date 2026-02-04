{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('category_name') ? ' has-error' : '' }}">
                    {{ html()->label('Category Name', 'category_name') }}
                    {{ html()->text('category_name')->class('form-control')->placeholder('Category Name') }}
                    <small class="text-danger">{{ $errors->first('category_name') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}

{{ html()->form()->close() }}
