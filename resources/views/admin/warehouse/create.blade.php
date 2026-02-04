
{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store.job-card'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">


            @if($dye)
                <div class="col-md-6 col-sm-12">
                    <div class="form-group{{ $errors->has("dye") ? ' has-error' : '' }}">
                        {{ html()->label('Die', "dye") }}
                        {{ html()->select("dye", App\Models\Dye::where('id', $dye->id)->get()->pluck('dye_info', 'id'), $dye->id)->class('form-control dye')->placeholder('Die') }}
                        <small class="text-danger">{{ $errors->first("dye") }}</small>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group{{ $errors->has("sheet_size") ? ' has-error' : '' }}">
                        {{ html()->label('Sheet Size', "sheet_size") }}
                        {{ html()->text("sheet_size", $dye->sheet_size)->class('form-control')->placeholder('Sheet Size') }}
                        <small class="text-danger">{{ $errors->first("sheet_size") }}</small>
                    </div> 
                </div>

            @else
                <div class="col-md-6 col-sm-12">
                    <div class="form-group{{ $errors->has("dye") ? ' has-error' : '' }}">
                        {{ html()->label('Die', "dye") }}
                        {{ html()->select("dye", [])->class('form-control dye')->placeholder('Die') }}
                        <small class="text-danger">{{ $errors->first("dye") }}</small>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group{{ $errors->has("sheet_size") ? ' has-error' : '' }}">
                        {{ html()->label('Sheet Size', "sheet_size") }}
                        {{ html()->text("sheet_size")->class('form-control')->placeholder('Sheet Size') }}
                        <small class="text-danger">{{ $errors->first("sheet_size") }}</small>
                    </div> 
                </div>

            @endif

            

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has("set_number") ? ' has-error' : '' }}">
                    {{ html()->label('Set Number', "set_number") }}
                    {{ html()->text("set_number")->class('form-control')->placeholder('Set Number') }}
                    <small class="text-danger">{{ $errors->first("set_number") }}</small>
                </div> 
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="media-area" file-name="file" style="max-width:80%;float:left;margin-top:24px;">
                    <div class="media-file-value file-arrange"></div>
                    <div class="media-file file-arrange"></div>
                    <a class="form-control select-mediatype " href="javascript:void(0);" mediatype='multiple' onclick="loadMediaFiles($(this))">
                        Select Attachement File
                    </a>
                    <small>only jpg & png</small>

                    <small class="text-danger">{{ $errors->first('file') }}</small>
                </div>
            </div>





            <div class="col-md-4 col-sm-12">
                <div class="m-0 form-group">
                    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
                </div>
            </div>
        </div>
    </div>
</div>
{{ html()->form()->close() }}


