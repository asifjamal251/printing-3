@extends('admin.layouts.master')
@push('links')
@endpush




@section('main')



        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>
                   
                    <div class="page-title-right">
                        <a id="create" data-title="Create Client" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                            <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                            Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
                        </a>
                    </div>
                  

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
                                <th>Company</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>City</th>
                                <th>GST</th>
                                <th>Status</th>
                                @can(['edit_admin','delete_admin', 'read_admin'])
                                  <th>Action</th>
                                @endcan
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
     "processing": true,
     "serverSide": true,
    'ajax': {
    'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
    'data': function(d) {
        d._token = '{{ csrf_token() }}';
        d._method = 'PATCH';
    }

    },
    "columns": [
        { "data": "sn" },
        { "data": "company_name" },
        { "data": "email" },
        { "data": "contact_no" },
        { "data": "city" },
        { "data": "gst" },
        { "data": "status" },
        {
            "data": "action",
            render: function(data, type, row) {
                if (type === 'display') {
                    var btn = '<div class="dropdown d-inline-block"><button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="align-middle ri-more-fill"></i></button><ul class="dropdown-menu dropdown-menu-end">';

                    @can(['edit_admin','delete_admin','read_admin'])

                    @can('read_admin')
                    btn += '<li><a class="dropdown-item" href="{{ request()->url() }}/' + row['id'] + '"><i class="align-bottom ri-eye-fill me-2 text-muted"></i> View</a></li>';
                    @endcan

                    @can('edit_admin')
                        btn+='<li><a class="editData dropdown-item edit-item-btn" href="javascript:void(0);" data-url="'+window.location.href+'/'+row['id']+'/edit"><i class="align-bottom ri-pencil-fill me-2 text-muted"></i> Edit</a></li>';
                    @endcan

                    @can('delete_admin')
                        btn += '<li><button type="button" onclick="deleteModel(\''+window.location.href+'/'+row['id']+'/delete\')" class="dropdown-item remove-item-btn"><i class="align-bottom ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>';
                    @endcan

                    @endcan
                     btn += '</ul></div>';
                    return btn;
                }
                return ' ';
            },
    }]

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
