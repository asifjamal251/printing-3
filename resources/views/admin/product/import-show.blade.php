@extends('admin.layouts.master')
@push('links')
<style>
    .card-preloader{
        display:none;
    }
    .card-preloader.show{
        display:block;
    }
</style>
@endpush


@section('main')
@php
$errorCount = 0;
@endphp
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Previews Before Import</h4>

            <div class="text-danger errorCount fs-16">Error in <b>{{$errorCount}}</b> Row</div>
            
            @if($products->count() > 0)
            <div class="page-title-right">
                <a data-import="0" href="javascript:void(0);"  class="import-data btn-sm btn btn-danger btn-label rounded-pill">
                   <i class="ri-download-2-line label-icon align-middle rounded-pill fs-16 me-2"></i>
                   Cancel This Data
               </a>

               <a data-import="1" href="javascript:void(0);"  class="import-data btn-sm btn btn-success btn-label rounded-pill">
                   <i class="ri-download-2-line label-icon align-middle rounded-pill fs-16 me-2"></i>
                   Import This Data
               </a>
           </div>
           @endif
        



    </div>
</div>
</div>
<!-- end page title -->


@if($products->count() > 0)

<div class="row previews-items">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-body">
               <div class="card-preloader"><div class="card-status"><div class="spinner-grow text-danger"><span class="visually-hidden">Loading...</span></div></div></div>

               <div class="table-responsive">
                <table class="datatable table-sm border-secondary table-hover table table-bordered nowrap align-middle" style="width:100%">
        <thead class="gridjs-thead">
            <tr>
                <th>Si</th>
                <th>Paper Quality</th>
                <th>Sheet/Packet</th>
                <th>Weight/Packet</th>
                <th>Name (cm)</th>
                <th>Name (inch)</th>
                <th>GSM</th>
                <th>Opening Stock</th>
                <th>Quantity</th>
                <th>In-Hand Qty</th>
                <th>Location</th>
                <th>HSN</th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $item)
                @php
                    $line = $loop->index + 2;
                    $godownDisplay = trim($item->godown);
                    
                    $godownName = '';
                    $godownCode = null;

                    if (preg_match('/^(.*?)\((\d+)\)$/', $godownDisplay, $matches)) {
                        $godownName = strtolower(trim($matches[1]));
                        $godownCode = (int) $matches[2];
                    }

                    $godownModel = null;
                    if ($godownName && $godownCode) {
                        $godownModel = App\Models\Godown::whereRaw('LOWER(name) = ?', [$godownName])
                            ->where('code', $godownCode)
                            ->first();
                    }

                    $categoryName = trim($item->mill);
                    $paperTypeName = trim($item->paper_quality);

                    $mill = App\Models\Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
                    $paper_quality = 'N/A';

                    $existingProduct = null;
                    $stockExists = false;

                    if ($paper_quality && $item->gsm && ($item->name_cm || $item->name_inch)) {
                        $existingProduct = App\Models\Product::where(function ($q) use ($item) {
                                $q->where('name', $item->name_cm)
                                  ->orWhere('name_other', $item->name_inch);
                            })
                            ->where('gsm', $item->gsm)
                            ->first();

                        if ($existingProduct && $godownModel) {
                            $stockExists = App\Models\Stock::where('product_id', $existingProduct->id)
                                ->where('godown_id', $godownModel->id)
                                ->exists();
                        }
                    }

                    $hasError = !$paper_quality || !$mill || !$godownModel || !$item->sheet_per_packet || !$item->weight_per_packet || !$item->gsm || $stockExists;

                    if ($hasError) $errorCount++;
                @endphp

                <tr>
                    <td>{{ $item->si ?? $loop->iteration }}</td>

                    <td class="{{ $paper_quality ? '' : 'bg-danger text-light' }}">
                        {{ $paper_quality ? $item->paper_quality : 'Not Found (' . $item->paper_quality . ')' }}
                    </td>


                    <td class="{{ $item->sheet_per_packet ? '' : ' bg-danger text-light' }}">
                        {{ $item->sheet_per_packet }}
                    </td>

                    <td class="{{ $item->weight_per_packet ? '' : ' bg-danger text-light' }}">
                        {{ $item->weight_per_packet }}
                    </td>

                    <td class="{{ !$stockExists ? '' : ' bg-danger text-light' }}"> 
                        {{ !$stockExists ? $item->name_cm : 'Stock Exists (' . $item->name_cm . ')' }}
                    </td>

                    <td class="{{ !$stockExists ? '' : ' bg-danger text-light' }}">
                        {{ !$stockExists ? $item->name_inch : 'Stock Exists (' . $item->name_inch . ')' }}
                    </td>

                    <td class="{{ $item->gsm ? '' : ' bg-danger text-light' }}">
                        {{ $item->gsm }}
                    </td>

                    <td>{{ $item->opening_stock }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->in_hand_quantity }}</td>
                    <td>{{ $item->location }}</td>
                    <td>{{ $item->hsn }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
            </div>

        </div>
    </div>
</div><!--end col-->
</div><!--end row-->

@else
<div class="row data-mported-error">
    <div class="col-lg-12">
        <div class="card" style="max-width: 520px;text-align: center;margin:20px auto">

            <div class="card-body">
                <div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15 mx-5"><h4>Oops...! Something went Wrong !</h4><p class="text-muted mx-4 mb-0">There is any excel file found for import data</p></div></div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row data-mported-no" style="display: none;">
    <div class="col-lg-12">
        <div class="card" style="max-width: 520px;text-align: center;margin:20px auto">

            <div class="card-body">
                <div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon><div class="mt-4 pt-2 fs-15 mx-5"><h4>Oops...!</h4><p class="text-muted mx-4 mb-0">Package Slip has not imported in material inward</p></div></div>
            </div>
        </div>
    </div>
</div>

<div class="row data-mported-yes" style="display: none;">
    <div class="col-lg-12">
        <div class="card" style="max-width: 520px;text-align: center;margin:20px auto">

            <div class="card-body">
                <div class="mt-3"><lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15 mx-5"><h4>Well done !</h4>
                    <p class="text-muted mx-4 mb-0">Package Slip has imported in material inward</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row data-mported-yes" style="display: none;">
    <div class="col-lg-12">
        <div class="card" style="max-width: 520px;margin:20px auto">

            <div class="card-body">

                <div class="table-responsive table-card">
                    <table class="table table-nowrap table-striped-columns mb-0 table-hover table-bordered border">

                        <tr>
                            <th width="50%">Total Items</th>
                            <td width="50%" class="total-item">60</td>
                        </tr>

                        <tr>
                            <th width="50%">Error Items</th>
                            <td width="50%" class="total-error">0</td>
                        </tr>

                        <tr>
                            <th width="50%">Success Items</th>
                            <td width="50%" class="total-success">0</td>
                        </tr>

                        <tr>
                            <th width="50%">Error in line no.</th>
                            <td width="50%" class="error-item">0</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script type="text/javascript">
    $('.errorCount b').html({{$errorCount}});

    $('body').on('click', '.import-data', function(){
        $('.data-mported-yes').hide();
        $('.data-mported-no').hide();
        $('.data-mported-error').hide();
        $('.card-preloader').addClass('show');
        $('.import-data').addClass('disabled');
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url:'{{ route('admin.product.import.update') }}',
            data: { '_method': 'POST', '_token': '{{ csrf_token() }}' },
            success:function(response){
                if(response.error === false){
                    setTimeout(function(){
                        $('.data-mported-yes').show();
                        $('.previews-items').hide();
                        var total_error = parseInt(response.error_item.length);
                        var total_item = parseInt(response.total_item);
                        var total_success = total_item - total_error;

                        $('.total-item').html(total_item);
                        $('.total-error').html(total_error);
                        $('.total-success').html(total_success);
                        $('.error-item').html(response.error_item.join(', '));
                        if(total_error > 0){
                            $('.data-mported-yes table').addClass('border-danger');
                        }
                        else{
                            $('.data-mported-yes table').addClass('border-success');
                        }
                    }, 1000);
                }
                if(response.error === true){
                    setTimeout(function(){
                        $('.data-mported-no').show();
                        $('.previews-items').hide();
                    }, 1000);
                }

                Toastify({
                    text: response.message,
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    className: response.class,

                }).showToast();
                setTimeout(function(){
                    $('.card-preloader').removeClass('show');
                }, 500);
            },
            error:function(error){
                Toastify({
                    text: error.responseJSON.message,
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    className: "error",

                }).showToast();
                handleErrors(error.responseJSON);
                setTimeout(function(){
                    $('.card-preloader').removeClass('show');
                }, 500);

            }
        });
    });
</script>
@endpush