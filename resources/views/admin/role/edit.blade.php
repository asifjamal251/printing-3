@extends('admin.layouts.master')

@push('links')
@endpush

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.' . request()->segment(2) . '.create') }}" class="btn-sm btn btn-primary btn-label rounded-pill">
                        <i class="bx bx-plus label-icon align-middle rounded-pill fs-16 me-2"></i>
                        Add {{ Str::title(str_replace('-', ' ', request()->segment(2))) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    {{ html()->modelForm($role, 'PUT', route('admin.' . request()->segment(2) . '.update', $role->id))->open() }}
    <div class="row">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
        <input type="text" id="group-search" class="form-control" placeholder="Search permission group...">
  
  
                </div>
            </div>
        </div>


        <div class="col-7">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <div class="w-100">
                            <div class="form-group m-0">
                                {{ html()->text('name')
                                    ->value($role->name)
                                    ->class('form-control')
                                    ->placeholder('Name') }}
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                            </div>
                        </div>

                        <div class="w-100">
                            <div class="form-group m-0">
                                {{ html()->text('display_name')
                                    ->value($role->display_name)
                                    ->class('form-control')
                                    ->placeholder('Display Name') }}
                                    <small class="text-danger">{{ $errors->first('display_name') }}</small>
                            </div>
                        </div>

                        <div style="width:185px;">
                            <div class="form-group d-flex gap-2 m-0">
                                <button type="button" class="permission-select-all btn btn-success btn-icon waves-effect waves-light">
                                    <i class="ri-check-double-line"></i>
                                </button>
                                <button type="button" class="permission-deselect-all btn btn-danger btn-icon waves-effect waves-light">
                                    <i class="ri-delete-bin-5-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($permissions as $table => $groupPermission)
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <ul class="permissions list-group list-group-flush">
                            <li class="list-group-item">
                                {{ html()->checkbox('permission-group')->class('permission-group') }}
                                <label class="m-0" for="{{ $table }}">
                                    <strong>{{ Str::title(str_replace('_', ' ', $table)) }}</strong>
                                </label>
                                <ul class="list-group list-group-flush">
                                    @foreach($groupPermission as $permission)
                                    <li class="list-group-item">
                                        {{ html()->checkbox('permissions[]', $permission->checked, $permission->permission_key)
                                            ->id('permission-' . $permission->permission_key)
                                            ->class('the-permission') }}
                                        <label class="m-0" for="permission-{{ $permission->permission_key }}">
                                            {{ Str::title(str_replace('_', ' ', $permission->permission_key = str_replace($table, '', $permission->permission_key))) }}
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="col-md-12 text-right">
            <br>
            <div class="form-group" style="position: fixed; bottom: 50px; right: 25px;">
                {{ html()->button('Save Permissions')->class('btn btn-primary') }}
            </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery.matchHeight.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.permissions').matchHeight({ property: 'min-height' });

        $('.permission-group').on('change', function(){
            $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
        });

        $('.permission-select-all').on('click', function(){
            $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
            return false;
        });

        $('.permission-deselect-all').on('click', function(){
            $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
            return false;
        });

        function parentChecked(){
            $('.permission-group').each(function(){
                var allChecked = true;
                $(this).siblings('ul').find("input[type='checkbox']").each(function(){
                    if(!this.checked) allChecked = false;
                });
                $(this).prop('checked', allChecked);
            });
        }

        parentChecked();

        $('.the-permission').on('change', function(){
            parentChecked();
        });
    });


$('#group-search').on('keyup', function () {
    var searchTerm = $(this).val().toLowerCase();

    $('.card').each(function () {
        var groupTitle = $(this).find('label strong').text().toLowerCase();

        if (groupTitle.includes(searchTerm) || searchTerm === '') {
            $(this).closest('.col-md-4').show();
        } else {
            $(this).closest('.col-md-4').hide();
        }
    });
});
</script>
@endpush
