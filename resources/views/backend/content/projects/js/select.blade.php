
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
        $(".filter_clients").select2({
            placeholder: 'Αναζήτηση...',
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


        $(".companies-select").select2({
            placeholder: 'Αναζήτηση...',
            allowClear: true,
            ajax: {
                type: 'POST',
                delay: 500,
                url: "{{ route('api.internal.companies.namesPaginated') }}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(params) {
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
    });
</script>
