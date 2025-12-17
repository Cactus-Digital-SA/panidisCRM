@extends('backend.layouts.app')

@section('title', 'Sales Dashboard')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/chartjs/chartjs.scss'])

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
                        <div class="col-md-2 col-12">
                            <label for="salesmanSelect" class="form-label">Επιλογή Πωλητή</label>
                            <select name="salesman" id="salesmanSelect" class="form-control select2" data-placeholder="Πωλητής" data-allow-clear="true">
                                <option></option>
                                @foreach($salesmen as $salesman)
                                    <option value="{{ $salesman->getName() }}"
                                        {{ request('salesman') == $salesman->getName() ? 'selected' : '' }}>
                                        {{ $salesman->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label for="productCode" class="form-label">Προϊόν</label>
                            <select name="productCode" id="productCode" class="form-control select2 items-select" data-placeholder="Προϊόν" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label for="categoryProduct" class="form-label">Κατηγορία</label>
                            <select name="categoryProduct" id="categoryProduct" class="form-control select2 categories-select" data-placeholder="Προϊόν" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <label for="area" class="form-label">Επιλογή Περιοχής - Τομέα</label>
                            <select name="area" id="area" class="form-control select2" data-placeholder="Περιοχή/Τομέας" data-allow-clear="true">
                                <option></option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->getName() }}"
                                        {{ request('area') == $area->getName() ? 'selected' : '' }}>
                                        {{ $area->getName() }}
                                    </option>
                                @endforeach
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

    <div class="row g-6 mb-4">
        <div class="col-xl-6 col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Πωλήσεις/Πωλητή</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesBySalesmanChart" style="max-height: 400px"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Πωλήσεις/Περιοχή (Sector)</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesByAreaChart" style="max-height: 400px"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/chartjs/chartjs.js'])

@endsection

@section('page-script')
    <script type="module">
        const salesByArea = @json($salesData['byArea']);
        const salesBySalesman = @json($salesData['bySalesman']);

        new Chart(document.getElementById('salesByAreaChart'), {
            type: 'pie',
            data: salesByArea
        });

        new Chart(document.getElementById('salesBySalesmanChart'), {
            type: 'bar',
            data: salesBySalesman,
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        $(document).ready(function () {
            let selectedProduct = @json(request('productCode'));

            if (selectedProduct) {
                let option = new Option(selectedProduct, selectedProduct, true, true);
                $('#productCode').append(option).trigger('change');
            }

            $('.items-select').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $('.items-select').parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.items.itemsPaginated') }}",
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
                                    id: obj.sku,
                                    text: obj.text + ' (' + obj.sku + ')',
                                    sku: obj.sku,
                                    color: obj.color,
                                    price: obj.price,
                                    product_name: obj.name
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


            let selectedCategory = @json(request('categoryProduct'));

            if (selectedCategory) {
                let option = new Option(selectedCategory, selectedCategory, true, true);
                $('#categoryProduct').append(option).trigger('change');
            }

            $('.categories-select').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $('.categories-select').parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.items.categoriesPaginated') }}",
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
                                    id: obj.text,
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
