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

    @can('edit_purchase_order')
    <li>
        <a class="dropdown-item editData" model-size="modal-lg" bg-color="#f3f3f9" data-title="Update Item" href="javascript:void(0)" id="contextEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
    </li>
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

<div class="card">
    <div class="card-body">
        <table id="POTable" class="m-0 table align-middle table-sm border-success table-bordered nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="width:20%;">Client</th>
                    {{-- <th style="width:20%;">MFG BY</th> --}}
                    <th>PO Number</th>
                    <th>PO Date</th>
                    <th style="width:30%;">Remarks</th>
                    <th style="width:50px;">Status</th>
                </tr>
            </thead>

            <tbody class="POTableBody">
                <tr>
                    <td>{{$purchase_order->client?->company_name}}</td>
                    {{-- <td>{{$purchase_order->client->company_name}}</td> --}}
                    <td>{{$purchase_order->po_number}}</td>
                    <td>{{$purchase_order->po_date->format('d F Y')}}</td>
                    <td>{!! $purchase_order->remarks !!}</td>
                    <td><div class="poStatus">{!! status($purchase_order->status_id) !!}</div></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<div class="d-flex gap-2">

<div class="" style="width:100%;">
    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                    <thead>
                        <tr>
                            <th style="max-width: 17px; width: 17px;">
                                <div class="form-check form-check-success" style="max-width: 17px; width: 17px;">
                                    <input class="form-check-input" type="checkbox" id="selectAllProcess">
                                </div>
                            </th>
                            <th style="width:12px">Sr</th>
                            <th>MFG/MKDT</th>
                            <th>Item Name</th>
                            <th>Item Size</th>
                            {{-- <th>Last Date</th> --}}
                            <th>Quantity</th>
                            <th>Rate(₹)</th>
                            <th>Coating</th>
                            <th>Colour</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            @can(['edit_purchase_order','delete_purchase_order','read_purchase_order'])
                                <th>Action</th>
                            @endcan
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
                'url': '{{ route('admin.'.request()->segment(2).'.approval', $purchase_order->id) }}',
                'data': function(d) {
                    d._token = '{{ csrf_token() }}';
                    d._method = 'PATCH';
                    d.user = $('#tableUser').val();
                    d.status = $('#tableStatus').val();
                }

            },
            "columns": [
                { "data": "checkbox" },
                { "data": "sn" },
                { "data": "mfg_mkdt_by"},
                { "data": "item_name"},
                { "data": "item_size"},
                //{ "data": "last_date"},
                { "data": "quantity"},
                { "data": "rate"},
                { "data": "coating"},
                { "data": "colour"},
                { "data": "remarks"},
                { "data": "status"},
                {
    data: "action",
    render: function (data, type, row) {
        if (type === 'display') {

            let btn = `
            <div class="dropdown d-inline-block">
                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown">
                    <i class="align-middle ri-more-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">`;

            @can(['edit_purchase_order','delete_purchase_order','read_purchase_order'])

                @can('read_purchase_order')
                    let coaUrl = "{{ route('admin.purchase-order.show.coa', ':id') }}".replace(':id', row['id']);
                    btn += `
                    <li>
                        <a class="dropdown-item" href="${coaUrl}">
                            <i class="align-bottom ri-eye-fill me-2 text-muted"></i> View COA
                        </a>
                    </li>`;
                @endcan

                @can('delete_purchase_order')
                    let canItemUrl = "{{ route('admin.purchase-order.cancel.item') }}";

                        btn += `
                        <li>
                            <button type="button"
                                class="dropdown-item remove-item-btn"
                                onclick="updateData(
                                    '${canItemUrl}',
                                    { id: ${row['id']} },
                                    false,
                                    'PO Item Cancel',
                                    'Do you want to cancel this PO item?'
                                )">
                                <i class="bx bx-x-circle align-center me-2 text-muted"></i> Cancel
                            </button>
                        </li>`;


                    let deleteItemUrl = "{{ route('admin.purchase-order.destroy.item', ':id') }}".replace(':id', row['id']);
                    btn += `
                        <li>
                            <button type="button"
                                onclick="deleteModel(\'${deleteItemUrl}\')" 
                                class="dropdown-item remove-item-btn deleteItem"
                                data-url="${deleteItemUrl}">
                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                            </button>
                        </li>`;

                @endcan


            @endcan

            btn += `</ul></div>`;
            return btn;
        }
        return '';
    }
},
                { "data": "id", "visible": false },
            ],

            "createdRow": function(row, data, dataIndex) {
                $(row).addClass('data-row');
                $(row).attr('data-row', JSON.stringify(data));
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
            const restrictedStatuses = [1, 8, 6, 20];

            if (!restrictedStatuses.includes(statusId)) {
                $('#contextEdit')
                    .addClass('disabled')
                    .css('pointer-events', 'none')
                    .css('opacity', 0.5);
            } else {
                $('#contextEdit')
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

            if (this.id === 'contextCreate') {
                var createUrl = $(this).attr('data-url');
                window.location.href = createUrl;
            }

            if (rowData) {
                if (this.id === 'contextEdit') {
                    var editUrl ='/admin/purchase-order/item/' + rowData.id + '/edit';
                    $(this).attr('data-url', editUrl); 
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


    });



</script>


<script type="text/javascript">
    $(function () {
        $('body').on('click', '#quantityStatusAll', function () {
            const checked = $(this).is(':checked');
            $('.quantityStatus:enabled').prop('checked', checked);
            sendSelectedIds();
        });

        $('body').on('click', '.quantityStatus', function () {
            const all = $('.quantityStatus:enabled').length;
            const checkedCount = $('.quantityStatus:enabled:checked').length;
            $('#quantityStatusAll').prop('checked', all > 0 && all === checkedCount);
            sendSelectedIds();
        });

        function getSelectedQuantity() {
            return $('.quantityStatus:checked').map(function () {
                return $(this).val();
            }).get();
        }

        function sendSelectedIds() {
            $.ajax({
                url: '{{ route('admin.purchase-order.update.item.quantity') }}?po_id={{ $purchase_order->id }}',
                type: "POST",
                data: {
                    ids: getSelectedQuantity(),
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
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
                        if (response.status && response.status.status_badge) {
                            $('#POTable .poStatus').html(response.status.status_badge);
                        }
                    }
                },
                error: function () {
                    Toastify({
                        text: "Something went wrong while updating quantity.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "bg-warning",
                    }).showToast();
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(function () {
        $('body').on('click', '#rateStatusAll', function () {
            const checked = $(this).is(':checked');
            $('.rateStatus:enabled').prop('checked', checked);
            sendRateSelectedIds();
        });

        $('body').on('click', '.rateStatus', function () {
            const allEnabled = $('.rateStatus:enabled').length;
            const checkedCount = $('.rateStatus:enabled:checked').length;
            $('#rateStatusAll').prop('checked', allEnabled > 0 && allEnabled === checkedCount);
            sendRateSelectedIds();
        });

        function getSelectedRate() {
            return $('.rateStatus:checked').map(function () {
                return $(this).val();
            }).get();
        }

        function sendRateSelectedIds() {
            $.ajax({
                url: '{{ route('admin.purchase-order.update.item.rate') }}?po_id={{ $purchase_order->id }}',
                type: "POST",
                data: {
                    ids: getSelectedRate(),
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
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
                        if (response.status && response.status.status_badge) {
                            $('#POTable .poStatus').html(response.status.status_badge);
                        }
                    }
                },
                error: function () {
                    Toastify({
                        text: "Something went wrong while updating rates.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "bg-warning",
                    }).showToast();
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(function () {
        $('body').on('click', '#selectAllProcess', function () {
            const checked = $(this).is(':checked');
            $('.assignProcess:enabled').prop('checked', checked);
            sendAssignSelectedIds();
        });

        $('body').on('click', '.assignProcess', function () {
            const all = $('.assignProcess:enabled').length;
            const checked = $('.assignProcess:enabled:checked').length;
            $('#selectAllProcess').prop('checked', all > 0 && all === checked);
            sendAssignSelectedIds();
        });

        function getSelectedProcess() {
            return $('.assignProcess:checked').map(function () {
                return $(this).val();
            }).get();
        }

        function sendAssignSelectedIds() {
            $.ajax({
                url: '{{ route('admin.purchase-order.update.item.assign.order-sheet') }}?po_id={{ $purchase_order->id }}',
                type: "POST",
                data: {
                    ids: getSelectedProcess(),
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
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
                        if (response.status && response.status.status_badge) {
                            $('#POTable .poStatus').html(response.status.status_badge);
                        }
                    }
                },
                error: function () {
                    Toastify({
                        text: "Something went wrong while assigning process.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "bg-warning",
                    }).showToast();
                }
            });
        }
    });
</script>

@endpush
