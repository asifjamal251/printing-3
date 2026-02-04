<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Job No</th>
            <th>Item Name</th>
            <th>Operator</th>
            <th>Product</th>
            <th>Total Sheet</th>
            <th>Impression</th>
            <th>Paper Devide</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->jobCard->set_number ?? '-' }}</td>
                <td>
    {{ $item->jobCard?->items?->pluck('item_name')->implode(', ') ?? '-' }}
</td>
                <td>{{ $item->operator->name ?? '-' }}</td>
                
                <td>
                	{{$item->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return
                                ($paper?->product?->product_name ?? '-') .
                                ' - ' .
                                ($paper?->product?->productType?->name ?? '-') .
                                ' - ' .
                                ($paper?->product?->gsm ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),}}
                </td>
                <td>{{$item->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->total_sheet ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),}}</td>

                <td>{{$item->jobCard?->jobCardProducts
                            ->map(function ($paper) {
                                $required = $paper?->required_sheet ?? 0;
                                $wastage  = $paper?->wastage_sheet ?? 0;

                                return ($required + $wastage) ?: '-';
                            })
                            ->implode('<hr class="border-secondary my-1">'),}}</td>


                <td>{{$item->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->paper_divide ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">')}}</td>


                <td>{!! status($item->status_id) !!}</td>

            </tr>
        @empty
            <tr>
                <td colspan="6">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>