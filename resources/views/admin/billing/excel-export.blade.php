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
         <tr style="vertical-align:middle;text-align: center;">
            
             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Client</th>
             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Item Name</th>

              <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Item Size</th>

              <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Product Type</th>

               <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">GSM</th>

             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Quantity</th>
             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">PO Number</th>

             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Job Number</th>
             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">PO Date</th>
 
             <th style="height: 42px;font-size:14px; text-align:left; font-weight:500; vertical-align: middle;border: 1px solid #0ab39c; width:160px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @php $sn = 1; @endphp
        @foreach($items as $item)
        <tr>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item->purchaseOrder?->client->company_name }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item?->purchaseOrderItem?->item_name }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item?->purchaseOrderItem?->item_size }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item?->purchaseOrderItem?->productType?->name }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item?->purchaseOrderItem?->gsm }}</td>
            
            
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item?->purchaseOrderItem?->quantity }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item->purchaseOrder?->po_number }}</td>

            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item->jobCard->set_number }}</td>
            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{{ $item->purchaseOrder?->po_date->format('d/m/Y') }}</td>

            <td style="height: 36px;font-size:12px; text-align:left;vertical-align: middle;border: 1px solid #0ab39c;">{!! status($item->JobCart?->status_id) !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>