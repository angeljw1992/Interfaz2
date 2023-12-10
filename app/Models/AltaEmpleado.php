<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AltaEmpleado extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'alta_empleados';

    public const ROLE_SELECT = [
        '0'  => 'Crew',
        '86' => 'Asistente',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id_empleado',
        'nombre_empleado',
        'role',
        'local_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function local()
    {
        return $this->belongsTo(AltaRestaurante::class, 'local_id');
    }
}
