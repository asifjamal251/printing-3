{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->text('name')->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
               <div class="form-group{{ $errors->has('module') ? ' has-error' : '' }}">
                   {{ html()->label('Module', 'module') }} <span class="star-mandatory text-danger"> *</span>
                   {{ html()->select('module', App\Models\Module::orderBy('name', 'asc')->pluck('name', 'id'))->class('js-choice form-control')->placeholder('Choose Module') }}
                   <small class="text-danger">{{ $errors->first('module') }}</small>
               </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                    {{ html()->label('Login')->for('login') }} <span class="star-mandatory text-danger"> *</span>
                   {{ html()->select('login', \App\Models\Admin::whereNotIn('role_id', [1, 2])->selectRaw("id, CONCAT(name, ' (', email, ')') as label")->pluck('label', 'id'))->class('form-control js-choice')->id('login')->placeholder('Login') }}
                    <small class="text-danger">{{ $errors->first('login') }}</small>
                </div>
            </div>



            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status')->for('status') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 14)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
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
