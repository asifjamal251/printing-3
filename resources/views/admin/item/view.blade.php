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
                <h5 class="mb-0 card-title">MKDT BY Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Company Name</th>
                            <td>{{ $item?->mkdtBy?->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $item?->mkdtBy?->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $item?->mkdtBy?->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $item?->mkdtBy?->city->name }}</td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">MFG BY Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Company Name</th>
                            <td>{{ $item?->mfgBy?->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $item?->mfgBy?->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $item?->mfgBy?->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $item?->mfgBy?->city->name }}</td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>


    </div>







    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Item Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Item Name</th>
                            <td>{{ $item->item_name }}</td>
                        </tr>
                        <tr>
                            <th>Item Size</th>
                            <td>{{ $item->item_size }}</td>
                        </tr>


                        <tr>
                            <th>Artwork Code</th>
                            <td>{{ $item->artwork_code }}</td>
                        </tr>

                        <tr>
                            <th>Colour</th>
                            <td>{{ $item->colour }}</td>
                        </tr>

                        <tr>
                            <th>Paper Type(Board Quality)</th>
                            <td>{{ $item?->productType?->name }}</td>
                        </tr>

                        <tr>
                            <th>GSM</th>
                            <td>{{ $item->gsm }}</td>
                        </tr>

                        <tr>
                            <th>Coating</th>
                            <td>{{ $item->coatingType->name }}</td>
                        </tr>

                        <tr>
                            <th>Other Coating</th>
                            <td>{{ $item->otherCoatingType->name }}</td>
                        </tr>


                        <tr>
                            <th>Embossing</th>
                            <td>{{ $item->embossing }}</td>
                        </tr>



                        <tr>
                            <th>Leafing</th>
                            <td>{{ $item->leafing }}</td>
                        </tr>


                        <tr>
                            <th>Back Print</th>
                            <td>{{ $item->back_print }}</td>
                        </tr>

                        <tr>
                            <th>Braille</th>
                            <td>{{ $item->braille }}</td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Item Process Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Date</th>
                            <th>Job Type</th>
                            <th>Set No.</th>
                            <th>Dye Number</th>
                            <th>Sheet Size</th>
                            <th>Product Type</th>
                            <th>GSM</th>
                            <th>Paper</th>
                            <th>UPS</th>
                            <th>Printing Machine</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                        </tr>

                        @foreach($item->itemProcessDetails as $processItem)
                        <tr>
                            <td>{{$processItem->created_at?->format('d F Y')??'--'}}</td>
                            <td>{{$processItem->job_type??'--'}}</td>
                            <td>{{$processItem?->set_number??'--'}}</td>
                            <td>{{$processItem?->dye?->dye_number??'--'}}</td>
                            <td>{{$processItem?->sheet_size??'--'}}</td>
                            <td>{{$processItem?->productType?->name??'--'}}</td>
                            <td>{{$processItem?->gsm??'--'}}</td>
                            <td>{{$processItem?->jobCard?->jobCardProducts?->pluck('product.full_name')->filter()->unique()->implode(', ') ?? '--',}}</td>
                            <td>{{$processItem?->ups??'--'}}</td>
                            <td>{{$processItem?->printingMachine?->name??'--'}}</td>
                            <td>{{$processItem?->quantity??'--'}}</td>
                            <td>{{$processItem?->rate??'--'}}</td>
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
