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


   {{--  <li>
        <a class="dropdown-item create" model-size="modal-md" data-url="{{route('admin.purchase-order.export.form')}}" data-title="Export {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextExport"><i class="mdi mdi-export-variant me-2"></i> Export</a>
    </li> --}}

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
                            <div class="m-0 form-group{{ $errors->has('filterStatus') ? ' has-error' : '' }}">
                                {{ html()->select('filterStatus', App\Models\Status::pluck('name', 'id'))->class('form-control js-choice-search onChange')->placeholder('Status') }}
                                <small class="text-danger">{{ $errors->first('filterStatus') }}</small>
                            </div>
                        </div>


                    </div>
                </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle datatable border-secondary table-bordered nowrap" style="width:100%">

                        <thead>
                            <tr>
                                <th style="width:20px">Sr</th>
                                <th>PO From</th>
                                <th>MFG/MKDT</th>
                                <th>PO Date</th>
                                <th>Item</th>
                                <th>Colour</th>
                                <th>Paper</th>
                                <th>Quantity</th>
                                <th>Coatings</th>
                                <th>Emb</th>
                                <th>Leaf</th>
                                <th>B.P</th>
                                <th>Braille</th>
                                <th>Remarks</th>
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
                    d.mfg_by    = $('#filterMFGBY').val();
                    d.mkdt_by  = $('#filterMKDTBY').val();
                    d.client = $('#filterClient').val();
                    d.item_name = $('#filterItemName').val();
                    d.item_size = $('#filterItemSize').val();
                    d.status    = $('#filterStatus').val();
                }

            },
            "columns": [
                { "data": "sn" },
                { "data": "po_from"},
                { "data": "mfg_mkdt"},
                { "data": "po_date"},
                { "data": "item"},
                { "data": "color"},
                { "data": "paper"},
                { "data": "quantity"},
                { "data": "coating"},
                { "data": "embossing"},
                { "data": "leafing"},
                { "data": "back_print"},
                { "data": "braille"},
                { "data": "remarks"},
                { "data": "status"},
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

        getClient('#filterMFGBY', false, 'Choose MFG By');
        getClient('#filterMKDTBY', false, 'Choose MKDT By');
        getClient('#filterClient', false, 'Choose Client');
    });



</script>



@endpush
