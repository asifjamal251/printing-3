@extends('admin.layouts.master')
@push('links')
<style>
    .custom-padding{
        padding:12px 16px !important;
    }
    .cancen-jobcard {
        position: absolute;
        transform: rotate(309deg);
        font-size: 55px;
        opacity: 0.4;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        display: inline-table;
        z-index:1;
    }
</style>
@endpush




@section('main')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>
            <a class="btn btn-sm btn-primary" href="{{route('admin.job-card.pdf', $job_card->id)}}"><i class="bx bx-download  align-middle me-2"></i> Download PDF</a>
        </div>


    </div>
</div>
<!-- end page title -->




<div class="row">




    <div class="col-md-12 col-sm-12 position-relativ">
        @if($job_card->status_id == 5)
            <div class="cancen-jobcard text-danger">Cancelled</div>
        @endif
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title text-center">{{get_app_setting('app_name')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table mb-0 align-middle table-sm border-secondary table-bordered nowrap" style="width:100%">

                        <tr>
                            <th>JOB NO.</th>
                            <td>{{$job_card->set_number}}</td>
                            <th></th>
                            <th>DIE NUMBER</th>
                            <td>{{$job_card->dye?->dye_info??'N/A'}}</td>
                            <th></th>
                            <th>DATE</th>
                            <td>{{$job_card->created_at->format('d F Y')}}</td>
                        </tr>

                        <tr class="text-center bg-secondary-subtle text-secondary">
                            <th>CARTON NAME</th>
                            <th>SIZE</th>
                            <th>MFG</th>
                            <th>MKTD</th>
                            <th>QUANTITY</th>
                            <th>PO NO</th>
                            <th>PO DATE</th>
                            <th></th>
                        </tr>
                        @foreach($job_card->items as $item)
                        <tr>
                            <td>{{$item->item->item_name}}</td>
                            <td>{{$item->item->item_size}}</td>
                            <td>{{$item->item->mfgBy->company_name}}</td>
                            <td>{{$item->item->mkdtBy->company_name}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->purchaseOrder->po_number}}</td>
                            <td>{{$item->purchaseOrder->po_date?->format('d/m/y')}}</td>
                            <td></td>
                        </tr>
                        @endforeach



                        <tr>
                            <th colspan="3"></th>
                            <th>TOTAL</th>
                            <th>{{$job_card->items->sum('quantity')}}</th>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>

                        <!-- PAPER CUTTING SECTION -->
                        <tr class="text-center bg-secondary-subtle text-secondary">
                            <th colspan="8">PAPER CUTTING</th>
                        </tr>

                        <tr>
                            <th>BOARD QUALITY</th>
                            <th>SHEET SIZE</th>
                            <th>GSM</th>
                            <th>CUT SIZE</th>
                            <th>SHEET QUANTITY</th>
                            <th>IMPRESSION</th>
                            <th></th>
                            <th></th>
                        </tr>

                        @forelse($job_card->jobCardProducts as $paper)
                        <tr>
                            <td>{{$paper?->product?->productType?->name}}</td>
                            <td>{{$paper?->product?->product_name}}</td>
                            <td>{{$paper?->product?->gsm}} </td>
                            <td>1/{{$paper?->paper_divide}} - ({{ $job_card->sheet_size }} )</td>
                             <td>{{$paper?->total_sheet}} </td>
                            <td>{{$paper?->required_sheet + $paper?->wastage_sheet}}</td>
                            <td></td>
                            <td></td>
                           
                        </tr>
                        @empty
                            <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>
                        @endforelse



                        <tr>
                            <th>REMARKS</th>
                            <td colspan="5"></td>
                            <th>OPERATOR SIGN.</th>
                            <th></th>
                        </tr>

                        <!-- PRINTING DEPARTMENT -->
                        <tr class="text-center bg-secondary-subtle text-secondary">
                            <th colspan="8">OTHER DEPARTMENT</th>
                        </tr>

                        <tr>
                            <th>NEW</th>
                            <td></td>
                            <th>REPEAT</th>
                            <td></td>
                            <th>PRINT WITH SAMPLE</th>
                            <td></td>
                            <td>PRINTING OPT</td>
                            <td>{{$job_card->stages->firstWhere('name', 'Printing')?->operator?->name ?? 'NA',}}</td>
                        </tr>

                        <tr class="text-center bg-secondary-subtle text-secondary">
                            <th>CARTON NAME</th>
                            <th>COLOUR</th>
                            <th>BACK PRINTING</th>
                            <th>EMBOSSING</th>
                            <th>LEAFING</th>
                            <th>BRAILLE</th>
                            <th>COATING</th>
                            <th>OTHER COATING</th>
                        </tr>
                        @forelse($job_card->items as $item)
                            <tr>
                                <td>{{$item->item->item_name}}</td>
                                <td>{{$item->itemProcessDetail->colour}}</td>
                                <td>{{$item->itemProcessDetail->back_print}}</td>
                                <td>{{$item->itemProcessDetail->embossing}}</td>
                                <td>{{$item->itemProcessDetail->leafing}}</td>
                                <td>{{$item->itemProcessDetail->braille}}</td>
                                <td>{{$item->itemProcessDetail->coatingType?->name}}</td>
                                <td>{{$item->itemProcessDetail->otherCoatingType?->name}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>
                        @endforelse

            

                        <tr class="text-center bg-secondary-subtle text-secondary">
                            <th>NVZ</th>
                            <th>OPERATOR SIGN.</th>
                            <td></td>
                            <td>REMARKS</td>
                            <td colspan="4">{{$job_card?->remarks}}</td>
                            
                        </tr>

                        <tr>
                            <th style="padding:20px 3px;">OTHER INSTRUCTION</th>
                            <td colspan="7"></td>
                        </tr>

                        
                        <tr>
                            <th>PRINTING OPERATOR</th>
                            <th>COATING OPERATOR</th>
                            <th>DIE OPERATOR</th>
                            <th>EMB/LEAF</th>
                            <th>PASTING</th>
                            <th colspan="3">AUTH. SING.</th>
                        </tr>
                        <tr>
                            <td style="padding:20px;3px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="3"></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->


@endsection


@push('scripts')
@endpush
