


        
{{ html()->form('POST', route('admin.' . request()->segment(2) . '.import.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <div class="w-75 m-0 form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                        {{ html()->label('File', 'file') }}
                        {{ html()->file('file')->class('form-control')->id('file')}}
                        <small class="text-danger">{{ $errors->first('file') }}</small>
                        <a href="{{asset('sample/clients.xlsx')}}">Download Sample</a> 
                    </div>

                    <div class="w-25 mb-0 form-group" style="margin-top:22px;">
                        {{ html()->button('Import')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
                    </div>

                </div>
            </div>
        </div>
    </div>    
</div>

{{ html()->form()->close() }}

