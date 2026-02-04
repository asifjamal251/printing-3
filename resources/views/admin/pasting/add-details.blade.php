{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update.details', $pasting->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
       <div class="table-responsive">
        <table class="mb-0 table align-middle table-sm border-secondary table-bordered nowrap">
            <thead>
                <tr>
                    <th>MKDT/MFG</th>
                    <th>Item Name</th>
                    <th>Item Size</th>
                    <th>PO Quantity</th>
                    <th>Pasted Quantity</th>
                </tr>
            </thead>

            <tbody>
             <tr>
                 <td>{!! '<div class="col"><p class="mt-0 mb-0">'.$pasting->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$pasting->item?->mkdtBy?->company_name.'</p></div>' !!}
                 </td>
                 <td>{{$pasting->item?->item_name}}</td>
                 <td>{{$pasting->item?->item_size}}</td>
                 <td>{{$pasting->purchaseOrderItem->quantity}}</td>
                 <td>{{$pasting->items?->sum('total_quantity')??0}}</td>
             </tr>
         </tbody>
     </table>
 </div>
</div>
</div>


@if($pasting->items->where('status_id', 3)->count() > 0)
<div class="card">
    <div class="card-body">
       <div class="table-responsive">
        <table class="mb-0 table align-middle table-sm border-secondary table-bordered nowrap">
            <thead>
                <tr>
                    <th>Quantity/Box</th>
                    <th>Number Of Box</th>
                    <th>Total Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            @foreach($pasting->items->where('status_id', 3) as $index => $item)
                <tr>
                    <td>{{$item->quantity_per_box}}</td>
                    <td>{{$item->number_of_box}}</td>
                    <td>{{$item->total_quantity}}</td>
                    <th><span class="badge bg-success">Sent To Warehouse</span></th>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</div>
@endif






<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @if($pasting->items->where('status_id', 1)->count() > 0)

            @foreach($pasting->items->where('status_id', 1) as $index => $item)
            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">

                            <input type="hidden" name="kt_docs_repeater_advanced[{{$index}}][id]" value="{{ $item->id }}">

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.quantity_per_box") ? ' has-error' : '' }}">
                                {{ html()->label('Quantity/Box', "kt_docs_repeater_advanced[$index][quantity_per_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][quantity_per_box]", $item['quantity_per_box'])->class('form-control')->placeholder('Quantity/Box') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity_per_box") }}</small>
                            </div>

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.number_of_box") ? ' has-error' : '' }}">
                                {{ html()->label('Number Of Box', "kt_docs_repeater_advanced[$index][number_of_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][number_of_box]", $item['number_of_box'])->class('form-control')->placeholder('Number Of Box') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.number_of_box") }}</small>
                            </div>


                            <div class="m-0 form-group remove-item" style="width:44px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" class="btn-labels btn btn-danger" style="margin-top: 23px;">
                                        <i class="label-icon ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            @endforeach
            @else
            @foreach(old('kt_docs_repeater_advanced', [[]]) as $index => $item)

            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">
                            <input type="hidden" name="kt_docs_repeater_advanced[{{$index}}][id]" value="">
                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.quantity_per_box") ? ' has-error' : '' }}">
                                {{ html()->label('Quantity/Box', "kt_docs_repeater_advanced[$index][quantity_per_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][quantity_per_box]")->class('form-control')->placeholder('Quantity/Box') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity_per_box") }}</small>
                            </div>

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.number_of_box") ? ' has-error' : '' }}">
                                {{ html()->label('Number Of Box', "kt_docs_repeater_advanced[$index][number_of_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][number_of_box]")->class('form-control')->placeholder('Number Of Box') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.number_of_box") }}</small>
                            </div>


                            <div class="m-0 form-group remove-item" style="width:44px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" class="btn-labels btn btn-danger" style="margin-top: 23px;">
                                        <i class="label-icon ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="form-group m-0">
                <button data-repeater-create type="button" class="btn-label btn btn-warning text-end btn-sm">
                    <i class="label-icon align-middle fs-16 me-2 bx bx-plus-circle"></i> Add New Row
                </button>
            </div>
        </div>
    </div>

</div>



<div class="mt-4 form-group">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>
{{ html()->form()->close() }}


<script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">



    $('#kt_docs_repeater_advanced').repeater({
        show: function () {
            var $row = $(this);
            if ($('.js-choice').length > 0) {
                $(".js-choice").each(function() {
                    new Choices($(this)[0], { allowHTML: true });
                });
            }

            $row.find('small.text-danger').html('');
            $row.find('.form-group').removeClass('has-error');

            $row.slideDown('fast', function () {
                $row.find('input[name*="[product]"]').first().focus();
            });

        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
</script>


