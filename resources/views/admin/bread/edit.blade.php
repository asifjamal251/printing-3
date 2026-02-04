@extends('admin.layouts.master')
@section('title', 'Edit Bread')
@push('links')
<style type="text/css">
    span.permissionbox {
        border: 1px solid gray;
        padding: 8px;
        margin: 10px 10px;
        display: inline-block;
        position: relative;
    }
    span.permissionbox i {
        right: -5px;
        top: 9px;
    }
</style>
@endpush

@section('main')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ Str::title(str_replace('-', ' ', request()->segment(2))) }}</h4>

            <div class="page-title-right">
                <a href="{{ route('admin.' . request()->segment(2) . '.create') }}" class="btn-sm btn btn-primary btn-label rounded-pill">
                    <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                    Add {{ Str::title(str_replace('-', ' ', request()->segment(2))) }}
                </a>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="my-1 row">
                <div class="col-lg-12 col-12">

                    {!! html()->form('PUT', route('admin.' . request()->segment(2) . '.update', $menu->slug))->id('breadForm')->open() !!}
                    
                    <div class="form-group">
                        {!! html()->label('Name') !!}
                        {!! html()->text('name', $menu->name)->class('form-control') !!}
                    </div>

                    <div class="form-group">
                        {!! html()->label('Icon') !!}
                        {!! html()->text('icon', $menu->icon)->class('form-control') !!}
                    </div>

                    <label for="">Permissions</label>
                    <br>
                    <div class="form-group" id="permissions">
                        @foreach(array_unique(array_merge($permissions, ['browse_'.$menu->slug, 'read_'.$menu->slug, 'add_'.$menu->slug, 'edit_'.$menu->slug, 'delete_'.$menu->slug])) as $per)
                            <button type="button" class="mb-3 permissionbox btn btn-primary btn-label waves-effect waves-light right">
                                <i class="align-middle ri-close-line label-icon fs-16 ms-2" onClick="removePermission(this)"></i>
                                <div class="form-check form-check-outline">
                                    {!! html()->checkbox('permissions[]', in_array($per, $permissions), $per)->class('js-switch-sm form-check-input')->id($per) !!}
                                    {!! html()->label($per)->class('form-check-label m-0')->for($per) !!}
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <div class="form-group">
                        {!! html()->button('Add More Permission')->type('button')->class('btn btn-soft-warning waves-effect waves-light')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#addPermission') !!}
                    </div>

                    <div class="form-group pull-right">
                        {!! html()->button('Submit')->class('btn btn-soft-secondary waves-effect waves-light')->attribute('onclick', 'submitForm()') !!}
                    </div>

                    {!! html()->form()->close() !!}

                    <!-- Modal -->
                    <div id="addPermission" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel">New Permission</h5>
                                    {!! html()->button('', 'button')->class('btn-close')->attribute('data-bs-dismiss', 'modal')->attribute('aria-label', 'Close') !!}
                                </div>
                                <div class="modal-body">
                                    {!! html()->label('Permission Type') !!}
                                    {!! html()->text('per')->class('form-control')->placeholder('Permission Type')->attribute('autocomplete', 'off') !!}
                                </div>
                                <div class="modal-footer">
                                    {!! html()->button('Close')->class('btn btn-soft-danger waves-effect waves-light')->attribute('data-bs-dismiss', 'modal') !!}
                                    {!! html()->button('Add New Permission')->class('btn btn-soft-secondary waves-effect waves-light')->attribute('onclick', 'addPermission(this)') !!}
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/switch.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    function addPermission(el) {
        var val = $(el).closest('.modal-content').find('input').val();
        var html = '<button type="button" class="mb-3 permissionbox btn btn-primary btn-label waves-effect waves-light right"><i class="align-middle ri-close-line label-icon fs-16 ms-2" onClick="removePermission(this)"></i>' +
            '<div class="form-check form-check-outline"><input type="checkbox" name="permissions[]" value="' + slug(val) + '" class="js-switch-sm form-check-input" id="' + val + '">' +
            '<label class="m-0 form-check-label" for="' + val + '">' + val + '</label></div></button>';
        $('#permissions').append(html);
        $('#addPermission').modal('hide');
        $(el).closest('.modal-content').find('input').val('');
        switchBtn();
    }

    function removePermission(element) {
        if (confirm('Are you sure to remove this permission')) {
            $(element).parent().remove();
        }
    }

    function slug(string) {
        return string.toLowerCase().split(' ').filter(function(n) { return n != '' }).join('_');
    }

    function switchBtn() {
        var elems2 = $('input[data-switchery!="true"].js-switch-sm');
        for (var i = 0; i < elems2.length; i++) {
            new Switchery(elems2[i], { size: 'small' });
        }
    }

    function submitForm() {
        $('#breadForm').submit();
    }
</script>

@endpush