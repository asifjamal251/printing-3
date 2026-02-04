@extends('admin.layouts.master')
@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }

    .po-qty-green {
        background-color: #d1e7dd !important;
        color: #0f5132 !important;
    }

    .po-qty-red {
        background-color: #f8d7da !important;
        color: #842029 !important;
    }
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    <li>
        <a class="dropdown-item create" model-size="modal-md" data-url="{{route('admin.order-sheet.export.form')}}" data-title="Export {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextExport"><i class="mdi mdi-export-variant me-2"></i> Export</a>
    </li>


    <li>
        <a class="dropdown-item" href="javascript:void(0)" id="contextBack"><i class="bx bx-arrow-back me-2"></i> Back</a>
    </li>

</ul>








<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="createProcessing">Create Processing</a> --}}
            <a href="javascript:void(0);" bg-color="" model-size="modal-md" data-ur="{{route('admin.order-sheet.create')}}" data-title="Create Processing" class="btn btn-sm btn-primary create" id="createPO">Create Processing</a>
        </div>


    </div>
</div>
<!-- end page title -->




<div class="row">

    <div class="col-md-13" style="width:100%;">
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
                                {{ html()->select('filterStatus', App\Models\Status::whereIn('id', [1,3])->pluck('name', 'id'), 1)->class('form-control js-choice onChange')->placeholder('Status') }}
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
                    <table id="datatable" class="table align-middle datatable border-secondary table-bordered nowrap" style="width:100%">

                        <thead>

                            <tr>
                                <th style="width:17px"><input type="checkbox"></th>
                                <th style="width:20px">Sr</th>
                                <th>MFG BY<br>MKDT BY</th>
                                <th>PO Date <br>Last Date</th>
                                <th>Item<br>Size</th>
                                <th>Colour</th>
                                <th>Paper</th>
                                <th>Last QTY<br>Job Type</th>
                                <th>Die</th>
                                <th>Set No.</th>
                                <th>Coating<br>Other</th>
                                <th>Emb</th>
                                <th>Leaf</th>
                                <th>B.P</th>
                                <th>Braille</th>
                                <th>Rate</th>
                                <th class="po-qty-red">PO QTY</th>
                                <th class="po-qty-green">Final QTY</th>
                                
                                <th>GSM</th>
                                <th>Job Type</th>
                                <th>UPS</th>
                                <th>Urgent</th>
                                <th>Remarks</th>

                                {{-- <th>PO Remarks</th> --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script type="text/javascript">
    const rollId = {{auth('admin')->user()->role_id}}
    $(document).ready(function(){
        var table2 = $('#datatable').DataTable({
            "drawCallback": function(settings) {
                $('.js-choice').each(function () {
                    if (!this.choicesInstance) {
                        this.choicesInstance = new Choices(this, {
                            allowHTML: true,
                            searchEnabled: false
                        });
                    }
                });
                $('#datatable tbody [data-bs-toggle="tooltip"]').each(function () {
                    bootstrap.Tooltip.getInstance(this)?.dispose();
                    new bootstrap.Tooltip(this);
                });

                            // POPOVER
                $('#datatable tbody [data-bs-toggle="popover"]').each(function () {
                    bootstrap.Popover.getInstance(this)?.dispose();
                    new bootstrap.Popover(this);
                });
            },
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
                { "data": "checkbox" },
                { "data": "sn" },
                { "data": "mfg_mkdt_by" },
                { "data": "date" },
                { "data": "item" },
                { "data": "colour" },
                { "data": "paper_type" },
                { "data": "last_quantity" },
                { "data": "dye_number" },
                { "data": "set_number" },
                { "data": "coating" },
                { "data": "embossing" },
                { "data": "leafing" },
                { "data": "back_print" },
                { "data": "braille" },
                { "data": "rate" },
                { "data": "po_quantity" },
                { "data": "final_quantity" },
               
                { "data": "current_gsm" },
                { "data": "current_job_type" },
                { "data": "current_ups" },
                { "data": "urgent" },
                 { "data": "po_item_remarks" },

                    //{ "data": "po_remarks" },
                { "data": "id", "visible": false }
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).addClass('data-row');
                $(row).attr('data-row', JSON.stringify(data));

                if (data.row_class) {
                    $(row).addClass(data.row_class);
                }

                if(data.added_processing === 1){
                    $(row).addClass('bg-warning-subtle');
                }

                if(data.status_id === 3){
                    $(row).addClass('bg-success-subtle');
                }

                $('td:eq(16)', row).addClass('po-qty-red');
                $('td:eq(17)', row).addClass('po-qty-green');
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


            {{-- $(document).on('contextmenu', '.data-row', function(e) {
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
             
            });
 --}}


 $(document).click(function() {
    $('#customContextMenu').hide();
});


 $('#customContextMenu').on('click', 'a', function(e) {
    e.preventDefault();
    var rowData = $('#customContextMenu').data('rowData');

    if (this.id === 'contextCreate') {
        var createUrl = $(this).attr('data-url');
        window.location.href = createUrl;
    }

    if (this.id === 'contextExport') {
        var exportURL = $(this).attr('data-url');
    }

    if (rowData) {
        if (this.id === 'contextEdit') {
            if(rowData.status_id == 3){
                $(this).hide();
            }
            var editUrl = window.location.href + '/' + rowData.id + '/edit';
            window.location.href = editUrl;
        }

        if (this.id === 'contextReceived') {
            if(rowData.status_id == 3){
                $(this).hide();
            }
            var editUrl = window.location.href + '/received/' + rowData.id;
            $(this).attr('data-url', editUrl); 
        }


        if (this.id === 'contextView') {
            var viewUrl = '{{ request()->url() }}/show/' + rowData.id;
            $(this).attr('data-url', viewUrl); 
            window.location.href = viewUrl;
        }

        if (this.id === 'contextBack') {
            var id = rowData.id;
            var url = "{{ route('admin.' . request()->segment(2) . '.back') }}";

            updateData(
                url,
                { id: id},
                false,
                'Back To PO',
                'Do you want to delete from order sheet'
                );
        }

        if (this.id === 'contextDelete') {
            var deleteUrl = window.location.href + '/' + rowData.id + '/delete';
            $(this).attr('data-url', deleteUrl); 
            deleteModel(deleteUrl);
        }
    }

    $('#customContextMenu').hide();
});


 $('#customContextMenu').on('click', 'a', function(e) {
    e.preventDefault();
    var rowData = $('#customContextMenu').data('rowData');

    if (rowData) {
        if (this.id === 'contextCancel') {
            var deleteUrl = window.location.href + '/' + rowData.id + '/delete';
            $(this).attr('data-url', deleteUrl); 
            deleteModel(deleteUrl);
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


});







</script>

<script type="text/javascript">

    $('body').on('change', '.makeProcessing', function(){
        if($(this).is(':checked')) {
            var id = $(this).val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: '{{ route('admin.'.request()->segment(2).'.store')}}',
                data: {'id':id, 'type':'added', '_method': 'POST', '_token': '{{ csrf_token() }}'},
                success:function(response){
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                },
                error:function(error){
                    console.log(error);
                }
            });
        } else {
            var id = $(this).val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: '{{ route('admin.'.request()->segment(2).'.store')}}',
                data: {'id':id, 'type':'remove', '_method': 'POST', '_token': '{{ csrf_token() }}'},
                success:function(response){
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                },
                error:function(error){
                    console.log(error);
                }
            });
        }
    });



    $('body').on('click', '#createProcessing', function () {
        let ids = [];

        $('.makeProcessing:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            alert('Please select at least one item.');
            return;
        }

        $.ajax({
            url: '{{route('admin.'. request()->segment(2) .'.create.processing')}}',
            method: 'POST',
            data: {
                ids: ids,
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function (response) {
                if (response.class) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                }
            },
            error: function (xhr) {
                if (xhr){
                    Toastify({
                        text: xhr.responseText,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                }
            }
        });
    });

    $('body').on('change', '.finalQuantity', function() {
        var id = $(this).data('id');
        var final_quantity = $(this).val();
        var url = "{{ route('admin.' . request()->segment(2) . '.update.final-quantity') }}";

        updateData(
            url,
            { id: id, final_quantity: final_quantity },
            false,
            'Update Order Sheet',
            'Do you want to update final quantity?'
            );
    });

    $('body').on('change', '.currentJobType', function() {
        var id = $(this).data('id');
        var job_type = $(this).val();
        var url = "{{ route('admin.' . request()->segment(2) . '.update.job-type') }}";

        updateDataSingle(
            url,
            { id: id, job_type: job_type },
            false,
            'Update Order Sheet',
            'Do you want to update Job Type?'
            );
    });


    $('body').on('change', '.urgent', function() {
        var id = $(this).data('id');
        var urgent = $(this).val();
        var url = "{{ route('admin.' . request()->segment(2) . '.update.urgent') }}";

        updateData(
            url,
            { id: id, urgent: urgent },
            false,
            'Update Order Sheet',
            'Do you want to update Urgent?'
            );
    });



    $('body').on('change', '.currentUps', function() {
        const id = $(this).data('id');
        const ups = $(this).val();
        const url = "{{ route('admin.' . request()->segment(2) . '.update.ups') }}";

        updateDataSingle(url, { id: id, ups: ups });
    });


    $('body').on('change', '.currentGSM', function() {
        const id = $(this).data('id');
        const gsm = $(this).val();
        const url = "{{ route('admin.' . request()->segment(2) . '.update.gsm') }}";

        updateDataSingle(url, { id: id, gsm: gsm });
    });

    $(document).ready(function(){
        getClient('#filterMFGBY', false, 'Choose MFG By');
        getClient('#filterMKDTBY', false, 'Choose MKDT By');
        getClient('#filterClient', false, 'Choose Client');
    })
</script>




@endpush
