{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $category->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('category_name') ? ' has-error' : '' }}">
                    {{ html()->label('Category Name', 'category_name') }}
                    {{ html()->text('category_name', $category->name)->class('form-control')->placeholder('Category Name') }}
                    <small class="text-danger">{{ $errors->first('category_name') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
{{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}

{!! html()->form()->close() !!}
