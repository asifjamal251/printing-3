@extends('admin.layouts.master')
@push('links')
<style>
    .custom-padding{
        padding:12px 16px !important;
    }
</style>
@endpush




@section('main')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>

    </div>
</div>
</div>
<!-- end page title -->




<div class="row">

    


    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Personal Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Company Name</th>
                            <td>{{ $client->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $client->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $client->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $client->city->name }}</td>
                        </tr>
                        <tr>
                            <th>Logo</th>
                            <td>
                                @if($client->media)
                                <div class="rounded avatar-title bg-light">
                                    <img src="{{asset($client->media->file)}}" alt="" height="50">
                                </div>
                                @else
                                N/A
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Address</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>GST</th>
                            <td>{{ $client->gst }}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>{{ $client->state->name }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $client->city->name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $client->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div><!--end col-->



    {{-- <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle d-flex justify-content-between align-items-center custom-padding">
                <h5 class="mb-0 card-title">Rate Details</h5>
                <button type="button" class="btn-sm btn btn-warning btn-label addRate">
                    <i class="align-middle bx bx-plus label-icon fs-16 me-2"></i>
                    Add Rate
                </button>
                
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table-sm border-info table table-bordered nowrap align-middle" style="width:100%">
                        <thead class="gridjs-thead">
                            <tr>
                                <th>Si</th>
                                <th>Product Type</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($client->rates->count() > 0)
                            @foreach($client->rates as $rate)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$rate->productType->type}}</td>
                                <td>{{$rate->rate}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="3">N/A</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
</div><!--end row-->



<div class="row">
            <div class="col-lg-12">
                <div class="card">
                     <div class="card-header bg-info-subtle d-flex justify-content-between align-items-center custom-padding">
                <h5 class="mb-0 card-title">Sale Details</h5>
                <span><b>Total Weight: </b><span class="fw-700" id="totalWeightSum">{{$client->sales->flatMap->items->sum('weight')}}</span> KG</span>

                <span><b>Total Amount: </b>₹ <span class="fw-700" id="totalWeightSum">{{$client->sales_sum_total_amount ?? 0}}</span></span>
               
                
            </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="text-center table align-middle datatable table-sm border-info table-bordered nowrap" style="width:100%">

                            <thead>
                                <tr class="align-middle">
                                    <th rowspan="2" style="width:5%;">Sr No.</th>
                                    <th rowspan="2">Godown</th>
                                    <th rowspan="2">SO No.</th>
                                    <th rowspan="2">Invoice No.</th>
                                    <th rowspan="2">MILL</th>
                                    <th rowspan="2">QUALITY</th>
                                    <th colspan="2">SIZE(L)</th>
                                    <th colspan="2">SIZE(W)</th>
                                    <th rowspan="2">GSM</th>
                                    <th rowspan="2">PKT. WT</th>
                                    <th rowspan="2">PKT</th>
                                    <th rowspan="2">WEIGHT</th>
                                    <th rowspan="2">SO Date</th>
                                    <th rowspan="2">Rate</th>
                                </tr>

                                <tr>
                                    <th class="py-1 border-start-0">CM</th>
                                    <th class="py-1">Inch</th>
                                    <th class="py-1">CM</th>
                                    <th class="py-1">Inch</th>
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
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    $(document).ready(function() {
        $(".js-choice").each(function() {
            new Choices($(this)[0], {
                allowHTML: true,
            }); 
        });
    });

    $('body').on('click', '.addRate', function() {
        $('#addClentRate').modal("show");
    });
</script>


 <script type="text/javascript">
        $(document).ready(function() {
    var table2 = $('#datatable').DataTable({
        "ordering": false,
        "searchning": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [25],
        "ajax": {
            "url": '{{ route('admin.' . request()->segment(2) . '.show', $client->id) }}',
            "data": function(d) {
                d._token = '{{ csrf_token() }}';
                d._method = 'PATCH';
            },
            "dataSrc": function(json) {
                if (json.total_weight_sum !== undefined) {
                    $('#totalWeightSum').text(json.total_weight_sum.toFixed(2)); 
                }
                return json.data;
            }
        },
        "columns": [
            { "data": "sn" },
            { "data": "godown" },
            { "data": "so_no" },
            { "data": "invoice_no" },
            { "data": "mill" },
            { "data": "quality" },
            { "data": "length_cm" },
            { "data": "length_inch" },
            { "data": "width_cm" },
            { "data": "width_inch" },
            { "data": "gsm" },
            { "data": "pkt_wt" },
            { "data": "pkt" },
            { "data": "weight" },
            { "data": "so_date" },
            { "data": "rate" }
        ],
        "language": {
            "emptyTable": "No records found"
        }
    });
});
    </script>
@endpush
