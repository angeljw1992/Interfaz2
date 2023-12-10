<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTeamRequest;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Permission;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('team_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teams = Team::all();

        return view('admin.team.index',compact("teams"));
    }

    public function create()
    {
       abort_if(Gate::denies('team_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.team.create');
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create($request->all());

        return redirect()->route('admin.team.index');
    }

    public function edit(Team $team)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
 

        return view('admin.team.edit', compact('team'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $team->update($request->all());
        return redirect()->route('admin.team.index');
    }

    public function show(Team $team)
    {
        abort_if(Gate::denies('team_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.team.show', compact('team'));
    }

    public function destroy(Team $team)
    {
        abort_if(Gate::denies('team_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $team->delete();

        return back();
    }

    public function massDestroy(MassDestroyTeamRequest $request)
    {
        $team = Team::find(request('ids'));

        foreach ($team as $team) {
            $team->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
