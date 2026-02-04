@extends('admin.layouts.master')
@push('links')
<style type="text/css">
    
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
{{--     @can('add_material_order')
        <li>
            <a class="dropdown-item" data-url="{{ route('admin.'.request()->segment(2).'.create') }}" href="javascript:void(0)" id="contextCreate">
                <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
        </li>
    @endcan --}}
    @can('edit_material_order')
        <li>
            <a class="dropdown-item" id="contextEdit" href="javascript:void(0)"><i class="ri-pencil-fill me-2"></i> Edit</a>
        </li>
    @endcan
    @can('read_material_order')
        <li><a class="dropdown-item" href="#" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>
    @endcan
    @can('delete_material_order')
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


<div id="notification">
                        
                    </div>

        <div class="row">

            <div class="col-lg-3">

                <div class="mb-3 accordion accordion-flush filter-accordion">
                    <div class="accordion-item">
                        
                        <div id="flush-collapseSearch" class="accordion-collapse collapse show"
                            aria-labelledby="flush-product-type">
                            <div class="accordion-body text-body p-2">
                                <div class="m-0 form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                                    {{ html()->search('search')->class('form-control keyUp')->id('tableNme')->placeholder('Search Name') }}
                                    <small class="text-danger">{{ $errors->first('search') }}</small>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>




            </div>


            <div class="col-lg-9">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table align-middle datatable table-sm border-success table-bordered nowrap" style="width:100%">

                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Receipt No.</th>
                                <th>Bill Date</th>
                                <th>Vendor</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Receipt By</th>
                                <th>Status</th>
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

<script type="text/javascript">
$(document).ready(function(){
    var table2 = $('#datatable').DataTable({
        "drawCallback": function(settings) {
            getCityAll('cityTable');
        },
        "ordering": false,
        "searchning": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [25],
        'ajax': {
        'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
        'data': function(d) {
            d._token = '{{ csrf_token() }}';
            d._method = 'PATCH';
            d.name = $('#tableName').val();
            d.email = $('#tableEmail').val();
            d.contavt_no = $('#tableContactNo').val();
            d.city = $('#cityTable').val();
            d.status = $('#status').val();
            d.gst = $('#tableGST').val();
        }

        },
        "columns": [
            { "data": "sn" },
            { "data": "receipt_no" },
            { "data": "date" },
            { "data": "vendor" },
            { "data": "items" },
            { "data": "total" },
            { "data": "receipt_by" },
            { "data": "status" }
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

            // Handle context menu when there's NO row (empty table)
            $(document).on('contextmenu', '.data-row', function(e) {
                e.preventDefault();

                var rowData = JSON.parse($(this).attr('data-row'));
                $('#customContextMenu').data('rowData', rowData);

                // Position the menu
                $('#customContextMenu').css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px',
                    display: 'block'
                });

                {{-- if (rowData.status_id == 3) {
                    $('#contextEdit').closest('li').hide();
                } else {
                    $('#contextEdit').closest('li').show();
                } --}}

                {{-- if (rowData.status_id == 3) {
                    $('#contextEdit')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.8);
                } else {
                    $('#contextEdit')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                } --}}

            });



            $(document).click(function() {
                $('#customContextMenu').hide();
            });

            // Menu actions
            $('#customContextMenu').on('click', 'a', function(e) {
                e.preventDefault();
                var rowData = $('#customContextMenu').data('rowData');


                if (this.id === 'contextCreate') {
                    var createUrl = $(this).attr('data-url');
                    window.location.href = createUrl;
                }

                if (rowData) {
                    if (this.id === 'contextEdit') {
                        var editUrl = window.location.href + '/' + rowData.id + '/edit';
                        console.log(editUrl)
                        window.location.href = editUrl;
                    }

                    if (this.id === 'contextView') {
                        var viewUrl = '{{ request()->url() }}/show/' + rowData.id;
                        $(this).attr('data-url', viewUrl); 
                        window.location.href = viewUrl;
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

            
});



    </script>












{{-- <script type="module">
            window.Echo.channel('posts')
                .listen('.create', (data) => {
                    //document.getElementById('datatable').DataTable().draw('page');
                    var table = new DataTable(document.getElementById('datatable'));
                    table.draw('page');

                    console.log('Order status updated: ', data);
                    var d1 = document.getElementById('notification');
                    d1.insertAdjacentHTML('beforeend', '<div class="alert alert-success alert-dismissible fade show"><span><i class="fa fa-circle-check"></i>  '+data.message+'</span></div>');
                });
    </script> --}}
@endpush
