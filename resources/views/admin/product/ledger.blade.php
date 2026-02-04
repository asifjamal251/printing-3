@extends('admin.layouts.master')
@push('links')
@endpush

@php
if(!function_exists('orderReceipt')){
    function orderReceipt($type, $module){
        if($type === 'in'){
            $docNo = '<a href="'.route('admin.material-inward.show', $module->id).'">'.$module->receipt_no.'</a>';
        }

        if($type === 'out'){
            $docNo = '<a href="'.route('admin.sales.show', $module->id).'">'.$module->order_no.'</a>';
        }
        return $docNo;
    }

}
@endphp


@section('main')







        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="card-title m-0 d-flex justify-content-center gap-5 align-items-center">
                            <div class="">
                                {!! userName($company->id) !!}
                            </div>
                            <p class="m-0 border-end"></p>
                            <div class="">
                                <p class="mb-1">{{$product->name}}</p>
                                <p class="m-0 text-muted fs-14">{{$product->name_cm}}</p>
                            </div>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="datatable table-sm border-secondary table-hover table table-bordered nowrap align-middle" style="width:100%">
                                <thead class="gridjs-thead">
                                    <tr>
                                        <th style="width:12px">Si</th>
                                        <th>Reference</th>
                                        <th>Reference ID</th>
                                        <th>Type</th>
                                        <th>Old Quantity</th>
                                        <th>New Quantity</th>
                                        <th>Current Quantity</th>
                                        <th>Remarks</th>
                                        <th>Transaction By</th>
                                        <th>Created AT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product_ledgers as $ledger)
                                    @php
                                        $relatedModule = $ledger->relatedModule ? $ledger->relatedModule()->first() : null;
                                    @endphp
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>
                                                @if($relatedModule)
                                                {!! orderReceipt($ledger->transaction_type, $relatedModule) !!}
                                                @else
                                                    New Added Product
                                                @endif
                                            </td>
                                            <td>
                                                @if($ledger->materialInward)
                                                   <a target="_blank" class="text-secondary" href="{{route('admin.material-inward.show', $ledger->materialInward->id)}}"> 
                                                        {{$ledger->materialInward->receipt_no}}
                                                    </a>
                                                @endif
                                                @if($ledger->sale)
                                                    <a target="_blank" class="text-success" href="{{route('admin.sales.show', $ledger->sale->id)}}"> 
                                                         {{$ledger->sale->order_no}}
                                                     </a>
                                                @endif

                                                @if(!$ledger->sale && !$ledger->materialInward)
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($ledger->transaction_type === 'in')
                                                    <span class="badge  bg-secondary-subtle text-secondary badge-border">In</span>
                                                @else 
                                                    <span class="badge bg-success-subtle text-success badge-border">Out</span>
                                                @endif
                                                
                                            </td>
                                            <td>{{$ledger->old_quantity}}</td>
                                            <td>{{$ledger->new_quantity}}</td>
                                            <td>{{$ledger->current_quantity}}</td>
                                            <td>{{$ledger->remarks}}</td>
                                            <td> {!! userName($ledger->transaction_by) !!}</td>
                                            <td>{{$ledger->created_at->format('d F, Y | h:s a')}}</td>
                                        </tr>
                                    @endforeach
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
    $(document).ready(function(){
        $('#datatable').DataTable();
    });
</script>
@endpush