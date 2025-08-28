@php use App\Domains\Notes\Models\Note;use App\Models\CactusEntity; @endphp
@php
    /** @var Note $note */
    /** @var CactusEntity $model */
@endphp
@push('after-styles')
    @vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])
    <style>
        .notes-app-details {
            height: 50vh;
        }
    </style>
@endpush
@can('notes.view')
    <div class="notes-app-details show mb-5 ">
        <div class="notes-detail-header h-auto">
            <div class="notes-header-left d-flex align-items-center">
                <h4 class="notes-subject mb-0"><i class="fa fa-book-open"></i> Σημειώσεις</h4>
            </div>
        </div>
        <div class="notes-scroll-area h-100" id="vertical-example">
            @foreach($model->getNotes()??[] as $note)
                <div class="row py-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header email-detail-head py-2" style="border-bottom: 1px solid #ebe9f1;">
                                <div class="user-details d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mail-items">
                                        <h6 class="mb-0">{{$note->getUser()->getName()}}</h6>
                                        <span class="font-small-3 text-muted">
                                        {{ $note->getUser()->getEmail() }}
                                    </span>
                                    </div>
                                </div>
                                <div class="mail-meta-item">
                                    <div class="d-flex align-items-center">
                                         <small class="mail-date-time text-muted">{{ \Carbon\Carbon::parse($note->getCreatedAt())->timezone(config('app.timezone'))->format('d/m/Y H:i:s') }}</small>
                                    </div>
                                    @if($note->getUserId() === \Illuminate\Support\Facades\Auth::id() ||  \Illuminate\Support\Facades\Auth::user()->hasRole('Administrator'))
                                        <div class="d-flex justify-content-between">
                                            @can('notes.update')
                                                <div class="d-flex align-items-center"
                                                     style="justify-content: space-evenly;">
                                                    <button noteId="{{ $note->getId() }}"
                                                            class="edit-button btn rounded-pill btn-outline-secondary btn-sm btn-flat-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            @endcan
                                            @can('notes.delete')
                                                <div class="d-flex align-items-right"
                                                     style="justify-content: space-evenly;">
                                                    <form class="delete-form" method="POST"
                                                          action="{{ str_replace(':noteId', $note->getId(), route('notes.destroy', $note->getId()) ?? '') }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="delete btn rounded-pill btn-outline-danger btn-sm btn-flat-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body mail-message-wrapper p-3">
                                <div class="mail-message">
                                    <textarea class="form-control autosize" name="content" id="label-textarea"
                                              noteId="{{$note->getId()}}" disabled rows="1"
                                              placeholder="Προσθήκη σχολίου">{{$note->getContent()}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @can('notes.create')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <form method="post" action="{{ route('notes.store', ['model' => class_basename($model) , 'id'=>$model->getId()] ) }}">
                                            @csrf
                                            <fieldset class="mb-75">
                                                <textarea class="form-control autosize" name="content" id="label-textarea"
                                                          rows="2" placeholder="Προσθήκη σχολίου"></textarea>
                                            </fieldset>
                                            <button type="submit"
                                                    class="btn btn-sm btn-primary waves-effect waves-float waves-light mt-1">
                                                Προσθήκη
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @endcan
        </div>
    </div>
@endcan

@push('after-scripts')
    @vite('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')
    @vite('resources/assets/js/extended-ui-perfect-scrollbar.js')
    @vite('resources/assets/vendor/libs/autosize/autosize.js')

    <script type="module">
        $(function() {
            let textareaAutoSize = $('.autosize');

            function initializeAutosize() {
                textareaAutoSize.each(function () {
                    autosize(this); // Reinitialize autosize
                });
            }

            function destroyAutosize() {
                textareaAutoSize.each(function () {
                    autosize.destroy(this); // Destroy autosize before reinitializing
                });
            }

            // Initial autosize for textareas that are visible
            initializeAutosize();

            // Re-initialize autosize when a tab is shown
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                destroyAutosize();

                setTimeout(function() {
                    initializeAutosize();
                }, 1000); // Adjust the delay if necessary
            });
        });
    </script>
    <script type="module">
        let edit = $('.edit-button');
        edit.on('click', function () {
            let noteId = $(this).attr('noteId');
            let textarea = $('#label-textarea[noteId="' + noteId + '"]');
            let icon = $(this).find('i');

            if (textarea.prop('disabled')) {
                textarea.prop('disabled', false);
                icon.removeClass('fa-edit').addClass('fa-check');
                $(this).removeClass('btn-outline-danger').addClass('btn-success');
            }
            else {

                let updatedText = textarea.val(); // Get the updated textarea contents

                let action_url = '{{ route('notes.update',  ':noteId') ?? '-' }}';
                action_url = action_url.replace(':noteId', noteId);
                // Send the updated text to the notes.update controller via AJAX
                $.ajax({
                    {{--url: `{{ route('notes.update', '') }}/${noteId}`,--}}
                    url: action_url,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        _method: "PATCH",
                        content: updatedText
                    },
                    success: function () {
                        Swal.fire(
                            {
                                timer: 1500,
                                icon: 'success',
                                title: 'Ενημέρωση',
                                html: 'Η σημείωση έχει ενημερωθεί',
                                showCancelButton: false,
                                showConfirmButton: false
                            }
                        )
                    },
                    error: function () {
                        Swal.fire({
                            timer: 1500,
                            icon: 'error',
                            title: 'Ενημέρωση',
                            html: 'Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση.',
                            showCancelButton: false,
                            showConfirmButton: false
                        })
                    }
                });

                textarea.prop('disabled', true);
                icon.removeClass('fa-check').addClass('fa-edit');
                $(this).removeClass('btn-success').addClass('btn-outline-secondary');
            }
        });
    </script>

@endpush
