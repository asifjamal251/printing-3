{!! html()->form('PUT', route('admin.'.request()->segment(2).'.operator.update', $jobCard->id))->attribute('files', true)->open() !!}

<div class="row">
    @foreach($stages as $stage)
    @php
    $module = App\Models\Module::where('model_name', $stage->name)->pluck('id');
    @endphp

    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group{{ $errors->has('stage') ? ' has-error' : '' }}">
                    {{ html()->label('Stage', 'stage') }}
                    {{ html()->select('stage[]', App\Models\JobCardStage::where('id', $stage->id)->get()->mapWithKeys(fn($s) => [ $s->id => preg_replace('/(?<!^)([A-Z])/', ' $1', $s->name) ]), $stage->id)->class('form-control') }}
                        <small class="text-danger">{{ $errors->first('stage') }}</small>
                </div>

                <div class="m-0 form-group{{ $errors->has('operator') ? ' has-error' : '' }}">
                    {{ html()->label('Operator', 'operator') }}
                    {{ html()->select('operator[]', App\Models\Operator::where('status_id', 14)->whereIn('module_id', $module)->pluck('name', 'id'), $stage->operator_id)->class('js-choice form-control')->placeholder('Choose Operator') }}
                    <small class="text-danger">{{ $errors->first('operator') }}</small>
                </div>


                </div>
            </div>
        </div>

        @endforeach

    </div>



    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
    {{ html()->form()->close() }}



