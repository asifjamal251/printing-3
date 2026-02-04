@extends('admin.layouts.master')
@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_purchase_order')
    <li>
        <a class="dropdown-item" href="javascript:void(0)" id="contextCreate">
            <i class="fs-16 bx bx-plus me-2"></i> Add More Item</a>
        </li>

        @endcan

         @can('edit_purchase_order')
            <li><a class="dropdown-item" href="#" id="contextEdit"><i class="ri-pencil-fill me-2"></i>  Edit</a></li>
        @endcan

        @can('read_purchase_order')
            <li><a class="dropdown-item" href="#" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>
            <li><a class="dropdown-item" href="#" id="contextApproval"><i class="fa fa-list me-2"></i> Item Details</a></li>
        @endcan

        <li>
            <a class="dropdown-item create" model-size="modal-md" data-url="{{route('admin.purchase-order.export.form')}}" data-title="Export {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextExport"><i class="mdi mdi-export-variant me-2"></i> Export</a>
        </li>

        @can('delete_purchase_order')
        <div class="dropdown-divider m-0"></div>
        
        <li><a class="dropdown-item" href="javascript:void(0)" id="contextDelete"><i class="ri-delete-bin-fill me-2"></i> Delete</a></li>
        @endcan
    </ul>











    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->




    <div class="row">


        <div class="col-md-12">
            <div class="card">

                <div class="card-body border border-dashed border-end-0 border-start-0">




                    <div class="d-flex gap-2">

                        <div class="w-75">
                            <div class="m-0 form-group{{ $errors->has('filter_client') ? ' has-error' : '' }}">
                                {{ html()->select('filter_client', [])->id('filterClient')->class('form-control onChange')->placeholder('Choose Client') }}
                                <small class="text-danger">{{ $errors->first('filter_client') }}</small>
                            </div>
                        </div>


                        <div class="w-50">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_po_number') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_po_number')->class('form-control onKeyup')->id('filterPONO')->placeholder('PO No.') }}
                                    <small class="text-danger">{{ $errors->first('filter_po_number') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


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
                                <div class="m-0 form-group{{ $errors->has('filter_po_date') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_po_date')->class('form-control onChange dateSelectorRange')->id('filterPODate')->placeholder('PO Date') }}
                                    <small class="text-danger">{{ $errors->first('filter_po_date') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                        

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle datatable border-secondary table-bordered nowrap" style="width:100%">

                            <thead>
                                <tr>
                                    <th style="width:12px">Sr</th>
                                    <th>Client</th>
                                    <th>PO No.</th>
                                    <th>PO Date</th>
                                    <th>PO Created By</th>
                                    <th>PO Remarks</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Items Status</th>
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

    <script type="text/javascript">
        const rollId = {{auth('admin')->user()->role_id}}
        $(document).ready(function(){
            var table2 = $('#datatable').DataTable({
                "drawCallback": function(settings) {
                    //getUser('#tableUser', false);
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
                        d.client = $('#filterClient').val();
                        d.po_number = $('#filterPONO').val();
                        d.item_name  = $('#filterItemName').val();
                        d.item_size  = $('#filterItemSize').val();
                        d.po_date    = $('#filterPODate').val();
                    }

                },
                "columns": [
                    { "data": "sn" },
                    { "data": "client"},
                    { "data": "po_number"},
                    { "data": "po_date"},
                    { "data": "created_by"},
                    { "data": "remarks"},
                    { "data": "status"},
                    { "data": "items"},
                    { "data": "items_status"},
                    { "data": "id", "visible": false },
                ],
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass('data-row');
                    $(row).attr('data-row', JSON.stringify(data));

                    if (data.row_class) {
                        $(row).addClass(data.row_class);
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

        



            $(document).click(function() {
                $('#customContextMenu').hide();
            });

            
            $('#customContextMenu').on('click', 'a', function(e) {
                e.preventDefault();
                var rowData = $('#customContextMenu').data('rowData');

                

                if (rowData) {

                    if (this.id === 'contextCreate') {
                        var createUrl = '{{route('admin.item.index')}}?po_id='+rowData.id;
                        window.location.href = createUrl;
                    }

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

                    if (this.id === 'contextApproval') {
                        window.location.href = `{{ request()->url() }}/${rowData.id}/approvals`;
                    }

                    if (this.id === 'contextDelete') {
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

           getClient('#filterClient', false, 'Choose Client'); 




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
                const addmore = [1];
                const cancel = [1];

                if (addmore.includes(statusId)) {
                    $('#contextCreate')
                    .removeClass('disabled')
                    .css('pointer-events', '')
                    .css('opacity', '');
                } else {
                    $('#contextCreate')
                    .addClass('disabled')
                    .css('pointer-events', 'none')
                    .css('opacity', 0.5);
                }


                if (cancel.includes(statusId)) {
                    $('#contextDelete')
                    .removeClass('disabled')
                    .css('pointer-events', '')
                    .css('opacity', '');
                } else {
                    $('#contextDelete')
                    .addClass('disabled')
                    .css('pointer-events', 'none')
                    .css('opacity', 0.5);
                }
            }
 
        });



    </script>

    

            @endpush
