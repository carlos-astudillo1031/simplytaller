<?php

namespace App\Models;

use CodeIgniter\Model;

class Vehiculo_Model extends Base_Empresa_Model
{
    protected $table            = 'vehiculo';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['patente', 'id_marca', 'id_modelo', 'id_cliente', 'color', 'anio' ,'chasis', 'token' ,'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function GetVehiculosPorCliente($id_cliente){
         $db = $this->db;
        $builder = $db->table('vehiculo vh');

        $builder->select('vh.*, m.nombre as marca, mo.nombre as modelo');
        $builder->join('marca m', 'm.id = vh.id_marca');
        $builder->join('modelo mo', 'mo.id = vh.id_modelo');
        $builder->where('vh.id_cliente', $id_cliente);
        $builder->where('vh.deleted_at', null);

        $query = $builder->get();
        $vehiculos = $query->getResult();
        return $vehiculos;
    }

     public function GetVehiculosPorPatente($patente){
         $db = $this->db;
        $builder = $db->table('vehiculo vh');

        $builder->select('vh.*, m.nombre as marca, mo.nombre as modelo');
        $builder->join('marca m', 'm.id = vh.id_marca');
        $builder->join('modelo mo', 'mo.id = vh.id_modelo');
        $builder->where("REPLACE(vh.patente, '-', '')", $patente);
        $builder->where('vh.deleted_at', null);

        $query = $builder->get();
        $vehiculos = $query->getRow();
        return $vehiculos;
    }


    public function getVehiculoConClientePorToken(string $token)
    {
        return $this->select('vehiculo.*, cliente.nombre_cliente AS cliente_nombre, cliente.email_cliente AS cliente_email, cliente.telefono_cliente AS cliente_telefono, marca.nombre as marca, modelo.nombre as modelo')
                    ->join('cliente', 'cliente.id_cliente = vehiculo.id_cliente')
                    ->join('marca', 'marca.id = vehiculo.id_marca')
                    ->join('modelo', 'modelo.id = vehiculo.id_modelo')
                    ->where('vehiculo.token', $token)
                    ->first();
    }

    public function getVehiculoConClientePorId(int $id)
    {
        return $this->select('vehiculo.*, cliente.nombre_cliente AS cliente_nombre, cliente.email_cliente AS cliente_email, cliente.telefono_cliente AS cliente_telefono, marca.nombre as marca, modelo.nombre as modelo')
                    ->join('cliente', 'cliente.id_cliente = vehiculo.id_cliente')
                    ->join('marca', 'marca.id = vehiculo.id_marca')
                    ->join('modelo', 'modelo.id = vehiculo.id_modelo')
                    ->where('vehiculo.id', $id)
                    ->first();
    }

}
