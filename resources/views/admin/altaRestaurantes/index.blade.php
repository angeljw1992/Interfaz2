@extends('layouts.admin')
@section('content') 
@if(Gate::check('alta_restaurante_create') or Auth::user()->type=='member')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('admin.alta-restaurantes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.altaRestaurante.title_singular') }}
            </a>
        </div>
    </div>
@endif
<div class="card">
    <div class="card-header">
        {{ trans('cruds.altaRestaurante.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-AltaRestaurante">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.altaRestaurante.fields.id_local') }}
                        </th>
                        <th>
                            {{ trans('cruds.altaRestaurante.fields.nombre_local') }}
                        </th>
                        <th>
                            {{ trans('cruds.altaRestaurante.fields.nombre_team') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($altaRestaurantes as $key => $altaRestaurante)
                        <tr data-entry-id="{{ $altaRestaurante->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $altaRestaurante->id_local ?? '' }}
                            </td>
                            <td>
                                {{ $altaRestaurante->nombre_local ?? '' }}
                            </td>
                            <td>
                                {{ $altaRestaurante->team_name ?? '' }}
                            </td>
                            <td>
                                @if(Gate::check('alta_restaurante_show') or Auth::user()->type=='member')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.alta-restaurantes.show', $altaRestaurante->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif

                                @if(Gate::check('alta_restaurante_edit') or Auth::user()->type=='member')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.alta-restaurantes.edit', $altaRestaurante->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif

                                @if(Gate::check('alta_restaurante_delete') or Auth::user()->type=='member')
                                    <form action="{{ route('admin.alta-restaurantes.destroy', $altaRestaurante->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-primary" value="{{ trans('global.delete') }}">
                                    </form>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('alta_restaurante_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.alta-restaurantes.massDestroy') }}",
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
  let table = $('.datatable-AltaRestaurante:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-bs-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection