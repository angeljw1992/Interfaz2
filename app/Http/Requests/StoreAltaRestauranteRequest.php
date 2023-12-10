<?php

namespace App\Http\Requests;

use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreAltaRestauranteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('alta_restaurante_create') or (Auth::user()->type == "member");
    }

    public function rules()
    {
        return [
            'id_local' => [
                'string',
                'min:1',
                'max:3',
                'required',
                'unique:alta_restaurantes',
            ],
            'nombre_local' => [
                'string',
                'required',
            ],
        ];
    }
}
