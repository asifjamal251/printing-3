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

<ul id="cylinderContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_cylinder_inward')
    <li>
        <a class="dropdown-item create" bg-color="" model-size="modal-xl" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.'.request()->segment(2).'.create')}}" href="javascript:void(0)" id="cylinderCreate">
            <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
        </li>

        @endcan
        @can('edit_cylinder_inward')
        <li>
            <a class="dropdown-item editData" model-size="modal-lg" data-title="Edit {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="cylinderEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
        </li>

        @endcan
        @can('read_cylinder_inward')
            <li><a class="dropdown-item" href="#" id="cylinderView"><i class="ri-eye-fill me-2"></i> View</a></li>
        @endcan

        @can('delete_cylinder_inward')
        <div class="dropdown-divider m-0"></div>
        
        <li><a class="dropdown-item" href="javascript:void(0)" id="cylinderDelete"><i class="ri-delete-bin-fill me-2"></i> Delete</a></li>
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




    <div class="d-flex gap-2">



        <div class="" style="width:100%;">
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                            <thead>
                                <tr>
                                    <th style="width:12px">Sr</th>
                                    <th>Client</th>
                                    <th>Vendor</th>
                                    <th>Bill No.</th>
                                    <th>Bill Date</th>
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
                        d.user = $('#tableUser').val();
                        d.status = $('#tableStatus').val();
                    }

                },
                "columns": [
                    { "data": "sn" },
                    { "data": "client"},
                    { "data": "vendor"},
                    { "data": "bill_no"},
                    { "data": "bill_date"},
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

                $('#cylinderContextMenu')
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
                $('#cylinderContextMenu').data('rowData', rowData);

                
                $('#cylinderContextMenu').css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px',
                    display: 'block'
                });

                {{-- if (rowData.status_id == 3) {
                    $('#contextEdit').closest('li').hide();
                } else {
                    $('#contextEdit').closest('li').show();
                } --}}

                if (rollId != 1 && rollId != 2) {
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
                }
            });



            $(document).click(function() {
                $('#cylinderContextMenu').hide();
            });

            
            $('#cylinderContextMenu').on('click', 'a', function(e) {
                e.preventDefault();
                var rowData = $('#cylinderContextMenu').data('rowData');

                if (this.id === 'cylinderCreate') {
                    var createUrl = $(this).attr('data-url');
                    $(this).attr('data-url', createUrl);
                }

                if (rowData) {
                    if (this.id === 'cylinderEdit') {
                        if(rowData.status_id == 3){
                            $(this).hide();
                        }
                        var editUrl = window.location.href + '/' + rowData.id + '/edit';
                        $(this).attr('data-url', editUrl); 
                    }

                    if (this.id === 'cylinderReceived') {
                        if(rowData.status_id == 3){
                            $(this).hide();
                        }
                        var editUrl = window.location.href + '/received/' + rowData.id;
                        $(this).attr('data-url', editUrl); 
                    }


                    if (this.id === 'cylinderView') {
                        var viewUrl = '{{ request()->url() }}/show/' + rowData.id;
                        $(this).attr('data-url', viewUrl); 
                        window.location.href = viewUrl;
                    }

                    if (this.id === 'cylinderfollowUp') {
                        window.location.href = `{{ request()->url() }}/${rowData.id}/followups`;
                    }

                    if (this.id === 'cylinderDelete') {
                        var deleteUrl = window.location.href + '/' + rowData.id + '/delete';
                        $(this).attr('data-url', deleteUrl); 
                        deleteModel(deleteUrl);
                    }
                }

                $('#cylinderContextMenu').hide();
            });


            $(window).on('scroll resize', function() {
                $('#cylinderContextMenu').hide();
            });

            $(document).on('keydown', function(e) {
                if (e.key === "Escape") {
                    $('#cylinderContextMenu').hide();
                }
            });

            
        });

    </script>




@endpush

