@extends('admin.layouts.master')

@push('links')
<link rel="stylesheet" href="{{ asset('admin-assets/libs/dropify/css/dropify.min.css') }}"> 
@endpush

@section('main')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>

            @can('add_admin')
            <div class="page-title-right">
                <a href="{{ route('admin.' . request()->segment(2) . '.create') }}" class="btn-sm btn btn-primary btn-label rounded-pill">
                    <i class="bx bx-plus label-icon align-middle rounded-pill fs-16 me-2"></i>
                    Add {{ Str::title(str_replace('-', ' ', request()->segment(2))) }}
                </a>
            </div>
            @endcan

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div style="max-width: 500px;margin:0 auto;">

            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div><b>Email:</b> {{ $admin->email }}</div>
                    <div>|</div>
                    <div><b>Name:</b> {{ $admin->name }}</div>
                </div>
            </div>

            {{ html()->form('POST', route('admin.admin.2fa.enable', $id))->attribute('enctype', 'multipart/form-data')->open() }}

            <div class="card">
                <div class="card-header">
                    Scan this QR code with your Google Authenticator app:
                </div>

                <div class="card-body text-center">
                    {!! $qrCodeUrl !!}
                    {{ html()->hidden('secret', $secret) }}
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="form-group{{ $errors->has('one_time_password') ? ' has-error' : '' }}">
                        {{ html()->label('Enter the OTP')->for('one_time_password') }}

                        {{ html()->text('one_time_password')
                            ->class('form-control')
                            ->placeholder('Enter the OTP from the app') }}

                        <small class="text-danger">{{ $errors->first('one_time_password') }}</small>
                    </div>

                    {{ html()->submit('Enable 2FA')->class('btn btn-info mt-2 float-end') }}

                </div>
            </div>

            {{ html()->form()->close() }}

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('admin-assets/libs/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/dropify/dropify.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush