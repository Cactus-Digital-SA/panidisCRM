@php
    /**
     * @var \App\Domains\Files\Models\File $file
     * @var array<\App\Domains\Files\Models\File> $files
     */
@endphp
@push('after-styles')
    @vite(['resources/assets/vendor/libs/dropzone/dropzone.scss'])
@endpush
<div class="card">
    <div class="card-header border-bottom p-3">
        <h4 class="card-title m-0">Αρχεία</h4>
    </div>
    <div class="card-body">
        <div class="d-flex row align-items-start justify-content-between pt-4">
            @foreach($model->getFiles()??[] as $file)
                <div class="d-flex mt-4">
                    <div class="d-flex align-item-center flex-grow-1">
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            @if($file->getMimeType() == "image/jpeg")
                                <img class="round"
                                     src="{{route('file.preview',['filePath' => $file->getPath()])}}"
                                     alt="avatar" height="40" width="40">
                            @elseif($file->getMimeType() == "application/pdf")
                                <i class="fa-sharp fa-regular fa-file-pdf fa-2xl" style="color: purple;"></i>
                            @else
                                <i class="fa-sharp fa-regular fa-file fa-2xl" style="color: cadetblue;"></i>
                            @endif
                        </div>
                        <div class="{{ 'col-6' }} text-center">
                            <p class="fw-bolder mb-0">{{$file->getFileName()}}</p>
                            <span>{{$file->getSize()}} kb</span>
                        </div>
                        <div class="col-2 text-center">
                            <p class="fw-bolder mb-0">Ημ/νια Δημιουργίας</p>
                            <span>{{$file->getCreatedAt()->format('d-m-Y')}}</span>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <div class="d-flex my-auto">
                                <form method="post" action="{{ route('file.download')}}" class="me-50 mx-1">
                                    @csrf
                                    <input type="hidden" name="filePath" value="{{ $file->getPath() }}">
                                    <button class="btn btn-icon btn-outline-info waves-effect" type="submit"><i class="fa fa-download"></i></button>
                                </form>
                                <a href="" class="btn btn-icon btn-outline-danger waves-effect delete-file-submit" role="button" data-path="{{$file->getPath()}}" data-bs-toggle="modal" data-bs-target="#deleteFileModal"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="card mt-3">

    <h5 class="card-header">{{__("Upload")}}</h5>
    <div class="card-body">
        <div class="cactus-dropzone"></div>
        <form enctype="multipart/form-data" action="{{ route('file.store', [class_basename($model), $model->getId()]) }}" class="dropzone needsclick" method="post" id="dropzone-multi">
            <div class="dz-message needsclick">
                Drop files here or click to upload
            </div>
            <div class="fallback">
                <input multiple name="file[]" type="file" />
            </div>
            @csrf
        </form>
        <button type="button" class="btn btn-primary mt-3 float-end" id="submit-upload">Upload</button>
    </div>
</div>



<div class="modal fade" id="deleteFileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <form id="delete-file-form" enctype="multipart/form-data" method="POST" action="{{ route('file.destroy')}}" class="row gy-1 pt-75">
                    @method('DELETE')
                    @csrf()
                    <input type="hidden" name="filePath" id="file-path">
                    <div class="col-12">
                        <div style="text-align: center;"> <h3 style="color: #EA5455">Είστε σίγουροι για την διαγραφή;</h3></div>
                        <br>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Διαγραφή <i class="fa fa-trash ms-2"></i></button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Άκυρο
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


    <script type="module">
        const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

        const dropzoneMulti = document.querySelector('#dropzone-multi');
        if (dropzoneMulti) {
            new Dropzone(dropzoneMulti, {
                url: "{{ route('file.store', [class_basename($model), $model->getId()]) }}", // Your Laravel route for uploads
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                previewTemplate: previewTemplate,
                uploadMultiple: true,
                parallelUploads: 1,
                autoProcessQueue: false, // Disable auto-upload to manually trigger
                maxFilesize: 15000, // Max file size in MB
                addRemoveLinks: true, // Allow removing files from the queue
                init: function () {
                    let dz = this;

                    document.getElementById("submit-upload").addEventListener("click", function () {
                        console.log("Queued files:", dz.getQueuedFiles());
                        if (dz.getQueuedFiles().length > 0) {
                            console.log(dz);
                            dz.processQueue(); // Manually trigger the upload
                        } else {
                            alert("No files in the queue!");
                        }
                    });

                    // Clear the dropzone after successful upload
                    dz.on("successmultiple", function (files, response) {
                        // Clear the files after successful upload

                        if (dz.getQueuedFiles().length == 0) {
                            dz.removeAllFiles(true);
                            toastr.success("{{ __('File uploaded successfully')  }}");
                            // Build the new URL with the 'tab' parameter
                            let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=navs-pills-files';

                            // Redirect to the new URL
                            window.location.href = newUrl;

                        }else{
                            dz.processQueue();
                        }

                    });
                }
            });
        }



    </script>
    <script type="module">
        $('.delete-file-submit').on('click', function () {
            $('#file-path').val($(this).data("path"));
        });
    </script>
@endpush
