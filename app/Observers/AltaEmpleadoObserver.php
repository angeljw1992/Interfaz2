<?php

namespace App\Observers;

use App\Models\AltaEmpleado;
use App\Models\AltaRestaurante;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AltaEmpleadoObserver
{
    /**
     * Handle the AltaEmpleado "creating" event.
     *
     * @param  \App\Models\AltaEmpleado  $altaEmpleado
     * @return void
     */
    public function creating(AltaEmpleado $altaEmpleado)
    {
        // Convertir el nombre del empleado a mayúsculas
        $altaEmpleado->nombre_empleado = strtoupper($altaEmpleado->nombre_empleado);

        // Validar que el nombre del empleado no contenga caracteres específicos
        if (!$this->validateName($altaEmpleado->nombre_empleado)) {
            abort(422, 'El nombre del empleado contiene caracteres inválidos.');
        }
    }

    /**
     * Handle the AltaEmpleado "created" event.
     *
     * @param  \App\Models\AltaEmpleado  $altaEmpleado
     * @return void
     */
    public function created(AltaEmpleado $altaEmpleado)
    {
        $this->sendEmailNotification($altaEmpleado, 'Nuevo alta de empleado automática', 'Se ha creado un nuevo empleado en tu aplicación.');
    }

    /**
     * Handle the AltaEmpleado "deleted" event.
     *
     * @param  \App\Models\AltaEmpleado  $altaEmpleado
     * @return void
     */
    public function deleted(AltaEmpleado $altaEmpleado)
    {
        $this->sendEmailNotification($altaEmpleado, 'Empleado eliminado', 'Se ha eliminado un empleado en tu aplicación.');
    }

    /**
     * Send email notification.
     *
     * @param  \App\Models\AltaEmpleado  $altaEmpleado
     * @param  string  $subject
     * @param  string  $message
     * @return void
     */
    private function sendEmailNotification(AltaEmpleado $altaEmpleado, string $subject, string $message)
    {
        // ... El código del correo electrónico permanece sin cambios ...
    }

    /**
     * Validate name to disallow specific characters.
     *
     * @param  string  $name
     * @return bool
     */
    private function validateName(string $name)
    {
        // La expresión regular para permitir solo letras mayúsculas y minúsculas sin acentos ni "ñ" o "Ñ".
        // Puedes ajustar la expresión regular según tus necesidades.
        return preg_match('/^[a-zA-Z\s]+$/', $name);
    }
}
