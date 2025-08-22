<div id="columns" style="display: none;" class="p-2 col-12 card card-accent-dark mt-card-accent">
    <div class="card-body p-0">
        <div class="dt-buttons row">
            <div class="col-md-10">
                <div class="row">
                    @foreach($columns as $index => $column)
                        @if($index != 'id')
                            <div class="col-2"> <!-- This ensures 6 items per row -->
                                <div class="form-check form-check-dark m-1">
                                    <input class="form-check-input column-toggle" type="checkbox"
                                           name="toggleColumn[]" id="toggleColumn{{ $loop->index }}"
                                            value="{{ $loop->index  }}"
                                    >
                                    <label class="form-check-label"
                                           for="toggleColumn{{ $loop->index }}">{{ __($column['name']) }}</label>
                                </div>
                            </div>
                        @endif
                        @if(($loop->index) % 6 === 0 && ($loop->index) !== count($columns))
                </div>
                <div class="row">
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-2 col-12" style="display: flex; align-items: center;">
                <div class="form-group row p-0 m-0">
                    <div class="col-md-12">
                        <div class="ButtonToolbar" role="toolbar">
                            <button style="width: 100%;" id="selectAllColumns" class="btn btn-dark mr-1 mb-1 waves-effect waves-light" data-toggle="tooltip"><i class="fa fa-asterisk me-2"></i> {{__('Επιλογή όλων')}} </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <!-- Todo if checkbox is not checked -->
    <script type="module">
        $(function() {
            $('#selectAllColumns').on('click',function (){
                @foreach($columns as $index => $column)
                    @if($loop->index != 0)
                        if(!$('#toggleColumn{{$loop->index}}').prop('checked')){
                            $('#toggleColumn{{ $loop->index }}').trigger('click');
                        }
                    @endif
                @endforeach
            })

            // Ensure to save state after selecting all columns
            //$('#selectAllColumns').on('click', saveColumnsState);

        })
    </script>


@endpush
