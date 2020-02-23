<div class="form-group">
    <label>{{ __('Author') }}</label>
{{ Form::select($fieldName, $accounts, $currentUser->id, ['class' => 'form-control', 'id' => 's-author']) }}
</div>
@push('js-stack')
    <script>
        $(function () {
            $('#s-author').select2({
                ajax: {
                    url: '{{ route('api.user.index') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                            per_page: 10
                        };
                    },
                    processResults: function (data) {
                        const users = data.data
                        let items = []
                        for(const i in users) {
                            if (users.hasOwnProperty(i)) {
                                items.push({id: users[i].id, text: users[i].name})
                            }
                        }
                        return {results: items}
                    }
                }
            });
        });
    </script>
@endpush
