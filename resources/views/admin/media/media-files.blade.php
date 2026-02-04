
<div class="offcanvas mediaselectionlist offcanvas-end" tabindex="-1" id="mediafiles" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <input type="text" placeholder="Search" id="mediafilesearch" class="form-control">
        {{-- <div class="form-group{{ $errors->has('inputname') ? ' has-error' : '' }}">
            {!! Form::text('inputname', null, ['class' => 'form-control', 'required' => 'required']) !!}
        </div> --}}
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="p-0 overflow-hidden text-center offcanvas-body">
        <div data-simplebar class="p-2" style="height: calc(100vh - 116px);padding-bottom: 12px;">

            <div class="mb-3 dropzone">
                <div class="fallback">

                    <input name="file" type="file" multiple="multiple">

                </div>
                <div class="dz-message needsclick">
                    <div class="mb-3">
                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                        <span>Choose or drag file here</span>
                    </div>

                </div>
            </div>

            <ul class="mb-0 d-none list-unstyled" id="dropzone-preview">
                <li class="mt-2" id="dropzone-preview-list">
                    <!-- This is used as the file preview template -->
                    <div class="border rounded">
                        <div class="p-2 d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded avatar-sm bg-light">
                                    <img data-dz-thumbnail class="rounded img-fluid d-block" src="#"
                                        alt="Dropzone-Image" />
                                </div>

                            </div>
                            <div class="flex-grow-1">
                                <div class="pt-1">
                                    <h5 class="mb-1 fs-14" data-dz-name>&nbsp;</h5>
                                    <p class="mb-0 fs-13 text-muted" data-dz-size></p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                </div>
                            </div>
                            {{-- <div class="flex-shrink-0 ms-3">
                                                    <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                                </div> --}}
                        </div>
                    </div>
                </li>
            </ul>

            <div id="getdata" class="">
            </div>
            

            @php
            $medias = App\Models\Media::orderBy('created_at', 'desc')->select('id', 'file', 'name')->paginate(10);
            @endphp
        

            <a id="load-more-mediafiles" class="btn-sm btn-soft-success" href="javascript:void(0);" first-page="1" current-page="{{$medias->currentPage()}}" last-page="{{$medias->lastPage()}}">Loadmore</a>

        </div>
    </div>
    <div class="text-center offcanvas-foorter">
        <button onclick="selectSingleFile()" class="p-3 border-none btn btn-success btn-border waves-effect waves-light d-block w-100" style="border-radius: 0;">Select File</button>
    </div>
</div>



