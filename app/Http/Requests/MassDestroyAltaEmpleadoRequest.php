<?php

namespace App\Http\Requests;

use App\Models\AltaEmpleado;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MassDestroyAltaEmpleadoRequest extends FormRequest
{
    public function authorize()
    {
        if(Auth::user()->type=='admin'){
        abort_if(Gate::denies('alta_empleado_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:alta_empleados,id',
        ];
    }
}
