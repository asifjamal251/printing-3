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
    @can('add_details_job_card')
    <li>
        <a class="dropdown-item editData" model-size="modal-xl" data-title="Add Details" href="javascript:void(0)" id="contextAddDetails" bg-color="#f3f3f9"><i class="bx bx-plus me-2"></i> Add Details</a>
    </li>
    @endcan

    @can('read_job_card')
    <li><a class="dropdown-item" href="javascript:void(0);" id="contextView"><i class="ri-eye-fill me-2"></i> View</a></li>
    <li><a class="dropdown-item" href="javascript:void(0);" id="contextPDF"><i class="bx bx-download  align-middle me-2"></i> Download PDF</a></li>
    @endcan

    <li>
        <a class="dropdown-item editData" model-size="modal-xl" data-title="Add Details" href="javascript:void(0)" id="contextAddOprator" bg-color="#f3f3f9"><i class="bx bx-plus me-2"></i>  Add Oprator</a>
    </li>

    <li>
        <a class="dropdown-item" href="javascript:void(0);" id="contextAssign"><i class="bx bx-plus me-2"></i> Assign</a>
    </li>
   
    <li><a class="dropdown-item" href="javascript:void(0)" id="contextCancelAssign"><i class="ri-delete-bin-fill me-2"></i> Assign Cancel</a></li>

     <div class="dropdown-divider m-0"></div>

    <li><a class="dropdown-item" href="javascript:void(0)" id="contextDelete"><i class="ri-delete-bin-fill me-2"></i> Cancel Job Card</a></li>

</ul>








<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <a href="{{route('admin.job-card.selected.download')}}" class="btn btn-sm btn-primary" id="createJobCard">Download Selected Job Card</a>
            <p class="m-0"></p>
        </div>


    </div>
</div>
<!-- end page title -->




<div class="d-flex gap-2">

    <div class="" style="width:100%;">
        <div class="card">

           <div class="card-body border border-dashed border-end-0 border-start-0">




            <div class="d-flex gap-2">

                {{-- <div class="w-75">
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
                </div> --}}

                <div class="w-50">
                    <div class="search-box">
                        <div class="m-0 form-group{{ $errors->has('filter_job_no') ? ' has-error' : '' }}">
                            {{ html()->search('filter_job_no')->class('form-control onKeyup')->id('filterJobNo')->placeholder('Job No.') }}
                            <small class="text-danger">{{ $errors->first('filter_job_no') }}</small>
                        </div>
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>


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
                    <div class="m-0 form-group{{ $errors->has('filter_operator') ? ' has-error' : '' }}">
                        {{ html()->select('filter_operator', App\Models\Operator::orderBy('name', 'asc')->where('module_id', 2)->pluck('name', 'id'))->id('filterOperator')->class('form-control onChange js-choice')->placeholder('Choose Operator') }}
                        <small class="text-danger">{{ $errors->first('filter_operator') }}</small>
                    </div>
                </div>



                <div class="w-50">
                    <div class="m-0 form-group{{ $errors->has('filterStatus') ? ' has-error' : '' }}">
                        {{ html()->select('filter_tatus', App\Models\Status::whereIn('id', [1,3,5,23,25,26,26,28,29,30,31,32,33])->pluck('name', 'id'))->id('filterStatus')->class('form-control js-choice onChange')->placeholder('Status') }}
                        <small class="text-danger">{{ $errors->first('filterStatus') }}</small>
                    </div>
                </div>

                <div style="width:120px;">
                    <div class="m-0 form-group{{ $errors->has('filter__length') ? ' has-error' : '' }}">
                        {{ html()->select('filter__length', [25 => 25, 50 => 50, 100 => 100, 150 => 150, 200 => 200], 25)->id('filterLength')->class('form-control onChange js-choice') }}
                        <small class="text-danger">{{ $errors->first('filter__length') }}</small>
                    </div>
                </div>


            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table  align-middle datatable border-secondary table-bordered nowrap" style="width:100%">

                    <thead>
                        <tr>
                            <th style="width:20px">Sr</th>
                            <th style="width:16px;"></th>
                            <th>Job No.</th>
                            <th>Items</th>
                            <th>Sheet Size</th>
                            <th>Required Sheet</th>
                            <th>Wastage Sheet</th>
                            <th>Total Sheet</th>

                            <th>Prnt Oprt</th>
                            <th>File</th>

                            <th>Create/Complete</th>

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
                lightbox.reload();
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
                    d.item_name = $('#filterItemName').val();
                    d.status = $('#filterStatus').val();
                    d.set_no = $('#filterJobNo').val();
                    d.length = $('#filterLength').val();
                    d.operator = $('#filterOperator').val();
                }

            },
            "columns": [
                { "data": "sn" },
                { "data": "checkbox" },
                { "data": "job" },
                { "data": "items" },
                { "data": "sheet_size" },
                { "data": "required_sheet" },
                { "data": "wastage_sheet" },
                { "data": "total_sheet" },
                { "data": "printing_operator" },
                { "data": "file" },
                { "data": "created_at" },
                { "data": "status" },
                { "data": "id", "visible": false }
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).addClass('data-row');
                $(row).attr('data-row', JSON.stringify(data));

                if (data.row_class) {
                    $(row).addClass(data.row_class);
                }

                if(data.selected_job_card === 1){
                    $(row).addClass('bg-info-subtle');
                }

                if(data.status_id === 3){
                    $(row).addClass('bg-success-subtle');
                }
            },

                    {{-- drawCallback: function(settings) {
    var api = this.api();
    var rows = api.rows({ page: 'current' }).nodes();
    var data = api.rows({ page: 'current' }).data().toArray();

    
    const mergeCols = [1, 2, 3, 4, 11]; 
    let lastJobCardId = null;
    let firstRow = null;
    let rowspanCount = 0;


    $('td[rowspan]', api.table().body()).removeAttr('rowspan');

    data.forEach(function(rowData, i) {
        const currentJobCardId = rowData.job_card_id;
        const currentRow = $(rows).eq(i);

        if (currentJobCardId === lastJobCardId) {
            // Same JobCard → remove duplicate job columns
            mergeCols.forEach(colIndex => {
                $(api.cell(i, colIndex).node()).remove();
            });
            rowspanCount++;
        } else {
            // Different job card → apply rowspan to previous
            if (firstRow && rowspanCount > 1) {
                mergeCols.forEach(colIndex => {
                    $(api.cell($(firstRow), colIndex).node()).attr('rowspan', rowspanCount);
                });
            }
            // Reset
            firstRow = rows[i];
            rowspanCount = 1;
            lastJobCardId = currentJobCardId;
        }
    });

    // Final rowspan for last group
    if (firstRow && rowspanCount > 1) {
        mergeCols.forEach(colIndex => {
            $(api.cell($(firstRow), colIndex).node()).attr('rowspan', rowspanCount);
        });
    }
} --}}

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


                {{-- $(document).on('contextmenu', '.data-row', function(e) {
                    e.preventDefault();

                    var rowData = JSON.parse($(this).attr('data-row'));
                    $('#customContextMenu').data('rowData', rowData);


                    $('#customContextMenu').css({
                        top: e.pageY + 'px',
                        left: e.pageX + 'px',
                        display: 'block'
                    });


                    const statusId = rowData?.status_id;
                    const restrictedStatuses = [1, 2];
                    const cancelAssign = [23];
                    const assign = [2];

                    if (!restrictedStatuses.includes(statusId)) {
                        $('#contextCancel, #contextAddDetails')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.5);
                    } else {
                        $('#contextCancel, #contextAddDetails')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                    }


                    if (!cancelAssign.includes(statusId)) {
                        $('#contextCancelAssign')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.5);
                    } else {
                        $('#contextCancelAssign')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                    }


                    if (!assign.includes(statusId)) {
                        $('#contextAssign')
                        .addClass('disabled')
                        .css('pointer-events', 'none')
                        .css('opacity', 0.5);
                    } else {
                        $('#contextAssign')
                        .removeClass('disabled')
                        .css('pointer-events', '')
                        .css('opacity', '');
                    }

                });
 --}}


 $(document).click(function() {
    $('#customContextMenu').hide();
});


 $('#customContextMenu').on('click', 'a', function(e) {
    e.preventDefault();
    var rowData = $('#customContextMenu').data('rowData');




    if (rowData) {
        if (this.id === 'contextView') {
            var url = window.location.href + '/view/' + rowData.id;
            window.location.href = url;
        }

        if (this.id === 'contextPDF') {
            var url = window.location.href + '/pdf/download/' + rowData.id;
            window.location.href = url;
        }

        if (this.id === 'contextCancel') {
            var deleteUrl = window.location.href + '/' + rowData.id + '/delete';
            $(this).attr('data-url', deleteUrl); 
            deleteModel(deleteUrl);
        }

        if (this.id === 'contextAddDetails') {
            var editUrl = window.location.href + '/add/details/' + rowData.id;
            $(this).attr('data-url', editUrl); 
        }

        if (this.id === 'contextAddOprator') {
            let urlTemplate = "{{ route('admin.job-card.operator.create', ':id') }}";
            let url = urlTemplate.replace(':id', rowData.id);
            $(this).attr('data-url', url);
        }

        if (this.id === 'contextAssign') {
            var editUrl = '{{route('admin.job-card.assign')}}';
            updateData(editUrl, {id: rowData.id});
        }

        if (this.id === 'contextCancelAssign') {
            var editUrl = '{{route('admin.job-card.assign.cancel')}}';
            cancelData(editUrl, {id: rowData.id});
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
    const addDetails = [1, 23, 25];
    const cancelAssign = [25];
    const assign = [23];
    const addOprator = [23, 24, 25];

    if (addDetails.includes(statusId)) {
        $('#contextAddDetails')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    } else {
        $('#contextAddDetails')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    }


    if (cancelAssign.includes(statusId)) {
        $('#contextCancelAssign')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    } else {
        $('#contextCancelAssign')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    }


    if (assign.includes(statusId)) {
        $('#contextAssign')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    } else {
        $('#contextAssign')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    }

    if (addOprator.includes(statusId)) {
        $('#contextAddOprator')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    } else {
        $('#contextAddOprator')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    }
}

getClient('#filterMFGBY', false, 'Choose MFG By');
    getClient('#filterMKDTBY', false, 'Choose MKDT By');

});


$('body').on('change', '.selectJobCard', function(){
    if($(this).is(':checked')) {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '{{ route('admin.'.request()->segment(2).'.store')}}',
            data: {'id':id, 'type':'added', '_method': 'POST', '_token': '{{ csrf_token() }}'},
            success:function(response){
                Toastify({
                    text: response.message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    className: response.class,
                }).showToast();
                //$('.datatable').DataTable().draw('page');
            },
            error:function(error){
                console.log(error);
            }
        });
    } else {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '{{ route('admin.'.request()->segment(2).'.store')}}',
            data: {'id':id, 'type':'remove', '_method': 'POST', '_token': '{{ csrf_token() }}'},
            success:function(response){
                Toastify({
                    text: response.message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    className: response.class,
                }).showToast();
                //$('.datatable').DataTable().draw('page');
            },
            error:function(error){
                console.log(error);
            }
        });
    }
});


</script>





@endpush
