<?php

namespace App\Http\Requests;

use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MassDestroyAltaRestauranteRequest extends FormRequest
{
    public function authorize()
    {
        if(Auth::user()->type=='admin'){
        abort_if(Gate::denies('alta_restaurante_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:alta_restaurantes,id',
        ];
    }
}
