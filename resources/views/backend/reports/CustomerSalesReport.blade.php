@extends('backend.layouts.app')

@section('title', 'Στατιστικά πωλήσεων / πελάτη')

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

    <div class="row g-6 mb-4">
        <div class="col-12">
            <div style="overflow-y: auto; overflow-x: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                {!! $html !!}
                @if ($maxPages > 1)
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            @php
                                $currentPage = request('page', 1);
                            @endphp

                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="?customerCode={{ $customerCode }}&page={{ $currentPage - 1 }}">Προηγούμενη</a>
                            </li>

                            @if ($currentPage > 3)
                                <li class="page-item">
                                    <a class="page-link" href="?customerCode={{ $customerCode }}&page=1">1</a>
                                </li>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif

                            @for ($i = max(1, $currentPage - 2); $i <= min($maxPages, $currentPage + 2); $i++)
                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="?customerCode={{ $customerCode }}&page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($currentPage < $maxPages - 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                <li class="page-item">
                                    <a class="page-link" href="?customerCode={{ $customerCode }}&page={{ $maxPages }}">{{ $maxPages }}</a>
                                </li>
                            @endif

                            <li class="page-item {{ $currentPage == $maxPages ? 'disabled' : '' }}">
                                <a class="page-link" href="?customerCode={{ $customerCode }}&page={{ $currentPage + 1 }}">Επόμενη</a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
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


        $(document).on('click', '.pagination a.page-link', function () {
            $('#loadingOverlay').show();
            $('body').css('pointer-events', 'none');
        });

        document.addEventListener("DOMContentLoaded", function () {
            let hasCustomer = "{{ request('customerCode') }}";

            if (hasCustomer) {
                $('#loadingOverlay').show();
                $('body').css('pointer-events', 'none');
            }
        });
    </script>
@endsection
