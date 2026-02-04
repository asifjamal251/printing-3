<?php

namespace App\Exports;

use App\Models\JobCardItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CartonRateExport implements FromView, ShouldAutoSize, WithTitle
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = JobCardItem::with([
            'item.mfgBy',
            'item.mkdtBy',
            'item.coatingType',
            'item.otherCoatingType',
            'purchaseOrder.client',
            'purchaseOrderItem',
            'jobCard',
            'itemProcessDetail.dye',
            'jobCard.jobCardProducts.product',
        ]);

        if (!empty($this->filters['mkdt_by'])) {
            $mkdtBy = $this->filters['mkdt_by'];

            $query->whereHas('item', function ($q) use ($mkdtBy) {
                $q->where('mkdt_by', $mkdtBy);
            });
        }

        if (!empty($this->filters['mfg_by'])) {
            $mfgBy = $this->filters['mfg_by'];

            $query->whereHas('item', function ($q) use ($mfgBy) {
                $q->where('mfg_by', $mfgBy);
            });
        }

        if (!empty($this->filters['export_status'])) {
            $query->where('status_id', $this->filters['export_status']);
        }

        if (!empty($this->filters['export_po_date'])) {
            $dateRange = str_replace(' to ', ' - ', $this->filters['export_po_date']);
            $dates = explode(' - ', $dateRange);

            $from = date('Y-m-d', strtotime(trim($dates[0])));
            $to = isset($dates[1]) ? date('Y-m-d', strtotime(trim($dates[1]))) : $from;

            $query->whereHas('purchaseOrder', function ($q) use ($from, $to) {
                $q->whereBetween('po_date', [$from, $to]);
            });
        }

        $items = $query->orderByDesc('id')->get();

        return view('admin.carton-rate.excel-export', compact('items'));
    }

    public function title(): string
    {
        return 'Carton Rate';
    }
}