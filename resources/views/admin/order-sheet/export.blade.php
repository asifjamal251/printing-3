{{ html()->form('POST', route('admin.order-sheet.export'))->attribute('enctype', 'multipart/form-data')->id('paymentExcelForm')->open() }}

<div class="row bg-white pt-3">


    {{-- Status --}}
    <div class="col-sm-12 col-md-4">
        <div class="form-group{{ $errors->has('export_po_date') ? ' has-error' : '' }}">
            {{ html()->label('PO Date', 'export_po_date') }}
            {{ html()->text('export_po_date')->id('exportPODate')->id('exportPODate')->class('form-control onChange dateSelectorRange')->placeholder('PO Date') }}
            <small class="text-danger">{{ $errors->first('export_po_date') }}</small>
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group{{ $errors->has('export_status') ? ' has-error' : '' }}">
            {{ html()->label('Status', 'export_status') }}
            {{ html()->select('export_status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [1,3])->pluck('name', 'id'))->id('exportStatus')->class('form-control js-choice')->placeholder('Choose Status') }}
            <small class="text-danger">{{ $errors->first('export_status') }}</small>
        </div>
    </div>



    

</div>

<div class="col-md-4 col-sm-12 mt-4">
   {{ html()->button('Download PO Items')->type('button')->class('btn btn-secondary bg-gradient')->attribute('onclick = downloadExcel(this)') }}
</div>

{{ html()->form()->close() }}