<!DOCTYPE html>
<html>
<head>
    <style>
        th, td {
            border: 1px solid #333;
            padding: 4px;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<table class="table table-sm border-success table-bordered table-centered table-nowrap mb-0">
    <thead>
        <tr style="vertical-align:middle;text-align:center;">
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:40px;">Sr</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">MFG BY</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">MKDT BY</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">PO Date</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Last Date</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:200px;">Item</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Size</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:100px;">Colour</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:140px;">Paper</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:140px;">Last QTY</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:100px;">Last Job Type</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">Die</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">Set No.</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Coating</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Other Coating</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">Emb</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">Leaf</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">B.P</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">Braille</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">Rate</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">PO QTY</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">Final QTY</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">GSM</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:90px;">Job Type</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">UPS</th>
            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:70px;">Urgent</th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:250px;">Remarks</th>
        </tr>
    </thead>

    <tbody>
        @php $sn = 1; @endphp
        @foreach($items as $item)

            @php
                $mfgBy = $item->item?->mfgBy?->company_name ?? '--';
                $mkdtBy = $item->item?->mkdtBy?->company_name ?? '--';

                $poDate = $item->purchaseOrder?->po_date?->format('d/m/Y') ?? '--';
                $lastDate = $item->item?->lastItem?->created_at?->format('d/m/Y') ?? '--';

                $itemName = $item->purchaseOrderItem?->item_name ?? '--';
                $itemSize = $item->purchaseOrderItem?->item_size ?? '--';

                $colour = $item->item?->colour ?? '--';

                $productType = $item->item?->lastItem?->productType?->name
                    ?? $item->itemProcess?->productType?->name
                    ?? '--';

                $gsm = $item->item?->lastItem?->gsm
                    ?? $item->itemProcess?->gsm
                    ?? '--';

                $paper = trim($productType . '-' . $gsm, '-');

                $lastQty = $item->item?->lastItem?->quantity ?? '--';
                $lastJobType = $item->item?->lastItem?->job_type ?? '--';

                $dieNo = $item->item?->lastItem?->dye?->dye_number ?? '--';
                $setNo = $item->item?->lastItem?->set_number ?? '--';

                $coating = $item->item?->coatingType?->name ?? '--';
                $otherCoating = $item->item?->otherCoatingType?->name ?? '--';

                $emb = $item->item?->embossing ?? '--';
                $leaf = $item->item?->leafing ?? '--';
                $bp = $item->item?->back_print ?? '--';
                $braille = $item->item?->braille ?? '--';

                $rate = $item->purchaseOrderItem?->rate ?? 0;

                $poQty = $item->purchaseOrderItem?->quantity ?? '--';
                $finalQty = $item->final_quantity ?? $poQty;

                $currentGsm = $item->itemProcess?->gsm ?? $gsm;
                $currentJobType = $item->job_type ?? $lastJobType;
                $currentUps = $item->ups ?? ($item->item?->lastItem?->ups ?? '--');

                $urgent = $item->urgent ?? 'No';
                $remarks = $item->purchaseOrderItem?->remarks ?? '';
            @endphp

            <tr>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $sn++ }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $mfgBy }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $mkdtBy }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $poDate }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $lastDate }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $itemName }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $itemSize }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $colour }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $paper }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $lastQty }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $lastJobType }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $dieNo }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $setNo }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $coating }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $otherCoating }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $emb }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $leaf }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $bp }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $braille }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $rate }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $poQty }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $finalQty }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $currentGsm }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $currentJobType }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $currentUps }}</td>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $urgent }}</td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>