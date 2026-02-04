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

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:60px;">
                Sr
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">
                Set No.
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">
                MFG By
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">
                MKDT By
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:260px;">
                Item
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:110px;">
                Job Type
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:110px;">
                Colour
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:240px;">
                Paper
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">
                UPS
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:100px;">
                Quantity
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:90px;">
                Die
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">
                Coating
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">
                Other Coating
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:90px;">
                Embossing
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:90px;">
                Leafing
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:110px;">
                Back Print
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:90px;">
                Braille
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">
                Rate
            </th>

            <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:110px;">
                Status
            </th>

        </tr>
    </thead>

    <tbody>
        @php $sn = 1; @endphp
        @foreach($items as $item)

            @php
                $setNo = $item->jobCard?->set_number ?? '--';

                $mfgBy = $item->item?->mfgBy?->company_name ?? '--';
                $mkdtBy = $item->item?->mkdtBy?->company_name ?? '--';

                $itemName = $item->item_name ?? $item->purchaseOrderItem?->item_name ?? '--';
                $itemSize = $item->item_size ?? $item->purchaseOrderItem?->item_size ?? '';
                $itemFull = trim($itemName . (!empty($itemSize) ? " ({$itemSize})" : ''));

                $jobType = $item->itemProcessDetail?->job_type ?? '--';
                $colour  = $item->itemProcessDetail?->colour ?? '--';

                $paper = $item->jobCard?->jobCardProducts?->pluck('product.full_name')->filter()->unique()->implode(', ');
                if (empty($paper)) {
                    $productType = $item->itemProcessDetails?->productType?->name ?? $item->itemProcess?->productType?->name ?? null;
                    $gsm = $item->item?->lastItem?->gsm ?? $item->itemProcess?->gsm ?? null;
                    $paper = trim(implode('-', array_filter([$productType, $gsm])), '-') ?: '--';
                }

                $ups = $item->ups ?? '--';
                $qty = $item->itemProcessDetail?->quantity ?? '--';

                $die = $item->itemProcessDetail?->dye?->dye_number ?? '--';

                $coating = $item->item?->coatingType?->name ?? '--';
                $otherCoating = $item->item?->otherCoatingType?->name ?? '--';

                $embossing = $item->itemProcessDetail?->embossing ?? '--';
                $leafing   = $item->itemProcessDetail?->leafing ?? '--';
                $backPrint = $item->itemProcessDetail?->back_print ?? '--';
                $braille   = $item->itemProcessDetail?->braille ?? '--';

                $rate = $item->rate ?? '--';

                $statusText = strip_tags(status($item->status_id));
            @endphp

            <tr>
                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $sn++ }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $setNo }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $mfgBy }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $mkdtBy }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $itemFull }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $jobType }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $colour }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $paper }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $ups }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $qty }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $die }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $coating }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $otherCoating }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $embossing }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $leafing }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $backPrint }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $braille }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $rate }}
                </td>

                <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                    {{ $statusText }}
                </td>
            </tr>

        @endforeach
    </tbody>
</table>

</body>
</html>