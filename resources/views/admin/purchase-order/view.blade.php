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


<div class="card">
    <div class="card-body">
        <table class="table-sm table align-middle border-secondary table-bordered nowrap" style="width:100%">

            <colgroup>
                @for ($i = 1; $i < 18; $i++)
                <col style="width:5.55%;">
                @endfor
            </colgroup>

            <tr>
                <td colspan="9">
                    <p class="m-1"><b>{{get_app_setting('app_name')}}</b></p>
                    <p class="m-0">{{get_app_setting('address')}}</p>
                </td>

                <td colspan="9">
                    <p class="m-1"><b>{{$purchase_order->client?->company_name}}</b></p>
                    <p class="m-0">{{$purchase_order->client?->city->name}}, {{$purchase_order->client?->address}}, {{$purchase_order->client?->state->name}} - {{$purchase_order->client?->pincode}}</p>
                    
                    {{-- <ul class="list-unstyled vstack gap-2 fs-13 mb-0">
                        <li class="fw-medium fs-14">Client</li>
                        <li><b>Name:</b> {{$purchase_order->client?->company_name}}</li>
                        <li><b>Address:</b> {{$purchase_order->client?->city->name}}, {{$purchase_order->client?->address}}, {{$purchase_order->client?->state->name}} - {{$purchase_order->client?->pincode}}</li>
                    </ul> --}}
                </td>

            </tr>
            <tr class="bg-secondary-subtle text-secondary">
                <th colspan="3">Purchase Order Number</th>
                <td colspan="3">{{$purchase_order->po_number}}</td>
                <td colspan="6"></td>
                <th colspan="3">Purchase Order Date</th>
                <td colspan="3">{{$purchase_order->po_date->format('d F Y')}}</td>
            </tr>
            <tr class="bg-secondary-subtle text-secondary">
                <th colspan="2">Item Name</th>
                <th>Item Size</th>
                <th>Last date</th>
                <th>Paper Quality</th>
                <th>Colour</th>
                <th>GSM</th>
                <th>Coating</th>
                <th>Embosing</th>
                <th>Leafing</th>
                <th>Back Print</th>
                <th>Braille</th>
                <th>Artwork Code</th>
                <th>Batch</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>GST %</th>
                <th>Remarks</th>
            </tr>
            @foreach($purchase_order->items as $po_item)
            <tr>
                <td colspan="2">{{$po_item->item_name}}</td>
                <td>{{$po_item->item_size}}</td>
                <td>{{$po_item->item?->lastItem?->created_at?->format('d F Y')??'--'}}</td>
                <td>{{$po_item->productType->name}}</td>
                <td>{{$po_item->colour}}</td>
                <td>{{$po_item->gsm}}</td>
                <td>
                    <p class="mb-1">{{$po_item->coatingType->name}}</p>
                    <p class="text-danger mb-0">{{$po_item->otherCoatingType->name}}</p>
                </td>
                <td>{{$po_item->embossing}}</td>
                <td>{{$po_item->leafing}}</td>
                <td>{{$po_item->back_print}}</td>
                <td>{{$po_item->braille}}</td>
                <td>{{$po_item->artwork_code}}</td>
                <td>{{$po_item->batch}}</td>
                <td>{{$po_item->quantity}}</td>
                <td>{{$po_item->rate}}</td>
                <td>{{$po_item->gst_percentage}}</td>
                <td>{{$po_item->remarks}}</td>
            </tr>
            @endforeach



        </table>
    </div>
</div>







@endsection


@push('scripts')

@endpush
