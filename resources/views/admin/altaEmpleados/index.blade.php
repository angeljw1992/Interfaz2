@extends('layouts.admin')
@section('content') 
    @if(Gate::check('alta_empleado_create') or Auth::user()->type=='member')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary" href="{{ route('admin.alta-empleados.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.altaEmpleado.title_singular') }}
                </a>
                 <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
			<button class="btn btn-outline-success export-waystation-btn btn_exp" role="button" type="button" data-href="{{ route('export-xml') }}">Enviar Waystation</button>

			<button class="btn btn-outline-warning export-security-btn btn_exp" role="button" type="button" data-href="{{ route('export-security') }}">Enviar POS</button>

               @include('csvImport.modal', [
                    'model' => 'AltaEmpleado',
                    'route' => 'admin.alta-empleados.parseCsvImport',
                ])
            </div>

			&nbsp;
			<div class="col-lg-12">
                <!-- Mensaje de procesando -->
                <div class="alert alert-warning processing-message" style="display: none;">
                    Ejecutando proceso de alta... Espera
                </div>
            </div>
						
        </div>
    @endif
	    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.altaEmpleado.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-AltaEmpleado">
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
                                {{ trans('cruds.altaRestaurante.fields.nombre_team') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <input id="search_id_empleado" class="search" type="text" placeholder="{{ trans('global.search') }}">
                            </td>
                            <td>
                                <input id="search_nombre_empleado" class="search" type="text" placeholder="{{ trans('global.search') }}">
                            </td>
                            <td>
                                <select id="search_role" class="search" strict="true">
                                    <option value>{{ trans('global.all') }}</option>
                                    @foreach (App\Models\AltaEmpleado::ROLE_SELECT as $key => $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select id="search_local" class="search">
                                    <option value>{{ trans('global.all') }}</option>
                                    @foreach ($alta_restaurantes as $key => $item)
                                        <option value="{{ $item->nombre_local }}">{{ $item->nombre_local }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($altaEmpleados as $key => $altaEmpleado)
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
                                    {{ $altaEmpleado->nombre_local ?? '' }}
                                </td>
                                <td>
                                    {{ $altaEmpleado->nombre_local ?? '' }}
                                </td>
                                <td>
                                    {{ $altaEmpleado->team_name ?? '' }}
                                </td>
                                <td>
                                    @if(Gate::check('alta_empleado_show') or Auth::user()->type=='member')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.alta-empleados.show', $altaEmpleado->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @if(Gate::check('alta_empleado_edit') or Auth::user()->type=='member')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.alta-empleados.edit', $altaEmpleado->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @if(Gate::check('alta_empleado_delete') or Auth::user()->type=='member')
                                        <form action="{{ route('admin.alta-empleados.destroy', $altaEmpleado->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-primary"
                                                value="{{ trans('global.delete') }}">
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
        $(document).ready(function() {
            // Escuchar el clic del botón "Enviar Waystation"
            $('.export-waystation-btn').click(function() {
                // Mostrar el mensaje de "procesando"
                $('.processing-message').show();

                // Realizar la solicitud AJAX al controlador para exportar Waystation
                $.ajax({
                    type: 'GET',
                    url: '{{ route("export-xml") }}',
                    success: function(data) {
                        // Ocultar el mensaje de "procesando" cuando la solicitud se complete
                        $('.processing-message').hide();
                        // Aquí podrías mostrar cualquier otro mensaje de éxito si lo deseas
                        // Por ejemplo, mostrar el mensaje devuelto por el controlador:
                        // alert(data.success);
                    },
                    error: function() {
                        // Ocultar el mensaje de "procesando" si ocurre un error
                        $('.processing-message').hide();
                        alert('Ha ocurrido un error durante la exportación.');
                    }
                });
            });
        });
    </script>
	
<script>
// Exportar Employees
$(document).ready(function() {
    // Escuchar el clic del botón "Enviar POS"
    $('.export-security-btn').click(function() {
        // Mostrar el mensaje de "procesando"
        $('.processing-message').show();

        // Realizar la solicitud AJAX al controlador para exportar a .txt
        $.ajax({
            type: 'GET',
            url: '{{ route("export-security") }}', // Asegúrate de que esta ruta genere un .txt
            success: function(data) {
                // Ocultar el mensaje de "procesando" cuando la solicitud se comple
                $('.processing-message').hide();
                // Manejar la respuesta, por ejemplo, descargar el archivo .txt
                // ...
            },
            error: function() {
                // Ocultar el mensaje de "procesando" si ocurre un error
                $('.processing-message').hide();
                alert('Ha ocurrido un error durante la exportación.');
            }
        });
    });
});
</script>
	
	
    <script>
	// Exportar Security.Data
        $(function() {


            $(".btn_exp").click(function(){
                var id_empleado = $("#search_id_empleado").val();
                var local = $("#search_local").val();
                var nommbre_empleado = $("#search_nombre_empleado").val();
                var role = $("#search_role").val();

                var selected = "";

                var sel = document.querySelectorAll("tr.selected");

                for (const i in sel) {
                    if (Object.hasOwnProperty.call(sel, i)) {
                        const tr = sel[i];
                        selected += $(tr).attr("data-entry-id")+",";
                    }
                }




                var url = new URL(this.getAttribute("data-href"));

                url.search = "?id_empleado="+id_empleado+"&"+"local="+local+"&"+"nombre_empleado="+nommbre_empleado+"&"+"role="+local+"&selected="+selected;
                console.log(url);

                location.href = url.href;


            });
























            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('alta_empleado_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.alta-empleados.massDestroy') }}",
                    className: 'btn-primary',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-AltaEmpleado:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function() {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function(e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function(colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        });
    </script>
	
	
@endsection
