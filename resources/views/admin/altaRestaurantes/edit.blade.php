@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.altaRestaurante.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.alta-restaurantes.update", [$altaRestaurante->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @if (Auth::user()->type == 'admin')
                
            <div class="form-group mb-2">
                <label class="form-label required" for="roles">{{ 'TEAM' }}</label> 

                <select class="form-control select2" name="team_id" id="team_id" required>
                        
                    @foreach ($teams??[] as $item)
                    <option {{ $item->id==$altaRestaurante->team_id?"selected":"" }} value="{{ $item->id }}">{{ $item->name }}</option>
                        
                    @endforeach
                </select>

                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif 
            </div>
@endif

            <div class="form-group mb-2">
                <label class="form-label required" for="id_local">{{ trans('cruds.altaRestaurante.fields.id_local') }}</label>
                <input class="form-control {{ $errors->has('id_local') ? 'is-invalid' : '' }}" type="text" name="id_local" id="id_local" value="{{ old('id_local', $altaRestaurante->id_local) }}" required>
                @if($errors->has('id_local'))
                    <span class="text-danger">{{ $errors->first('id_local') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaRestaurante.fields.id_local_helper') }}</span>
            </div>
            <div class="form-group mb-2">
                <label class="form-label required" for="nombre_local">{{ trans('cruds.altaRestaurante.fields.nombre_local') }}</label>
                <input class="form-control {{ $errors->has('nombre_local') ? 'is-invalid' : '' }}" type="text" name="nombre_local" id="nombre_local" value="{{ old('nombre_local', $altaRestaurante->nombre_local) }}" required>
                @if($errors->has('nombre_local'))
                    <span class="text-danger">{{ $errors->first('nombre_local') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.altaRestaurante.fields.nombre_local_helper') }}</span>
            </div>
            <div class="form-group mb-2">
				<label class="form-label required d-block" for="nombre_local">{{ "IP's" }}</label>
				<select style="width:100%" name="ip[]" class="form-control" multiple id="ips">
					@foreach (explode(',', $altaRestaurante->ips) as $ip)
						<option value="{{ $ip }}" selected>{{ $ip }}</option>
					@endforeach
				</select>
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

@section('scripts')
    <script>
        $(function() {
            $("#ips").select2({
                dropdownParent: null,
                tags: true
            });
        });
    </script>
@endsection
