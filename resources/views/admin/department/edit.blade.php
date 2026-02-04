{!! html()->form('PUT', route('admin.' . request()->segment(2) . '.update', $department->id))->attribute('files', true)->open() !!}

<div class="row">

    <div class="col-md-12 col-sm-12">
        <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
            {{ html()->label('Department', 'department') }}
            {{ html()->text('department', $department->department)->class('form-control slug_from')->placeholder('Department') }}
            <small class="text-danger">{{ $errors->first('department') }}</small>
        </div>
    </div>


    <div class="col-md-12 col-sm-12">
        <div class="mt-4 form-group">
            {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
        </div>
    </div>

</div>
{!! html()->form()->close() !!}


