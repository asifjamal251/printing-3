@extends('admin.layouts.master')
@push('links')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush




@section('main')



        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>
                    <h4>Low Stocks</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="datatable table-sm border-secondary table-hover table table-bordered nowrap align-middle" style="width:100%">
                                <thead class="gridjs-thead">
                                    <tr>
                                        <th style="width:12px">Si</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
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
        searching: false,
        ordering: false,
        processing: true,
        serverSide: true,
        "lengthMenu": [50, 100,200],
        'ajax': {
        'url': '{{ route('admin.product.lowstock') }}',
        'data': function(d) {
            d._token = '{{ csrf_token() }}';
            d._method = 'PATCH';
        }

        },
        "columns":[
            { "data": "sn" },
            { "data": "product" },
            { "data": "product_type" },
            { "data": "quantity" },
            { "data": "unit" }
        ]

       
    });
});


    </script>

@endpush