{{ html()->form('POST', route('admin.order-sheet.export'))->attribute('enctype', 'multipart/form-data')->id('paymentExcelForm')->open() }}

<div class="card">
    <div class="card-body">
        <div class="form-group{{ $errors->has('export_po_date') ? ' has-error' : '' }}">
            {{ html()->label('PO Date', 'export_po_date') }}
            {{ html()->text('export_po_date')->id('exportPODate')->id('exportPODate')->class('form-control onChange dateSelectorRange')->placeholder('PO Date') }}
            <small class="text-danger">{{ $errors->first('export_po_date') }}</small>
        </div>

        <div class="form-group{{ $errors->has('export_status') ? ' has-error' : '' }}">
            {{ html()->label('Status', 'export_status') }}
            {{ html()->select('export_status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [1,3])->pluck('name', 'id'))->id('exportStatus')->class('form-control js-choice')->placeholder('Choose Status') }}
            <small class="text-danger">{{ $errors->first('export_status') }}</small>
        </div>


        @can('mfg_mkdt_item')
        <div class="form-group{{ $errors->has('export_mfg_by') ? ' has-error' : '' }}">
            {{ html()->label('MFG By', 'export_mfg_by') }}
            {{ html()->select('export_mfg_by', [])->id('filterMFGBY')->class('client form-control onChange')->placeholder('MFG By') }}
            <small class="text-danger">{{ $errors->first('export_mfg_by') }}</small>
        </div>

        <div class="m-0 form-group{{ $errors->has('export_mkdt_by') ? ' has-error' : '' }}">
            {{ html()->label('MKDT By', 'export_mkdt_by') }}
            {{ html()->select('export_mkdt_by', [])->id('filterMKDTBY')->class('client form-control onChange')->placeholder('MKDT By') }}
            <small class="text-danger">{{ $errors->first('export_mkdt_by') }}</small>
        </div>

        @else

        <div class="m-0 form-group{{ $errors->has('export_client') ? ' has-error' : '' }}">
            {{ html()->label('Client', 'export_client') }}
            {{ html()->select('export_client', [])->id('filterClient')->class('client form-control onChange')->placeholder('Client') }}
            <small class="text-danger">{{ $errors->first('export_client') }}</small>
        </div>

        @endcan

    </div>
</div>



{{ html()->button('Download Order Sheet')->type('button')->class('btn btn-secondary bg-gradient')->attribute('onclick = downloadExcel(this)') }}


{{ html()->form()->close() }}