{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->text('name')->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>



            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('background_colour') ? ' has-error' : '' }}">
                    <label for="background_colour">Background Colour</label>
                    <span class="star-mandatory text-danger"> *</span>

                    <input type="color" 
                    name="background_colour" 
                    id="background_colour" 
                    value="{{ old('background_colour', '#000000') }}"
                    class="form-control" style="height:39px">

                    <small class="text-danger">{{ $errors->first('background_colour') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('text_colour') ? ' has-error' : '' }}">
                    <label for="text_colour">Text Colour</label>
                    <span class="star-mandatory text-danger"> *</span>

                    <input type="color" 
                    name="text_colour" 
                    id="text_colour" 
                    value="{{ old('text_colour', '#FFFFFF') }}"
                    class="form-control" style="height:39px">

                    <small class="text-danger">{{ $errors->first('text_colour') }}</small>
                </div>
            </div>



            <div class="col-md-12 col-sm-12">
                <div class="mt-4 form-group">
                    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
                </div>
            </div>

        </div>
    </div>
</div>

{{ html()->form()->close() }}
