<div class="card m-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle datatable table-sm border-success table-bordered nowrap m-0"
                style="width:100%">
                <thead class="gridjs-thead">
                    <tr>
                        <th style="width:12px">Sr</th>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Rate</th>
                        <th>GST(%)</th>
                        <th>Order Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @if($rates->count() > 0)
                    @foreach($rates as $rate)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$rate->product->fullname}}</td>
                            <td>{{$rate->materialOrder->vendor->company_name}}</td>
                            <td>{{$rate->rate}}</td>
                            <td>{{$rate->gst}}</td>
                            <td>{{$rate->quantity}}</td>
                        </tr>
                    @endforeach

                    @else
                        <tr class="text-center">
                            <th colspan="6">No Record Found.</th>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>
</div>