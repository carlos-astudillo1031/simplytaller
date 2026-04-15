<?php

namespace App\Models;

use CodeIgniter\Model;

class Detalle_Venta_Model extends Base_Empresa_Model
{
    protected $table            = 'detalle_venta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'id_venta',
        'id_repuesto',
        'cantidad',
        'precio_unitario'
    ];

    // Configuración de fechas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    
}