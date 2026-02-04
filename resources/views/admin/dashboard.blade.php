@extends('admin.layouts.master')
@push('links')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style type="text/css">
    .accordion-button:not(.collapsed)::after{
        display:none;
    }

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #066b5e !important;
    border: 1px solid #066b5e !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff !important;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 0px;
    position: relative;
    left: -6px;
    top: 1px !important;
}
select#filterClient {
    max-height: 40px;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
    padding-top:1px !important;
    padding-bottom:1px !important;
    padding-left:5px !important;
}
</style>
@endpush




@section('main')
@php
use Carbon\Carbon;
$todayDate = Carbon::now()->format('Y-m-d');
$startDate = $todayDate;
$endDate = $todayDate;
@endphp


<!-- start page title -->
<div class="row">
    <div class="col-12">

        <div class="offcanvas-body p-0">
        </div>
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            
            {{-- <button class="btn btn-primary btn-label" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="bx bx-slider-alt fs-18"></i></button> --}}

        </div>
    </div>
</div>
</div>
<!-- end page title -->




{{-- <div class="card">
    <div class="card-body">

        {{ html()->form('POST', route('admin.dashboard.filter'))->attribute('enctype', 'multipart/form-data')->id('storeForm')->open() }}
        
        <div class="form-group{{ $errors->has('duplicate_product_type') ? ' has-error' : '' }}">
            {{ html()->label('Duplicate Product Type', 'duplicate_product_type') }}
            {{ html()->select('duplicate_product_type[]', App\Models\ProductType::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control select2')->attribute('multiple') }}
            <small class="text-danger">{{ $errors->first('duplicate_product_type') }}</small>
        </div>

        <div class="form-group{{ $errors->has('replace_with') ? ' has-error' : '' }}">
            {{ html()->label('Replace With', 'replace_with') }}
            {{ html()->select('replace_with', App\Models\ProductType::orderBy('name', 'asc')->pluck('name', 'id'))->class('form-control select2') }}
            <small class="text-danger">{{ $errors->first('replace_with') }}</small>
        </div>
        {{ html()->button('Save Data')->type('submit')->class('btn btn-success bg-gradient') }}
        {{ html()->form()->close() }}

    </div>
</div> --}}


@endsection




@section('filter')
<!-- top offcanvas -->

@endsection


@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script type="text/javascript">


    $(function() {

        var start = moment();
        var end = moment();
        
        function cb(start, end) {
            $('#reportrange span input').val(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        }
        
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
           }
       }, cb);
        
       // cb(start, end);
        
    });

    
</script>
@endpush