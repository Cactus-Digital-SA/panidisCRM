@extends('backend.layouts.app')

@section('title', 'Επεξεργασία Quote #'.$quote->getId())

<!-- Vendor Style -->
@section('vendor-style')

@endsection

@section('page-style')

@endsection

@push('after-styles')
    <style>
        /* Hide arrows for number input in Chrome, Safari, Edge, and Opera */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide arrows for number input in Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .table td{
            padding: 0.5rem !important;
        }

        .table:not(.table-borderless):not(.dataTable) thead th {
            border-block-start-width: 1px;
        }

        .table:not(.table-dark):not(.table-light) thead:not(.table-dark) th, .table:not(.table-dark):not(.table-light) tfoot:not(.table-dark) th {
            background-color: transparent;
        }

        .sumTable tr {
            border-style: hidden;
        }

        .sumTable td {
            padding: 5px !important;
        }

        .input-group-text{
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .items-select-column{
            max-width: 130px;
        }

        .items-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #itemsTable {
            min-width: 1200px;
        }
        #tax_rate {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        #tax {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group .select2-container {
            flex: 0 0 auto !important;
            width: 90px !important;
        }
        .input-group .select2-container .select2-selection--single {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            inset-inline-end: 0rem;
        }
    </style>
@endpush

@section('content-header-breadcrumbs')
    <li class="breadcrumb-item"> <a href="{{ route('admin.home') }}" class="">Αρχική</a> </li>
    <li class="breadcrumb-item"> <a href="{{ route('admin.clients.index') }}" class="">Πελάτες</a> </li>
    <li class="breadcrumb-item active">Δημιουργία</li>
@endsection

@section('content')
    <form id="quotesForm" method="POST" action="{{ route('admin.quotes.update', $quote->getId()) }}" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PATCH')
        <div class="card">
            <div class="card-body">
                <div class="form-group row mb-4 mt-4">
                    <div class="col-lg-6 ">
                        <label for="company_id" class="col-form-label">Εταιρεία <small class="text-danger">*</small></label>
                        <div class="col-md-12">
                            <select name="company_id" id="company_id" class="form-control companies_select" data-placeholder="{{ 'Εταιρεία' }}" required>
                                @if($quote->getCompany())
                                    <option value="{{ $quote->getCompany()->getId() }}" selected>{{ $quote->getCompany()?->getName() }}</option>
                                @endif
                            </select>
                            <div class="invalid-feedback">Η εταιρεία είναι απαραίτητη.</div>
                        </div>
                    </div>

                    <div class="col-lg-6 ">
                        <label for="title" class="col-form-label">Τίτλος Quote <small class="text-danger"> *</small></label>
                        <div class="col-md-12">
                            <input type="text" name="title" class="form-control" placeholder="Τίτλος Quote" maxlength="255" required value="{{ old('title', $quote->getTitle()) }}"/>
                            <div class="invalid-feedback">Ο τίτλος Quote είναι απαραίτητος.</div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-4 mt-4">
                    <div class="col-lg-6">
                        <label for="valid_until" class="col-form-label">Valid until <small class="text-danger">*</small></label>
                        <div class="col-md-12">
                            <input type="text" name="valid_until" id="valid_until" placeholder="dd-mm-yyyy" autocomplete="off" class="form-control datepicker" required value="{{ old('valid_until', $quote->getValidUntil()?->format('Y-m-d')) }}">
                            <div class="invalid-feedback">Η ημ/νια αποδοχής είναι απαραίτητη.</div>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <label for="contacts" class="col-form-label">Επαφή επικοινωνίας</label>
                        <div class="col-md-12">
                            <select id="contacts" name="contacts[]" class="form-select select2 select_contacts" data-placeholder="Επαφή επικοινωνίας" data-allow-clear="true" multiple>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-4 mt-4">
                    <div class="col-lg-6 ">
                        <label for="assignees" class="col-form-label">Signed By</label>
                        <div class="col-md-12">
                            <select id="assignees" name="assignees[]" class="form-select select2 filter_assignees" data-placeholder="Signed By" data-allow-clear="true" multiple>
                                @foreach($quote->getAssignees() as $assignee)
                                    <option selected value="{{$assignee->getId()}}"> {{ $assignee->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <label for="status" class="col-form-label">Status</label>
                        <div class="col-md-12">
                            <select id="status" name="status" class="select2 form-select" data-placeholder="Status" data-allow-clear="true" required>
                                @foreach(\App\Domains\Quotes\Enums\QuoteStatusEnum::cases() as $status)
                                    <option value="{{$status->value}}" @if($quote->getStatus()->value == $status->value) selected @endif> {{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="items-container">
                    <div class="table-responsive items-table-container">
                        <table class="table" id="itemsTable">
                            <thead>
                            <tr id="table-header">
                                <th class="text-center col-md-2">Product / Service</th>
                                <th class="text-center col-md-2">Όνομα προϊόντος</th>
                                <th class="text-center col-md-1-5">SKU</th>
                                <th class="text-center col-md-1">Χρώμα</th>
                                <th class="text-center col-md-1">Ποσότητα</th>
                                <th class="text-center col-md-1">Τύπος Ποσότητας</th>
                                <th class="text-center col-md-2">Τιμή</th>
                                <th class="text-center col-md-2">Τελική Τιμή</th>
                                <th class="text-center col-md-1" style="width: 65px;"></th>
                            </tr>
                            </thead>
                            <tbody id="routes-table-body">
                            @foreach($quote->getItems() as $i => $item)
                                <tr class="item-row">
                                    <td class="col-md-2 items-select-column">
                                        <select name="items[{{ $i }}][item_id]" id="item_{{ $i }}_id" class="form-control select2 items-select" data-placeholder="Product/Service" data-allow-clear="true" required>
                                            <option value="{{ $item->getItemId() }}" selected>
                                                {{ $item->getProductName() ?? '' }}
                                            </option>
                                        </select>
                                    </td>
                                    <td class="col-md-2">
                                        <input type="text" name="items[{{ $i }}][product_name]" class="form-control" placeholder="Όνομα προϊόντος" required value="{{ $item->getProductName() }}">
                                    </td>
                                    <td class="col-md-1-5">
                                        <input type="text" name="items[{{ $i }}][sku]" class="form-control" placeholder="SKU" required value="{{ $item->getSku() }}">
                                    </td>
                                    <td class="col-md-1">
                                        <input type="text" name="items[{{ $i }}][color]" class="form-control" placeholder="Χρώμα" value="{{ $item->getColor() }}">
                                    </td>
                                    <td class="col-md-1">
                                        <input type="number" name="items[{{ $i }}][quantity]" class="form-control" min="0" step="0.01" onwheel="this.blur()" required value="{{ $item->getQuantity() }}">
                                    </td>
                                    <td class="col-md-1">
                                        <select name="items[{{ $i }}][unit_type]" id="unit_type_{{ $i }}" class="form-control select2 unit_type_select" data-placeholder="Τύπος" data-allow-clear="true" required>
                                            @foreach(\App\Domains\Quotes\Enums\UnitTypeEnum::cases() as $unitType)
                                                <option value="{{ $unitType->value }}" @selected($unitType->value == $item->getUnitType()?->value)>{{ $unitType->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="input-group">
                                            <input type="number" name="items[{{ $i }}][price]" class="form-control" min="0" step="0.01" placeholder="€" onwheel="this.blur()" required value="{{ $item->getPrice() }}">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="input-group">
                                            <input type="number" name="items[{{ $i }}][item_total_price]" class="form-control" placeholder="€" onwheel="this.blur()" readonly value="{{ $item->getTotal() }}">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </td>
                                    <td class="text-center remove-column-cell">
                                        <button type="button" class="btn btn-danger btn-small remove-row">
                                            <i class="fa-solid fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 content-header-left text-md-start col-md-auto d-flex my-2">
                        <div class="mb-1 breadcrumb-left">
                            <div class="content-header-left text-md-end col-md-auto col-12">
                                <div class="mb-1 breadcrumb-left">
                                    <button type="button" class="btn btn-success btn-round waves-effect waves-float waves-light add-item">
                                        <i class="ti ti-plus me-1"></i> Προσθήκη Υπηρεσίας
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-md-between mt-3">
                    <!-- Payment & Delivery Terms -->
                    <div class="col-xl-6 col-xxl-5">
                        <table class="table sumTable">
                            <tbody>
                            <tr>
                                <td class="col-3 text-start text-xl-end"><strong>Payment Terms:</strong></td>
                                <td class="text-end">
                                    <select id="payment_terms" name="payment_terms" class="select2 form-select text-end" data-placeholder="Payment Terms">
                                        <option></option>
                                        @foreach(\App\Domains\Quotes\Enums\PaymentTermsEnum::cases() as $paymentTerm)
                                            <option value="{{ $paymentTerm->value }}" @selected($paymentTerm->value == $quote->getPaymentTerms()?->value)>{{ $paymentTerm->label() }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-3 text-start text-xl-end"><strong>Delivery Terms:</strong></td>
                                <td class="text-end">
                                    <textarea id="delivery_terms" name="delivery_terms" class="form-control text-end" rows="2" placeholder="">{{ old('delivery_terms', $quote->getDeliveryTerms()) }}</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals summary panel -->
                    <div class="col-xl-6 col-xxl-5">
                        <table class="table sumTable">
                            <tbody>
                            <tr>
                                <td class="col-3 text-start text-xl-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">
                                    <input type="number" id="subtotal" name="subtotal" class="form-control text-end" value="{{ $quote->getSubtotal() }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start text-xl-end"><strong>Tax:</strong></td>
                                <td class="text-end">
                                    <div class="input-group">
                                        <select id="tax_rate" name="tax_rate" class="form-select text-end" style="max-width: 90px;">
                                            @foreach (App\Domains\Quotes\Enums\TaxRatesEnum::cases() as $rate)
                                                <option value="{{ $rate->value }}" @selected($rate->value == $quote->getTaxRate()?->value)>{{ $rate->label() }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" id="tax" name="tax" class="form-control text-end" value="{{ $quote->getTax() }}" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start text-xl-end"><strong>Total:</strong></td>
                                <td class="text-end">
                                    <input type="number" id="total" name="total" class="form-control text-end fw-bold" value="{{ $quote->getTotal() }}" readonly>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="col-12 text-center mt-5 pt-50">
                    <button type="submit" class="btn btn-primary me-1">Αποθήκευση <i class="fa fa-check ms-2"></i></button>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('modals')

@endsection

<!-- Vendor Script -->
@section('vendor-script')

@endsection

<!-- Page Script -->
@section('page-script')

    <script type="module">
        $(document).ready(function () {
            $('#tax_rate').select2({
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#tax_rate').closest('.input-group')
            });

            let routeIndex = 1;
            // Add Route Row
            $(".add-item").click(function () {
                let firstRow = $(".item-row:first");

                firstRow.find(".items-select").select2('destroy');
                firstRow.find(".unit_type_select").select2('destroy');

                let newRow = $(".item-row:first").clone();

                initSelectProduct(firstRow.find(".items-select"));
                initSelectUnitType(firstRow.find(".unit_type_select"));


                newRow.find("input").val("");
                newRow.find("textarea").each(function () {
                    $(this).val("");
                    $(this).removeAttr("style");
                    $(this).attr("rows", 1);
                });
                newRow.find(".remove-row").show();

                newRow.find("input[name^='items[']").each(function () {
                    let name = $(this).attr("name").replace(/\d+/, routeIndex);
                    $(this).attr("name", name);
                });

                newRow.find("textarea[name^='items[']").each(function () {
                    let id = $(this).attr("id").replace(/\d+/, routeIndex);
                    $(this).attr("id", id);

                    let name = $(this).attr("name").replace(/\d+/, routeIndex);
                    $(this).attr("name", name);
                });

                newRow.find("select[name^='items[']").each(function () {
                    let name = $(this).attr("name").replace(/\d+/, routeIndex);
                    $(this).attr("name", name);

                    if (name.includes('[unit_type]')) {
                        $(this).attr("id", `unit_type_${routeIndex}`);
                    } else if ($(this).attr("id")) {
                        let id = $(this).attr("id").replace(/\d+/, routeIndex);
                        $(this).attr("id", id);
                    }
                });

                newRow.find('select').each(function () {
                    $(this).val(null).trigger('change');
                });

                $("#itemsTable tbody").append(newRow);

                initSelectProduct(newRow.find(".items-select"));

                initSelectUnitType(newRow.find(".unit_type_select"));



                routeIndex++;


            });

            $(document).on('input', 'input[name*="[quantity]"], input[name*="[price]"]', function () {
                const row = $(this).closest('tr');
                calculateItemTotal(row);
            });

            // Auto-calculate item_total_price when quantity or price changes
            function calculateItemTotal(row){
                const quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0;
                const price = parseFloat(row.find('input[name*="[price]"]').val()) || 0;
                const total = quantity * price;
                row.find('input[name*="[item_total_price]"]').val(total.toFixed(2));
            }


            // Remove Route Row
            $(document).on("click", ".remove-row", function () {
                if ($("#itemsTable tbody tr").length > 1) {
                    $(this).closest("tr").remove();
                    updateTotal();
                }
            });


            $('#valid_until').each(function (i, date) {
                date.flatpickr({
                    minDate: new Date(new Date().setDate(new Date().getDate() - 2)),
                    locale: 'gr',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    dateFormat: 'Y-m-d',
                })
            });

            let url = `{{ route('api.internal.companies.namesPaginatedByType', ':type') }}`.replace(':type', 'client');

            $(".companies_select").select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: url,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {
                                    id: obj.id,
                                    text: obj.text + (obj.status ? ' (' + obj.status + ')' : '')
                                }; // Use id and name
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                }
            });


            $('#company_id').on('change', function () {
                fetchContacts();
            });

            fetchContacts();
            function fetchContacts(){
                let companyId = $('#company_id').val();

                let contacts = $("#contacts");
                contacts.val(null).trigger('change');
                contacts.empty();

                if (String(companyId) === "{{ $quote->getCompanyId() }}") {
                    @foreach($quote->getContacts() ?? [] as $contact)
                    contacts.append(new Option("{{ $contact->getName() }}", "{{ $contact->getId() }}", true, true));
                    @endforeach
                }

                let url = `{{ route('api.internal.companies.getContactsByCompanyId', ':companyId') }}`.replace(':companyId', companyId);
                $(".select_contacts").select2({
                    placeholder: 'Αναζήτηση...',
                    allowClear: true,
                    ajax: {
                        type: 'POST',
                        delay: 500,
                        url: url,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function (params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                        processResults: function (data, params) {
                            return {
                                results: $.map(data.results, function (obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.text
                                    }; // Use id and name
                                }),
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    }
                });
            }

            $(".filter_assignees").select2({
                placeholder: 'Assignees',
                allowClear: true,
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.users.namesPaginated') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    // processResults: function (data, params) {
                    //     return data
                    // },
                    processResults: function (data) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {id: obj.id, text: obj.text}; // Use id and name
                            })
                        };
                    },
                    cache: true
                }
            });

            // Totals calculation
            function updateTotal() {
                let subtotal = 0;
                $('input[name*="[item_total_price]"]').each(function () {
                    subtotal += parseFloat($(this).val()) || 0;
                });

                const taxRate = (parseFloat($('#tax_rate').val()) || 0) / 100;
                const tax = subtotal * taxRate;
                const total = subtotal + tax;

                $('#subtotal').val(subtotal.toFixed(2));
                $('#tax').val(tax.toFixed(2));
                $('#total').val(total.toFixed(2));
            }

            // Update total when changing tax rate
            $(document).on('change', '#tax_rate', function () {
                updateTotal();
            });


            // Update total when changing any price/quantity
            $(document).on('input', 'input[name*="[quantity]"], input[name*="[price]"]', function () {
                updateTotal();
            });

            // Update total when adding/removing rows
            $(document).on('click', '.add-item, .remove-row', function () {
                setTimeout(updateTotal, 100);
            });

            // Initial calculation
            setTimeout(updateTotal, 500);
            // End totals calculation

            initSelectProduct();
            function initSelectProduct(selector = $('.items-select')) {
                selector.select2({
                    placeholder: 'Αναζήτηση...',
                    allowClear: true,
                    dropdownParent: selector.parent(),
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
                                        id: obj.id,
                                        text: obj.text,
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
                        // processResults: data => ({
                        //     results: data.results.map(obj => ({
                        //         id: obj.id,
                        //         text: obj.text,
                        //         sku: obj.sku,
                        //         color: obj.color,
                        //         price: obj.price,
                        //         product_name: obj.name
                        //     }))
                        // }),
                        cache: true
                    }
                });

                selector.on('select2:select', function (e) {
                    const data = e.params.data;
                    const row = $(this).closest('tr');

                    // Συμπλήρωσε τα πεδία
                    row.find('input[name*="[product_name]"]').val(data.product_name || data.text || '');
                    row.find('input[name*="[sku]"]').val(data.sku || '');
                    // row.find('input[name*="[color]"]').val(data.color || '');
                    row.find('input[name*="[price]"]').val(data.price || '');

                    calculateItemTotal(row);
                    updateTotal();
                });

                selector.on('select2:clear', function () {
                    const row = $(this).closest('tr');
                    row.find('input[name*="[product_name]"]').val('');
                    row.find('input[name*="[sku]"]').val('');
                    // row.find('input[name*="[color]"]').val('');
                    row.find('input[name*="[price]"]').val('');
                });
            }
        });

        function initSelectUnitType(selector = $('.unit_type_select')) {
            selector.select2({
                placeholder: 'Τύπος',
                allowClear: true,
                width: '100%',
                dropdownParent: $(document.body),
            });
        }

    </script>
@endsection

