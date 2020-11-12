<?php


namespace App\Http\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class EmpleadoModel extends Model{

    /**
     * Funcion para eliminado logico del empleado
     * @param $id
     * @return int
     * @author Omar
     */
    public function eliminarEmpleado($id){
        return DB::table('empleados')
            ->where('id','=', $id)
            ->update(['eliminado' => 1]);
    }

    /**
     * @param $codigo
     * @return int
     */
    public function codigoUnico($codigo){
        return DB::table('empleados')
            ->where('codigo','=', $codigo)
            ->count();
    }
}
