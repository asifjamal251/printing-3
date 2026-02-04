{{ html()->form('POST', route('admin.purchase-order.export'))->attribute('enctype', 'multipart/form-data')->id('paymentExcelForm')->open() }}
<div class="card">
    <div class="card-body">

        <div class="form-group{{ $errors->has('export_po_date') ? ' has-error' : '' }}">
            {{ html()->label('PO Date', 'export_po_date') }}
            {{ html()->text('export_po_date')->id('exportPODate')->id('exportPODate')->class('form-control onChange dateSelectorRange')->placeholder('PO Date') }}
            <small class="text-danger">{{ $errors->first('export_po_date') }}</small>
        </div>

        <div class="form-group{{ $errors->has('export_status') ? ' has-error' : '' }}">
            {{ html()->label('Status', 'export_status') }}
            {{ html()->select('export_status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [1,2,3])->pluck('name', 'id'))->id('exportStatus')->class('form-control js-choice')->placeholder('Choose Status') }}
            <small class="text-danger">{{ $errors->first('export_status') }}</small>
        </div>

        <div class="m-0 form-group{{ $errors->has('export_clients') ? ' has-error' : '' }}">
            {{ html()->label('Choose Client', 'export_clients') }}
            {{ html()->select('export_clients[]', [])->id('export_clients')->class('client form-control')->attribute('multiple')->attributes(['data-placeholder' => 'Choose Client']) }}
            <small class="text-danger">{{ $errors->first('export_clients') }}</small>
        </div>

        
    </div>
</div>


{{ html()->button('Download PO Items')->type('button')->class('btn btn-secondary bg-gradient')->attribute('onclick = downloadExcel(this)') }}


{{ html()->form()->close() }}