
<script type="module">
    $(function () {
        $(".filter_owner").select2({
            placeholder: 'Αναζήτηση...',
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
        $(".filter_clients").select2({
            placeholder: 'Clients...',
            allowClear: true,
            ajax: {
                type: 'POST',
                delay: 500,
                url: "{{ route('api.internal.clients.namesPaginated') }}",
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

        let owners = $('.select_owners');
        owners.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $this.parent(),
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
        });

        let assignees = $('.select_assignees');
        assignees.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $this.parent(),
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
        });

        let clients = $('.select_clients');
        clients.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $this.parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.clients.namesPaginated') }}",
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
        });


        let companies = $('.select_companies');
        companies.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Αναζήτηση...',
                allowClear: true,
                dropdownParent: $this.parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.companies.namesPaginated') }}",
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
        });


        let blockedTickets = $('.blocked-tickets');
        blockedTickets.each(function () {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Επιλέξτε tickets...',
                allowClear: true,
                dropdownParent: $this.parent(),
                ajax: {
                    type: 'POST',
                    delay: 500,
                    url: "{{ route('api.internal.tickets.search-paginated') }}",
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
                    processResults: function (data, params) {
                        return {
                            results: $.map(data.results, function (obj) {
                                return {
                                    id: obj.id,
                                    text: '#' + obj.id + ' - ' + obj.text
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
        });
    });
</script>
