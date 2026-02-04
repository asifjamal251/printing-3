@extends('admin.layouts.master')
@push('links')
@endpush
@section('main')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>
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

<div class="card">
    <div class="card-content">
        <div class="card-body">
           {{ html()->form('PUT', route('admin.menu.update', $menu->slug))->id('menuForm')->open() }}
            <div class="row">
            <div class="col-md-3 col-sm-12">
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {{ html()->label('Menu Name')->for('name')->class('control-label') }}
        {{ html()->text('name', $menu->name)->class('form-control')->id('name') }}
        <b class="text-danger">{{ $errors->first('name') }}</b>
    </div>
</div>
            <div class="col-md-3 col-sm-12">
    <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
        {{ html()->label('Icon')->for('icon')->class('control-label') }}
        {{ html()->text('icon', $menu->icon)->class('form-control')->id('icon') }}
        <b class="text-danger">{{ $errors->first('icon') }}</b>
    </div>
</div>
            <div class="col-md-3 col-sm-12">
    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
        {{ html()->label('Status')->for('status')->class('control-label') }}
        {{ html()->select('status', [1 => 'Active', 0 => 'Deactive'], $menu->status)->class('form-control')->id('status') }}
        <b class="text-danger">{{ $errors->first('status') }}</b>
    </div>
</div>
            <div class="col-md-3 col-sm-12">
    <div class="form-group" style="margin-top:22px;">
        {{ html()->hidden('slug', $menu->slug) }}
        {{ html()->button('Update')
            ->type('submit')
            ->class('btn btn-success')
            ->attribute('onclick', 'submitForm()') }}
    </div>
</div>
{{ html()->form()->close() }}
        </div>
             
    </div>
</div>
@endsection
@push('scripts')


@endpush