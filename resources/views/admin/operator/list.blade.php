@extends('admin.layouts.master')
@push('links')
<style type="text/css">
    
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_operator')
        <li>
            <a class="dropdown-item create" model-size="modal-lg" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.operator.create')}}" href="javascript:void(0)" id="contextCreate">
                <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
        </li>

    @endcan
    @can('edit_operator')
        <li>
            <a class="dropdown-item editData" model-size="modal-lg" data-title="Edit {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
        </li>
    @endcan

    @can('delete_operator')
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
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                            <thead>
                                
                            <tr>
                                <th>Si</th>
                                <th>Name</th>
                                <th>Module</th>
                                <th>Login Username</th>
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
        }

        },
        "columns": [
            { "data": "sn" },
            { "data": "name" },
            { "data": "module" },
            { "data": "login" },
            { "data": "status" }
        ]

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
            $('#datatable').on('contextmenu', function(e) {
                if (!$(e.target).closest('tr').length || table2.data().count() === 0) {
                    e.preventDefault();

                    $('#customContextMenu')
                        .css({
                            top: e.pageY + 'px',
                            left: e.pageX + 'px',
                            display: 'block'
                        })
                        .data('rowData', null); // No data
                }
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
                    $(this).attr('data-url', createUrl);
                }

                if (rowData) {
                    if (this.id === 'contextEdit') {
                        var editUrl = window.location.href + '/' + rowData.id + '/edit';
                        $(this).attr('data-url', editUrl); 
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
                    }, touchDuration);
                }
            }).on('touchend touchmove touchcancel', function() {
                clearTimeout(touchTimer);
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
