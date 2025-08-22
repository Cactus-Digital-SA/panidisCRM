@php
    /**
     * @var \App\Domains\Files\Models\File $file
     * @var array<\App\Domains\Files\Models\File> $files
     */
@endphp
@push('after-styles')

@endpush
<div class="card">
    <div class="card-header border-bottom p-3 justify-content-center">
        <h4 class="card-title m-0">Αρχεία</h4>
    </div>
    <div class="card-body">
        <div class="d-flex row align-items-start justify-content-between pt-4">
            @foreach($files??[] as $file)
                <div class="d-flex mt-2">
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
                        <div class="col-8">
                            <p class="fw-bolder mb-0">{{$file->getFileName()}}</p>
                            <span>{{$file->getSize()}} kb</span>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <div class="d-flex my-auto">
                                <form method="post" action="{{ route('file.download')}}" class="me-50">
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
    <script type="module">
        $('.delete-file-submit').on('click', function () {
            $('#file-path').val($(this).data("path"));
        });
    </script>
@endpush
