@extends('admin.layouts.master')
@push('links')

<style type="text/css">
    .select2-container {
      min-width: 400px;
  }

  .select2-results__option {
      padding-right: 20px;
      vertical-align: middle;
  }
  .select2-results__option:before {
      content: "";
      display: inline-block;
      position: relative;
      height: 20px;
      width: 20px;
      border: 2px solid #e9e9e9;
      border-radius: 4px;
      background-color: #fff;
      margin-right: 20px;
      vertical-align: middle;
  }
  .select2-results__option[aria-selected=true]:before {
      font-family:fontAwesome;
      content: "\f00c";
      color: #fff;
      background-color: #f77750;
      border: 0;
      display: inline-block;
      padding-left: 3px;
  }
  .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #eaeaeb;
    color: #272727;
}
.select2-container--default .select2-selection--multiple {
    margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
    border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #f77750;
    border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
    border-width: 2px;
}
.select2-container--open .select2-dropdown--below {

    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
    content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
    display: none;
}
.select-icon .placeholder {
    display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
    display: none !important;
    /* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
    display: none;
}
</style>


<style>
        .custom-multiselect {
            position: relative;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            background: white;
        }

        .custom-multiselect .selected-items {
            min-height: 20px;
        }

        .custom-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 200px;
            border: 1px solid #ccc;
            background: white;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .custom-dropdown.show {
            display: block;
        }

        .custom-dropdown input[type="text"] {
            width: 95%;
            margin: 5px;
            padding: 5px;
        }

        .custom-option {
            padding: 5px 10px;
            display: flex;
            align-items: center;
        }

        .custom-option:hover {
            background: #f0f0f0;
        }

        .custom-option input {
            margin-right: 8px;
        }
    </style>
@endpush




@section('main')



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
               @can('add_admin')
               <a id="create" data-title="Create Admin" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
            </a>
            @endcan
        </div>


    </div>
</div>
</div>
<!-- end page title -->



<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                        <thead>
                            <tr>
                                <td></td>
                                <td class="p-1">
                                    <div class="m-0 sm-form-control  form-group{{ $errors->has('search_role') ? ' has-error' : '' }}">
                                        {{ html()->select('search_role', App\Models\Role::orderBy('name', 'asc')->whereNotIn('id', [1])->pluck('name', 'id'))->id('tableRole')->class('form-control form-control-sm js-choice onChange')->placeholder('Role') }}
                                        <small class="text-danger">{{ $errors->first('search_role') }}</small>
                                    </div>
                                </td>
                                <td class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('search_name') ? ' has-error' : '' }}">
                                        {{ html()->search('search_name')->id('tableName')->class('onKeyup form-control form-control-sm')->placeholder('Name') }}
                                        <small class="text-danger">{{ $errors->first('search_name') }}</small>
                                    </div>
                                </td>
                                <td class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('search_email') ? ' has-error' : '' }}">
                                        {{ html()->search('search_email')->id('tableEmail')->class('form-control form-control-sm onKeyup')->placeholder('Email') }}
                                        <small class="text-danger">{{ $errors->first('search_email') }}</small>
                                    </div>
                                </td>
                                <td class="p-1">
                                    <div class="m-0 sm-form-control  form-group{{ $errors->has('search_status') ? ' has-error' : '' }}">
                                        {{ html()->select('search_status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [14,15])->pluck('name', 'id'))->class('form-control js-choice onChange')->id('tableStatus')->placeholder('Choose Status') }}
                                        <small class="text-danger">{{ $errors->first('search_status') }}</small>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Si</th>
                                <th>Role</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                @can(['edit_admin', 'delete_admin', 'read_admin'])
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

<script type="text/javascript">
    $(document).ready(function(){
        var table2 = $('#datatable').DataTable({
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
                    d.name = $('#tableName').val();
                    d.tableEmail = $('#tableEmail').val();
                    d.role = $('#tableRole').val();
                    d.status = $('#tableStatus').val();
                }

            },
        "columns": [
            { "data": "sn" },
            { "data": "role" },
            { "data": "name" },
            { "data": "email" },
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


    });

</script>











{{-- 
<script type="module">
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
