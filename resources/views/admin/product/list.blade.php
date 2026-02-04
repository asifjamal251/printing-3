@extends('admin.layouts.master')
@push('links')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />
    <style type="text/css">
        #categories {
            margin: 20px;
            max-width: 300px;
        }
        /*.choices__list--single{
            padding:0 !important;
        }
        .choices__inner{
            min-height: 25px !important;
        }
        .choices[data-type*="select-one"] .choices__input{
            padding:3px 5px !important;
        }*/

        /* Style for context menu */
       
    </style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">
    @can('add_product')
        <li>
            <a class="dropdown-item create" bg-color="#f3f3f9" data-title="Add New Product" data-url="{{route('admin.product.create')}}" href="javascript:void(0)" id="contextCreate">
                <i class="fs-16 bx bx-plus me-2"></i> Add New</a>
        </li>
    @endcan
    @can('edit_product')
        <li>
            <a class="dropdown-item editData" bg-color="#f3f3f9" data-title="Edit Product" href="javascript:void(0)" id="contextEdit"><i class="ri-pencil-fill me-2"></i> Edit</a>
        </li>
    @endcan
    @can('read_product')

        <li><a class="dropdown-item" href="#" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>

        <li>
            <a class="dropdown-item create" bg-color="#f3f3f9" data-title="Import Product" data-url="{{route('admin.product.import.create')}}" model-size="modal-normal" href="javascript:void(0)" id="contextImport">
                <i class="align-middle ri-upload-cloud-2-line label-icon fs-16 me-2"></i> Import</a>
        </li>

    @endcan
    @can('delete_product')
    <div class="dropdown-divider m-0"></div>
        <li><a class="dropdown-item" href="javascript:void(0)" id="contextDelete"><i class="ri-delete-bin-fill me-2"></i> Delete</a></li>
    @endcan
</ul>


<ul id="storeContextMenu" class="p-0 dropdown-menu dropdownmenu-secondary" style="min-width:200px; display:none; position:absolute; z-index:10000;">
        @can('add_store')
        <li>
            <a model-size="modal-normal" class="dropdown-item" href="javascript:void(0)" id="storeCreate">
                <i class="bx bx-plus me-2"></i> Add New
            </a>
        </li>
        @endcan

        @can('edit_store')
        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="storeEdit">
                <i class="ri-pencil-fill me-2"></i> Edit
            </a>
        </li>
        @endcan

        @can('delete_store')
        <li><hr class="dropdown-divider m-0"></li> <!-- Correct way to add divider -->

        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="storeDelete">
                <i class="ri-delete-bin-fill me-2"></i> Delete
            </a>
        </li>
        @endcan
</ul>




    <ul id="categoryContextMenu" class="p-0 dropdown-menu dropdownmenu-secondary" style="min-width:200px; display:none; position:absolute; z-index:10000;">
        @can('add_category')
        <li>
            <a model-size="modal-normal" class="dropdown-item" href="javascript:void(0)" id="categoryCreate">
                <i class="bx bx-plus me-2"></i> Add New
            </a>
        </li>
        @endcan

        @can('edit_category')
        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="categoryEdit">
                <i class="ri-pencil-fill me-2"></i> Edit
            </a>
        </li>
        @endcan

        @can('delete_category')
        <li><hr class="dropdown-divider m-0"></li> <!-- Correct way to add divider -->

        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="categoryDelete">
                <i class="ri-delete-bin-fill me-2"></i> Delete
            </a>
        </li>
        @endcan
    </ul>



    <ul id="productTypeContextMenu" class="p-0 dropdown-menu dropdownmenu-secondary" style="min-width:200px; display:none; position:absolute; z-index:10000;">
        @can('add_product_type')
        <li>
            <a model-size="modal-normal" class="dropdown-item" href="javascript:void(0)" id="productTypeCreate">
                <i class="bx bx-plus me-2"></i> Add New
            </a>
        </li>
        @endcan

        @can('edit_product_type')
        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="productTypeEdit">
                <i class="ri-pencil-fill me-2"></i> Edit
            </a>
        </li>
        @endcan

        @can('delete_product_type')
        <li><hr class="dropdown-divider m-0"></li> <!-- Correct way to add divider -->

        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="productTypeDelete">
                <i class="ri-delete-bin-fill me-2"></i> Delete
            </a>
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

    <div class="row">
        <div class="col-lg-3 mb-3">

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





            <div class="mb-3 accordion accordion-flush filter-accordion">
                <div class="accordion-item">
                    <div class="w-100 align-items-center d-flex justify-content-between accordion-header border border-dashed border-top-0 border-end-0 border-start-0 mb-2" id="flush-headingBrands">
                        <div class="w-100 justify-content-between accordion-button bg-transparent shadow-none">
                            <span class="w-75 text-muted text-uppercase fs-12 fw-medium">Product Type</span>

                            @can('add_product_type')
                            @if(App\Models\ProductType::count() == 0)
                                <a href="javascript:void(0);" class="text-end addRootProductType text-decoration-underline fw-normal">Add New</a>
                            @endif
                            @endcan
                        </div>

                    </div>
                    <div class="accordion-collapse collapse show" aria-labelledby="flush-productType" data-simplebar data-simplebar-auto-hide="false" data-simplebar-track="success" style="height: 200px;">
                        <div class="tree m-0 ps-3 pb-3 refereshJSTree" id="productType">

                        </div>
                        <input type="hidden" id="product_type" value="">
                    </div>
                </div>
            </div>



            <div class="mb-3 accordion accordion-flush filter-accordion">
                <div class="accordion-item">
                    <div class="w-100 align-items-center d-flex justify-content-between accordion-header border border-dashed border-top-0 border-end-0 border-start-0 mb-2" id="flush-headingBrands">
                        <div class="w-100 justify-content-between accordion-button bg-transparent shadow-none">
                            <span class="w-75 text-muted text-uppercase fs-12 fw-medium">Category</span>

                            @can('add_category')
                            @if(App\Models\Category::count() == 0)
                                <a href="javascript:void(0);" class="text-end addRootCategory text-decoration-underline fw-normal">Add New</a>
                            @endif
                            @endcan
                        </div>

                    </div>
                    <div class="accordion-collapse collapse show" aria-labelledby="flush-category" data-simplebar data-simplebar-auto-hide="false" data-simplebar-track="success" style="height: 200px;">
                        <div class="tree m-0 ps-3 pb-3 refereshJSTree" id="category">

                        </div>
                        <input type="hidden" id="category" value="">
                    </div>
                </div>
            </div>


             @can('browse_store')
            <div class="mb-3 accordion accordion-flush filter-accordion">
                <div class="accordion-item">
                    <div class="w-100 align-items-center d-flex justify-content-between accordion-header border border-dashed border-top-0 border-end-0 border-start-0 mb-2" id="flush-headingStore">
                        <div class="w-100 justify-content-between accordion-button bg-transparent shadow-none">
                            <span class="w-75 text-muted text-uppercase fs-12 fw-medium">Stores</span>

                            @can('add_store')
                            @if(App\Models\Store::count() == 0)
                                <a href="javascript:void(0);" class="text-end addRootStore text-decoration-underline fw-normal">Add New</a>
                            @endif
                            @endcan
                        </div>

                    </div>
                    <div class="accordion-collapse collapse show" aria-labelledby="flush-Store" data-simplebar data-simplebar-auto-hide="false" data-simplebar-track="success" style="height: 200px;">
                        <div class="tree m-0 ps-3 pb-3 refereshJSTree" id="store">

                        </div>
                        <input type="hidden" id="store" value="">
                    </div>
                </div>
            </div>
            @endcan





        </div>

        <div class="col-lg-9">
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable"
                            class="table align-middle datatable table-sm border-success table-bordered nowrap"
                            style="width:100%">
                            <thead class="gridjs-thead">
                                <tr>
                                    <th style="width:12px">Sr</th>
                                    <th>Product</th>
                                    <th>Product Type</th>
                                    <th>Quantity</th>
                                    <th>Total WT</th>
                                    <th>ID</th>
                                    
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->








    <div id="kt_docs_jstree_dragdrop"></div>
@endsection




@push('scripts')
 

    <script type="text/javascript">
      






        $(document).ready(function() {
            var table2 = $('#datatable').DataTable({
                "drawCallback": function( settings ) {
                    //lightbox.reload();
                },
                "ordering": false,
                "searchning": false,
                "processing": true,
                "serverSide": true,
                "lengthMenu": [25],
                'ajax': {
                    'url': '{{ route('admin.' . request()->segment(2) . '.index') }}',
                    'data': function(d) {
                        d._token = '{{ csrf_token() }}';
                        d._method = 'PATCH';
                        d.product_type = $('#product_type_id').val();
                        d.category = $('#category_id').val();
                        d.paper_type = $('#paper_type_id').val()
                        d.gsm = $('#tableGSM').val();
                        d.name = $('#tableNme').val();
                    }

                },
                "columns": [
                    { "data": "sn" },
                    { "data": "name"},
                    { "data": "product_type"},
                    { "data": "quantity"},
                    { "data": "total_weight"},
                    { "data": "id", "visible": false },
                ]

            });

            $('body').on('keyup', '.keyUp', function(){
                table2.draw('page');
            });

            $('body').on('mouseup', '.keyUp', function(e){
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


            

            $('#categories').on('click', 'li a', function() {
                var category_id = $(this).parent().attr('id');
                $('#category_id').val(category_id);
                setTimeout(function() {
                    table2.draw('page');
                }, 100);

            });
            jQuery("#categories").jstree("select_node", "#3");


            $('#papertypes').on('changed.jstree', function (e, data) {
                var selectedIds = data.selected;
                $('#paper_type_id').val(selectedIds.join(','));
                table2.draw('page');
            });
            jQuery("#papertypes").jstree("select_node", "#3");


            $('#productTypes').on('changed.jstree', function (e, data) {
                var selectedIds = data.selected;
                $('#product_type_id').val(selectedIds.join(','));
                table2.draw('page');
            });
            jQuery("#productTypes").jstree("select_node", "#3");



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

                if (this.id === 'contextImport') {
                    var createUrl = $(this).attr('data-url');
                    //window.location.href = createUrl;
                }

                if (rowData) {
                    if (this.id === 'contextEdit') {
                        var editUrl = window.location.href + '/' + rowData.id + '/edit';
                        $(this).attr('data-url', editUrl); 
                    }

                    if (this.id === 'contextView') {
                        var viewUrl = '{{ request()->url() }}/' + rowData.id;
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

    <script>
        $(function () {
            let selectedcategoryId = null;
            let touchTimer = null;

        
            $('#category').jstree({
                core: {
                    themes: { responsive: false },
                    check_callback: true,
                    data: {
                        url: '{{ route('admin.'.request()->segment(2).'.index') }}?type=category',
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



    
            $('#category')
            .on('open_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder-opened");
            })
            .on('close_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder");
            });

    
            function showContextMenu(x, y) {
                $('#categoryContextMenu').css({
                    display: 'block',
                    left: x + 'px',
                    top: y + 'px'
                });
            }

    
            $('#category').on('contextmenu', '.jstree-anchor', function (e) {
                e.preventDefault();
                selectedcategoryId = $(this).closest('li').attr('id');
                showContextMenu(e.pageX, e.pageY);
            });

    
            $('#category').on('touchstart', '.jstree-anchor', function (e) {
                const target = this;
                const touch = e.originalEvent.touches[0];

                touchTimer = setTimeout(() => {
                    selectedcategoryId = $(target).closest('li').attr('id');
                    showContextMenu(touch.pageX, touch.pageY);
        }, 700); 
            });

            $('#category').on('touchend touchmove', '.jstree-anchor', function () {
        clearTimeout(touchTimer); 
    });

    
            $(document).on('click touchstart', function (e) {
                if (!$(e.target).closest('#categoryContextMenu').length) {
                    $('#categoryContextMenu').hide();
                }
            });

    
            @can('add_category')
            $(document).on('click', '#categoryCreate', function () {
                $('#categoryContextMenu').hide();
                openModal('/admin/category/create', 'Add Category');
            });

            $(document).on('click', '.addRootCategory', function () {
                openModal('/admin/category/create', 'Add Category');
            });
            @endcan

            @can('edit_category')
            $(document).on('click', '#categoryEdit', function () {
                $('#categoryContextMenu').hide();
                openModal('/admin/category/' + selectedcategoryId + '/edit', 'Edit Category');
            });
            @endcan

            @can('delete_category')
            $(document).on('click', '#categoryDelete', function () {
                $('#categoryContextMenu').hide();
                deleteModel('/admin/category/' + selectedcategoryId + '/delete');
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

<script>
        $(function () {
            let selectedproductTypeId = null;
            let touchTimer = null;

        
            $('#productType').jstree({
                core: {
                    themes: { responsive: false },
                    check_callback: true,
                    data: {
                        url: '{{ route('admin.'.request()->segment(2).'.index') }}?type=product_type',
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



    
            $('#productType')
            .on('open_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder-opened");
            })
            .on('close_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder");
            });

    
            function showContextMenu(x, y) {
                $('#productTypeContextMenu').css({
                    display: 'block',
                    left: x + 'px',
                    top: y + 'px'
                });
            }

    
            $('#productType').on('contextmenu', '.jstree-anchor', function (e) {
                e.preventDefault();
                selectedproductTypeId = $(this).closest('li').attr('id');
                showContextMenu(e.pageX, e.pageY);
            });

    
            $('#productType').on('touchstart', '.jstree-anchor', function (e) {
                const target = this;
                const touch = e.originalEvent.touches[0];

                touchTimer = setTimeout(() => {
                    selectedproductTypeId = $(target).closest('li').attr('id');
                    showContextMenu(touch.pageX, touch.pageY);
        }, 700); 
            });

            $('#productType').on('touchend touchmove', '.jstree-anchor', function () {
        clearTimeout(touchTimer); 
    });

    
            $(document).on('click touchstart', function (e) {
                if (!$(e.target).closest('#productTypeContextMenu').length) {
                    $('#productTypeContextMenu').hide();
                }
            });

    
            @can('add_product_type')
            $(document).on('click', '#productTypeCreate', function () {
                $('#productTypeContextMenu').hide();
                openModal('/admin/product-type/create', 'Add Product Type');
            });

            $(document).on('click', '.addRootProductType', function () {
                openModal('/admin/product-type/create', 'Add Product Type');
            });
            @endcan

            @can('edit_product_type')
            $(document).on('click', '#productTypeEdit', function () {
                $('#productTypeContextMenu').hide();
                openModal('/admin/product-type/' + selectedproductTypeId + '/edit', 'Edit Product Type');
            });
            @endcan

            @can('delete_product_type')
            $(document).on('click', '#productTypeDelete', function () {
                $('#productTypeContextMenu').hide();
                deleteModel('/admin/product-type/' + selectedproductTypeId + '/delete');
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

<script>
        $(function () {
            let selectedstoreId = null;
            let touchTimer = null;

        
            $('#store').jstree({
                core: {
                    themes: { responsive: false },
                    check_callback: true,
                    data: {
                        url: '{{ route('admin.'.request()->segment(2).'.index') }}?type=store',
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



    
            $('#store')
            .on('open_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder-opened");
            })
            .on('close_node.jstree', function (e, data) {
                data.instance.set_type(data.node, "folder");
            });

    
            function showContextMenu(x, y) {
                $('#storeContextMenu').css({
                    display: 'block',
                    left: x + 'px',
                    top: y + 'px'
                });
            }

    
            $('#store').on('contextmenu', '.jstree-anchor', function (e) {
                e.preventDefault();
                selectedstoreId = $(this).closest('li').attr('id');
                showContextMenu(e.pageX, e.pageY);
            });

    
            $('#store').on('touchstart', '.jstree-anchor', function (e) {
                const target = this;
                const touch = e.originalEvent.touches[0];

                touchTimer = setTimeout(() => {
                    selectedstoreId = $(target).closest('li').attr('id');
                    showContextMenu(touch.pageX, touch.pageY);
        }, 700); 
            });

            $('#store').on('touchend touchmove', '.jstree-anchor', function () {
        clearTimeout(touchTimer); 
    });

    
            $(document).on('click touchstart', function (e) {
                if (!$(e.target).closest('#storeContextMenu').length) {
                    $('#storeContextMenu').hide();
                }
            });

    
            @can('add_store')
            $(document).on('click', '#storeCreate', function () {
                $('#storeContextMenu').hide();
                openModal('/admin/store/create', 'Add store');
            });

            $(document).on('click', '.addRootStore', function () {
                openModal('/admin/store/create', 'Add store');
            });
            @endcan

            @can('edit_store')
            $(document).on('click', '#storeEdit', function () {
                $('#storeContextMenu').hide();
                openModal('/admin/store/' + selectedstoreId + '/edit', 'Edit store');
            });
            @endcan

            @can('delete_store')
            $(document).on('click', '#storeDelete', function () {
                $('#storeContextMenu').hide();
                deleteModel('/admin/store/' + selectedstoreId + '/delete');
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
