<?php

namespace App\Models;

use CodeIgniter\Model;

class Repuesto_Orden_Servicio_Model extends Base_Empresa_Model
{
    protected $table            = 'repuesto_orden_servicio';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'id_orden_servicio',
        'id_repuesto',
        'precio',
        'cantidad',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps    = true; // Usa created_at y updated_at

    protected $returnType       = 'array'; // Puedes cambiar a 'object' si prefieres

    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    

    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function copiarDesdePresupuesto(int $idPresupuesto, int $idOrdenServicio): bool
    {
         $db = $this->db;

        $sql = "
            INSERT INTO repuesto_orden_servicio (id_orden_servicio, id_repuesto, precio, cantidad, created_at, updated_at)
            SELECT
                ?,
                rp.id_repuesto,
                rp.precio_unitario,
                rp.cantidad,
                NOW(),
                NOW()
            FROM repuesto_presupuesto rp
            WHERE rp.id_presupuesto = ?          
        ";

        return $db->query($sql, [$idOrdenServicio, $idPresupuesto]);
    }

    public function GetDetalleRepuestoOrdenServicio(int $idOrdenServicio): array
    {
         $db = $this->db;
        $sql = "
            SELECT
                rp.id_repuesto,
                rp.precio,
                r.codigo,
                rp.cantidad,
                r.nombre
            FROM repuesto_orden_servicio rp
            LEFT JOIN repuesto r ON rp.id_repuesto = r.id    
            WHERE rp.id_orden_servicio = ?          
        ";
        return $db->query($sql, [$idOrdenServicio])->getResultArray();
    }

    public function GetTotalRepuestos(int $idOrdenServicio): array
    {
         $db = $this->db;
        $sql = "
            SELECT
                SUM(rp.precio * cantidad) as total
            FROM repuesto_orden_servicio rp
            WHERE rp.id_orden_servicio = ?          
        ";
        return $db->query($sql, [$idOrdenServicio])->getRowArray();
    }

}
