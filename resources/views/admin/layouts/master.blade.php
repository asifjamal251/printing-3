<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="img-4" data-preloader="disable" data-theme="default" data-theme-colors="green" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">
<head>

    <meta charset="utf-8" />
    <title>{{get_app_setting('app_name')}} | {{Str::title(str_replace('-', ' ', request()->segment(2)))}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Themesbrand" name="author" />
    {{-- <link rel="manifest" href="/manifest.json"> --}}

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(get_app_setting('favicon')??'assets/admin/images/favicon.png')}}">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> --}}

    <link rel="stylesheet" href="{{asset('assets/admin/libs/flatpickr/flatpickr.min.css')}}">  
    @stack('links')
    <!-- Layout config Js -->
    <script src="{{asset('assets/admin/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->

    <link href="{{asset('assets/admin/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">



    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    
    <link href="{{asset('assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/admin/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin/libs/glightbox/css/glightbox.min.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

     @include('admin.layouts.header')
     <!-- ========== App Menu ========== -->
     @include('admin.layouts.aside')
     <!-- Left Sidebar End -->
     <!-- Vertical Overlay-->
     <div class="vertical-overlay"></div>

     <!-- ============================================================== -->
     <!-- Start right Content here -->
     <!-- ============================================================== -->
     <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @section('main')
                @show 
                <!-- End Page-content -->
            </div>
        </div>

        @include('admin.layouts.footer')
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

@yield('filter')

<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->


<div id="preloader">
    <div id="loaderstatus">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>


@include('admin.media.media-files')
@include('admin.layouts.shortcut')

<!-- Theme Settings -->

<div id="removeItemModal" class="modal fade zoomIn" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this data ?</p>
                    </div>
                    <input type="hidden" id="deleteURL" value="">
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger " id="deleteData">Yes, Delete It!</button>
                    <button type="button" class="btn btn-danger btn-load d-none" id="loadingBtn">
                        <span class="d-flex align-items-center">
                            <span class="spinner-grow flex-shrink-0" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <span class="flex-grow-1 ms-2">
                                Loading...
                            </span>
                        </span>
                    </button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<div class="modal fade zoomIn" id="dataSave" tabindex="-1" aria-labelledby="dataSaveLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-size">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="dataSaveLabel">Create File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFileBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="addForm">

            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JAVASCRIPT -->
<script src="{{asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{asset('assets/admin/libs/dropzone/dropzone-min.js')}}"></script>
<script src="{{asset('assets/admin/js/custom.js')}}"></script>
<script src="{{asset('assets/admin/js/media.js')}}"></script>
<script src="{{asset('assets/admin/js/crudAjax.js')}}"></script>
<script src="{{asset('assets/admin/libs/select2/js/select2.min.js')}}" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script src="{{asset('assets/admin/libs/glightbox/js/glightbox.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/admin/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/keymaster/1.6.1/keymaster.min.js" integrity="sha512-BCAhaaB0/bmrMBa8RVw/Cgqg5OOuQ+kZPWfRL7RlRb/LLfQMSuxDZ48TNxmwk3dFs+R4diG6yVsXKFNELe6trw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@if (Session::has('message'))
<script type="text/javascript">
    Toastify({
        text: "{{Session::get('message')}}",
        duration: 3000,
        close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                className: "{{Session::get('class')}}",

            }).showToast();
        </script>
        @endif
        <!-- App js -->

        <script type="text/javascript">
           const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    </script>
    


    {{-- <script src="{{asset('assets/admin/js/app.js')}}"></script> --}}
    <script type="text/javascript">




        function deleteAjax(){  
            var url = $('#deleteURL').val();
            $.ajax({
                url:url,
                method: 'post',
                data:{'_method':'DELETE','_token':'{{ csrf_token() }}'},
                dataType:'json',
                success:function(response){
                    if(response.class){
                        $('#removeItemModal').modal('hide');
                        $('#deleteData').removeClass('d-none');
                        $('#loadingBtn').addClass('d-none');
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
                }
            });

            
        }



        function deleteModel(url){
            $('#removeItemModal').modal('show');
            $('#deleteURL').val(url);
        }
        $('#removeItemModal').on('hidden.bs.modal', function () {
            $('#deleteURL').val('');
        });
        $('body').on('click', '#deleteData', function(){
            $('#loadingBtn').removeClass('d-none');
            $('#deleteData').addClass('d-none');
            deleteAjax();
        });


        function updateData(url, data = {}, callback = null, titleA='Are you sure?', textA='Do you want to perform this action?') {
            Swal.fire({
                title: titleA,
                text: textA,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, do it!',
                customClass:{
                    confirmButton:"btn btn-primary w-xs me-2 mt-2",
                    cancelButton:"btn btn-danger w-xs mt-2"
                },
                buttonsStyling:!1,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: Object.assign({'_method': 'PUT', '_token': '{{ csrf_token() }}'}, data),
                        dataType: 'json',

                        success: function (response) {
                            if (response.class) {
                                Swal.fire({
                                    icon: response.class === 'bg-success' ? 'success' : 'error',
                                    title: response.title,
                                    text: response.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }

                            if (typeof callback === 'function') {
                                callback(response);
                            }

                            if (document.getElementsByClassName('datatable').length) {
                                $('.datatable').DataTable().draw('page');
                                let table = $('.datatable').DataTable();
                                table.ajax.reload(null, false);
                            } else {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 300);
                            }
                        },
                        error: function (xhr, status, error) {
                            let msg = 'Something went wrong!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg,
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });

            return false;
        }



        function updateDataSingle(url, data = {}, callback = null) {
            $.ajax({
                url: url,
                method: 'POST',
                data: Object.assign({'_method': 'PUT', '_token': '{{ csrf_token() }}'}, data),
                dataType: 'json',

                success: function (response) {
                    //console.log('ok')
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();

                    if ($('.datatable').length) {
                let table = $('.datatable').DataTable();
                let scrollTop = $(window).scrollTop();

                table.ajax.reload(function () {
                    $(window).scrollTop(scrollTop);
                }, false);
            } else {
                setTimeout(() => location.reload(), 300);
            }

            if (typeof callback === 'function') {
                callback(response);
            }

                },
                error: function (xhr) {
                    console.error('Update failed:', xhr.responseText || xhr.statusText);
                }
            });

            return false;
        }


        {{-- function updateData(url,data={},callback=null){  
            if (confirm('Are you sure to perform this action')){                      
                $.ajax({
                    url:url,
                    method: 'post',
                    data:Object.assign({'_method':'PUT','_token':'{{ csrf_token() }}'},data),
                    dataType:'json',

                    success:function(response){
                        if(response.class){
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        className: response.class,
                    }).showToast();
                        }

                        if(typeof callback == 'function'){
                            callback(response);
                        }

                        if(document.getElementsByClassName('datatable').length){
                            $('.datatable').DataTable().draw('page');
                        }
                        else{
                            setTimeout(function(){
                                window.location.reload();
                            }, 300)
                        }
                    }
                });

            } 
            return false;
        } --}}

        function cancelData(url,data={},callback=null){  
            if (confirm('Are you sure to perform this action')){                      
                $.ajax({
                    url:url,
                    method: 'post',
                    data:Object.assign({'_method':'PUT','_token':'{{ csrf_token() }}'},data),
                    dataType:'json',

                    success:function(response){
                        if(response.class){
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        className: response.class,
                    }).showToast();
                        }

                        if(typeof callback == 'function'){
                            callback(response);
                        }

                        if(document.getElementsByClassName('datatable').length){
                            $('.datatable').DataTable().draw('page');
                        }
                        else{
                            setTimeout(function(){
                                window.location.reload();
                            }, 300)
                        }
                    }
                });

            } 
            return false;
        }
    </script>

    <script type="text/javascript">
        var previewTemplate, dropzone, dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "", dropzonePreviewNode && (previewTemplate = dropzonePreviewNode.parentNode.innerHTML,
            dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode), dropzone = new Dropzone(".dropzone", {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.media.store') }}",
                method: "post",
                previewTemplate: previewTemplate,
                previewsContainer: "#dropzone-preview",
                addRemoveLinks: false,
                success: function(file, response) {
                    $('#mediafiles-list').html('');
                    setTimeout(function() {
                        mediafiles(1);
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        className: response.class,

                    }).showToast();
                    }, 1500);
                },
                error: function(file, response) {
                    return false;
                }
            }));

        

        </script>

        <script>
            $(document).ready(function() {
                $(".js-choice").each(function() {
                    new Choices($(this)[0], {
                        allowHTML: true,
                        searchEnabled: false
                    }); 
                });
                $(".js-choice-search").each(function() {
                    new Choices($(this)[0], {
                        allowHTML: true,
                        searchEnabled: true
                    }); 
                });
                $('.select2').select2();
                $(".dateSelector").flatpickr({
                    dateFormat: "d F Y",
            //defaultDate: "today"
                });

                $(".dateSelectorRange").flatpickr({
                    mode: "range",
                    dateFormat: "d/m/y",
                });
            });

            $('body').on('click', 'table tbody tr', function () {

                const $row = $(this);
                const $tbody = $row.closest('tbody');

    // if already active → toggle OFF
                if ($row.hasClass('table-active')) {
                    $row.removeClass('table-active');
                    return;
                }

    // remove active from other rows in same table
                $tbody.find('tr').removeClass('table-active');

    // activate current row
                $row.addClass('table-active');
            });

            $('body').on('click', '.more-less', function (e) {
                e.stopPropagation();

                $('.more-less.expanded').not(this).removeClass('expanded');
                $(this).toggleClass('expanded');
            });

            

    {{-- key('⌘+s', function(){ 
        window.location.href = '{{ route('admin.product.index') }}';
        return false
    }); --}}



</script>



{{-- <script>
(function () {

    key('⌘+o, ctrl+o', function () {
        window.location.href = "{{ route('admin.order-sheet.index') }}";
        return false;
    });

    key('⌘+d, ctrl+d', function () {
        window.location.href = "{{ route('admin.dye.index') }}";
        return false;
    });

    key('⌘+j, ctrl+j', function () {
        window.location.href = "{{ route('admin.job-card.index') }}";
        return false;
    });

    key('⌘+p, ctrl+p', function () {
        window.location.href = "{{ route('admin.purchase-order.index') }}";
        return false;
    });

    key('⌘+i, ctrl+i', function () {
        window.location.href = "{{ route('admin.item.index') }}";
        return false;
    });

    key('⌘+shift+p, ctrl+shift+p', function () {
        window.location.href = "{{ route('admin.product.index') }}";
        return false;
    });

    key('⌘+c, ctrl+c', function () {
        window.location.href = "{{ route('admin.client.index') }}";
        return false;
    });

    key('⌘+v, ctrl+v', function () {
        window.location.href = "{{ route('admin.vendor.index') }}";
        return false;
    });

    key('⌘+shift+o, ctrl+shift+o', function () {
        window.location.href = "{{ route('admin.material-order.index') }}";
        return false;
    });

    key('⌘+shift+i, ctrl+shift+i', function () {
        window.location.href = "{{ route('admin.material-inward.index') }}";
        return false;
    });

    key('⌘+shift+s, ctrl+shift+s', function () {
        window.location.href = "{{ route('admin.material-issue.index') }}";
        return false;
    });

})();
</script> --}}

<script>
    (function () {

        function getScrollBox() {
            return document.querySelector('.table-responsive:hover')
            || document.querySelector('.table-responsive');
        }

        key('ctrl+left', function () {
            const box = getScrollBox();
            if (!box) return;
            box.scrollBy({ left: -200, behavior: 'smooth' });
            return false;
        });

        key('ctrl+right', function () {
            const box = getScrollBox();
            if (!box) return;
            box.scrollBy({ left: 200, behavior: 'smooth' });
            return false;
        });
        key('⌘+left', function () {
            const box = getScrollBox();
            if (!box) return;
            box.scrollBy({ left: -200, behavior: 'smooth' });
            return false;
        });

        key('⌘+right', function () {
            const box = getScrollBox();
            if (!box) return;
            box.scrollBy({ left: 200, behavior: 'smooth' });
            return false;
        });

    })();
</script>

<script>
    (function () {

        const shortcuts = {
            'KeyO': "{{ route('admin.order-sheet.index') }}",
            'KeyD': "{{ route('admin.dye.index') }}",
            'KeyJ': "{{ route('admin.job-card.index') }}",
            'KeyP': "{{ route('admin.purchase-order.index') }}",
            'KeyI': "{{ route('admin.item.index') }}",
            'KeyS': "{{ route('admin.product.index') }}",
            'KeyC': "{{ route('admin.client.index') }}",
            'KeyV': "{{ route('admin.vendor.index') }}",
            'KeyM:KeyO': "{{ route('admin.material-order.index') }}",
            'KeyM:KeyI': "{{ route('admin.material-inward.index') }}",
            'KeyM:KeyS': "{{ route('admin.material-issue.index') }}",
        };

        let stage = 0;
        let firstKey = null;

        function reset() {
            stage = 0;
            firstKey = null;
        }

        document.addEventListener('keydown', function (e) {

            if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
            if (document.querySelector('.modal.show')) return;

        // ESC = manual reset
            if (e.code === 'Escape') {
                reset();
                return;
            }

        // Prefix: Alt/Option + Z
            if (stage === 0 && e.altKey && e.code === 'KeyZ') {
                e.preventDefault();
                reset();
                stage = 1;
                return;
            }

        // First key
            if (stage === 1) {
                e.preventDefault();
                firstKey = e.code;
                stage = 2;

                if (shortcuts[firstKey]) {
                    window.location.href = shortcuts[firstKey];
                    reset();
                }
                return;
            }

        // Second key
            if (stage === 2) {
                e.preventDefault();
                const combo = `${firstKey}:${e.code}`;

                if (shortcuts[combo]) {
                    window.location.href = shortcuts[combo];
                }

                reset();
            }
        });

    // Reset if Alt is released
        document.addEventListener('keyup', function (e) {
            if (e.key === 'Alt') {
                reset();
            }
        });

    })();
</script>

@include('admin.layouts.script')
@stack('scripts')
</body>
</html>