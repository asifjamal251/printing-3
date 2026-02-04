@extends('admin.layouts.master')
@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }
    td[rowspan] {
        vertical-align: middle;
    }


    

    .process-start td {
        border-top: 1.5px solid #000 !important;
    }

/* Optional: Add top/bottom borders for clarity */
.process-start td {
    border-top: 1.5px solid #000 !important;
}


.process-end td {
    border-bottom: 1.5px solid #000 !important;
}



/* Hide border between same processing_number rows */
tbody tr:not(.process-start) td {
    border-top: none !important;
}
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">


    <li>
        <a class="dropdown-item" href="javascript:void(0)" id="contextRateCompleted"><i class="mdi mdi-check-all me-2"></i>Approved Rate</a>
    </li>

    <li>
        <a class="dropdown-item" href="javascript:void(0)" id="contextBack"><i class="ri-delete-bin-fill me-2"></i> Back To Order Sheet</a>
    </li>

    <li>
        <a class="dropdown-item create" model-size="modal-lg" data-url="{{route('admin.carton-rate.export.form')}}" data-title="Export {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextExport"><i class="mdi mdi-export-variant me-2"></i> Export</a>
    </li>
</ul>








<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>
            {{ html()->form('POST', route('admin.carton-rate.update.approved'))->attribute('enctype', 'multipart/form-data')->id('storeForm')->open() }}

            <div id="selectedItem">
                
            </div> 
            
            {{ html()->button('Approved Selected Item')->type('button')->class('btn btn-sm btn-success bg-gradient')->attribute('onclick = store(this)') }}
            {{ html()->form()->close() }}
            
        </div>


    </div>
</div>
<!-- end page title -->




<div class="d-flex gap-2">

    <div class="" style="width:100%;">
        <div class="card">

            <div class="card-body border border-dashed border-end-0 border-start-0">




                    <div class="d-flex gap-2">

                        @can('mfg_mkdt_item')
                            <div class="w-75">
                                <div class="m-0 form-group{{ $errors->has('filter_mfg_by') ? ' has-error' : '' }}">
                                    {{ html()->select('filter_mfg_by', [])->id('filterMFGBY')->class('filterClient form-control onChange')->placeholder('MFG By') }}
                                    <small class="text-danger">{{ $errors->first('filter_mfg_by') }}</small>
                                </div>
                            </div>


                            <div class="w-75">
                                <div class="m-0 form-group{{ $errors->has('filter_mkdt_by') ? ' has-error' : '' }}">
                                    {{ html()->select('filter_mkdt_by', [])->id('filterMKDTBY')->class('filterClient form-control onChange')->placeholder('MKDT By') }}
                                    <small class="text-danger">{{ $errors->first('filter_mkdt_by') }}</small>
                                </div>
                            </div>
                        @else
                            <div class="w-75">
                                <div class="m-0 form-group{{ $errors->has('filter_client') ? ' has-error' : '' }}">
                                    {{ html()->select('filter_client', [])->id('filterClient')->class('filterClient form-control onChange')->placeholder('Client') }}
                                    <small class="text-danger">{{ $errors->first('filter_client') }}</small>
                                </div>
                            </div>
                        @endcan


                         <div class="w-50">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_item_name') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_item_name')->class('form-control onKeyup')->id('filterItemName')->placeholder('Item Name') }}
                                    <small class="text-danger">{{ $errors->first('filter_item_name') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="w-50">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_item_size') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_item_size')->class('form-control onKeyup')->id('filterItemSize')->placeholder('Item Size') }}
                                    <small class="text-danger">{{ $errors->first('filter_item_size') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                        <div class="w-50">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_set_no') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_set_no')->class('form-control onKeyup')->id('filterItemSetNo')->placeholder('Set No.') }}
                                    <small class="text-danger">{{ $errors->first('filter_set_no') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                        <div class="w-50">
                            <div class="m-0 form-group{{ $errors->has('filterStatus') ? ' has-error' : '' }}">
                                {{ html()->select('filterStatus', App\Models\Status::whereIn('id', [1,3,6])->pluck('name', 'id'), 1)->class('form-control js-choice onChange')->placeholder('Status') }}
                                <small class="text-danger">{{ $errors->first('filterStatus') }}</small>
                            </div>
                        </div>

                        <div style="width:120px;">
                            <div class="m-0 form-group{{ $errors->has('filter__length') ? ' has-error' : '' }}">
                                {{ html()->select('filter__length', [25 => 25, 50 => 50, 100 => 100, 150 => 150, 200 => 200], 25)->id('filterLength')->class('form-control onChange js-choice') }}
                                <small class="text-danger">{{ $errors->first('filter__length') }}</small>
                            </div>
                        </div>


                    </div>
                </div>


                

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                        <thead>
                            <tr>
                                <th style="width:20px">Sr</th>
                                <th></th>
                                <th>Set No.</th>
                                <th>MFG BY/MKDT By</th>
                                <th>Item</th>
                                <th>Job Type</th>
                                <th>Colour</th>
                                <th>Paper</th>
                                <th>UPS</th>
                                <th>Quantity</th>
                                <th>Die</th>
                                <th>Coating</th>
                                <th>Embossing</th>
                                <th>Leafing</th>
                                <th>Back Print</th>
                                <th>Braille</th>
                                <th style="width:60px">Rate</th>
                                <th>Status</th>
                                <th>ID</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->



@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script type="text/javascript">
    const rollId = {{auth('admin')->user()->role_id}}
    $(document).ready(function(){
        var table2 = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "lengthMenu": [25],
            'ajax': {
                'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
                'data': function(d) {
                    d._token = '{{ csrf_token() }}';
                    d._method = 'PATCH';
                    d.mfg_by    = $('#filterMFGBY').val();
                    d.mkdt_by  = $('#filterMKDTBY').val();
                    d.client = $('#filterClient').val();
                    d.item_name = $('#filterItemName').val();
                    d.item_size = $('#filterItemSize').val();
                    d.set_no    = $('#filterItemSetNo').val();
                    d.status    = $('#filterStatus').val();
                     d.length = $('#filterLength').val();
                }

            },
            "columns": [
                { "data": "sn" },
                { "data": "checkbox" },
                { "data": "set_number" },
                { "data": "mfg_mkdt_by"},
                { "data": "item" },
                { "data": "job_type" },
                { "data": "colour" },
                { "data": "paper" },
                { "data": "ups" },
                { "data": "quantity" },
                { "data": "dye_number" },
                { "data": "coating" },
                { "data": "embossing" },
                { "data": "leafing" },
                { "data": "back_print" },
                { "data": "braille" },
                { "data": "rate" },
                { "data": "status" },
                { "data": "id", "visible": false }
                ],
            createdRow: function(row, data) {
                $(row).addClass('data-row').attr('data-row', JSON.stringify(data));

                //if (data.added_processing === 1) $(row).addClass('bg-warning-subtle');
                if (data.status_id === 6) $(row).addClass('bg-warning-subtle');
                //if (data.status_id === 6) $(row).addClass('bg-primary-subtle');
                if (data.status_id === 3) $(row).addClass('bg-success-subtle');
            },
            drawCallback: function(settings) {

                $(".js-choice").each(function() {
                    new Choices($(this)[0], { allowHTML: true });
                });

                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var data = api.rows({ page: 'current' }).data();
                var colIndex = 2; 

                var lastProcessingNumber = null;
                var firstCell = null;
                var firstRow = null;
                var prevRow = null;
                var rowspan = 1;


                $(rows).removeClass('process-start process-end');


                for (var i = 0; i < data.length; i++) {
                    var currentProcessing = data[i].set_number;
                    var $currentRow = $(rows).eq(i);
                    var $currentCell = $(api.cell(i, colIndex).node());

                    if (currentProcessing === lastProcessingNumber) {

                        $currentCell.remove(); 
                        rowspan++;
                        prevRow = $currentRow; 
                    } else {

                        if (firstCell && rowspan > 1) {
                            firstCell.attr('rowspan', rowspan); 
                            $(prevRow).addClass('process-end'); 
                        }


                        $currentRow.addClass('process-start');
                        firstCell = $currentCell;
                        firstRow = $currentRow;
                        prevRow = $currentRow;
                        rowspan = 1;
                        lastProcessingNumber = currentProcessing;
                    }
                }


                if (firstCell && rowspan > 1) {
                    firstCell.attr('rowspan', rowspan);
                    $(prevRow).addClass('process-end');
                }
            }

        });

$('body').on('keyup', '.onKeyup', function(){
    table2.draw('page');
});

$('body').on('mouseup', '.onKeyup', function(e){
    var $input = $(this);
    setTimeout(function(){
        if ($input.val() === '') {
            table2.draw('page');
        }
    }, 1);
});

$('body').on('change', '.onChange', function(){
    table2.draw('page');
});



$('#datatable tbody').on('contextmenu', 'tr', function(e) {
    e.preventDefault();

    var data = table2.row(this).data();

    $('#customContextMenu')
    .css({
        top: e.pageY + 'px',
        left: e.pageX + 'px',
        display: 'block'
    })
    .data('rowData', data || null);
    handleContextMenuRestrictions(data);
});


            $(document).on('contextmenu', '.data-row', function(e) {
                e.preventDefault();

                var rowData = JSON.parse($(this).attr('data-row'));
                $('#customContextMenu').data('rowData', rowData);

                
                $('#customContextMenu').css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px',
                    display: 'block'
                });


                    const statusId = rowData?.status_id;
                    const restrictedStatuses = [3];

                    if (restrictedStatuses.includes(statusId)) {
                        $('#contextBack')
                            .addClass('disabled')
                            .css('pointer-events', 'none')
                            .css('opacity', 0.5);
                    } else {
                        $('#contextBack')
                            .removeClass('disabled')
                            .css('pointer-events', '')
                            .css('opacity', '');
                    }
             
            });



 $(document).click(function() {
    $('#customContextMenu').hide();
});


 $('#customContextMenu').on('click', 'a', function(e) {
    e.preventDefault();
    var rowData = $('#customContextMenu').data('rowData');

    if (rowData) {
        if (this.id === 'contextBack') {
            var url = window.location.href + '/back/to/order-sheet';
            updateData(
                url,
                { id: rowData.id },
                false,
                'Remove From Processing',
                'Do you want to go back to the Order Sheet?'
            );
        }


        if (this.id === 'contextRateCompleted') {
            var url = window.location.href + '/update/rate/completed';
            updateData(
                url,
                { id: rowData.id },
                false,
                'Rate Approval Completed',
                'Do you want to complete this approval rate?'
            );
        }
    }

    $('#customContextMenu').hide();
});


 $(window).on('scroll resize', function() {
    $('#customContextMenu').hide();
});

 $(document).on('keydown', function(e) {
    if (e.key === "Escape") {
        $('#customContextMenu').hide();
    }
});

 let touchTimer;
 let touchDuration = 500; 
 $('#datatable tbody').on('touchstart', 'tr', function(e) {
    const $row = $(this);
    touchTimer = setTimeout(() => {
        const data = table2.row($row).data();
        const touch = e.originalEvent.touches[0];

        $('#customContextMenu')
        .css({
            top: touch.pageY + 'px',
            left: touch.pageX + 'px',
            display: 'block'
        })
        .data('rowData', data || null);

        handleContextMenuRestrictions(data);

    }, touchDuration);
}).on('touchend touchmove touchcancel', function() {
    clearTimeout(touchTimer);
});

$('#datatable').on('touchstart', function(e) {
    if (!$(e.target).closest('tr').length || table2.data().count() === 0) {
        touchTimer = setTimeout(() => {
            const touch = e.originalEvent.touches[0];

            $('#customContextMenu')
            .css({
                top: touch.pageY + 'px',
                left: touch.pageX + 'px',
                display: 'block'
            })
            .data('rowData', null);
            handleContextMenuRestrictions(data);
        }, touchDuration);
    }
}).on('touchend touchmove touchcancel', function() {
    clearTimeout(touchTimer);
});


function handleContextMenuRestrictions(rowData) {
    const statusId = rowData?.status_id;
    const restrictedStatuses = [3];

    if (restrictedStatuses.includes(statusId)) {
        $('#contextCancel')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    } else {
        $('#contextCancel')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    }
}


$('body').on('change', '.rate', function() {
    const id = $(this).data('id');
    const rate = $(this).val();
    const url = "{{ route('admin.' . request()->segment(2) . '.update.rate') }}";

    updateDataSingle(url, { id: id, rate: rate });
});



    getCartonClient('#filterMFGBY', false, 'Choose MFG By');
    getCartonClient('#filterMKDTBY', false, 'Choose MKDT By');
    getClient('#filterClient', false, 'Choose Client');

});



$('body').on('change', '.cartonRate', function () {

    let id = $(this).val();

    if ($(this).is(':checked')) {

        if ($('#selectedItem').find('#selected_' + id).length === 0) {
            $('#selectedItem').append(`
                <input type="hidden" name="ids[]" id="selected_${id}" value="${id}">
            `);
        }

    } else {
        $('#selected_' + id).remove();
    }
});


$('body').on('change', 'select[name="filterStatus"]', function () {
    $('#filterMFGBY').val(null).trigger('change');
    $('#filterMKDTBY').val(null).trigger('change');
});
function getCartonClient(selector, usePopup = true, placeholder = 'Choose Client') {
    const $elements = $(selector);

    $elements.select2({
        dropdownParent: usePopup ? $('#dataSave') : $(document.body),
        placeholder: placeholder,
        allowClear: true,
        ajax: {
            url: '{{ route('admin.common.client.list.carton-rate') }}',
            dataType: 'json',
            cache: true,
            delay: 200,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    status_id: $('select[name="filterStatus"]').val() || 1,
                    type: (selector === '#filterMFGBY') ? 'mfg' : 'mkdt'
                };
            }
        }
    });
}

</script>


@endpush
