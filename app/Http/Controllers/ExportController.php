<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flores\PosFileGen;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function exportToTxt(Request $request)
    {
        // Mostrar el loader
        $this->showLoader();

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

            //separating employes by restaurant
            $restaurants[$value->local_id]->empleados[] = $value;
        }


        foreach ($restaurants as $key => $restaurant) {

            $restaurant->ips = explode(",", $restaurant->ips);

            $file = new PosFileGen(["id", "name", "level", "expire", "password", "signature"]);

            $line = $file->newLine();

            foreach ($restaurant->empleados ?? [] as $row) {
                $last_three_digits = substr($row->id_empleado, -3); // Obtener los últimos 3 dígitos del código de empleado

                $line = $file->newLine();
                $line->{"id"} = $row->id_empleado;
                $line->{"name"} = $row->nombre_empleado;
                $line->{"level"} = $row->role;
                $line->{"expire"} = "99991231"; //strtotime("+1 year");
                $line->{"password"} = $file->generatePassword($row->id_empleado, $last_three_digits);

                $arr = json_decode(json_encode($line), true);
                unset($arr["signature"]);
                $implode = implode(",", $arr) . ",";
                $line->{"signature"} = $file->hash($implode);

                $file->add($line);
            }

            $name = "security.data";
            $localPath = storage_path("app/public/" . $name);
            $file->save($name, storage_path("app/public/"));
 
            if (count($restaurant->ips) > 0) {

                $primary_ip = $restaurant->ips[array_key_first($restaurant->ips)];
                unset($restaurant->ips[array_key_first($restaurant->ips)]);
                copy(trim($localPath), "\\\\" . $primary_ip . "\\d$\\" . $name);

                foreach ($restaurant->ips as $destinationPath) {
					copy(trim($localPath), "\\\\" . $destinationPath . "\\e$\\" . $name);
                }

            }
        } #foreach restaurants

        // Ocultar el loader
        $this->hideLoader();

        // Almacenar el mensaje de éxito en la sesión
        $message = 'Se han actualizado los empleados en las POS de manera correcta';
        session()->flash('success', $message);

        // Redirigir a una vista o ruta (por ejemplo, a la página anterior)
        return redirect()->back();
    }

    // Función para mostrar el loader
    private function showLoader()
    {
        session()->put('export_in_progress', true);
    }

    // Función para ocultar el loader
    private function hideLoader()
    {
        session()->forget('export_in_progress');
    }
}
