<div class="m-3">
    @can('alta_empleado_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary" href="{{ route('admin.alta-empleados.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.altaEmpleado.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.altaEmpleado.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-localAltaEmpleados">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.altaEmpleado.fields.id_empleado') }}
                            </th>
                            <th>
                                {{ trans('cruds.altaEmpleado.fields.nombre_empleado') }}
                            </th>
                            <th>
                                {{ trans('cruds.altaEmpleado.fields.role') }}
                            </th>
                            <th>
                                {{ trans('cruds.altaEmpleado.fields.local') }}
                            </th>
                            <th>
                                {{ trans('cruds.altaRestaurante.fields.nombre_local') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($altaEmpleados as $key => $altaEmpleado)
                            <tr data-entry-id="{{ $altaEmpleado->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $altaEmpleado->id_empleado ?? '' }}
                                </td>
                                <td>
                                    {{ $altaEmpleado->nombre_empleado ?? '' }}
                                </td>
                                <td>
                                    {{ App\Models\AltaEmpleado::ROLE_SELECT[$altaEmpleado->role] ?? '' }}
                                </td>
                                <td>
                                    {{ $altaEmpleado->local->nombre_local ?? '' }}
                                </td>
                                <td>
                                    {{ $altaEmpleado->local->nombre_local ?? '' }}
                                </td>
                                <td>
                                    @can('alta_empleado_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.alta-empleados.show', $altaEmpleado->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('alta_empleado_edit')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.alta-empleados.edit', $altaEmpleado->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('alta_empleado_delete')
                                        <form action="{{ route('admin.alta-empleados.destroy', $altaEmpleado->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-primary" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('alta_empleado_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.alta-empleados.massDestroy') }}",
    className: 'btn-primary',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-localAltaEmpleados:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-bs-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection