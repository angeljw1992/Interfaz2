<?php

namespace App\Http\Requests;

use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAltaRestauranteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('alta_restaurante_edit');
    }

    public function rules()
    {
        return [
            'id_local' => [
                'string',
                'min:1',
                'max:3',
                'required',
                'unique:alta_restaurantes,id_local,' . request()->route('alta_restaurante')->id,
            ],
            'nombre_local' => [
                'string',
                'required',
            ],
        ];
    }
}
