@extends('admin.layouts.master')
@push('links')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css" />
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/select2/css/select2.min.css') }}">
    <style type="text/css">
        span.select2-selection.select2-selection--single,
        span.selection {
            height: 37px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            height: 37px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 37px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 14px;
            font-size: .8125rem;
        }

        textarea {
            display: block;
            width: 100%;
            height: auto;
            resize: none;
            /* Disable the draggable resizer handle */
            overflow: hidden;
            /* Hide the scrollbar */
            min-height: 100px;
            /* Minimum height */
        }
    </style>
@endpush


@php
    $categories = App\Models\Category::orderBy('ordering', 'asc')
        ->with('parent')
        ->whereNull('parent')
        ->with('children')
        ->get();
@endphp

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product Inventory</h4>
                @can('browse_employee')
                    <div class="page-title-right">
                        <a href="{{ route('admin.' . request()->segment(2) . '.index') }}"
                            class="btn-sm btn btn-secondary btn-label">
                            <i class="align-middle bx bx-list-ul label-icon fs-16 me-2"></i>
                            All {{ Str::title(str_replace('-', ' ', request()->segment(2))) }}s List
                        </a>
                    </div>
                @endcan

            </div>
        </div>
    </div>


    {{ html()->form('PUT', route('admin.' . request()->segment(2) . '.inventory.update', $product->id))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}
    <div class="row">
        <div class="col-md-12">



            <div class="report-repeater">
                <div id="kt_docs_repeater_advanced">
                    <div data-repeater-list="kt_docs_repeater_advanced">
                       
                            @foreach (old('kt_docs_repeater_advanced', [[]]) as $item)
                                <div data-repeater-item class="row-{{ $loop->index }}">
                                    <div class="card" style="position:relative;">
                                        <div class="card-body">
                                            <div
                                                class="custom-row gap-3 stock-error d-flex justify-content-between flex-sm-wrape">

                                                <div class="col-company" style="width:40%;">
                                                    <div
                                                        class="m-0 form-group{{ $errors->has('kt_docs_repeater_advanced.' . $loop->index . '.company') ? ' has-error' : '' }}">
                                                        {{ html()->label('Company')->for('company') }}
                                                        {{ html()->select('kt_docs_repeater_advanced[' . $loop->index . '][company]', App\Models\Company::select('id', 'company_name')->pluck('company_name', 'id'), $item['company_id'] ?? '')->class('js-choice form-control')->id('company')->placeholder('Choose Company') }}
                                                        <small
                                                            class="text-danger">{{ $errors->first('kt_docs_repeater_advanced.' . $loop->index . '.company') }}</small>
                                                    </div>
                                                </div>

                                                <div class="col-sheet_per_packet" style="width:40%;">
                                                    <div
                                                        class="m-0 form-group{{ $errors->has('kt_docs_repeater_advanced.' . $loop->index . '.sheet_per_packet') ? ' has-error' : '' }}">
                                                        {{ html()->label('Sheet/Packet')->for('sheet_per_packet') }}
                                                        {{ html()->select('kt_docs_repeater_advanced[' . $loop->index . '][sheet_per_packet]',App\Models\ProductVariant::where(['product_id' => $product->id])->select('id', 'variant_name')->pluck('variant_name', 'id'),$item['product_variant_id'] ?? '')->class('js-choice form-control')->id('sheet_per_packet')->placeholder('Choose Sheet/Packet') }}
                                                        <small
                                                            class="text-danger">{{ $errors->first('kt_docs_repeater_advanced.' . $loop->index . '.sheet_per_packet') }}</small>
                                                    </div>
                                                </div>

                                                <div class="col-opening_stock" style="width:40%;">
                                                    <div
                                                        class="m-0 form-group{{ $errors->has('kt_docs_repeater_advanced.' . $loop->index . '.opening_stock') ? ' has-error' : '' }}">
                                                        {{ html()->label('Opening Stock')->for('opening_stock') }}
                                                        {{ html()->text('kt_docs_repeater_advanced[' . $loop->index . '][opening_stock]', $item['opening_stock'] ?? '')->class('form-control')->id('opening_stock')->placeholder('Opening Stock') }}
                                                        <small class="fs-10 text-warning"><i class="ri-alert-line"></i>
                                                            Opening Stock Can not Be Changed</small>
                                                        <small
                                                            class="text-danger">{{ $errors->first('kt_docs_repeater_advanced.' . $loop->index . '.opening_stock') }}</small>
                                                    </div>
                                                </div>

                                                <div class="col-in_hand_quantity" style="width:40%;">
                                                    <div
                                                        class="m-0 form-group{{ $errors->has('kt_docs_repeater_advanced.' . $loop->index . '.in_hand_quantity') ? ' has-error' : '' }}">
                                                        {{ html()->label('In Hand Quantity')->for('in_hand_quantity') }}
                                                        {{ html()->text('kt_docs_repeater_advanced[' . $loop->index . '][in_hand_quantity]', $item['in_hand_quantity'] ?? '')->class('form-control')->id('in_hand_quantity')->placeholder('In Hand Quantity') }}
                                                        <small
                                                            class="text-danger">{{ $errors->first('kt_docs_repeater_advanced.' . $loop->index . '.in_hand_quantity') }}</small>
                                                    </div>
                                                </div>



                                                <div class="m-0 form-group remove-item" style="width:44px;">
                                                    <div class="text-end">
                                                        <button data-repeater-delete type="button"
                                                            class="btn-labels btn btn-danger" style="margin-top: 23px;"><i
                                                                class="label-icon ri-delete-bin-fill"></i></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                       
                        
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div class="m-0 form-group">
                            {{ html()->button('Save Inventory Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
                        </div>

                        <div class="m-0 form-group m-0">
                            <button data-repeater-create type="button" class="btn-label btn btn-warning text-end btn-sm"><i
                                    class="label-icon align-middle fs-16 me-2 bx bx-plus-circle"></i> Add New Row</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
    {{ html()->form()->close() }}
@endsection




@push('scripts')
    <script src="{{ asset('admin-assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/js/pages/form-repeater.js') }}"></script>

    <script type="text/javascript">
        var rowCounter = 0;
        $('#kt_docs_repeater_advanced').repeater({
            defaultValues: {
                'text-input': 'foo'
            },

            show: function() {
                $(this).addClass('row-' + rowCounter);
                rowCounter++;

                $(this).slideDown();
                $(this).find('small.text-danger').html('');
                $(this).find('.m-0 form-group').removeClass('has-error');
                $(this).find(".js-choice").each(function() {
                    new Choices($(this)[0]);
                });

                $('.getVariant').select2({
                    delay: 200,
                    ajax: {
                        url: '{{ route('admin.common.variant.list', $product->id) }}',
                        dataType: 'json',
                        cache: true,
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                    }
                });

                var $containers = $(this).find('.select2-container--default');
                if ($containers.length >= 2) {
                    $containers.slice(1).remove();
                }

            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });


        $(document).ready(function() {
            $(".js-choice").each(function() {
                new Choices($(this)[0]);
            });


            $("form").on("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                }
            });
        });
    </script>


    <script>
        jsTreeLoad();
        $('#category').on('changed.jstree', function(e, data) {
            var selectedCategories = [],
                selectedAndParentIds = [];
            var clickedNodeId = '';
            for (var i = 0, j = data.selected.length; i < j; i++) {
                var clickedNodeId = data.node.id;
                var node = data.instance.get_node(data.selected[i]);
                selectedAndParentIds.push(node.id);
                selectedAndParentIds = selectedAndParentIds.concat(
                    node.parents.filter(function(parentId) {
                        return parentId !== '#';
                    })
                );

            }
            selectedAndParentIds = [...new Set(selectedAndParentIds)];
            $('#categories').val(selectedAndParentIds.join(', '));
            $('#category_id').val(clickedNodeId);
        });

        function jsTreeLoad() {
            $('#category').jstree({
                'plugins': ["checkbox", "types"],
                'core': {
                    'data': {
                        'url': '{{ route('admin.' . request()->segment(2) . '.index') }}?type=category',
                    }
                },
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            function makeProductName(length, width, productType, gsm) {
                let formattedLength, formattedWidth;

                if (isFloat(length) || (typeof length === 'string' && /\.\d*[1-9]/.test(length))) {
                    formattedLength = length;
                } else {
                    formattedLength = removeTrailingZeros(parseInt(length));
                }

                if (isFloat(width) || (typeof width === 'string' && /\.\d*[1-9]/.test(width))) {
                    formattedWidth = width;
                } else {
                    formattedWidth = removeTrailingZeros(parseInt(width));
                }

                return formattedLength + 'X' + formattedWidth + '-' + productType + '-' + gsm;
            }

            function removeTrailingZeros(number) {
                number = number.toString();
                if (number.indexOf('.') !== -1) {
                    number = number.replace(/\.?0+$/, '');
                }
                return number;
            }

            function isFloat(n) {
                return Number(n) === n && n % 1 !== 0;
            }

            // Auto-fill function
            function autoFillProductName() {
                var lengthCm = $('#length_cm').val();
                var widthCm = $('#width_cm').val();
                var lengthInch = $('#length_inch').val();
                var widthInch = $('#width_inch').val();
                var productType = $('#product_type option:selected').text(); // Get the selected product type text
                var gsm = $('#gsm').val();

                // Check if all fields are filled
                if (lengthCm && widthCm && productType && gsm) {
                    var productNameCm = makeProductName(lengthCm, widthCm, productType, gsm);
                    $('#name_cm').val(productNameCm);
                }

                if (lengthInch && widthInch && productType && gsm) {
                    var productNameInch = makeProductName(lengthInch, widthInch, productType, gsm);
                    $('#name_inch').val(productNameInch);
                }
            }

            // Trigger auto-fill on keyup, change, or select change
            $('#length_cm, #width_cm, #gsm, #length_inch, #width_inch, #product_type').on('keyup change',
                function() {
                    autoFillProductName();
                });
        });
    </script>
@endpush
