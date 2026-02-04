<?php

namespace App\Exports;

use App\Models\OrderSheet;
use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PurchaseOrderExport implements FromView, ShouldAutoSize, WithTitle
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = PurchaseOrder::orderBy('client_id', 'asc')->orderBy('status_id', 'asc')->with('client');

        if (!empty($this->filters['export_status'])) {
            $query->where('status_id', $this->filters['export_status']);
        }

        if (!empty($this->filters['export_clients'])) {
            $query->whereIn('client_id', $this->filters['export_clients']);
        }
        if (!empty($this->filters['export_po_date'])) {
            $dateRange = str_replace(' to ', ' - ', $this->filters['export_po_date']);
            $dates = explode(' - ', $dateRange);

            $from = date('Y-m-d', strtotime(trim($dates[0])));
            $to = isset($dates[1])
                ? date('Y-m-d', strtotime(trim($dates[1])))
                : $from;

            $query->whereBetween('po_date', [$from, $to]);
          
        }

        $items = $query->orderByDesc('id')->get();

        return view('admin.purchase-order.excel-export', compact('items'));
    }

    public function title(): string
    {
        return 'PO Items';
    }
}