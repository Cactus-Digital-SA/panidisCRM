@php
    use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
    /**
    * @var array<\App\Domains\ExtraData\Models\ExtraData> $extraData
    */

    if(!isset($row)) $row = 'row mb-3 mt-1';
    if(!isset($labelCol)) $labelCol = 'col-md-2';
    if(!isset($fieldCol)) $fieldCol = 'col-md-8';
@endphp
@foreach($extraData ?? [] as $data)
    <div class="form-group {{$row}}">
        <label for="extra_data[{{$data->getId()}}]" class="{{$labelCol}} col-form-label">{{$data->getDescription()}} @if($data->getRequired()) <small class="text-danger"> *</small> @endif</label>
        <div class="{{$fieldCol}}">
            @php
                if($data->isMultiple()){
                    $pivots = [];
                    if (isset($model) && is_object($model) && method_exists($model, 'getExtraData')) {
                        foreach ($model?->getExtraData() ?? [] as $modelExtraData) {
                            if ($modelExtraData->getId() == $data->getId()) {
                                $pivots[] = $modelExtraData->getPivot();
                            }
                        }
                    }

                    $values = [];
                    foreach($pivots as $pivot){
                        $values[] = $pivot->getValue();
                    }
                }
                else{
                    $pivot = null;
                    if (isset($model) && is_object($model) && method_exists($model, 'getExtraData')) {
                        foreach ($model?->getExtraData() ?? [] as $modelExtraData) {
                            if ($modelExtraData->getId() == $data->getId()) {
                                $pivot = $modelExtraData->getPivot();
                                break;
                            }
                        }
                    }

                    $value = $pivot ? $pivot->getValue() : null;
                }
            @endphp

            @if($data->getType() == ExtraDataTypesEnum::TEXT)
                <input type="text" class="form-control" name="extra_data[{{$data->getId()}}]" placeholder="{{$data->getDescription()}}" value="{{ $value }}" @if($data->getRequired()) required @endif>
           @elseif($data->getType() == ExtraDataTypesEnum::INT)
                <input type="number" class="form-control" name="extra_data[{{$data->getId()}}]" placeholder="{{$data->getDescription()}}" value="{{ $value }}" step="1" min="0"  max="9999999" @if($data->getRequired()) required @endif>
            @elseif($data->getType() == ExtraDataTypesEnum::SELECT)
                <select class="form-select select2 enable-tag"
                        @if($data->isMultiple()) name="extra_data[{{$data->getId()}}][]" @else name="extra_data[{{$data->getId()}}]"  @endif
                        @if($data->getRequired()) required @endif
                        data-allow-clear="true" data-placeholder="{{$data->getDescription()}}"
                        @if($data->isMultiple()) multiple @endif
                >
                    <option value="">---</option>
                    @if($data->isMultiple())
                        @foreach (json_decode($data->getOptions()) ?? [] as $key => $option)
                            <option value="{{$key}}" @if(in_array($key, $values)) selected @endif>{{$option}}</option>
                        @endforeach
                    @else
                        @foreach (json_decode($data->getOptions()) ?? [] as $key => $option)
                            <option value="{{$key}}" @if($key == $value) selected @endif>{{$option}}</option>
                        @endforeach
                    @endif

                </select>
            @endif
        </div>
    </div>
@endforeach
@push('after-scripts')
    <script type="module">
        $(document).ready(function () {
            $('.enable-tag').select2({
                tags: true,
            });
        });
    </script>

@endpush


