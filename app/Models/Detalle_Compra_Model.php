<?php

namespace App\Models;

use CodeIgniter\Model;

class Detalle_Compra_Model extends Base_Empresa_Model
{
    protected $table            = 'detalle_compra';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_compra',
        'id_repuesto',
        'cantidad',
        'precio_unitario'
    ];

    protected $useTimestamps = false;

    protected $createdField  = 'created_at';

    // 🔹 Obtener detalle por compra
    public function getDetalleByCompra($id_compra)
    {
        return $this->select('detalle_compra.*, repuestos.nombre as repuesto')
                    ->join('repuestos', 'repuestos.id = detalle_compra.id_repuesto')
                    ->where('id_compra', $id_compra)
                    ->findAll();
    }
}