<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAltaRestauranteRequest;
use App\Http\Requests\StoreAltaRestauranteRequest;
use App\Http\Requests\UpdateAltaRestauranteRequest;
use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Team;

class AltaRestauranteController extends Controller
{
    public function index()
    {
        if(Auth::user()->type == "admin"){
        abort_if(Gate::denies('alta_restaurante_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $query = DB::table("alta_restaurantes")
        ->leftJoin('teams','teams.id','alta_restaurantes.team_id') 
        ->select("alta_restaurantes.*",'teams.name as team_name');

        if(Auth::user()->type == "member"){
            $query->where("teams.id",Auth::user()->team_id);
        }


        $altaRestaurantes = $query->get();

        return view('admin.altaRestaurantes.index', compact('altaRestaurantes'));
    }

    public function create()
    {
        if(Auth::user()->type == "admin"){
        abort_if(Gate::denies('alta_restaurante_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $teams = Team::all();
        return view('admin.altaRestaurantes.create',compact('teams'));
    }

    public function store(StoreAltaRestauranteRequest $request)
    {
        if (is_countable($request->get("ip"))) {
            $request->request->add([
                'ips' => implode(",", $request->get("ip"))
            ]);
        }

        if(Auth::user()->tpoe =="member"){
            $request->merge(["team_id"=>Auth::user()->team_id]);
        }

        $altaRestaurante = AltaRestaurante::create($request->all());

        return redirect()->route('admin.alta-restaurantes.index');
    }

    public function edit(AltaRestaurante $altaRestaurante)
    {
        if(Auth::user()->type == "admin"){
        abort_if(Gate::denies('alta_restaurante_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
    $teams = Team::all(); 
        return view('admin.altaRestaurantes.edit', compact('altaRestaurante','teams'));
    }

    public function update(UpdateAltaRestauranteRequest $request, AltaRestaurante $altaRestaurante)
    {
        if (is_countable($request->get("ip"))) {
            $request->request->add([
                'ips' => implode(",", $request->get("ip"))
            ]);
        }else{
            $request->request->add([
                'ips' => ""
            ]);

        }
        if(Auth::user()->tpoe =="member"){
            $request->merge(["team_id"=>Auth::user()->team_id]);
        }

        $altaRestaurante->update($request->all());

        return redirect()->route('admin.alta-restaurantes.index');
    }

    public function show(AltaRestaurante $altaRestaurante)
    {
        if(Auth::user()->type == "admin"){
        abort_if(Gate::denies('alta_restaurante_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $altaRestaurante->load('localAltaEmpleados');

        return view('admin.altaRestaurantes.show', compact('altaRestaurante'));
    }

    public function destroy(AltaRestaurante $altaRestaurante)
    {
        if(Auth::user()->type == "admin"){
        abort_if(Gate::denies('alta_restaurante_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $altaRestaurante->forceDelete();

        return back();
    }

    public function massDestroy(MassDestroyAltaRestauranteRequest $request)
    {
        $altaRestaurantes = AltaRestaurante::find(request('ids'));

        foreach ($altaRestaurantes as $altaRestaurante) {
            $altaRestaurante->forceDelete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
