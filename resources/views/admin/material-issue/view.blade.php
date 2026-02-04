@extends('admin.layouts.master')
@push('links')
<style>
    table{
        vertical-align: middle!important;
    }
    table , tr, td, th {
        border-collapse: collapse;
    }
    td, th {
        padding:8px 4px !important;
        font-size: 12px;
    }
    th {

    }

    .cancel-order {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        display: inline-table;
        color: red;
        font-size: 70px;
        transform: rotate(325deg);
        opacity: 0.15;
    }
</style>
@endpush


@section('main')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>

            <div class="hstack gap-2 justify-content-end d-print-none">
                {{-- <a href="javascript:window.print()" class="btn btn-sm btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a> --}}
                {{-- <a href="{{ route('admin.sale.download.pdf', $sale->id) }}" class="btn btn-sm btn-primary download-btn"><i class="ri-download-2-line align-bottom me-1"></i> Download (<span class="downloadCount">{{$sale->download_count}}</span>)</a> --}}
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                @if($material_issue->status_id === 5)
                <div class="cancel-order">Cancelled</div>
                @endif
                <table class="table table-bordered border-secondary">
                    
                    <colgroup>
                        @for ($i = 0; $i < 10; $i++)
                        <col style="width:10%;">
                        @endfor
                    </colgroup>

                    <tr class=" text-secondary">
                        <td colspan="5"><b>Material Issued From</b></td>
                        <td colspan="5"><b>Material Issue To</b></td>
                    </tr>

                    <tr style="vertical-align:top;">
                        <td colspan="5">{{$material_issue->createdBy->name}}</td>
                        <td colspan="5">{{$material_issue->department->name}}</td>
                    </tr>

                    <tr>
                        <th class=" text-secondary" colspan="2">Material Issue No./th>
                        <td colspan="2">{{ $material_issue->material_issue_number }}</td>
                        <td colspan="2"></td>
                        <th class=" text-secondary" colspan="2">Material Issue Date</th>
                        <td colspan="2">{{ $material_issue->created_at->format('d F, Y | h:i a') }}</td>
                    </tr>

                    @if($material_issue->remarks)
                    <tr>
                        <th class=" text-secondary">Remarks</th>
                        <td colspan="9">{{ $material_issue->remarks }}</td>
                    </tr>
                    @endif


                    <tr class="bg-secondary-subtle text-secondary">
                        <th >Sr No.</th>
                        <th colspan="3">Product</th>
                        <th >Quantity</th>
                        <th >Item/Packet</th>
                        <th >WT/PC/PKT</th>
                        <th >Total WT</th>
                        <th colspan="2">Remarks</th>
                    </tr>

                    @foreach($material_issue->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td colspan="3">{{ $item->product->full_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->productAttribute->item_per_packet }}</td>
                        <td>{{ $item->productAttribute->weight_per_piece }}</td>
                        <td>{{ $item->weight }}</td>
                        <td colspan="2">{{ $item->remarks }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="4" class="text-end">Total Quantity</th>
                        <td>{{$material_issue->items->sum('quantity')}}</td>
                        <th colspan="2" class="text-end">Total Weight</th>
                        <td>{{$material_issue->items->sum('weight')}}</td>
                        <td colspan="2"></td>
                    </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection



    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            let countSpan = this.querySelector('.downloadCount');

            if (!countSpan) return;

            let current = parseInt(countSpan.textContent) || 0;
            countSpan.textContent = current + 1;

            // IMPORTANT:
            // We do NOT preventDefault()
            // Download continues normally
        });
    });

});
</script>
    @endpush