

{!! html()->form('PUT', route('admin.' . request()->segment(2) . '.update.item', $item->id))->attribute('files', true)->open() !!}

<div class="card mb-3">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">

                <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                    {{ html()->label('Item Name', 'item_name') }}
                    {{ html()->text('item_name', $item->item_name)->class('form-control')->placeholder('Item Name')->attribute('readonly') }}
                    <small class="text-danger">{{ $errors->first('item_name') }}</small>
                </div>

                <div class="form-group{{ $errors->has('item_size') ? ' has-error' : '' }}">
                    {{ html()->label('Item Size', 'item_size') }}
                    {{ html()->text('item_size', $item->item_size)->class('form-control')->placeholder('Item Size')->attribute('readonly') }}
                    <small class="text-danger">{{ $errors->first('item_size') }}</small>
                </div>

                <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                    {{ html()->label('Quantity', 'quantity') }}
                    {{ html()->text('quantity', $item->quantity)->class('form-control')->placeholder('Quantity') }}
                    <small class="text-danger">{{ $errors->first('quantity') }}</small>
                </div>

                <div class="m-lg-0 form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                    {{ html()->label('Rate', 'rate') }}
                    <div class="input-group"> 
                        <span class="input-group-text">₹</span>
                        {{ html()->text('rate', $item->rate)->class('form-control')->placeholder('Rate') }}
                    </div>
                    <small class="text-danger">{{ $errors->first('rate') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status', 'status') }}
                    {{ html()->select('status', App\Models\Status::whereIn('id', [1, 6, 7, 8, 20])->pluck('name', 'id'), $item->status_id)->class('js-choice form-control') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>

                <div class="m-0 form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', 'remarks') }}
                    {{ html()->textarea('remarks', $item->remarks)->class('form-control')->placeholder('Remarks')->rows(8)->attribute('style', 'min-height: 186px;') }}
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>



<div class="mb-0 form-group" style="margin-top:22px; min-width:110px;">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>




{{ html()->form()->close() }}
