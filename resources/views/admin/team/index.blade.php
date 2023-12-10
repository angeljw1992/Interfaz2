@extends('layouts.admin')
@section('content')
    @can('team_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary" href="{{ route('admin.team.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.team.title_singular') }}
                </a>
            </div>


        </div>
    @endcan
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.team.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-team">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.team.fields.id_team') }}
                            </th>
                            <th>
                                {{ trans('cruds.team.fields.nombre_team') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <input id="search_id_team" class="search" type="text"
                                    placeholder="{{ trans('global.search') }}">
                            </td>
                            <td>
                                <input id="search_nombre_team" class="search" type="text"
                                    placeholder="{{ trans('global.search') }}">
                            </td>
                            <td>
                            </td> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $key => $team)
                            <tr data-entry-id="{{ $team->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $team->id ?? '' }}
                                </td>
                                <td>
                                    {{ $team->name ?? '' }}
                                </td>
                                <td>

                                    @can('team_edit')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.team.edit', $team->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('team_delete')
                                        <form action="{{ route('admin.team.destroy', $team->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-primary"
                                                value="{{ trans('global.delete') }}">
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
                    url: '{{ route('export-xml') }}',
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
                    url: '{{ route('export-security') }}', // Asegúrate de que esta ruta genere un .txt
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


            $(".btn_exp").click(function() {
                var id_team = $("#search_id_team").val();
                var local = $("#search_local").val();
                var nommbre_team = $("#search_nombre_team").val();
                var role = $("#search_role").val();

                var selected = "";

                var sel = document.querySelectorAll("tr.selected");

                for (const i in sel) {
                    if (Object.hasOwnProperty.call(sel, i)) {
                        const tr = sel[i];
                        selected += $(tr).attr("data-entry-id") + ",";
                    }
                }




                var url = new URL(this.getAttribute("data-href"));

                url.search = "?id_team=" + id_team + "&" + "local=" + local + "&" + "nombre_team=" +
                    nommbre_team + "&" + "role=" + local + "&selected=" + selected;
                console.log(url);

                location.href = url.href;


            });
























            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('team_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.team.massDestroy') }}",
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
            let table = $('.datatable-team:not(.ajaxTable)').DataTable({
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
