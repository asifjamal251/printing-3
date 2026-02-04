@extends('admin.layouts.master')
@push('links')
<style>
/*  table , td, th {
    border: 1px solid #595959;
    border-collapse: collapse;
}
td, th {
    padding: 3px;
    width: 30px;
    height: 25px;
}
th {
    background: #f0e6cc;
}
.even {
    background: #fbf8f0;
}
.odd {
    background: #fefcf9;
}*/
tr td, tr th{
    white-space: nowrap;
}
</style>
@endpush




@section('main')



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="hstack gap-2 justify-content-end d-print-none">
               {{--  <a href="javascript:window.print()" class="btn btn-sm btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                <a href="{{ route('admin.material-inward.download.pdf', $order->id) }}" class="btn btn-sm btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}
            </div>

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered border-secondary w-100">
                        <colgroup>
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                        </colgroup>
                        <tr>
                            <td colspan="5">
                                
                                <p class="mb-1"><b>{{ $order->billTo->company_name }}</b></p>
                                <p style="margin-bottom: 0;">{{ $order->billTo->contact_no }}</p>
                                <p style="margin-bottom: 0;">{{ $order->billTo->email }}</p>
                                <p style="margin-bottom: 0;">{{ $order->billTo->gst }}</p>
                                <p style="margin-bottom: 0;">{{ $order->billTo->address }}, {{ $order->billTo->city->name }}<br>{{ $order->billTo->state->name }} - {{ $order->billTo->pincode }}</p>
                            </td>
                            <td colspan="5">
                                <p class="mb-1"><b>{{ $order->vendor->company_name }}</b></p>
                                <p style="margin-bottom: 0;">{{ $order->vendor->phone_no }}</p>
                                <p style="margin-bottom: 0;">{{ $order->vendor->email }}</p>
                                <p style="margin-bottom: 0;">{{ $order->vendor->gst }}</p>
                                <p style="margin-bottom: 0;">{{ $order->vendor->address }}</p>
                            </td>
                        </tr>

                        

                        <tr>
                            <th colspan="2">Order No.</th>
                            <td colspan="2">{{ $order->order_no }}</td>
                            <td colspan="2"></td>
                            <th colspan="2">Order Date</th>
                            <td colspan="2">{{ $order?->mo_date?->format('d F, Y') }}</td>
                        </tr>
                        

                        <tr>
                            <th>Si. No.</th>
                            <th colspan="2">Product Details</th>
                            <th colspan="2">Product Type</th>
                            <th>Item/PC/PKT</th>
                            <th>WT/PC/PKT</th>
                            <th>Quantity</th>
                            <th colspan="2">Total Weight(KG)</th>
                        </tr>

                        @php
                            $total = 0;
                            $pkt_total = 0;
                        @endphp
                        @foreach($order->items as $item)
                            @php
                                $total += $item->total_weight;
                                $pkt_total += $item->quantity;
                            @endphp
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td colspan="2">{!! $item->product->product_name_gsm !!}</td>
                                <td colspan="2">{{ $item->product->productType->name }}</td>
                                <td>{{ $item->productAttribute->item_per_packet }}</td>
                                <td>{{ number_format($item->productAttribute->weight_per_piece, 1, '.', '') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td colspan="2">{{ $item->total_weight }}</td>
                            </tr>
                        @endforeach

                        

                        <tr>
                            <td colspan="7"></td>
                            <th>{{ $pkt_total }}</th>
                            <th colspan="2">{{ $total }}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


    @endsection








    @push('scripts')

    @endpush