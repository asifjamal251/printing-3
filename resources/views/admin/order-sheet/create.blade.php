@php
use App\Models\Role;
use App\Models\Admin;

$designerRole = Role::where('name', 'designer')->first();
$designers = $designerRole ? Admin::where('role_id', $designerRole->id)->pluck('name', 'id') : collect();
@endphp

{{ html()->form('POST', route('admin.' . request()->segment(2) . '.create.processing'))
    ->attribute('enctype', 'multipart/form-data')
    ->id('store')
    ->open() }}

<div class="card">
    <div class="card-body">

        @if($designers->count())
            <div class="form-group m-0 {{ $errors->has('designer') ? 'has-error' : '' }}">
                {{ html()->label('Designer', 'designer') }}
                {{ html()->select('designer', $designers)
                    ->class('form-control js-choice')
                    ->placeholder('Select Designer') }}
                <small class="text-danger">{{ $errors->first('designer') }}</small>
            </div>

            <div class="form-group mt-4">
                {{ html()->button('Save Details')
                    ->type('button')
                    ->class('btn btn-success bg-gradient')
                    ->attribute('onclick', 'store(this)') }}
            </div>
        @else
            <p class="m-0">Please create an <b>Admin</b> with the <b>Designer</b> role.</p>
        @endif

    </div>
</div>

{{ html()->form()->close() }}