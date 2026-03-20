<?php

namespace App\Models;

use CodeIgniter\Model;

class Servicio_Presupuesto_Model extends Base_Empresa_Model
{
    protected $table = 'servicio_presupuesto';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_presupuesto',
        'id_servicio',
        'precio',
        'cantidad',
        'created_at',
        'updated_at'      
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    /* protected $deletedField  = 'deleted_at';
 */
    protected $useSoftDeletes = false;

    // Validaciones básicas (opcional)
    protected $validationRules = [
        'id_presupuesto' => 'required|integer',
        'id_servicio'    => 'required|integer',
        'precio'         => 'required|decimal'
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function obtenerServiciosConNombre($id_presupuesto)
    {
        return $this->select('servicio_presupuesto.*, servicio.nombre as nombre_servicio')
                    ->join('servicio', 'servicio.id = servicio_presupuesto.id_servicio')
                    ->where('id_presupuesto', $id_presupuesto)
                    ->findAll();
    }

    public function copiarServiciosDesdeOrdenServicio(int $idOrdenServicio, int $idPresupuesto): bool
    {
        
        $db = $this->db;


        $sql = "
            INSERT INTO servicio_presupuesto (id_presupuesto, id_servicio, precio, cantidad, created_at, updated_at)
            SELECT
                ?,
                sos.id_servicio,
                sos.precio,
                sos.cantidad,
                NOW(),
                NOW()
            FROM servicio_orden_servicio sos
            WHERE sos.id_orden_servicio = ?
        ";

        return $db->query($sql, [$idPresupuesto, $idOrdenServicio]);
    }

}
