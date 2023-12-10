@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Mostrar Detalle de team
    </div>

    <div class="card-body">
        <div class="form-group mb-2">
            <div class="form-group mb-2">
                <a class="btn btn-danger" href="{{ route('admin.team.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.id_team') }}
                        </th>
                        <td>
                            {{ $team->id_team }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.nombre_team') }}
                        </th>
                        <td>
                            {{ $team->nombre_team }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.role') }}
                        </th>
                        <td>
                            {{ App\Models\team::ROLE_SELECT[$team->role] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.local') }}
                        </th>
                        <td>
                            {{ $team->local->nombre_local ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group mb-2">
			<br>
                <a class="btn btn-danger" href="{{ route('admin.team.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection