@extends('admin.layouts.master')
@push('links')

@endpush




@section('main')


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
                <a href="{{ route('admin.'.request()->segment(2).'.create') }}" class="btn-sm btn btn-primary btn-label rounded-pill">
                    <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                    Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
                </a>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="card">
    <div class="card-body">
        {{ html()->form('POST', route('admin.role.store'))->class('form-horizontal')->open() }}
            <div class="my-1 row">
                <div class="col-md-4 col-sm-12">
                    <div class="mb-3 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ html()->label('Name')->for('name') }}
                        {{ html()->text('name', null)
                                ->class('form-control')
                                ->placeholder('Name') }}
                        <small class="text-danger">{{ $errors->first('name') }}</small>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div class="mb-3 form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
                        {{ html()->label('Display Name')->for('display_name') }}
                        {{ html()->text('display_name', null)
                                ->class('form-control')
                                ->placeholder('Display Name') }}
                        <small class="text-danger">{{ $errors->first('display_name') }}</small>
                    </div>
                </div>

                <div class="mt-4 col-md-4 col-sm-12">
                    {{ html()->button('Create New Role')->type('submit')->class('btn btn-primary') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>

@endsection




@push('scripts')

@endpush
