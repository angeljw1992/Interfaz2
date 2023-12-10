<?php

namespace App\Http\Requests;

use App\Models\AltaEmpleado;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAltaEmpleadoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('alta_empleado_edit');
    }

    public function rules()
    {
        return [
            'id_empleado' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'nombre_empleado' => [
                'string',
                'required',
            ],
            'role' => [
                'required',
            ],
        ];
    }
}
