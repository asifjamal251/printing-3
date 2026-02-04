{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $admin->id))->attribute('files', true)->open() !!}
<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }}
                    {{ html()->text('name', $admin->name)->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ html()->label('Email Address', 'email') }}
                    {{ html()->email('email', $admin->email)->class('form-control')->placeholder('eg: foo@bar.com') }}
                    <small class="text-danger">{{ $errors->first('email') }}</small>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    {{ html()->label('Password', 'password') }}
                    {{ html()->text('password', $admin->plain_password)->class('form-control')->attribute('autocomplete','new-password')->placeholder('Password') }}
                    <small class="text-danger">{{ $errors->first('password') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                    {{ html()->label('Role', 'role') }}
                    {{ html()->select('role', App\Models\Role::whereNotIn('id', [1])->pluck('display_name', 'id'), $admin->role_id)->class('form-control js-choice')->placeholder('Choose Roll') }}
                    <small class="text-danger">{{ $errors->first('role') }}</small>
                </div>
            </div>

            

            <div class="col-md-4 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status')->for('status') }}
                    {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $admin->status_id)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>
            </div>


            <div class="col-md-4 col-sm-12">
                <div class="form-group{{ $errors->has('enabled_2fa') ? ' has-error' : '' }}">
                    {{ html()->label('Enabled 2FA')->for('enabled_2fa') }}
                    {{ html()->select('enabled_2fa', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $admin->google2fa_enabled)->class('form-control js-choice')->id('enabled_2fa')->placeholder('Enabled 2FA') }}
                    <small class="text-danger">{{ $errors->first('enabled_2fa') }}</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="form-group{{ $errors->has('listing_type') ? ' has-error' : '' }}">
                    {{ html()->label('Listing Type', 'listing_type') }}
                    {{ html()->select('listing_type', ['Own' => 'Own', 'All' => 'All'], $admin->listing_type)->class('form-control js-choice')->placeholder('Listing Type') }}
                    <small class="text-danger">{{ $errors->first('listing_type') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('login_time_restriction_enabled') ? ' has-error' : '' }}">
                    {{ html()->label('Login Time Restriction Enabled')->for('login_time_restriction_enabled') }}
                    {{ html()->select('login_time_restriction_enabled', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $admin->login_time_restriction_enabled)->class('form-control js-choice')->id('login_time_restriction_enabled')->placeholder('Login Time Restriction Enabled') }}
                    <small class="text-danger">{{ $errors->first('login_time_restriction_enabled') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12 d-flex gap-3">

                <div class="w-50 form-group{{ $errors->has('login_allowed_from') ? ' has-error' : '' }}">
                    {{ html()->label('Login Allowed From', 'login_allowed_from') }}
                    {{ html()->text('login_allowed_from', $admin->login_allowed_from)->class('form-control timeInput')->placeholder('Login Allowed From') }}
                    <small class="text-danger">{{ $errors->first('login_allowed_from') }}</small>
                </div>

                <div class="w-50 form-group{{ $errors->has('login_allowed_to') ? ' has-error' : '' }}">
                    {{ html()->label('Login Allowed To', 'login_allowed_to') }}
                    {{ html()->text('login_allowed_to', $admin->login_allowed_to)->class('form-control timeInput')->placeholder('Login Allowed To') }}
                    <small class="text-danger">{{ $errors->first('login_allowed_to') }}</small>
                </div>
            </div>


            <div class="col-md-4 col-sm-12">
                <div class="form-group{{ $errors->has('stores') ? ' has-error' : '' }}">
                    {{ html()->label('Store')->for('stores') }}

                    {{ html()->select(
                        'stores[]',
                        App\Models\Store::orderBy('name', 'asc')->pluck('name', 'id'),
                        $admin->stores->pluck('id')->toArray() ?? []
                    )
                    ->class('form-control js-choice')
                    ->id('productStore')
                    ->multiple()
                    ->attributes([
                        'data-placeholder' => 'Choose Store',
                        'data-choices-removeItem' => true
                        ]) }}

                        <small class="text-danger">{{ $errors->first('stores') }}</small>
                    </div>
                </div>


                <div class="col-md-4 col-sm-12">
                    <div class="form-group{{ $errors->has('ip_enabled') ? ' has-error' : '' }}">
                        {{ html()->label('IP Enabled')->for('ip_enabled') }}
                        {{ html()->select('ip_enabled', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $admin->ip_enabled)->class('form-control js-choice')->id('ip_enabled')->placeholder('IP Enabled') }}
                        <small class="text-danger">{{ $errors->first('ip_enabled') }}</small>
                    </div>
                </div>





                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Allowed IP Addresses</label>

                        <div id="ip-wrapper">
                            @forelse($admin->ips as $ip)
                            <div class="d-flex mb-2 ip-row">
                                <input type="text" name="ip_addresses[]" value="{{ $ip->ip_address }}" class="form-control" placeholder="e.g. 192.168.1.1">

                                <button type="button" class="btn btn-danger btn-sm ms-2 remove-ip">−</button>
                            </div>
                            @empty
                            {{-- Show one empty row when no IP exists --}}
                            <div class="d-flex mb-2 ip-row">
                                <input type="text" name="ip_addresses[]" class="form-control" placeholder="e.g. 192.168.1.1">

                                <button type="button" class="btn btn-success btn-sm ms-2 add-ip">+</button>
                            </div>
                            @endforelse
                        </div>

                        <small class="text-muted">Leave blank if not restricting by IP.</small>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="mt-4 form-group">
                        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    {!! html()->form()->close() !!}

    <script>
        document.addEventListener('click', function (e) {

    // ADD NEW FIELD
            if (e.target.classList.contains('add-ip')) {
                let newRow = `
            <div class="d-flex mb-2 ip-row">
            <input type="text" name="ip_addresses[]" class="form-control" placeholder="e.g. 192.168.1.1">
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-ip">-</button>
                    </div>`;
                    document.getElementById('ip-wrapper').insertAdjacentHTML('beforeend', newRow);
                }

    // REMOVE FIELD
                if (e.target.classList.contains('remove-ip')) {
                    e.target.closest('.ip-row').remove();
                }
            });
        </script>
