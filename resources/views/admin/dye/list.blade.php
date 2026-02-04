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

<ul id="contextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_dye')
    <li>
        <a class="dropdown-item create" bg-color="" model-size="modal-xl" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.'.request()->segment(2).'.create')}}" href="javascript:void(0)" id="contextCreate">
            <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
        </li>

        @endcan
        @can('edit_dye')
        <li>
            <a class="dropdown-item editData" model-size="modal-xl" data-title="Edit {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" href="javascript:void(0)" id="contextEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
        </li>

        @endcan
        @can('read_dye')
            <li><a class="dropdown-item" href="#" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>

            <li>
                <a class="dropdown-item create" bg-color="#f3f3f9" data-title="Import Clients" data-url="{{route('admin.dye.import.create')}}" model-size="modal-normal" href="javascript:void(0)" id="contextCreate">
                    <i class="align-middle ri-upload-cloud-2-line label-icon fs-16 me-2"></i> Import</a>
            </li>

        @endcan

        @can('delete_dye')
        <div class="dropdown-divider m-0"></div>
        
        <li><a class="dropdown-item" href="javascript:void(0)" id="contextDelete"><i class="ri-delete-bin-fill me-2"></i> Delete</a></li>
        @endcan
    </ul>


    <ul id="dyeLockTypeContextMenu" class="p-0 dropdown-menu dropdownmenu-secondary" style="min-width:200px; display:none; position:absolute; z-index:10000;">
        @can('add_dye_lock_type')
        <li>
            <a model-size="modal-normal" class="dropdown-item" href="javascript:void(0)" id="dyeLockTypeCreate">
                <i class="bx bx-plus me-2"></i> Add New
            </a>
        </li>
        @endcan

        @can('edit_dye_lock_type')
        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="dyeLockTypeEdit">
                <i class="ri-pencil-fill me-2"></i> Edit
            </a>
        </li>
        @endcan

        @can('delete_dye_lock_type')
        <li><hr class="dropdown-divider m-0"></li> <!-- Correct way to add divider -->

        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="dyeLockTypeDelete">
                <i class="ri-delete-bin-fill me-2"></i> Delete
            </a>
        </li>
        @endcan
    </ul>






    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Die</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->


<div class="row">
        <div class="col-lg-3 mb-3">

            <div class="mb-3 accordion accordion-flush filter-accordion">
                <div class="accordion-item">
                    <div class="w-100 align-items-center d-flex justify-content-between accordion-header border border-dashed border-top-0 border-end-0 border-start-0 mb-2" id="flush-headingBrands">
                        <div class="w-100 justify-content-between accordion-button bg-transparent shadow-none">
                            <span class="w-75 text-muted text-uppercase fs-12 fw-medium">Lock Type</span>

                            @can('add_dye_lock_type')
                            @if(App\Models\DyeLockType::count() == 0)
                                <a href="javascript:void(0);" class="text-end addRootDyeLockType text-decoration-underline fw-normal">Add New</a>
                            @endif
                            @endcan
                        </div>

                    </div>
                    <div class="accordion-collapse collapse show" aria-labelledby="flush-dyeLockType" data-simplebar data-simplebar-auto-hide="false" data-simplebar-track="success" style="height: 200px;">
                        <div class="tree m-0 ps-3 pb-3 refereshJSTree" id="dyeLockType">

                        </div>
                        <input type="hidden" id="dyeLockTypeValue" value="">
                    </div>
                </div>
            </div>

        </div>

   <div class="col-lg-9 mb-9">
            <div class="card">

                <div class="card-body border border-dashed border-end-0 border-start-0">




                    <div class="row g-3">

                        <div class="col-xxl-4 col-sm-6">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_search') ? ' has-error' : '' }}">
                                    {{ html()->search('filter_search')->class('form-control onKeyup')->id('filterSearch')->placeholder('Search Dye Number, Dye size, Remarks') }}
                                    <small class="text-danger">{{ $errors->first('filter_search') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-6">
                            <div class="m-0 form-group{{ $errors->has('filter_type') ? ' has-error' : '' }}">
                                {{ html()->select('filter_type', ['Separate' => 'Separate', 'Mix' => 'Mix'])->id('filterType')->class('form-control js-choice onChange')->placeholder('Type') }}
                                <small class="text-danger">{{ $errors->first('filter_type') }}</small>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-6">
                            <div class="m-0 form-group{{ $errors->has('filter_dye_type') ? ' has-error' : '' }}">
                                {{ html()->select('filter_dye_type', ['Automatic' => 'Automatic', 'Manual' => 'Manual'])->id('filterDyeType')->class('form-control js-choice onChange')->placeholder('Dye Type') }}
                                <small class="text-danger">{{ $errors->first('filter_dye_type') }}</small>
                            </div>
                        </div>


                        <div class="col-xxl-2 col-sm-6">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_dye_number') ? ' has-error' : '' }}">
                                    {{ html()->text('filter_dye_number')->class('form-control onKeyup')->id('filterDyeNumber')->placeholder('Dye Number') }}
                                    <small class="text-danger">{{ $errors->first('filter_dye_number') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                        <div class="col-xxl-2 col-sm-6">
                            <div class="search-box">
                                <div class="m-0 form-group{{ $errors->has('filter_sheet_size') ? ' has-error' : '' }}">
                                    {{ html()->text('filter_sheet_size')->class('form-control onKeyup')->id('filterSheetSize')->placeholder('Sheet Size')}}
                                    <small class="text-danger">{{ $errors->first('filter_sheet_size') }}</small>
                                </div>
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                            <thead>
                                <tr>
                                    <th style="width:12px">Sr</th>
                                    <th>Die Number</th>
                                    <th>Type</th>
                                    <th>Sheet Size</th>
                                    <th>Die Type</th>
                                    <th>Details</th>
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
                        d.search = $('#filterSearch').val();
                        d.type = $('#filterType').val();
                        d.dye_type = $('#filterDyeType').val();
                        d.dye_number = $('#filterDyeNumber').val();
                        d.sheet_size = $('#filterSheetSize').val();
                        d.lock_type = $('#dyeLockTypeValue').val();
                    }

                },
                "columns": [
                    { "data": "sn" },
                    { "data": "dye_number"},
                    { "data": "type"},
                    { "data": "sheet_size"},
                    { "data": "dye_type"},
                    { "data": "dye_details"},
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

            $('#dyeLockType').on('changed.jstree', function (e, data) {
                var selectedIds = data.selected;
                $('#dyeLockTypeValue').val(selectedIds.join(','));
                table2.draw('page');
            });
            jQuery("#dyeLockType").jstree("select_node", "#3");




            $('#datatable tbody').on('contextmenu', 'tr', function(e) {
                e.preventDefault();

                var data = table2.row(this).data();

                $('#contextMenu')
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
                $('#contextMenu').data('rowData', rowData);

                
                $('#contextMenu').css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px',
                    display: 'block'
                });

                if (rowData.status_id == 3) {
                    $('#contextEdit').closest('li').hide();
                } else {
                    $('#contextEdit').closest('li').show();
                } 

              {{-- if (rollId != 1 && rollId != 2 && rollId != 11) {
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
                $('#contextMenu').hide();
            });

            
            $('#contextMenu').on('click', 'a', function(e) {
                e.preventDefault();
                var rowData = $('#contextMenu').data('rowData');

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

                $('#contextMenu').hide();
            });


            $(window).on('scroll resize', function() {
                $('#contextMenu').hide();
            });

            $(document).on('keydown', function(e) {
                if (e.key === "Escape") {
                    $('#contextMenu').hide();
                }
            });

            
        });

    </script>



    <script>
        $(function () {
            let selecteddyeLockTypeId = null;
            let touchTimer = null;

        
            $('#dyeLockType').jstree({
                core: {
                    themes: { responsive: false },
                    check_callback: true,
                    data: {
                        url: '{{ route('admin.'.request()->segment(2).'.index') }}?type=dye_lock_type',
                    }
                },
                types: {
                    "#": {
                        max_depth: 2,
                        valid_children: ["default"]
                    },
                    "default": {
                        icon: "bx bxs-folder text-primary fs-20"
                    },
                    "user": {
                        icon: "bx bxs-user text-warning fs-16"
                    }
                },
                state: { key: "coatingTreeState" },
                plugins: ["types"]
            });



    
            $('#dyeLockType')
            .on('open_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder-opened");
            })
            .on('close_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder");
            });

    
            function showContextMenu(x, y) {
                $('#dyeLockTypeContextMenu').css({
                    display: 'block',
                    left: x + 'px',
                    top: y + 'px'
                });
            }

    
            $('#dyeLockType').on('contextmenu', '.jstree-anchor', function (e) {
                e.preventDefault();
                selecteddyeLockTypeId = $(this).closest('li').attr('id');
                showContextMenu(e.pageX, e.pageY);
            });

    
            $('#dyeLockType').on('touchstart', '.jstree-anchor', function (e) {
                const target = this;
                const touch = e.originalEvent.touches[0];

                touchTimer = setTimeout(() => {
                    selecteddyeLockTypeId = $(target).closest('li').attr('id');
                    showContextMenu(touch.pageX, touch.pageY);
        }, 700); 
            });

            $('#dyeLockType').on('touchend touchmove', '.jstree-anchor', function () {
        clearTimeout(touchTimer); 
    });

    
            $(document).on('click touchstart', function (e) {
                if (!$(e.target).closest('#dyeLockTypeContextMenu').length) {
                    $('#dyeLockTypeContextMenu').hide();
                }
            });

    
            @can('add_dye_lock_type')
            $(document).on('click', '#dyeLockTypeCreate', function () {
                $('#dyeLockTypeContextMenu').hide();
                openModal('/admin/dye-lock-type/create', 'Add Lock Type');
            });

            $(document).on('click', '.addRootDyeLockType', function () {
                openModal('/admin/dye-lock-type/create', 'Add Lock Type');
            });
            @endcan

            @can('edit_dye_lock_type')
            $(document).on('click', '#dyeLockTypeEdit', function () {
                $('#dyeLockTypeContextMenu').hide();
                openModal('/admin/dye-lock-type/' + selecteddyeLockTypeId + '/edit', 'Edit Lock Type');
            });
            @endcan

            @can('delete_dye_lock_type')
            $(document).on('click', '#dyeLockTypeDelete', function () {
                $('#dyeLockTypeContextMenu').hide();
                deleteModel('/admin/dye-lock-type/' + selecteddyeLockTypeId + '/delete');
            });
            @endcan

    
            function openModal(url, title) {
                $('.editData').remove();
                $('<button>', {
                    type: 'button',
                    'data-url': url,
                    'data-title': title,
                    'model-size': 'modal-md',
                    class: 'editData',
                    style: 'display:none;'
                }).appendTo('body').trigger('click');
            }
        });
    </script>

@endpush

