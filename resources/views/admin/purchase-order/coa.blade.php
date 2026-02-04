@extends('admin.layouts.master')
@push('links')
<style>
    .custom-padding{
        padding:12px 16px !important;
    }
</style>
@endpush




@section('main')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>
        </div>
    </div>
</div>
<!-- end page title -->


<div class="card">
    <div class="card-body">
        <h4 class="text-center">{{get_app_setting('app_name')}}</h4>
        <p class="text-center">CERTIFICATE OF APPROPRIATENESS</p>
        <table class="table align-middle border-secondary table-bordered nowrap" style="width:100%">

            <tr>
                <th>PRODUCT NAME</th>
                <td>{{$coa->item_name}}</td>
            </tr>

            <tr>
                <th>MANUFACTURED BY</th>
                <td>{{$coa->item->mfgBy->company_name}}</td>
            </tr>

            <tr>
                <th>CARTON SIZE</th>
                <td>{{$coa->item_size}}</td>
            </tr>

            <tr>
                <th>BOARD GSM</th>
                <td>{{$coa->gsm}} GSM (+/- 5%)</td>
            </tr>

            <tr>
                <th>BOARD QUALITY</th>
                <td>{{$coa->productType->name}}</td>
            </tr>

            <tr>
                <th>LAM/UV/AQ</th>
                <td>{{$coa->coating}}</td>
            </tr>

            <tr>
                <th>COLOURS:</th>
                <td>{{$coa->colour}}</td>
            </tr>

        </table>

        <div class="w-100 d-flex justify-content-between mt-5 mb-5">
            <p><b>{{get_app_setting('app_name')}}</b></p>
            <p>Date:<span class="ms-2" style="width:100px;">_____________________</span></p>
        </div>
        <p style="height:80px;">Auth. Signatory</p>
    </div>
</div>







@endsection


@push('scripts')

@endpush
