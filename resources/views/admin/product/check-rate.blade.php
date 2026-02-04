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
                    <h4>Product Rates</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h6 class="card-title mb-0">{{$product->name}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="datatable table-sm border-secondary table-hover table table-bordered nowrap align-middle" style="width:100%">
                                <thead class="gridjs-thead">
                                    <tr>
                                        <th style="width:12px">Si</th>
                                        <th>Order ID df</th>
                                        <th>Vendor</th>
                                        <th>Product Type</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if($product->materialOrderItems->count() > 0)
                                        @foreach($product->materialOrderItems as $item)
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td><a class="" href="{{route('admin.material-order.show', $item->materialOrder->id)}}">{{$item->materialOrder->order_no}}</a></td>
                                            <td>{{$item->materialOrder->vendor->name}}</td>
                                            <td>{{$product->productType->type}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$item->unit->name}}</td>
                                            <td>₹ {{$item->rate_on}}</td>
                                            <td>{{$item->materialOrder->created_at->format('d F, Y')}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="8">No Order Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->



@endsection





@push('scripts')

<script type="text/javascript">


</script>

@endpush