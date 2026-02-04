{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $job_card->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
     <div class="table-responsive">
        <table class="mb-0 table align-middle table-sm border-secondary table-bordered nowrap">
            <thead>
                <tr>
                    <th>Job Card Type</th>
                    <th>Set Number</th>
                    <th>Die No.</th>
                    <th>Sheet Size</th>
                    <th>Impression</th>
                    <th>Item Name</th>
                    <th>Item Size</th>
                    <th>Product Type</th>
                    <th>GSM</th>
                    <th>Quantity</th>
                    <th>UPS</th>
                </tr>
            </thead>

            <tbody>
                @foreach($outerGroups as $outerGroup)
                @php
                $outerRowspan = $outerGroup->count(); 
                $printedOuter = false;              
                $innerGroups = $outerGroup->groupBy(function($itm) {
                    return ($itm->item->item_name ?? '').'|'.($itm->item->item_size ?? '').'|'.($itm->required_sheet ?? '');
                });
                @endphp
                @foreach($innerGroups as $innerGroup)
                @php
                $innerCount = $innerGroup->count(); 
                $firstOfInner = $innerGroup->first();
                @endphp

                @foreach($innerGroup as $index => $item)
                <tr>
                    @if(! $printedOuter)
                    <td rowspan="{{ $outerRowspan }}">{{ $job_card->job_type }}</td>
                    <td rowspan="{{ $outerRowspan }}">{{ $job_card->set_number }}</td>
                    <td rowspan="{{ $outerRowspan }}">{{ $job_card?->dye?->dye_number??'New' }}</td>
                    <td rowspan="{{ $outerRowspan }}">{{ $job_card->sheet_size }}</td>
                    <td id="requiredSheet" data-total="{{ $job_card->required_sheet }}" rowspan="{{ $outerRowspan }}">{{ $job_card->required_sheet }}</td>
                    @php $printedOuter = true; @endphp
                    @endif
                    @if($index == 0)
                    <td rowspan="{{ $innerCount }}">{{ $firstOfInner->item->item_name ?? '' }}</td>
                    <td rowspan="{{ $innerCount }}">{{ $firstOfInner->item->item_size ?? '' }}</td>
                    @endif
                    <td>{{ optional($item->itemProcessDetail)->productType?->name }}</td>
                    <td>{{ optional($item->itemProcessDetail)->gsm }}</td>
                    {{-- Always-print columns --}}
                    <td>{{ $item->quantity }}</td>
                    <td>{{ optional($item->itemProcessDetail)->ups }}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>



<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 col-sm-23">
                <div class="m-0 form-group{{ $errors->has('printing') ? ' has-error' : '' }}">
                    {{ html()->label('Printing', 'printing') }}
                    {{ html()->select('printing', ['Online' => 'Online', 'Offline' => 'Offline'], 'Offline')->class('js-choice form-control')->placeholder('Choose Printing') }}
                    <small class="text-danger">{{ $errors->first('printing') }}</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-23">
                <div class="form-group{{ $errors->has('tentative_date') ? ' has-error' : '' }}">
                    {{ html()->label('Tentative Date', 'tentative_date') }}
                    {{ html()->text('tentative_date', $job_card->tentative_date)->class('form-control dateSelector')->placeholder('Tentative Date') }}
                    <small class="text-danger">{{ $errors->first('tentative_date') }}</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-23">
                <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                    {{ html()->label('Remarks', 'remarks') }}
                    {{ html()->text('remarks', $job_card->remarks)->class('form-control')->placeholder('Remarks') }}
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">
        <div data-repeater-list="kt_docs_repeater_advanced">

            @php
            $rows = old('kt_docs_repeater_advanced')
            ?: ($job_card->jobCardProducts->count() ? $job_card->jobCardProducts : [['']]);
            @endphp

            @foreach($rows as $index => $item)
            <div data-repeater-item class="repeater-row mb-3">
                <input type="hidden" name="id" value="{{ $item['id'] ?? $item->id ?? '' }}">

                <div class="card">
                    <div class="card-body">
                        <div class="product-row custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">



                            {{-- Product --}}
                            <div class="w-75 form-group m-0">
                                <label>Choose Product <span class="totalStock text-success"></span></label>

                                <select name="product" 
                                class="form-control form-select getProduct totalStockShow"
                                data-placeholder="Select Product">

                                @php
                                $selectedProduct = old("kt_docs_repeater_advanced.$index.product")
                                ?? ($item['product_id'] ?? $item->product_id ?? '');

                                $productName = $selectedProduct 
                                ? optional(App\Models\Product::find($selectedProduct))->fullname 
                                : null;
                                @endphp

                                @if($selectedProduct)
                                <option value="{{ $selectedProduct }}" selected>{{ $productName }}</option>
                                @else
                                <option value="">Choose Product</option>
                                @endif
                            </select>

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced.$index.product") }}
                            </small>
                        </div>



                        @php
                        $productId = $selectedProduct;
                        $selectedAttr = old("kt_docs_repeater_advanced.$index.item_per_packet")
                        ?? ($item['item_per_packet'] ?? $item->product_attribute_id ?? null);
                        $attributes = $productId
                        ? App\Models\ProductAttribute::where('product_id', $productId)
                        ->pluck('item_per_packet', 'id')
                        : [];
                        @endphp

                        <div class="w-50 m-0 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.item_per_packet") ? ' has-error' : '' }}">
                            {{ html()->label('Item/Packet', "kt_docs_repeater_advanced[$index][item_per_packet]") }}

                            {{ html()
                                ->select("kt_docs_repeater_advanced[$index][item_per_packet]", $attributes, $selectedAttr)
                                ->class('productAttribute form-control')
                                ->placeholder('Item/Packet')
                            }}

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced.$index.item_per_packet") }}
                            </small>
                        </div>


                        {{-- Required Sheet --}}
                        <div class="w-50 form-group m-0">
                            <label>Impression</label>
                            <input type="text"
                            class="form-control ktquantity"
                            name="required_sheet"
                            value="{{ old("kt_docs_repeater_advanced.$index.required_sheet", $item['required_sheet'] ?? $item->required_sheet ?? $job_card->required_sheet) }}"
                            placeholder="Impression">
                            
                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced.$index.required_sheet") }}
                            </small>
                        </div>

                        {{-- Wastage --}}
                        <div class="w-50 form-group m-0">
                            <label>Wastage</label>
                            <input type="text"
                            class="form-control ktwastage"
                            name="wastage"
                            value="{{ old("kt_docs_repeater_advanced.$index.wastage", $item['wastage_sheet'] ?? $item->wastage_sheet ?? '') }}"
                            placeholder="Wastage">
                            
                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced.$index.wastage") }}
                            </small>
                        </div>

                        {{-- Paper Divide --}}
                        <div class="w-50 form-group m-0">
                            <label>Paper Divide</label>
                            <select name="paper_devide" class="form-control js-choice">
                                <option value="">Choose</option>
                                @foreach([1,2,3,4,5,6] as $no)
                                <option value="{{ $no }}"
                                {{ old("kt_docs_repeater_advanced.$index.paper_devide", $item['paper_divide'] ?? $item->paper_divide ?? '') == $no ? 'selected' : '' }}>
                                {{ $no }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-danger">
                            {{ $errors->first("kt_docs_repeater_advanced.$index.paper_devide") }}
                        </small>
                    </div>

                    {{-- Total Sheet --}}
                    <div class="w-50 form-group m-0">
                        <label>Total Sheet</label>
                        <input type="text"
                        class="form-control kttotal_sheet"
                        name="total_sheet"
                        value="{{ old("kt_docs_repeater_advanced.$index.total_sheet", $item['total_sheet'] ?? $item->total_sheet ?? '') }}"
                        placeholder="Total Sheet">
                        
                        <small class="text-danger">
                            {{ $errors->first("kt_docs_repeater_advanced.$index.total_sheet") }}
                        </small>
                    </div>

                    {{-- Delete Button --}}
                    <div class="form-group m-0" style="width:44px;">
                        <button data-repeater-delete type="button" class="btn btn-danger mt-4">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

{{-- Add Button --}}
<div class="d-flex justify-content-end my-3">
    <button data-repeater-create type="button" class="btn btn-warning btn-sm">
        <i class="bx bx-plus-circle me-1"></i> Add New Row
    </button>
</div>

</div>
</div>



<div class="mt-4 form-group">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>
{{ html()->form()->close() }}


<script type="text/javascript" src="{{asset('assets/admin/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">

    function updateQuantityDefaults() {
        var requiredSheet = parseInt($('#requiredSheet').data('total') || 0);
        var totalUsed = 0;

        $('.ktquantity').each(function() {
            var val = parseInt($(this).val()) || 0;
            totalUsed += val;
        });

        var remaining = requiredSheet - totalUsed;

    // Set default for the last added row
        var $lastRowQty = $('.repeater-row').last().find('.ktquantity');
        if($lastRowQty.length && remaining > 0){
            $lastRowQty.val(remaining);
        } else{
            $lastRowQty.val(0);
        }
    }

    var rowCounter = 0;

    $('#kt_docs_repeater_advanced').repeater({
        show: function () {
            var $row = $(this);
            $row.addClass('row-' + rowCounter);
            rowCounter++;

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
            getProduct('.getProduct', true);
            updateQuantityDefaults();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });


    function calculateTotalSheet(row) {
        let required = parseFloat(row.find('input[name$="[required_sheet]"]').val()) || 0;
        let wastage  = parseFloat(row.find('input[name$="[wastage]"]').val()) || 0;
        let divide   = parseFloat(row.find('select[name$="[paper_devide]"]').val()) || 1;

        let totalSheet = (required + wastage) / divide;

        row.find('input[name$="[total_sheet]"]').val(
            isFinite(totalSheet) ? totalSheet.toFixed(0) : 0
            );
    }

    $('body').on('input change', 'input[name$="[required_sheet]"], input[name$="[wastage]"], select[name$="[paper_devide]"]',
        function () {
            let row = $(this).closest('[data-repeater-item]');
            calculateTotalSheet(row);
        });

    $('.repeater-row').each(function () {
        calculateTotalSheet($(this));
    });
</script>


