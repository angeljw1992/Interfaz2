<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAltaEmpleadoRequest;
use App\Http\Requests\StoreAltaEmpleadoRequest;
use App\Http\Requests\UpdateAltaEmpleadoRequest;
use App\Models\AltaEmpleado;
use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AltaEmpleadosController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        if (Auth::user()->type == "admin") {
            abort_if(Gate::denies('alta_empleado_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $query = DB::table("alta_restaurantes")
            ->leftJoin('teams', 'teams.id', 'alta_restaurantes.team_id')
            ->select("alta_restaurantes.*", 'teams.name as team_name');

        if (Auth::user()->type == "member") {
            $query->where("teams.id", Auth::user()->team_id);
        }

        $alta_restaurantes = $query->get();

        $query = DB::table("alta_empleados")
            ->leftJoin('alta_restaurantes', 'alta_restaurantes.id', 'alta_empleados.local_id')
            ->leftJoin('teams', 'teams.id', 'alta_restaurantes.team_id')
            ->select("alta_empleados.*", 'alta_restaurantes.nombre_local','teams.name as team_name');

        if (Auth::user()->type == "member") {
            $query->where("teams.id", Auth::user()->team_id);
        }
        $altaEmpleados = $query->get();




        return view('admin.altaEmpleados.index', compact('altaEmpleados', 'alta_restaurantes'));
    }

    public function create()
    {
        if (Auth::user()->type == "admin") {
            abort_if(Gate::denies('alta_empleado_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $locals = AltaRestaurante::pluck('nombre_local', 'id')->prepend(trans('global.pleaseSelect'), '');
        }else{
            
            $locals = DB::table("alta_restaurantes")->where("alta_restaurantes.team_id", Auth::user()->team_id)->pluck('nombre_local', 'id')->prepend(trans('global.pleaseSelect'), '');
 
        }

        return view('admin.altaEmpleados.create', compact('locals'));
    }

    public function store(StoreAltaEmpleadoRequest $request)
    {
        // Validar los datos recibidos, incluyendo el nombre del empleado y el ID Empleado único
        $request->validate([
            'nombre_empleado' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
            'id_empleado' => [
                'required', 'string',
                Rule::unique('alta_empleados')->where(function ($query) use ($request) {
                    return $query->where('local_id', $request->input('local_id'));
                })
            ],
            // Otros campos requeridos y reglas de validación que puedas tener
        ], [
            'nombre_empleado.regex' => 'El nombre del empleado contiene caracteres inválidos.',
            'id_empleado.unique' => 'El ID Empleado ya ha sido utilizado en este restaurante. Debe ser único.',
            // Mensajes de error personalizados para otras reglas de validación si es necesario
        ]);

        // Convertir el nombre del empleado a mayúsculas
        $request->merge(['nombre_empleado' => strtoupper($request->nombre_empleado)]);

        // Crear el empleado solo si los datos son válidos
        $altaEmpleado = AltaEmpleado::create($request->all());

        return redirect()->route('admin.alta-empleados.index');
    }


    public function edit(AltaEmpleado $altaEmpleado)
    {
        if (Auth::user()->type == "admin") {
            abort_if(Gate::denies('alta_empleado_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $locals = AltaRestaurante::pluck('nombre_local', 'id')->prepend(trans('global.pleaseSelect'), '');
    }else{
        
        $locals = DB::table("alta_restaurantes")->where("alta_restaurantes.team_id", Auth::user()->team_id)->pluck('nombre_local', 'id')->prepend(trans('global.pleaseSelect'), '');

    }

        $altaEmpleado->load('local');

        return view('admin.altaEmpleados.edit', compact('altaEmpleado', 'locals'));
    }

    public function update(UpdateAltaEmpleadoRequest $request, AltaEmpleado $altaEmpleado)
    {
        $altaEmpleado->update($request->all());

        return redirect()->route('admin.alta-empleados.index');
    }

    public function show(AltaEmpleado $altaEmpleado)
    {
        if (Auth::user()->type == "admin") {
            abort_if(Gate::denies('alta_empleado_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $altaEmpleado->load('local');

        return view('admin.altaEmpleados.show', compact('altaEmpleado'));
    }

    public function destroy(AltaEmpleado $altaEmpleado)
    {
        if (Auth::user()->type == "admin") {
            abort_if(Gate::denies('alta_empleado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $altaEmpleado->forceDelete();

        return back();
    }

    public function massDestroy(MassDestroyAltaEmpleadoRequest $request)
    {
        $altaEmpleados = AltaEmpleado::find(request('ids'));

        foreach ($altaEmpleados as $altaEmpleado) {
            $altaEmpleado->forceDelete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
