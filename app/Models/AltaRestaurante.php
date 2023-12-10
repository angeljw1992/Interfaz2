<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AltaRestaurante extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'alta_restaurantes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id_local',
        'ips',
        'team_id',
        'nombre_local',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function localAltaEmpleados()
    {
        return $this->hasMany(AltaEmpleado::class, 'local_id', 'id');
    }
}
