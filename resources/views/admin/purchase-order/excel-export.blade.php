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

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">Client</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:160px;">PO No.</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">PO Date</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:200px;">Item</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Size</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:140px;">Quantity</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:80px;">Job No.</th>

                <th style="height:42px;font-size:14px;text-align:left;font-weight:500;vertical-align:middle;border:1px solid #0ab39c;width:120px;">Status</th>
            </tr>
        </thead>

        <tbody>

 <tbody>
        @php $sr = 1; @endphp

        @foreach($items as $po)
            @foreach($po->items as $item)
                <tr>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $sr++ }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $po->client?->company_name ?? '-' }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $po->po_number ?? '-' }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $po->po_date?->format('d/m/Y') ?? '-' }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $item->item_name ?? '-' }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $item->item_size ?? '-' }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $item->quantity ?? 0 }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">{{ $item->id }}</td>
                    <td style="height:36px;font-size:12px;text-align:left;vertical-align:middle;border:1px solid #0ab39c;">
                        {!! status($item->status_id) !!}
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>

    </table>

</body>
</html>