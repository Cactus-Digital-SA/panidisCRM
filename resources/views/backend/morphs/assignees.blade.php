@php
/** @var \App\Models\CactusEntity $model */
@endphp
@push('after-styles')
    @vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])
    <style>
        .notes-app-details{
            height: 50vh;
        }
    </style>
@endpush

<div class="card">
    <div class="card-header border-bottom p-3">
        <h4 class="card-title m-0">{{__('Assignees')}}</h4>
    </div>
    <div class="card-body">
        <div class="d-flex row align-items-start justify-content-between pt-4">
            <form action="{{ route('admin.users.sync.morph', [class_basename($model), $model->getId()]) }}" method="post" class="form">
                <div class="form-group row mb-1 mt-1">
                    <div class="col-md-12">
                        <label aria-label="Assignees" class="form-label">@lang('Assignees')</label>
                        <select type="text" name="assignees[]" class="form-control select2 filter_assignees"
                                data-placeholder="{{ __('Assignees') }}" multiple>
                            @foreach($model->getAssignees() as $assignee)
                                <option selected value="{{$assignee->getId()}}"> {{ $assignee->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="mt-3 btn btn-primary float-end">{{__('Save')}}</button>
                @csrf
            </form>
        </div>
    </div>
</div>

@push('after-scripts')
    @include('backend.components.js.select')
@endpush
