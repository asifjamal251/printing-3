{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $store->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }}
                    {{ html()->text('name', $store->name)->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
{{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}

{!! html()->form()->close() !!}
