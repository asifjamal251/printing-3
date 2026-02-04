@extends('admin.layouts.master')
@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }
    td[rowspan] {
        vertical-align: middle;
    }


   /* .table-responsive {
    overflow-y: hidden;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}*/

tr:last-child .choices__list--dropdown {
    top: auto !important;
    bottom: 100%;
    margin-top: 0 !important;
    margin-bottom: 1px;
    border-radius: .25rem .25rem 0 0;
}

    .process-start td {
        border-top: 1.5px solid #000 !important;
    }

/* Optional: Add top/bottom borders for clarity */
.process-start td {
    border-top: 1.5px solid #000 !important;
}


.process-end td {
    border-bottom: 1.5px solid #000 !important;
}



/* Hide border between same processing_number rows */
tbody tr:not(.process-start) td {
    border-top: none !important;
}
</style>
@endpush




@section('main')

<ul id="customContextMenu" class="p-0 dropdown-menu dropdown-menu dropdownmenu-secondary" style="min-width:200pxdisplay:none; position:absolute; z-index: 10000;">


    <li><a class="dropdown-item" href="javascript:void(0)" id="contextCancel"><i class="ri-delete-bin-fill me-2"></i> Cancel</a></li>
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

            <div class="card-body border border-dashed border-end-0 border-start-0">




            <div class="row">

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

                <div class="col-md-3 col-sm-12">
                    <div class="search-box">
                        <div class="m-0 form-group{{ $errors->has('filter_job_no') ? ' has-error' : '' }}">
                            {{ html()->search('filter_job_no')->class('form-control onKeyup')->id('filterJobNo')->placeholder('Job No.') }}
                            <small class="text-danger">{{ $errors->first('filter_job_no') }}</small>
                        </div>
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>


                <div class="col-md-3 col-sm-12">
                    <div class="search-box">
                        <div class="m-0 form-group{{ $errors->has('filter_item_name') ? ' has-error' : '' }}">
                            {{ html()->search('filter_item_name')->class('form-control onKeyup')->id('filterItemName')->placeholder('Item Name') }}
                            <small class="text-danger">{{ $errors->first('filter_item_name') }}</small>
                        </div>
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>

                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('filter_operator') ? ' has-error' : '' }}">
                        {{ html()->select('filter_operator', App\Models\Operator::orderBy('name', 'asc')->where('module_id', 3)->pluck('name', 'id'))->id('filterOperator')->class('form-control onChange js-choice')->placeholder('Choose Operator') }}
                        <small class="text-danger">{{ $errors->first('filter_operator') }}</small>
                    </div>
                </div>



                <div class="col-md-3 col-sm-12">
                    <div class="m-0 form-group{{ $errors->has('filterStatus') ? ' has-error' : '' }}">
                        {{ html()->select('filterStatus', App\Models\Status::whereIn('id', [1,3])->pluck('name', 'id'), 1)->id('filterStatus')->class('form-control js-choice onChange')->placeholder('Status') }}
                        <small class="text-danger">{{ $errors->first('filterStatus') }}</small>
                    </div>
                </div>


            </div>
        </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:20px">Sr</th>
                                <th>Operator</th>
                                <th>Job No.</th>
                                <th>Items</th>
                                <th>Coating</th>
                                <th>Sheet Size</th>
                                <th>Total Sheet</th>
                                <th>Counter</th>
                                <th>Date</th>
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
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script type="text/javascript">
    const rollId = {{auth('admin')->user()->role_id}}
    $(document).ready(function(){
        var table2 = $('#datatable').DataTable({
            "drawCallback": function(settings) {
                $(".js-choice").each(function() {
                    new Choices($(this)[0], {
                        allowHTML: true,
                        searchEnabled: false
                    }); 
                });
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
                    d.operator = $('#filterOperator').val();
                }

            },
            "columns": [
                { "data": "sn" },
                { "data": "operator" },
                { "data": "job" },
                { "data": "items" },
                { "data": "coating" },
                { "data": "sheet_size" },
                { "data": "total_sheet" },
                { "data": "counter" },
                { "data": "date" },
                { "data": "status" },
                { "data": "id", "visible": false }
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).addClass('data-row');
                $(row).attr('data-row', JSON.stringify(data));

                if(data.status_id === 3){
                    $(row).addClass('bg-success-subtle');
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

    $('#customContextMenu')
    .css({
        top: e.pageY + 'px',
        left: e.pageX + 'px',
        display: 'block'
    })
    .data('rowData', data || null);
    handleContextMenuRestrictions(data);
});


            $(document).on('contextmenu', '.data-row', function(e) {
                e.preventDefault();

                var rowData = JSON.parse($(this).attr('data-row'));
                $('#customContextMenu').data('rowData', rowData);

                
                $('#customContextMenu').css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px',
                    display: 'block'
                });


                    const statusId = rowData?.status_id;
                    const restrictedStatuses = [3];

                    if (restrictedStatuses.includes(statusId)) {
                        $('#contextBack')
                            .addClass('disabled')
                            .css('pointer-events', 'none')
                            .css('opacity', 0.5);
                    } else {
                        $('#contextBack')
                            .removeClass('disabled')
                            .css('pointer-events', '')
                            .css('opacity', '');
                    }
             
            });



 $(document).click(function() {
    $('#customContextMenu').hide();
});


 $('#customContextMenu').on('click', 'a', function(e) {
    e.preventDefault();
    var rowData = $('#customContextMenu').data('rowData');

    if (rowData) {
        if (this.id === 'contextCancel') {
            var url = '{{route('admin.'.request()->segment(2).'.cancel')}}';
            updateData(
                url,
                { id: rowData.id },
                false,
                'Cancel and Back here',
                'Do you want to back to the Job Card?'
            );
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
    const restrictedStatuses = [3];

    if (restrictedStatuses.includes(statusId)) {
        $('#contextCancel')
        .removeClass('disabled')
        .css('pointer-events', '')
        .css('opacity', '');
    } else {
        $('#contextCancel')
        .addClass('disabled')
        .css('pointer-events', 'none')
        .css('opacity', 0.5);
    }
}


$('body').on('change', '.operator', function() {
    const id = $(this).data('id');
    const operator_id = $(this).val();
    const url = "{{ route('admin.' . request()->segment(2) . '.update.operator') }}";

    updateDataSingle(url, { id: id, operator_id: operator_id });
});


$('body').on('change', '.counter', function() {
    const id = $(this).data('id');
    const counter = $(this).val();
    const url = "{{ route('admin.' . request()->segment(2) . '.update.counter') }}";

    updateDataSingle(url, { id: id, counter: counter });
});

});





</script>


@endpush
