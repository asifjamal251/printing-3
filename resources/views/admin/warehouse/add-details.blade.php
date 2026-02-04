{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update.details', $warehouse->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
     <div class="table-responsive">
        <table class="mb-0 table align-middle table-sm border-secondary table-bordered nowrap">
            <thead>
                <tr>
                    <th>MKDT/MFG</th>
                    <th>Item Name</th>
                    <th>Item Size</th>
                    <th>PO Number</th>
                    <th>PO Quantity</th>
                    <th>Pasted Quantity</th>
                </tr>
            </thead>

            <tbody>
               <tr>
                   <td>{!! '<div class="col"><p class="mt-0 mb-0">'.$warehouse->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$warehouse->item?->mkdtBy?->company_name.'</p></div>' !!}
                   </td>
                   <td>{{$warehouse->item?->item_name}}</td>
                   <td>{{$warehouse->item?->item_size}}</td>
                   <td>{{$warehouse->purchaseOrder?->po_number}}</td>
                   <td>{{$warehouse->purchaseOrderItem?->quantity}}</td>
                   <td>{{$warehouse->items?->sum('total_quantity')??0}}</td>
               </tr>
           </tbody>
       </table>
   </div>
</div>
</div>






<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @if($warehouse->items->count() > 0)

            @foreach($warehouse->items as $index => $item)
            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">

                            <input type="hidden" name="kt_docs_repeater_advanced[{{$index}}][id]" value="{{ $item->id }}">

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.quantity_per_box") ? ' has-error' : '' }}">
                                {{ html()->label('Quantity/Box', "kt_docs_repeater_advanced[$index][quantity_per_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][quantity_per_box]", $item['quantity_per_box'])->class('form-control')->placeholder('Quantity/Box')->attribute('readonly') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.quantity_per_box") }}</small>
                            </div>

                            <div class="m-0 w-75 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.number_of_box") ? ' has-error' : '' }}">
                                {{ html()->label('Number Of Box', "kt_docs_repeater_advanced[$index][number_of_box]") }}
                                <div class="input-group">
                                    {{ html()->text("kt_docs_repeater_advanced[$index][number_of_box]", $item['pending_number_of_box'])->class('form-control')->placeholder('Number Of Box') }}
                                </div>
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.number_of_box") }}</small>
                            </div>


                            {{-- <div class="m-0 form-group remove-item" style="width:44px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" class="btn-labels btn btn-danger" style="margin-top: 23px;">
                                        <i class="label-icon ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div> --}}

                        </div>


                    </div>
                </div>
            </div>
            @endforeach
            @endif
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


