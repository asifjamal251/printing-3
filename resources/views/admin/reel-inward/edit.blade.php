<style>
    .is-loading{opacity:.5;pointer-events:none;transition:opacity .2s ease}
</style>

{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update',$client->id))
->attribute('enctype','multipart/form-data')
->id('update')
->open() !!}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    {{ html()->label('Company Name') }} <span class="text-danger">*</span>
                    {{ html()->text('company_name',$client->company_name)->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('company_name') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
                    {{ html()->label('GST') }} <span class="text-danger">*</span>
                    {{ html()->text('gst',$client->gst)->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('gst') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
                    {{ html()->label('Pincode') }} <span class="text-danger">*</span>
                    {{ html()->text('pincode',$client->pincode)->id('pincode')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('pincode') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                    {{ html()->label('State') }}
                    {{ html()->text('state',$client->state)->id('state')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('state') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('district') ? ' has-error' : '' }}">
                    {{ html()->label('District') }}
                    {{ html()->text('district',$client->district)->id('district')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('district') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                    {{ html()->label('City') }}
                    {{ html()->text('city',$client->city)->id('city')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('city') }}</small>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                    {{ html()->label('Address') }}
                    {{ html()->text('address',$client->address)->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('address') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    {{ html()->label('Status') }}
                    {{ html()->select('status',App\Models\Status::whereIn('id',[14,15])->pluck('name','id'),$client->status_id)->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('address') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    {{ html()->label('Stock On Email') }}
                    {{ html()->select('stock_on_email',App\Models\Status::whereIn('id',[14,15])->pluck('name','id'),$client->stock_on_email)->class('form-control') }}
                </div>
            </div>

             <div class="col-md-6 col-sm-12">
            <div class="form-group{{ $errors->has('allocated_stock_on_email') ? ' has-error' : '' }}">
                {{ html()->label('Allocated Stock On Email')->for('allocated_stock_on_email') }}  <span class="text-danger">*</span>
                {{ html()->select('allocated_stock_on_email', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 15)->class('form-control js-choice')->id('allocated_stock_on_email')->placeholder('Allocated Stock On Email') }}
                <small class="text-danger">{{ $errors->first('allocated_stock_on_email') }}</small>
            </div>
        </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    {{ html()->label('Login Status') }}
                    {{ html()->select('login_status',App\Models\Status::whereIn('id',[14,15])->pluck('name','id'),$client->login_status)->id('loginStatus')->class('form-control') }}
                </div>
            </div>

            <div class="mb-3 col-md-12" id="loginCredentials" style="{{ $client->login_status==14?'':'display:none' }}">
                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label('Username') }}
                        {{ html()->text('username',$client->username)->class('form-control')->placeholder('username') }}
                    </div>
                    <div class="col-md-6">
                        {{ html()->label('Password') }}
                        {{ html()->text('password', $client->plain_password)->class('form-control')->placeholder('Password') }}
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
    <div class="report-repeater">
        <div id="kt_docs_repeater_advanced_email">

            <div data-repeater-list="kt_docs_repeater_advanced_email">
                @foreach(
                    old(
                        'kt_docs_repeater_advanced_email',
                        collect($client->email ?? [])->map(fn ($e) => ['email' => $e])->toArray()
                    ) as $index => $item
                )

                <div data-repeater-item class="repeater-row row-{{$index}}">

                    <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                        <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_email.$index.email") ? ' has-error' : '' }}">
                            {{ html()->label('Email', "kt_docs_repeater_advanced_email[$index][email]") }} 

                            {{ html()->email("kt_docs_repeater_advanced_email[$index][email]")
                                ->class('form-control numOnly')
                                ->placeholder('Email')
                                ->value($item['email'] ?? '') }}

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced_email.$index.email") }}
                            </small>
                        </div>

                        <div class="m-0 form-group remove-item" style="width:44px;">
                            <div class="text-end">
                                <button data-repeater-delete type="button"
                                    class="btn btn-danger btn-sm fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    -
                                </button>
@if($loop->index == 0)
                                <button data-repeater-create type="button"
                                    class="btn btn-success btn-sm add fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    +
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>




<div class="col-md-6 col-sm-12">
    <div class="report-repeater">
        <div id="kt_docs_repeater_advanced_contact_no">

            <div data-repeater-list="kt_docs_repeater_advanced_contact_no">
                @foreach(
                    old(
                        'kt_docs_repeater_advanced_contact_no',
                        collect($client->contact_no ?? [])->map(fn ($c) => ['contact_no' => $c])->toArray()
                    ) as $index => $item
                )

                <div data-repeater-item class="repeater-row row-{{$index}}">

                    <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                        <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_contact_no.$index.contact_no") ? ' has-error' : '' }}">
                            {{ html()->label('Contact No.', "kt_docs_repeater_advanced_contact_no[$index][contact_no]") }} 

                            {{ html()->text("kt_docs_repeater_advanced_contact_no[$index][contact_no]")
                                ->class('form-control numOnly')
                                ->placeholder('Contact No.')
                                ->value($item['contact_no'] ?? '') }}

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced_contact_no.$index.contact_no") }}
                            </small>
                        </div>

                        <div class="m-0 form-group remove-item" style="width:44px;">
                            <div class="text-end">
                           
                                <button data-repeater-delete type="button"
                                    class="btn btn-danger btn-sm fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    -
                                </button>
@if($loop->index == 0)
                                <button data-repeater-create type="button"
                                    class="btn btn-success btn-sm add fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    +
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>




        </div>
    </div>
</div>
 <div class="col-md-12 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
    {{ html()->form()->close() }}


<script type="text/javascript" src="{{asset('assets/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">

 var rowCounter = 0;

 $('#kt_docs_repeater_advanced_contact_no').repeater({
    isFirstItemUndeletable: true,
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');
        $row.find('.add').hide();

        $row.slideDown('fast', function () {
            //$row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});


  $('#kt_docs_repeater_advanced_email').repeater({
    isFirstItemUndeletable: true,
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');
        $row.find('.add').hide();

        $row.slideDown('fast', function () {
            //$row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
</script>
<script>

    function startPincodeLoading() {
        $('#pincodeLoader').removeClass('d-none');

        $('#state, #district, #city')
        .addClass('is-loading')
        .prop('disabled', true);
    }

    function stopPincodeLoading() {
        $('#pincodeLoader').addClass('d-none');

        $('#state, #district, #city')
        .removeClass('is-loading')
        .prop('disabled', false);
    }

    let choiceInstances = {}; // store all Choice objects

    function initChoice(selector) {
        let el = document.querySelector(selector);
        if (!el) return;

        if (choiceInstances[selector]) {
            try { choiceInstances[selector].destroy(); } catch (e) {}
        }

        choiceInstances[selector] = new Choices(el, {
            searchEnabled: true,
            searchChoices: true,
            placeholder: true,
            searchPlaceholderValue: "Search or type...",

        // allow user typing
            addItems: true,
        addChoices: true,      // 🟢 IMPORTANT
        allowHTML: true,
        allowHtmlUserInput: true,

        // allow manual text to become a choice
        addItemFilter: (value) => !!value && value.trim() !== "",
        duplicateItemsAllowed: false,
        removeItemButton: true,

        noResultsText: "No results found. Press Enter to add.",
        noChoicesText: "Type to add.",
        itemSelectText: "Press Enter to select",

        callbackOnInit: function () {
            let instance = this;

            // 🟢 When user types text → add it as a new choice
            instance.input.element.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    let value = instance.input.element.value.trim();
                    if (value !== "" && !instance._findChoiceByValue(value)) {
                        instance.setChoices([{
                            value: value,
                            label: value,
                            selected: true,
                            customProperties: { manual: true }
                        }], 'value', 'label', true);
                    }
                }
            });
        }
    });
    }

    function updateSelect(selector, items) {
        let select = $(selector);

        if (select.length === 0) return;

        select.empty();
        items.forEach(item => {
            select.append(`<option value="${item.value}">${item.text}</option>`);
        });
    }

    $('#pincode').on('keyup', function () {
        $('#pincode').next('small').text('');
        let pincode = $(this).val();

        if (pincode.length !== 6) return;
        startPincodeLoading();

        $.ajax({
            url: "/admin/common/ajax/pincode/" + pincode,
            type: "GET",
            success: function (res) {
               stopPincodeLoading();
               if (!res.success) {
                updateSelect('#state', [{value: '', text: 'Select State'}]);
                updateSelect('#district', [{value: '', text: 'Select District'}]);
                updateSelect('#city', [{value: '', text: 'Select City'}]);

                initChoice('#state');
                initChoice('#district');
                initChoice('#city');
                $('#pincode').next('small').text(res.message);
                return;
            }

            updateSelect('#state', [{value: res.state, text: res.state}]);
            updateSelect('#district', [{value: res.district, text: res.district}]);

            let cities = [{value: '', text: 'Select City'}];

            res.cities.forEach(c => {
                res.names.forEach(n => {
                    let text = `${c} – ${n}`;
                    cities.push({value: text, text});
                });
            });

            updateSelect('#city', cities);

            // Initialize Choices AFTER updating options
            initChoice('#state');
            initChoice('#district');
            initChoice('#city');
        },
        error: function (xhr) {
           stopPincodeLoading();
           console.log('error', xhr)
           if (xhr.status === 422) {
            let msg = xhr.responseJSON.errors.pincode[0];
            $('#pincode').next('small').text(msg);
        }
    }
});
    });


$(document).ready(function () {

    function toggleLoginFields(value) {
        if (parseInt(value) === 14) {
            $('#loginCredentials').stop(true, true).slideDown(300);
        } else {
            $('#loginCredentials').stop(true, true).slideUp(300);
        }
    }

    toggleLoginFields($('#loginStatus').val());

    $('#loginStatus').on('change', function () {
        toggleLoginFields(this.value);
    });

});
</script>    

