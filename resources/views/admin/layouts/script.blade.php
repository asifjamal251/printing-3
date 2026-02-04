{{-- -<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}




<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    var createRoute = @json(Route::has('admin.' . request()->segment(2) . '.create') ? route('admin.' . request()->segment(2) . '.create') : null);
</script>


<script type="text/javascript">
    $(document).ready(function() {
        function create(url) {

            var finalUrl = url || createRoute;
            if (!finalUrl) {
                alert('Route not defined!');
                return;
            }

            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: finalUrl,
                success: function(response) {
                    $('#addForm').html(response);
                    initializePlugins();
                    $('#dataSave').modal('show');
                }
            });
        }

        function edit(url) {
            if (!url) {
                alert('Invalid Edit URL!');
                return;
            }

            $.ajax({
                type: "GET",
                enctype: 'multipart/form-data',
                url: url,
                success: function(response) {
                    $('#addForm').html(response);
                    initializePlugins();
                    $('#dataSave').modal('show');
                }
            });
        }

        function initializePlugins() {

            if ($('.dateSelector').length > 0) {
                $(".dateSelector").flatpickr({
                    dateFormat: "d F Y",
                //defaultDate: "today"
                });
            }

            if ($('.dateSelectorRange').length > 0) {
                $(".dateSelectorRange").flatpickr({
                    mode: "range",
                    dateFormat: "d F Y",
                });
            }
            if ($('.js-choice').length > 0) {
                $(".js-choice").each(function() {
                    new Choices($(this)[0], { allowHTML: true, searchEnabled: false });
                });
            }
            if ($('.js-choice-search').length > 0) {
                $(".js-choice-search").each(function() {
                    new Choices($(this)[0], { allowHTML: true, searchEnabled: true });
                });
            }
            if ($('.timeInput').length > 0) {
                $(".timeInput").flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            }
            if ($('.js-choice-multiple').length > 0) {
                $(".js-choice-multiple").each(function() {
                    new Choices($(this)[0], {
                        allowHTML: true});
                });
            }

            if ($('.client').length > 0) {
                getClient('.client', true, 'Choose Client');
            }

            if ($('.mkdt_by').length > 0) {
                getClient('.mkdt_by', true, 'Choose MKTD By');
            }

            if ($('.mfg_by').length > 0) {
                getClient('.mfg_by', true, 'Choose MFG By');
            }

            if ($('.vendor').length > 0) {
                getVendor('.vendor', true, 'Choose Vendor');
            }

            if ($('.firm').length > 0) {
                getFirm('.firm', true, 'Choose Firm');
            }

            if ($('.getItem').length > 0) {
                getItem('.getItem', true, 'Choose Item');
            }

            if ($('#state').length > 0) {
                getState('state'); 
            }
            if ($('.dye').length > 0) {
                getDye('.dye', true, 'Choose Dye')
            }
            if ($('.getProduct').length > 0) {
                getProduct('.getProduct', true);
            }


        }

        var modalSize = '';
        var bgColor = '';
        var bgColorDefault = '#f3f3f9';
        $('body').on('click', '#create, .create', function () {
            modalSize = $(this).attr('model-size') || 'modal-xl';
            bgColor = $(this).attr('bg-color') || bgColorDefault;
            $('.modal-dialog').removeClass(modalSize);
            $('#dataSaveLabel').html($(this).attr('data-title'));
            $('.modal-size').addClass(modalSize);
            $('.modal-size .modal-body').css('background-color', bgColor);
            var url = $(this).attr('data-url') || null;
            create(url);
        });

        $('#dataSave').on('hidden.bs.modal', function () {
            $('.modal-dialog').removeClass(modalSize);
        });


        $('body').on('click', '.editData', function() {
            modalSize = $(this).attr('model-size') || 'modal-xl';
            bgColor = $(this).attr('bg-color') || bgColorDefault;
            $('.modal-dialog').removeClass(modalSize);
            $('#dataSaveLabel').html($(this).attr('data-title'));
            $('.modal-size').addClass(modalSize);
            $('.modal-size .modal-body').css('background-color', bgColor);
            var url = $(this).attr('data-url') || null;
            edit(url);
        });

    });





    function getState(selector){
        $('#'+selector).select2({
            dropdownParent: $('#dataSave'),
            placeholder: 'Choose State',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.state.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
            }
        });
    }

    $('body').on('change', '#state', function(){
        var stateId = $(this).val();
        getCity('city', stateId);
    });


    function getCity(selector, stateId){
        $('#'+selector).select2({
            dropdownParent: $('#dataSave'),
            placeholder: 'Choose City',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.city.list') }}?state_id='+stateId,
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
            }
        });
    }

    function getCityAll(selector){
        $('#'+selector).select2({
            placeholder: 'Choose City',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.city.list.all') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
            }
        });
    }


    function getClient(selector, usePopup = true, placeholder = 'Choose Client') {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: placeholder,
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.client.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });
    }


    function getVendor(selector, usePopup = true) {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: 'Choose Vendor',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.vendor.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });
    }

    function getFirm(selector, usePopup = true) {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: 'Choose Vendor',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.firm.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });
    }



    function getDye(selector, usePopup = true) {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: 'Choose Dye Details',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.dye.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });
    }


    $('body').on('change', '[name="dye"]', function(){
        var dyeId = $(this).val();
        $('[name="sheet_size"]').removeAttr('readonly');
        $('[name="sheet_size"]').val();
        $.ajax({
            type: "GET",
            url: `/admin/common/dye/single/${dyeId}`,
            success: function (response) {
             $('[name="sheet_size"]').val(response.datas.sheet_size);
             $('[name="sheet_size"]').attr('readonly','readonly');
            },
            error: function (xhr) {
                console.error("Fetch failed", xhr.responseText);
                $('[name="sheet_size"]').removeAttr('readonly');
                $('[name="sheet_size"]').val('');
            }
        });

    })



    function getItem(selector, usePopup = true) {
        const $elements = $(selector);
        $elements.select2({
            dropdownParent: usePopup ? $('#dataSave') : $(document.body),
            placeholder: 'Choose Item',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.common.item.list') }}',
                dataType: 'json',
                cache: true,
                delay: 200,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1,
                        client: $('select[name="client"]').val(),
                    };
                }
            }
        });
    }




   function updateRepeaterChoices($select, selectedValue = '', selectedLabel = 'Select') {
        const selectElement = $select[0];
        if (selectElement.choicesInstance) {
            selectElement.choicesInstance.destroy();
            selectElement.choicesInstance = null;
        } else if ($select.hasClass('choices__input')) {
            const parent = $select.closest('.choices');
            if (parent.length) {
                parent.find('.choices__list').remove();
                $select.unwrap();
            }
        }
        $select.removeClass('choices__input').removeAttr('data-choice');
        $select.empty();
        $select.append(
            new Option(selectedLabel, selectedValue, true, true)
        );

        const newInstance = new Choices(selectElement, {
            allowHTML: true,
            shouldSort: false,
            placeholder: true,
            placeholderValue: 'Select',
        });

        selectElement.choicesInstance = newInstance;
    }

    $('body').on('change', '.getItem', function () {
        const itemId = $(this).val();
        const row = $(this).closest('[data-repeater-item]');

        if (!itemId) return;

        $.ajax({
            type: "GET",
            url: `/admin/common/item/details/${itemId}`,
            success: function (response) {
             let item = response.item;
             let rate = response.rate;
             row.find('input[name$="[item_size]"]').val(item.item_size ?? '');
             row.find('input[name$="[colour]"]').val(item.colour ?? '');
             row.find('input[name$="[artwork_code]"]').val(item.artwork_code ?? '');
             if(rate){
                row.find('input[name$="[rate]"]').val(rate.rate ?? '');
                row.find('input[name$="[gst_percentage]"]').val(rate.gst_percentage ?? '');
             } else{
                row.find('input[name$="[gst_percentage]"]').val(item.gst ?? '');
             }

             updateRepeaterChoices(row.find('[name$="[coating]"]'), item.coating, item.coating);
            },
            error: function (xhr) {
                console.error("Fetch failed", xhr.responseText);
                alert("Failed to fetch item details.");
            }
        });
    });









$(document).ready(function () {
    bindProductChange();
    bindAttributeChange();
    bindStockChange();
});


/*-------------------------------
    PRODUCT SELECT2 + CHANGE
--------------------------------*/
function getProduct(selector, usePopup = true) {
    const $el = $(selector);

    $el.select2({
        dropdownParent: usePopup ? $('#dataSave') : $(document.body),
        placeholder: 'Choose Product',
        allowClear: true,
        ajax: {
            url: '{{ route('admin.common.product.list') }}',
            dataType: 'json',
            cache: true,
            delay: 200,
            data: params => ({
                term: params.term || '',
                page: params.page || 1
            })
        }
    });

    $(document)
        .off("change.getProduct", selector)
        .on("change.getProduct", selector, function () {
            processProductChange($(this), usePopup);
        });
}


/*-------------------------------
    FETCH TOTAL STOCK
--------------------------------*/
function fetchTotalStock($select) {
    const row = $select.closest('[data-repeater-item]');
    const totalStock = row.find('.totalStock');
    const productId = $select.val();

    if (!productId) return totalStock.text("0");

    $.ajax({
        type: "GET",
        url: "{{ route('admin.common.product.stock.all') }}?id=" + productId,
        success: res => totalStock.text(res.datas ?? 0),
        error: () => totalStock.text("0")
    });
}

function bindStockChange() {
    $('body').on('change', '.totalStockShow', function () {
        fetchTotalStock($(this));
    });
}


/*-------------------------------
    PRODUCT CHANGE HANDLER
--------------------------------*/
function processProductChange($select, usePopup = true) {
    const row = $select.closest('[data-repeater-item]');
    const parent = $select.closest('.product-row');
    const productId = $select.val();

    const weight = row.find('[name*="[weight_per_piece]"]');
    const rate = row.find('[name*="[rate]"]');
    const gst = row.find('[name*="[gst]"]');
    const amount = row.find('[name*="[amount]"]');
    const totalWeight = row.find('[name*="[total_weight]"]');
    const qty = row.find('input[name*="[quantity');
    const attributeSelect = parent.find('.productAttribute');

    attributeSelect.val(null).trigger('change');
    weight.val('');
    gst.val('');
    totalWeight.val('');
    rate.val('');
    amount.val('');

    getAttribute(productId, attributeSelect, usePopup);
    productMORate(productId, row);
    getAmount(row);

    qty.next().html('Unit');
    totalWeight.next().html('Unit');

    if (!productId) return;

    $.ajax({
        type: "GET",
        url: "{{ route('admin.common.product.single') }}?id=" + productId,
        success: function (response) {
            if (response.datas) {
                const unit = response.datas.unit.code;
                qty.next().html(unit);
                totalWeight.next().html(response.datas.product_type_id != 1 ? unit : 'KG');
            }
        }
    });
}

function bindProductChange() {
    $('body').on('change', '.getProduct', function () {
        processProductChange($(this), true);
    });
}


/*-------------------------------
    ATTRIBUTE SELECT2 + CHANGE
--------------------------------*/
function getAttribute(productId, attributeSelect, usePopup = true) {
    $(attributeSelect).select2({
        dropdownParent: usePopup ? $('#dataSave') : $(document.body),
        allowClear: true,
        delay: 200,
        ajax: {
            url: '{{ route('admin.common.product.attribute.list') }}?product_id=' + productId,
            dataType: 'json',
            cache: true,
            data: params => ({
                term: params.term || '',
                page: params.page || 1
            })
        }
    });
}


/*-------------------------------
    ATTRIBUTE CHANGE HANDLER
--------------------------------*/
function processAttributeChange($select) {
    const row = $select.closest('[data-repeater-item]');
    const productAttrId = $select.val();

    const weight = row.find('[name*="[weight_per_piece]"]');
    const gst = row.find('[name*="[gst]"]');

    if (!productAttrId) return;

    $.ajax({
        type: "GET",
        url: "{{ route('admin.common.product.attribute.single') }}?id=" + productAttrId,
        success: function (response) {
            if (response.datas) {
                weight.val(response.datas.weight_per_piece);
                gst.val(response.datas.product.gst);
                getTotalWT(row);
            }
        }
    });
}

function bindAttributeChange() {
    $('body').on('change', '.productAttribute', function () {
        processAttributeChange($(this));
    });
}







$('body').on('input', '.ktrate, .ktgst, .ktquantity', function(){
    const row = $(this).closest('[data-repeater-item]');
    getTotalWT(row);
    getAmount(row);
});

function getTotalWT(row){
    const weightPerPieceInput = row.find('[name*="[weight_per_piece]"]');
    const quantityInput = row.find('[name*="[quantity]"]');
    const totalWTInput = row.find('[name*="[total_weight]"]');

    const weightPerPieceValue = parseFloat(weightPerPieceInput.val()) || 0;
    const quantityValue = parseFloat(quantityInput.val()) || 0;
    
    const totalWeight = (quantityValue * weightPerPieceValue).toFixed(3);
    totalWTInput.val(totalWeight);
}

function productMORate(productId, row){
    $.ajax({
        type: "GET",
        enctype: 'multipart/form-data',
        url: '{{ route('admin.common.product.mo.rate') }}?id='+productId,
        success: function(response) {
             const rateInput = row.find('[name*="[rate]"]');
            if(response.datas){
                rateInput.val(response.datas.rate);
            }
        }
    });
}

function getAmount(row){
    const rateInput = row.find('[name*="[rate]"]');
    const gstInput = row.find('[name*="[gst]"]');
    const amountInput = row.find('[name*="[amount]"]');
    const quantitytInput = row.find('[name*="[quantity]"]');
    const totalWeightInput = row.find('[name*="[total_weight]"]');

    const rate = parseFloat(rateInput.val()) || 0;
    const gst = parseFloat(gstInput.val()) || 0;
    const totalWeight = parseFloat(totalWeightInput.val()) || 0;

    const amount = (totalWeight * (rate * (1 + gst / 100))).toFixed(2);
    amountInput.val(amount);
}


</script>
