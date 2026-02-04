@php
    $carts = session()->get('cart', []);
    $reelItemIds = array_keys($carts); 
    $reelItems = App\Models\ReelInwardItem::whereIn('id', $reelItemIds)->get()->keyBy('id');
@endphp

<div class="table-responsive">
    <table id="cartDatatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Godown</th>
                <th>Quality</th>
                <th>GSM</th>
                <th>Size</th>
                <th>Cut Size</th>
                <th>Weight</th>
                <th>Sheet Per Packet</th>
                <th>Packet Weight</th>
                <th>Bundle Pack</th>
                <th>Number Of Packet</th>
                <th>Rate</th>
                <th>GST</th>
            </tr>
        </thead>
        <tbody>
            @if(count($carts) > 0)
                @foreach($carts as $id => $cart)
                    @php
                        $reelItem = $reelItems[$id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reelItem ? $reelItem->godown->display_name : '-' }}</td>
                        <td>{{ $cart['quality'] ?? '-' }}</td>
                        <td>{{ $cart['gsm'] ?? '-' }}</td>
                        <td>{{ $cart['size'] ?? '-' }}</td>
                        <td><div class="m-0 form-group"><input type="text" data-id="{{ $id }}" class="form-control form-control-sm cutSize cutSize-{{ $id }}" name="cut_size" placeholder="Cut Size" style="width:100px;" value="{{ $cart['cut_size'] ?? '-' }}"><small class="text-danger"></small></div></td>
                        <td>{{ $cart['weight'] ?? '-' }}</td>
                        <td>{{ $cart['sheet_per_packet'] ?? '-' }}</td>
                        <td>{{ $cart['packet_weight'] ?? '-' }}</td>
                        <td>{{ $cart['bundle_pack'] ?? '-' }}</td>
                        <td>{{ $cart['number_of_packet'] ?? '-' }}</td>
                        <td><div class="m-0 form-group"><input type="text" data-id="{{ $id }}" class="form-control form-control-sm rate rate-{{ $id }}" name="rate" placeholder="Rate" style="width:100px;" value="{{ $cart['rate'] ?? '-' }}"><small class="text-danger"></small></div></td>
                        <td><div class="m-0 form-group"><input type="text" data-id="{{ $id }}" class="form-control form-control-sm gst gst-{{ $id }}" name="gst" placeholder="GST" style="width:100px;" value="{{ $cart['gst'] ?? 12 }}"><small class="text-danger"></small></div></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13" class="text-center">Record not found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<script>
    $('#cartDatatable').DataTable({
        "processing": true,
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "lengthMenu": [15]
    });
</script>