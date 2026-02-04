@extends('admin.layouts.master')
@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }
    .dtfh-floatingparent.dtfh-floatingparenthead {
        top: 110px !important;      /* space below your fixed site header */
        height: 52px !important;   
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        /* z-index: 105 !important; */
    }

</style>
@endpush




@section('main')
@php
$poId = request()->query('po_id');
@endphp

<ul id="foilContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-primary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_item')

    @can('add_purchase_order')
    <li>
        <a class="dropdown-item editData" bg-color="" model-size="modal-xl" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}"  href="javascript:void(0)" id="contextAddToPO">
            <i class="bx bx-plus-circle me-2"></i> Add To PO</a>
        </li>
        @endcan

        <li>
            <a class="dropdown-item create" bg-color="" model-size="modal-xl" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.'.request()->segment(2).'.create')}}" href="javascript:void(0)" id="contextCreate">
                <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
            </li>

        {{-- <li>
                <a class="dropdown-item create" bg-color="#f3f3f9" data-title="Item Import" data-url="{{route('admin.'.request()->segment(2).'.import.create')}}" model-size="modal-normal" href="javascript:void(0)" id="contextCreate">
                    <i class="align-middle ri-upload-cloud-2-line label-icon fs-16 me-2"></i> Import</a>
                </li> --}}

                @endcan


                @can('edit_item')
                <li>
                    <a class="dropdown-item editData" model-size="modal-xl" data-title="Edit {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
                </li>

                @endcan


                @can('read_item')
                <li><a class="dropdown-item" href="#" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>
                @endcan



                @can('delete_item')
                <div class="dropdown-divider m-0"></div>

                <li><a class="dropdown-item" href="javascript:void(0)" id="contextDelete"><i class="ri-delete-bin-fill me-2"></i> Delete</a></li>
                @endcan
            </ul>





            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>
                        @if($poId)
                        <a href="{{route('admin.purchase-order.add.more.item', $poId)}}" class="btn btn-sm btn-primary create">Create PO</a>
                        @else
                        <a href="javascript:void(0);" bg-color="" model-size="modal-xl" data-title="Generate PO" data-url="{{route('admin.'.request()->segment(2).'.generate.po')}}" class="btn btn-sm btn-primary create" id="createPO">Create PO</a>
                        @endif
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
                                            <th style="width:12px">Sr</th>
                                            @can('mfg_mkdt_item')
                                            <th>MFG BY/MKDT By</th>
                                            @else
                                            <th>Client</th>
                                            @endcan
                                            <th>Item Name</th>
                                            <th>Last Date</th>



                                            <th style="width:80px;">Colour</th>

                                            <th>Product Type</th>

                                            <th>GSM</th>
                                            <th>Die No.</th>

                                            <th style="width:60px;!important">Set No.</th>

                                            <th>Coating</th>
                                            <th>Oth Coating</th>

                                            <th>Emb</th>
                                            <th>Leaf</th>
                                            <th>B.P</th>

                                            <th>Brl</th>
                                            <th>Rate</th>

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

            <script type="text/javascript">
                const rollId = {{auth('admin')->user()->role_id}}
                $(document).ready(function(){
                    var table2 = $('#datatable').DataTable({
                        "drawCallback": function(settings) {
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
                      "lengthMenu": [50, 100,200],

                      scrollX: true,
                    //scrollY: '60vh',
                      scrollCollapse: true,

                      fixedHeader: {
                        header: true,
                        headerOffset: $('.page-title-box').outerHeight() || 70
                    },

                    //fixedColumns: {
                        //leftColumns: 4
                   // },

                    'ajax': {
                        'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
                        'data': function(d) {
                            d._token = '{{ csrf_token() }}';
                            d._method = 'PATCH';
                            d.mkdt_by = $('#filterMKDTBY').val();
                            d.mfg_by = $('#filterMFGBY').val();
                            d.client = $('#filterClient').val();
                            d.item_name = $('#filterItemName').val();
                            d.item_size = $('#filterItemSize').val();
                            d.set_no = $('#filterItemSetNo').val();
                            d.status = $('#tableStatus').val();
                            d.length = $('#filterLength').val();
                        }

                    },
                    "columns": [
                        { "data": "sn" },
                        @can('mfg_mkdt_item')
                        { "data": "mfg_mkdt_by"},
                        @else
                        { "data": "client"},
                        @endcan
                        { "data": "item_name"},
                        { "data": "last_date"},


                        { "data": "colour"},

                        { "data": "product_type"},

                        { "data": "gsm"},
                        { "data": "dye_no"},

                        { "data": "set_no"},
                        { "data": "coating"},
                        { "data": "other_coating"},

                        { "data": "embossing"},
                        { "data": "leafing"},
                        { "data": "back_print"},

                        { "data": "braille"},
                        { "data": "rate"},

                        { "data": "status"},
                        { "data": "id", "visible": false },
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).addClass('data-row');
                        $(row).attr('data-row', JSON.stringify(data));

                        if (data.row_class) {
                            $(row).addClass(data.row_class);
                        }
                        if(data.added_po === 1){
                            $(row).addClass('bg-warning-subtle');
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

                        $('#foilContextMenu')
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
                        $('#foilContextMenu').data('rowData', rowData);


                        $('#foilContextMenu').css({
                            top: e.pageY + 'px',
                            left: e.pageX + 'px',
                            display: 'block'
                        });


                {{-- if (rowData.status_id == 3) {
                    $('#contextEdit').closest('li').hide();
                } else {
                    $('#contextEdit').closest('li').show();
                } --}}

                {{-- if (rollId != 1 && rollId != 2) {
                    const isLatest = rowData?.is_latest ?? false;
                    const statusId = rowData?.status_id;
                    const restrictedStatuses = [3, 5, 19];

                    if (!isLatest) {
                        $('#contextEdit, #contextDelete')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.5);
                    } else {
                        $('#contextEdit, #contextDelete')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                    }

                    if (restrictedStatuses.includes(statusId)) {
                        $('#contextfollowUp')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.5);
                    } else {
                        $('#contextfollowUp')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                    }
                } else {

                    $('#contextEdit, #contextDelete, #contextfollowUp')
                    .removeClass('disabled')
                    .css('pointer-events', '')
                    .css('opacity', '');
                } --}}
            });



                    $(document).click(function() {
                        $('#foilContextMenu').hide();
                    });


                    $('#foilContextMenu').on('click', 'a', function(e) {
                        e.preventDefault();
                        var rowData = $('#foilContextMenu').data('rowData');

                        if (this.id === 'contextCreate') {
                            var createUrl = $(this).attr('data-url');
                            $(this).attr('data-url', createUrl);
                        }

                        if (rowData) {
                            if (this.id === 'contextEdit') {
                                if(rowData.status_id == 3){
                                    $(this).hide();
                                }
                                var editUrl = window.location.href + '/' + rowData.id + '/edit';
                                $(this).attr('data-url', editUrl); 
                            }

                            if (this.id === 'contextAddToPO') {
                                if(rowData.status_id == 15){
                                    $(this).hide();
                                }
                                var editUrl = "{{ route('admin.item.add.to.po', ':id') }}";
                                editUrl = editUrl.replace(':id', rowData.id);
                                $(this).attr('data-url', editUrl); 
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

                            if (this.id === 'contextfollowUp') {
                                window.location.href = `{{ request()->url() }}/${rowData.id}/followups`;
                            }

                            if (this.id === 'contextDelete') {
                                var deleteUrl = window.location.href + '/' + rowData.id + '/delete';
                                $(this).attr('data-url', deleteUrl); 
                                deleteModel(deleteUrl);
                            }
                        }

                        $('#foilContextMenu').hide();
                    });


                    $(window).on('scroll resize', function() {
                        $('#foilContextMenu').hide();
                    });

                    $(document).on('keydown', function(e) {
                        if (e.key === "Escape") {
                            $('#foilContextMenu').hide();
                        }
                    });

                    getClient('#filterMFGBY', false, 'Choose MFG By');
                    getClient('#filterMKDTBY', false, 'Choose MKDT By');
                    getClient('#filterClient', false, 'Choose Client');

                });




            </script>

            @endpush

