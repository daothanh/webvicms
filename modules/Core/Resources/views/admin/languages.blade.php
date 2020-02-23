@extends($adminTheme.'::layouts.master')
@section('title')
    {{ settings('website.name.'.locale()) }}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th>{{ __('Language Name') }}</th>
                        <th>{{ __('Native') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Active') }}</th>
                        <th width="200">{{ __('Default') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js-stack')
    <script>
      $(function () {
        let table = $('#data-table').DataTable({
          buttons: [
            'print',
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
          ],
          autoWidth: false,
          responsive: true,
          lengthMenu: [[25, 50, 100], ['25 {{ __("Rows") }}', '50 {{ __("Rows") }}', '100 {{ __("Rows") }}']],
          "serverSide": true,
          "ajax": {
            url: "{{ route('api.language.index') }}",
            // headers: {'Authorization': AuthorizationHeaderValue}
          },
          "columns": [
            {"data": "name", 'searchable': false, "orderable": false},
            {"data": "native", 'searchable': false, "orderable": false},
            {"data": "code", 'searchable': false, "orderable": false},
            {"data": "status", 'searchable': false, "orderable": true},
            {"data": "default", 'searchable': false, "orderable": false},
          ],
          // "order": [[0, "desc"]],
          createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(3)').html('<a class="text-' + (data.status === 'Active' ? 'success ' : '') + ' toggle" data-id="' + data.id + '"><i class="icon ion-md-' + (data.status === 'Active' ? 'checkmark-circle ' : 'radio-button-off') + '"></i></a>');
            $(row).find('td:eq(4)').html('<a  class="text-' + (data.default === 1 ? 'success' : '') + ' t-default" data-id="' + data.id + '"><i class="icon ion-md-' + (data.default === 1 ? 'checkmark-circle' : 'radio-button-off') + '"></i></a>\n'
            );
          }
        });
        $('body').on('click', '.toggle', function (e) {
          e.preventDefault();
          let url = route('api.language.toggle', {id: $(this).data('id')});
          axios.put(url)
            .then(rs => {
              if (rs.data.msg === undefined) {
                table.ajax.reload();

                notify('{{ __("Alert") }}', (rs.data.data.status === 'Active' ? 'Kích hoạt ngôn ngữ ' : 'Bỏ kích hoạt ngôn ngữ ') + rs.data.data.native, 'success');
              } else {
                notify('{{ __("Alert") }}', rs.data.msg, 'error');
              }
            });
        });

        $('body').on('click', '.t-default', function (e) {
          e.preventDefault();
          let url = route('api.language.default', {id: $(this).data('id')});
          axios.put(url)
            .then(rs => {
              if (rs.data.msg === undefined) {
                table.ajax.reload();

                notify('{{ __("Alert") }}', 'Ngôn ngữ chính là ' + rs.data.data.native, 'success');
              } else {
                notify('{{ __("Alert") }}', rs.data.msg, 'error');
              }
            });
        });
      });
    </script>
@endpush
