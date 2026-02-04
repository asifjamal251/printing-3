{!! html()->form('PUT', route('admin.state.update', $state->id))->id('updateForm')->attribute('files', true)->open() !!}
<div class="row">
    <div class="col-md-5 col-sm-12">
       <div class="form-group{{ $errors->has('state_name') ? ' has-error' : '' }}">
           {{ html()->label('State Name', 'state_name') }}
           {{ html()->text('state_name', $state->name)->class('form-control')->placeholder('State Name') }}
           <small class="text-danger">{{ $errors->first('state_name') }}</small>
       </div>
    </div>
    <div class="col-md-5 col-sm-12">
        <div class="form-group{{ $errors->has('short_name') ? ' has-error' : '' }}">
            {{ html()->label('Short Name', 'short_name') }}
            {{ html()->text('short_name', $state->short_name)->class('form-control')->placeholder('Short Name') }}
            <small class="text-danger">{{ $errors->first('short_name') }}</small>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        {{ html()->button('Save Details')->type('button')->class('btn-label-adjust btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}