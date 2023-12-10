@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.altaEmpleado.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.alta-empleados.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-2">
                <label class="form-label required" for="id_empleado">{{ trans('cruds.altaEmpleado.fields.id_empleado') }}</label>
                <input class="form-control {{ $errors->has('id_empleado') ? 'is-invalid' : '' }}" type="number" name="id_empleado" id="id_empleado" value="{{ old('id_empleado', '') }}" step="1" required>
                @if($errors->has('id_empleado'))
                    <span class="text-danger">{{ $errors->first('id_empleado') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaEmpleado.fields.id_empleado_helper') }}</span>
            </div>
            <div class="form-group mb-2">
                <label class="form-label required" for="nombre_empleado">{{ trans('cruds.altaEmpleado.fields.nombre_empleado') }}</label>
                <input class="form-control {{ $errors->has('nombre_empleado') ? 'is-invalid' : '' }}" type="text" name="nombre_empleado" id="nombre_empleado" value="{{ old('nombre_empleado', '') }}" required>
                @if($errors->has('nombre_empleado'))
                    <span class="text-danger">{{ $errors->first('nombre_empleado') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaEmpleado.fields.nombre_empleado_helper') }}</span>
            </div>
            <div class="form-group mb-2">
                <label class="form-label required">{{ trans('cruds.altaEmpleado.fields.role') }}</label>
                <select class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" name="role" id="role" required>
                    <option value disabled {{ old('role', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AltaEmpleado::ROLE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('role', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('role'))
                    <span class="text-danger">{{ $errors->first('role') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaEmpleado.fields.role_helper') }}</span>
            </div>
            <div class="form-group mb-2">
                <label for="local_id">{{ trans('cruds.altaEmpleado.fields.local') }}</label>
                <select class="form-control select2 {{ $errors->has('local') ? 'is-invalid' : '' }}" name="local_id" id="local_id">
                    @foreach($locals as $id => $entry)
                        <option value="{{ $id }}" {{ old('local_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('local'))
                    <span class="text-danger">{{ $errors->first('local') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaEmpleado.fields.local_helper') }}</span>
            </div>
            <div class="form-group mb-2">
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection