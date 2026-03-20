<?php

namespace App\Models;

use CodeIgniter\Model;

class Servicio_Orden_Servicio_Model extends Base_Empresa_Model
{
    protected $table            = 'servicio_orden_servicio';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'id_orden_servicio',
        'id_servicio',
        'precio',
        'cantidad',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps    = true;

    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $returnType       = 'array';

    protected $validationRules = [
        'id_orden_servicio' => 'required|integer',
        'id_servicio'       => 'required|integer',
        'precio'            => 'required|decimal',
        'cantidad'          => 'required|integer'
    ];

    protected $skipValidation     = false;

    public function copiarServiciosDesdePresupuesto(int $idPresupuesto, int $idOrdenServicio): bool
    {
         $db = $this->db;

        $sql = "
            INSERT INTO servicio_orden_servicio (id_orden_servicio, id_servicio, precio, cantidad, created_at, updated_at)
            SELECT
                ?,
                sp.id_servicio,
                sp.precio,
                sp.cantidad,
                NOW(),
                NOW()
            FROM servicio_presupuesto sp
            WHERE sp.id_presupuesto = ?               
        ";

        return $db->query($sql, [$idOrdenServicio, $idPresupuesto]);
    }

    public function GetDetalleServicioOrden(int $idOrdenServicio): array
    {
         $db = $this->db;
        $sql = "
            SELECT
                so.id_servicio,
                so.precio,
                so.cantidad,
                s.nombre
            FROM servicio_orden_servicio so
            LEFT JOIN servicio s ON s.id = so.id_servicio
            WHERE so.id_orden_servicio = ?        
        ";
        return $db->query($sql, [$idOrdenServicio])->getResultArray();
    }

    public function GetTotalServicios(int $idOrdenServicio): array
    {
         $db = $this->db;
        $sql = "
            SELECT
                SUM(so.precio * so.cantidad) as total
            FROM servicio_orden_servicio so
            WHERE so.id_orden_servicio = ?          
        ";
        return $db->query($sql, [$idOrdenServicio])->getRowArray();
    }

}
