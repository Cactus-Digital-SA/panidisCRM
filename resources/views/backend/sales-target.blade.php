@extends('backend.layouts.app')

@section('title', 'Στόχοι Πωλήσεων')

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
                        <div class="col-md-2 col-12">
                            <label for="salesmanSelect" class="form-label">Επιλογή Πωλητή</label>
                            <select name="salesman" id="salesmanSelect" class="form-control select2" data-placeholder="Πωλητής" data-allow-clear="true">
                                <option></option>
                                @foreach($salesmen as $salesman)
                                    <option value="{{ $salesman->getErpId() }}"
                                        {{ request('salesman') == $salesman->getErpId() ? 'selected' : '' }}>
                                        {{ $salesman->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col12">
                            <label for="salesmanSelect" class="form-label">Επιλογή Μήνα</label>
                            <select name="month" class="form-select select2" data-placeholder="Μήνας" data-allow-clear="true">
                                <option value="">Όλοι οι μήνες</option>

                                @foreach ($months as $month)
                                    <option value="{{ $month['value'] }}"
                                        @selected($activeMonth ?? request('month') == $month['value'])>
                                        {{ $month['label'] }}
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
        <div class="col-xl-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    @if(empty($salesData))
                        <div class="alert alert-info mb-0">
                            Δεν βρέθηκαν δεδομένα για τα επιλεγμένα φίλτρα.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Πωλητής</th>
                                        <th>Μήνας</th>
                                        <th class="text-end">Στόχος (€)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesData as $row)
                                        <tr>
                                            <td>{{ $row->getSalesmanName() ?? '-' }}</td>
                                            <td>{{ $row->getMonthSales() ?? '-' }}</td>
                                            <td class="text-end">
                                                {{ number_format($row->getTargetAmount() ?? 0, 0, ',', '.') }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script type="module">
        $(document).ready(function () {

        });

        $('button[name="search"]').on('click', function () {
            $('#loadingOverlay').show();

            $('body').css('pointer-events', 'none');
            $(this).closest('form').submit();
        });
    </script>
@endsection
