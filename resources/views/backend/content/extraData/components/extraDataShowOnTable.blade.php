@php
    use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
    /**
    * @var array<\App\Domains\ExtraData\Models\ExtraData> $extraData
    */


@endphp

@foreach($extraData ?? [] as $data)
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
        }else{
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


    <tr>
        <td style="width: 10%;"><span class="fw-bold">{{$data->getDescription()}}</span></td>
        @if($data->getType() == ExtraDataTypesEnum::TEXT)
            <td>{{$value}}</td>
        @elseif($data->getType() == ExtraDataTypesEnum::INT)
            <td>{{$value}}</td>
        @elseif($data->getType() == ExtraDataTypesEnum::SELECT)
            <td>
                @if($data->isMultiple())
                    @foreach (json_decode($data->getOptions()) ?? [] as $key => $option)
                        @if(in_array($key, $values))
                            {{$option}} <br>
                        @endif
                    @endforeach
                @else
                    @foreach (json_decode($data->getOptions()) ?? [] as $key => $option)
                        @if($key == $value)
                            {{$option}} <br>
                        @endif
                    @endforeach
                @endif
            </td>
        @endif

    </tr>

@endforeach

