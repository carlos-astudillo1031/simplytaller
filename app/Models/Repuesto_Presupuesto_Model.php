<?php

namespace App\Models;

use CodeIgniter\Model;

class Repuesto_Presupuesto_Model extends Base_Empresa_Model
{
    protected $table      = 'repuesto_presupuesto';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;  // Para manejar created_at y updated_at automáticamente
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
/*     protected $deletedField  = 'deleted_at'; // Para soft deletes */

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_presupuesto',
        'id_repuesto',
        'cantidad',
        'precio_unitario'
    ];

    public function obtenerRepuestosConNombre($id_presupuesto)
    {
        return $this->select('repuesto_presupuesto.*, repuesto.nombre as nombre_repuesto, repuesto.codigo')
                    ->join('repuesto', 'repuesto.id = repuesto_presupuesto.id_repuesto')
                    ->where('id_presupuesto', $id_presupuesto)
                    ->findAll();
    }

    public function copiarRepuestosDesdeOrdenServicio(int $idOrdenServicio, int $idPresupuesto): bool
    {
         $db = $this->db;

        $sql = "
            INSERT INTO repuesto_presupuesto (id_presupuesto, id_repuesto, precio_unitario, cantidad, created_at, updated_at)
            SELECT
                ?,
                ros.id_repuesto,
                ros.precio,
                ros.cantidad,
                NOW(),
                NOW()
            FROM repuesto_orden_servicio ros
            WHERE ros.id_orden_servicio = ?            
        ";

        return $db->query($sql, [$idPresupuesto, $idOrdenServicio]);
    }


}
