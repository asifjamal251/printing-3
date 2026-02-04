@extends('admin.layouts.app')
@push('links')
<style>
    .error-row {
        background-color: #f8d7da !important; /* bootstrap danger light */
    }
</style>
@endpush
@section('main')

@if(count($rows))
<div class="container-fluid">

    {{-- ERRORS --}}
    @if(count($importErrors))
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            Import Errors
        </div>
        <div class="card-body">
            @foreach($importErrors as $error)
            <div class="mb-2">
                <strong>Row {{ $error['row'] }}:</strong>
                <ul class="mb-0">
                    @foreach($error['errors'] as $msg)
                    <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            All Imported Rows ({{ count($rows) }})
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered border-success align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        {{-- <th>Challan From</th> --}}
                        <th>Challan No</th>
                        <th>Challan Date</th>
                        <th>E-Way Bill</th>
                        <th>Vehicle No</th>
                        <th>Transport</th>

                        <th>Quality</th>
                        <th>GSM</th>
                        <th>Width</th>
                        {{-- <th>Allocation</th> --}}
                        <th>Weight</th>
                        <th>Core</th>
                        <th>Reel</th>
                        <th>Batch</th>
                        <th>Handling Unit</th>
                        <th>Sataus</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)

                    @php
                    $isValid = $row['_is_valid'];
                    // for error rows → show EXACT excel data
                    $data = $isValid ? $row : $row['_original'];
                    @endphp

                    <tr class="{{ !$isValid ? 'error-row' : '' }}">
                        <td>{{ $row['_excel_row'] }}</td>

                        {{-- <td>{{ $row['challan_from'] ?? '' }}</td> --}}
                        <td>{{ $data['challan_no'] ?? '' }}</td>
                        <td>{{ $row['_display_date'] }}</td>
                        <td>{{ $row['e_way_bill_no'] ?? '' }}</td>
                        <td>{{ $row['vehicle_no'] ?? '' }}</td>
                        <td>{{ $row['transport'] ?? '' }}</td>

                        <td>{{ $data['quality'] ?? '' }}</td>
                        <td>{{ $row['gsm'] ?? '' }}</td>
                        <td>{{ $row['width'] ?? '' }}</td>
                        {{-- <td>{{ $row['allocation'] ?? '' }}</td> --}}
                        <td>{{ $row['weight'] ?? '' }}</td>
                        <td>{{ $row['core_dia'] ?? '' }}</td>
                        <td>{{ $row['reel_dia'] ?? '' }}</td>
                        <td>{{ $row['batch'] ?? '' }}</td>
                        <td>{{ $data['handling_unit'] ?? '' }}</td>
                        <td>{!!  status($status) !!}</td>

                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($rows) && count($importErrors) == 0)
        <div class="card-footer text-end">
            <form method="POST" action="{{ route('admin.inward.confirm') }}">
                @csrf
                <button class="btn btn-success">
                    Confirm & Import
                </button>
            </form>
        </div>
        @endif

    </div>

</div>
@else
<div class="card">
    <div class="card-body">
        <h5 class="m-0 text-danger text-center">No Data For Import</h5>
    </div>
</div>
@endif
@endsection