<?php

namespace App\Models;

use CodeIgniter\Model;

class Repuesto_Model extends Base_Empresa_Model
{
    protected $table            = 'repuesto';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Puedes usar 'object' si prefieres.
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'codigo',
        'nombre',
        'precio',
        'stock',
        'stock_minimo',
        'id_ubicacion',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getRepuestosConUbicacion()
    {
        return $this->select('repuesto.*, ubicaciones.nombre AS ubicacion_nombre')
                    ->join('ubicaciones', 'ubicaciones.id = repuesto.id_ubicacion', 'left')
                    ->findAll();
    }
}

