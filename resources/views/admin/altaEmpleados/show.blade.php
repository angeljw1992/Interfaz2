@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Mostrar Detalle de Empleado
    </div>

    <div class="card-body">
        <div class="form-group mb-2">
            <div class="form-group mb-2">
                <a class="btn btn-danger" href="{{ route('admin.alta-empleados.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.altaEmpleado.fields.id_empleado') }}
                        </th>
                        <td>
                            {{ $altaEmpleado->id_empleado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.altaEmpleado.fields.nombre_empleado') }}
                        </th>
                        <td>
                            {{ $altaEmpleado->nombre_empleado }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.altaEmpleado.fields.role') }}
                        </th>
                        <td>
                            {{ App\Models\AltaEmpleado::ROLE_SELECT[$altaEmpleado->role] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.altaEmpleado.fields.local') }}
                        </th>
                        <td>
                            {{ $altaEmpleado->local->nombre_local ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group mb-2">
			<br>
                <a class="btn btn-danger" href="{{ route('admin.alta-empleados.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection