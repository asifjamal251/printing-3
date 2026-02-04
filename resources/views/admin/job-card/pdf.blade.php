<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Card</title>

    <style>
        @page {
            margin: 8px;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .title{
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        th, td{
            border: 1px solid #000;
            padding: 6px 5px;
            vertical-align: middle;
        }

        th{
            font-size: 11px!important;
        }

        .text-center{
            text-align: center;
        }

        .section{
            background: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        .muted{
            color: #444;
        }

        .cancen-jobcard {
            position: absolute;
            transform: rotate(309deg);
            font-size: 55px;
            opacity: 0.4;
            top: 40%;
            bottom: 50%;
            left: 0;
            right: 0;
            margin: auto;
            display: inline-table;
            z-index:1;
            color:red;
        }
    </style>
</head>

<body style="position:relative;">

    @if($job_card->status_id == 5)
            <div class="cancen-jobcard text-danger">Cancelled</div>
        @endif

    <div class="title">{{ get_app_setting('app_name') }}</div>

    <table>
        <tr>
            <th>JOB NO.</th>
            <td>{{ $job_card->set_number }}</td>
            <th></th>
            <th>DIE NUMBER</th>
            <td>{{ $job_card->dye?->dye_info ?? 'N/A' }}</td>
            <th></th>
            <th>DATE</th>
            <td>{{ $job_card->created_at->format('d F Y') }}</td>
        </tr>

        <tr class="section muted">
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
            <td>{{ $item->item?->item_name }}</td>
            <td>{{ $item->item?->item_size }}</td>
            <td>{{ $item->item?->mfgBy?->company_name }}</td>
            <td>{{ $item->item?->mkdtBy?->company_name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->purchaseOrder?->po_number }}</td>
            <td>{{ $item->purchaseOrder?->po_date?->format('d/m/y') }}</td>
            <td></td>
        </tr>
        @endforeach

        <tr>
            <th colspan="3"></th>
            <th>TOTAL</th>
            <th>{{ $job_card->items->sum('quantity') }}</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr class="section">
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
            <td>{{ $paper?->product?->productType?->name }}</td>
            <td>{{ $job_card->sheet_size }}</td>
            <td>{{ $paper?->product?->gsm }}</td>
            <td>1/{{ $paper?->paper_divide }} - ({{ $paper?->product?->product_name }})</td>
            <td>{{ $paper?->total_sheet }}</td>
            <td>{{ ($paper?->required_sheet ?? 0) + ($paper?->wastage_sheet ?? 0) }}</td>
            <td></td>
            <td></td>
        </tr>
        @empty
        <tr>
            <td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td>
        </tr>
        @endforelse

        <tr>
            <th>REMARKS</th>
            <td colspan="5"></td>
            <th>OPERATOR SIGN.</th>
            <th></th>
        </tr>

        <tr class="section">
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

        <tr class="section muted">
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
            <td>{{ $item->item?->item_name }}</td>
            <td>{{ $item->itemProcessDetail?->colour }}</td>
            <td>{{ $item->itemProcessDetail?->back_print }}</td>
            <td>{{ $item->itemProcessDetail?->embossing }}</td>
            <td>{{ $item->itemProcessDetail?->leafing }}</td>
            <td>{{ $item->itemProcessDetail?->braille }}</td>
            <td>{{ $item->itemProcessDetail?->coatingType?->name }}</td>
            <td>{{ $item->itemProcessDetail?->otherCoatingType?->name }}</td>
        </tr>
        @empty
        <tr>
            <td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td>
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
            <th style="height:40px;">OTHER INSTRUCTION</th>
            <td colspan="7"></td>
        </tr>

        <tr>
            <th>PRINTING OPERATOR</th>
            <th>COATING OPERATOR</th>
            <th>DIE OPERATOR</th>
            <th>EMB/LEAF</th>
            <th>PASTING</th>
            <th colspan="3">AUTH. SIGN.</th>
        </tr>

        <tr>
            <td style="height:40px;"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3"></td>
        </tr>
    </table>

</body>
</html>