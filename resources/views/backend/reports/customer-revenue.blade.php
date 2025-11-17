@extends('backend.layouts.app')

@section('title', 'Υπόλοιπα πελατών με τζίρο')

@section('vendor-style')

@endsection

@section('content-header')

@endsection

@section('content')

    <!-- Search Bar -->
    <div class="col-12 mb-4">
        <form method="GET" id="filtersForm">
            <div id="loadingOverlay" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.8); z-index: 9999; text-align: center; padding-top: 20%;">
                <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
                <div style="margin-top: 15px; font-size: 18px; font-weight: bold;">Φόρτωση...</div>
            </div>
            <div id="filters" class="col-12 card card-accent-info mt-card-accent">
                <div class="card-body p-0">
                    <div class="row justify-content-end card-header">
                        <div class="col-md-4 col-12">
                            <label for="customerCode" class="form-label">Επιλογή Πελάτη</label>
                            <select name="customerCode" id="customerCode" class="form-control select2 customers-select" data-placeholder="Επιλογή Πελάτη" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-2-5 col-12 align-self-end">
                            <div class="ButtonToolbar w-100" role="toolbar" aria-label="Toolbar with button groups">
                                <label class="form-label"></label>
                                <button style="width: 90%;" name="search" class="btn btn-success mr-1 mb-1 waves-effect waves-light" data-toggle="tooltip"><i class="fa fa-search me-2" ></i> Αναζήτηση</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--/ Search Bar -->

    <div class="card">
        <div class="card-body">
            @if ($customerCode && empty($dataArray))
                <div class="alert alert-warning">
                    Δεν βρέθηκαν δεδομένα για τον πελάτη: <strong>{{ $customerCode }}</strong>
                </div>
            @endif

            @if (!empty($dataArray))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Α/Α</th>
                            <th>Πωλητής</th>
                            <th>Κωδικός</th>
                            <th>Επωνυμία</th>
                            <th class="text-end">Υπόλοιπο</th>
                            <th class="text-end">Τζίρος χρήσης</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dataArray as $data)
                            <tr>
                                <td>{{ $data['index'] }}</td>
                                <td>{{ $data['salesman'] }}</td>
                                <td>{{ $data['code'] }}</td>
                                <td>{{ $data['name'] }}</td>
                                <td class="text-end">{{ number_format($data['balance'], 2, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($data['turnover'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script type="module">
        $(document).ready(function () {
            let selectedCustomer = @json(request('customerCode'));

            if (selectedCustomer) {
                let option = new Option(selectedCustomer, selectedCustomer, true, true);
                $('#customerCode').append(option).trigger('change');
            }

            $('.customers-select').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $('.customers-select').parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.companies.getCustomers') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name=\"csrf-token\"]').attr('content')
                    },
                    data: params => ({
                        term: params.term || '',
                        page: params.page || 1
                    }),
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {
                                    id: obj.id,
                                    text: obj.text,
                                };
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });

        });

        $('button[name="search"]').on('click', function () {
            $('#loadingOverlay').show();

            $('body').css('pointer-events', 'none');
            $(this).closest('form').submit();
        });
    </script>
@endsection
