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
<div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex justify-content-center gap-5">
                            <p class="m-0">{{$product->fullname}}</p>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm border-success table-hover table table-bordered nowrap align-middle" style="width:100%">
                                <thead class="gridjs-thead">
                                    <tr>
                                        <th style="width:12px">Si</th>
                                        <th>Item/PC/PKT</th>
                                        <th>WT/PC/PKT</th>
                                        <th>Quantity</th>
                                        <th>Location</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->attributes as $item)
                                        <tr class="getLedger" data-id="{{$item->id}}">
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$item->item_per_packet}}</td>
                                            <td>{{number_format($item->weight_per_piece, 1, '.', '')}}</td>
                                            <td>{{$item->stock->quantity}}</td>
                                            <td>{{$item->location}}</td>
                                            <td></td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        {{ html()->hidden('attrubutes')->id('attributes') }}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="datatable table-sm border-secondary table-hover table table-bordered nowrap align-middle" style="width:100%">
                                <thead class="gridjs-thead">
                                    <tr>
                                        <th>Si</th>
                                        <th>Item/PC/PKT</th>
                                        <th>Weight/PC/PKT</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Reference No.</th>
                                        <th>Previous Stock</th>
                                        <th>Stock(+/-)</th>
                                        <th>Available Stock</th>
                                        <th>Total WT</th>
                                        <th>Note</th>
                                        <th>Made By</th>
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
<script>
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
                'url': '{{ route('admin.'.request()->segment(2).'.show', $product->id) }}',
                'data': function(d) {
                    d._token = '{{ csrf_token() }}';
                    d._method = 'PATCH';
                    d.attributes = $('#attributes').val();
                }
            },
            "columns": [
                { "data": "sn" },
                { "data": "item" },
                { "data": "weight" },
                { "data": "date" },
                { "data": "type" },
                { "data": "reference_no" },
                { "data": "old_stock" },
                { "data": "new_stock" },
                { "data": "current_stock" },
                { "data": "total_wt" },
                { "data": "note" },
                { "data": "created_by" },
            ]

        });

        $('body').on('click', '.getLedger', function(){
            $('.getLedger').removeClass('border-success table-success');
            $(this).addClass('border-success table-success');
            var attribute = $(this).attr('data-id');
            $('#attributes').val(attribute);
            table2.draw('page');
        });
    });
</script>
@endpush