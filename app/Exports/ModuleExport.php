<?php

namespace App\Exports;

use App\Models\OrderSheet;
use App\Models\PaperCutting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ModuleExport implements FromView, ShouldAutoSize, WithTitle
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {

        $moduleMap = [
            'PaperCutting' => PaperCutting::class,
            'Printing'     => Printing::class,
            'Lamination'   => Lamination::class,
        ];

        if (!isset($moduleMap[$this->filters['module']])) {
            abort(404, 'Invalid module');
        }

        $model = $moduleMap[$this->filters['module']];

        $query = $model::with(['jobCard']);



        if (!empty($this->filters['filter_operator'])) {
            $query->where('operator_id', $this->filters['filter_operator']);
        }

        if (!empty($this->filters['filter_status'])) {
            $query->where('status_id', $this->filters['filter_status']);
        }

        if (!empty($this->filters['filter_job_no'])) {
            $query->whereHas('jobCard', function ($q) {
                $q->where('set_number', $this->filters['filter_job_no']);
            });
        }

        if (!empty($this->filters['filter_item_name'])) {
            $query->whereHas('item', function ($q) {
                $q->where('item_name', $this->filters['filter_item_name']);
            });
        }



        $items = $query->orderByDesc('id')->get();

        //dd($items);

        return view('admin.common.excel-export', compact('items'));
    }

    public function title(): string{
        $title = $this->filters['module'] ?? 'Sheet1';

        // Excel sheet name rules
        $title = preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $title);

        return substr($title, 0, 31);
    }
}