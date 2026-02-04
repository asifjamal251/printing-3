@extends('admin.layouts.master')
@push('links')
<link rel="stylesheet" href="{{asset('admin-assets/libs/select2/css/select2.min.css')}}">  
<style type="text/css">
    table span.select2-selection.select2-selection--single, span.selection {
        height: 27px!important;    
    }

   table  .select2-container .select2-selection--single .select2-selection__rendered {
        height: 27px!important;
    }
    table .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 27px!important;
    }
    table .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 27px!important;
    }
    table .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 14px!important;
        font-size: .8125rem;
    }
    textarea {
        display: block;
        width: 100%;
        height: auto;
        resize: none; /* Disable the draggable resizer handle */
        overflow: hidden; /* Hide the scrollbar */
        min-height: 100px; /* Minimum height */
    }
</style>




@section('main')


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <a id="create" data-title="Create City" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
            </a>

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row my-1">

    <div class="col-lg-12 col-sm-12 col-12">

        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <table id="dataTableAjax" class="display table-sm border-secondary dataTableAjax table table-striped table-bordered dom-jQuery-events" >
                        <thead>
                            <tr>
                                <td></td>
                                <td class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('search_name') ? ' has-error' : '' }}">
                                        {{ html()->search('search_name')->class('onKeyup form-control form-control-sm')->id('cityTable')->placeholder('Name') }}
                                        <small class="text-danger">{{ $errors->first('search_name') }}</small>
                                    </div>
                                </td>
                                <td class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('search_state') ? ' has-error' : '' }}">
                                        {{ html()->select('search_state', [])->id('stateTable')->class('onChange form-control form-control-sm')->placeholder('Choose State') }}
                                        <small class="text-danger">{{ $errors->first('search_state') }}</small>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Si</th>
                                <th>Name</th>
                                <th>State</th>
                                 @can(['edit_city','delete_city', 'read_city'])
                                    <th>Action</th>
                                @endcan

                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div>


    </div>
</div>



@endsection




@push('scripts')
<script src="{{asset('admin-assets/libs/select2/js/select2.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
    var table2 = $('#dataTableAjax').DataTable({
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
                d.state = $('#stateTable').val();
                d.name = $('#cityTable').val();
            }

        },
        "columns": [
            { "data": "sn" }, 
            { "data": "name" },  
            { "data": "state" },  
            @can(['edit_city','delete_city', 'read_city'])
            {
                "data": "action",
                render: function(data, type, row) {
                    if (type === 'display') {
                        var btn = '<div class="dropdown d-inline-block"><button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill align-middle"></i></button><ul class="dropdown-menu dropdown-menu-end">';

                        @can('edit_city')
                            btn+='<li><a class="editData dropdown-item edit-item-btn" href="javascript:void(0);" data-url="'+window.location.href+'/'+row['id']+'/edit"><i class="align-bottom ri-pencil-fill me-2 text-muted"></i> Edit</a></li>';
                        @endcan

                        @can('delete_city')
                       btn += '<li><button type="button" onclick="deleteModel(\''+window.location.href+'/'+row['id']+'/delete\')" class="dropdown-item remove-item-btn"><i class="align-bottom ri-delete-bin-fill me-2 text-muted"></i> Delete</button></li>';
                        @endcan

                        btn += '</ul></div>';
                        return btn;
                    }
                    return ' ';
                },

            }
            @endcan
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


    $('#stateTable').select2({
        placeholder: 'Choose State',
        allowClear: true,
        ajax: {
            url: '{{ route('admin.common.state.list') }}',
            dataType: 'json',
            cache: true,
            delay: 200,
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
        }
    });
    
});
</script>


@endpush