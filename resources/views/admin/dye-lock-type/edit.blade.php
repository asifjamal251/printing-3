{!! html()->form('PUT', route('admin.' . request()->segment(2) . '.update', $lock_type->id))->attribute('files', true)->open() !!}

<div class="row">

    <div class="col-md-12 col-sm-12">
        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            {{ html()->label('Type', 'type') }}
            {{ html()->text('type', $lock_type->type)->class('form-control slug_from')->placeholder('Type') }}
            <small class="text-danger">{{ $errors->first('type') }}</small>
        </div>
    </div>


    <div class="col-md-12 col-sm-12">
        <div class="mt-4 form-group">
            {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>

</div>
{!! html()->form()->close() !!}


