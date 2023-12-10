@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.altaRestaurante.title') }}
    </div>

    <div class="card-body">
        <div class="form-group mb-2">
            <div class="form-group mb-2">
                <a class="btn btn-danger" href="{{ route('admin.alta-restaurantes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.altaRestaurante.fields.id_local') }}
                        </th>
                        <td>
                            {{ $altaRestaurante->id_local }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.altaRestaurante.fields.nombre_local') }}
                        </th>
                        <td>
                            {{ $altaRestaurante->nombre_local }}
                        </td>
                    </tr>
					@php
						$ips = explode(',', $altaRestaurante->ips);
						$firstIp = array_shift($ips); // Obtiene la primera IP y la remueve del array
					@endphp

					@if($firstIp)
						<tr>
							<th>
								IP Waystation
							</th>
							<td>
								{{ $firstIp }}
							</td>
						</tr>
					@endif

					@if(count($ips) > 0)
						<tr>
							<th>
								IP POS
							</th>
							<td>
								@foreach($ips as $ip)
									<div>{{ $ip }}</div>
								@endforeach
							</td>
						</tr>
					@endif

                </tbody>
            </table>
            <div class="form-group mb-2">
                <a class="btn btn-danger" href="{{ route('admin.alta-restaurantes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Datos Relacionados
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#local_alta_empleados" role="tab" data-bs-toggle="tab">
                Ver Empleados de Este Local
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="local_alta_empleados">
            @includeIf('admin.altaRestaurantes.relationships.localAltaEmpleados', ['altaEmpleados' => $altaRestaurante->localAltaEmpleados])
        </div>
    </div>
</div>

@endsection