@extends('admin.layouts.app')
@push('links')

@endpush




@section('main')



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
             @can('add_admin')
             <a id="create" data-title="Create Admin" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
            </a>
            @endcan
        </div>


    </div>
</div>
</div>
<!-- end page title -->



<div class="row">


    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Challan Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Challan From</th>
                            <td>{{ $inward->challan_from }}</td>
                        </tr>

                        <tr>
                            <th>Challan Date</th>
                            <td>{{ $inward->challan_date?->format('d F Y') }}</td>
                        </tr>

                        <tr>
                            <th>Challan No.</th>
                            <td>{{ $inward->challan_no }}</td>
                        </tr>

                        <tr>
                            <th>E-Way Bill No.</th>
                            <td>{{ $inward->e_way_bill_no }}</td>
                        </tr>

                        <tr>
                            <th>Vehicle No.</th>
                            <td>{{ $inward->vehicle_no }}</td>
                        </tr>

                        <tr>
                            <th>Transport</th>
                            <td>{{ $inward->transport }}</td>
                        </tr>

                        <tr>
                            <th>Created By</th>
                            <td>{{ $inward->createdBy?->name }}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-12 col-sm-12">
       
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Challan Details</h5>
            </div>
            
            <div class="card-body">
               <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                   <tr>
                       <th>#</th>
                       <th>Quality</th>
                       <th>GSM</th>
                       <th>Width</th>
                       <th>Weight</th>
                       <th>Batch</th>
                       <th>Handling Unit</th>
                       <th>Core Dia</th>
                       <th>Reel Dia</th>
                       <th>Stock Date</th>
                       <th>Inward Date</th>
                   </tr>

                   @foreach($inward->items as $item)
                   <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->quality->name}}</td>
                    <td>{{$item->gsm}}</td>
                    <td>{{$item->width}}</td>
                    <td>{{$item->batch}}</td>
                    <td>{{$item->weight}}</td>
                    <td>{{$item->handling_unit}}</td>
                    <td>{{$item->core_dia}}</td>
                    <td>{{$item->reel_dia}}</td>
                    <td>{{$item->stock_date?->format('d F Y')}}</td>
                    <td>{{$item->created_at?->format('d F Y')}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
</div>



</div><!--end row-->



@endsection


@push('scripts')


@endpush
