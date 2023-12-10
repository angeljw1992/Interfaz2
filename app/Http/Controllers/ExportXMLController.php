<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportXmlController extends Controller
{
    public function exportTableToXml(Request $request)
    {

        // Aquí se conecta a la DB y busca los valores
        $query = DB::table('alta_empleados')
            ->select('alta_empleados.nombre_empleado', 'alta_empleados.id_empleado', 'alta_empleados.role', 'alta_empleados.local_id')
            ->leftJoin("alta_restaurantes", "alta_restaurantes.id", "alta_empleados.local_id")
            ->leftJoin("roles", "roles.id", "alta_empleados.role");

        $selected = explode(",", $request->get("selected"));
        $selected = array_filter($selected);

        if (count($selected) > 0) {
            $query->whereIn("alta_empleados.id", $selected);
        } else {

            if (!empty($request->get("id_empleado")) or is_numeric($request->get("id_empleado"))) {
                $search = $request->get("id_empleado");
                $query->where("alta_empleados.id", "like", "%" . $search . "%");
            }

            if (!empty($request->get("role")) or is_numeric($request->get("role"))) {
                $search = $request->get("role");
                $query->where("roles.title", "like", "%" . $search . "%");
            }

            if (!empty($request->get("nombre_empleado")) or is_numeric($request->get("nombre_empleado"))) {
                $search = $request->get("nombre_empleado");
                $query->where("alta_empleados.nombre_empleado", "like", "%" . $search . "%");
            }

            if (!empty($request->get("local")) or is_numeric($request->get("local"))) {
                $search = $request->get("local");
                $query->where("alta_restaurantes.nombre_local", "like", "%" . $search . "%");
            }
        }


        $data = $query->get();

        $restaurants = [];

        foreach ($data as $key => $value) {
            if (!isset($restaurants[$value->local_id])) {
                $restaurants[$value->local_id] = DB::table("alta_restaurantes")->where("id", $value->local_id)->first();

                if (empty($restaurants[$value->local_id]->id)) {
                    continue;
                }
                $restaurants[$value->local_id]->empleados = [];
            }

            if (empty($restaurants[$value->local_id]->id)) {
                continue;
            }

            $restaurants[$value->local_id]->empleados[] = $value;
        }

        foreach ($restaurants as $i => $restaurant) {
            $restaurant->ips = explode(",", $restaurant->ips);

            // Aquí se crea la cabecera del XML
            $xml = new SimpleXMLElement('<ns0:empleados xmlns:ns0="http://www.arcosdorados.com/integrationservices/cdm"/>');

            // Aquí se le da formato al XML
            foreach ($restaurant->empleados as $employee) {
                $xmlElement = $xml->addChild("ns0:empleado");
                $xmlElement->addChild("ns0:employeeID", $employee->id_empleado);
                $xmlElement->addChild("ns0:employeeName", $employee->nombre_empleado);
                $xmlElement->addChild("ns0:securityLevel", $employee->role);
            }

            // Obtener la salida XML
            $output = $xml->asXML();

            // Eliminar la primera línea de la salida
            $output = substr($output, strpos($output, "\n") + 1);

            // Agregar saltos de línea para mejorar la legibilidad
            $output = str_replace("><", ">\n<", $output);

            // Ruta al directorio temporal
            $tempDirectory = storage_path("app/tmp");

            // Crear el directorio si no existe
            if (!file_exists($tempDirectory)) {
                mkdir($tempDirectory, 0777, true);
            }

            // Guardar el archivo en el directorio temporal
            $name = 'employees.xml';

            $tempPath = $tempDirectory . "/" . $name;
            file_put_contents($tempPath, $output);

            // Guardar el archivo en la ubicación remota directamente 

            if (count($restaurant->ips) > 0) {

                $primary_ip = $restaurant->ips[array_key_first($restaurant->ips)];
                unset($restaurant->ips[array_key_first($restaurant->ips)]);
                copy($tempPath, "\\\\" . $primary_ip . "\\C$\\bioservice\\" . $name);

                foreach ($restaurant->ips as $destinationPath) {
                    //  copy($tempPath, "\\\\" . $destinationPath . "\\Desktop\\" . $name);
                }
            }
        }

        // Mensaje de éxito
        $message = 'Se han actualizado los empleados en la Waystation de manera correcta';

        // Almacenar el mensaje en la sesión
        $request->session()->flash('success', $message);

        // Redirigir a una vista o ruta (por ejemplo, a la página anterior)
        return redirect()->back();
    }
}
