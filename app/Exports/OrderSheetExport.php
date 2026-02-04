<?php

namespace App\Exports;

use App\Models\OrderSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderSheetExport implements FromView, ShouldAutoSize, WithTitle
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = OrderSheet::with([
            'item.productType',
            'purchaseOrder.client',
        ]);

        if (!empty($this->filters['export_status'])) {
            $query->where('status_id', $this->filters['export_status']);
        }

        if (!empty($this->filters['export_mkdt_by'])) {
            $query->whereHas('item', function ($q) {
                $q->where('mkdt_by', $this->filters['export_mkdt_by']);
            });
        }

        if (!empty($this->filters['export_mfg_by'])) {
            $query->whereHas('item', function ($q) {
                $q->where('mfg_by', $this->filters['export_mfg_by']);
            });
        }

        if (!empty($this->filters['client'])) {
            $query->whereHas('item', function ($q) {
                $q->where('mfg_by', $this->filters['client']);
            });
        }

        if (!empty($this->filters['export_po_date'])) {
            $dateRange = str_replace(' to ', ' - ', $this->filters['export_po_date']);
            $dates = explode(' - ', $dateRange);

            $from = date('Y-m-d', strtotime(trim($dates[0])));
            $to = isset($dates[1])
                ? date('Y-m-d', strtotime(trim($dates[1])))
                : $from;

            $query->whereHas('purchaseOrder', function ($q) use ($from, $to) {
                $q->whereBetween('po_date', [$from, $to]);
            });
        }

        $items = $query->orderByDesc('id')->get();

        return view('admin.order-sheet.excel-export', compact('items'));
    }

    public function title(): string
    {
        return 'Order Sheet';
    }
}