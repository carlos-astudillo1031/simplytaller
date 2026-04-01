<?php

namespace App\Models;

use CodeIgniter\Model;

class Proveedor_Model extends Base_Empresa_Model
{
    protected $table            = 'proveedores';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'rut',
        'nombre',
        'telefono',
        'email'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 🔍 Obtener todos ordenados
    public function getProveedores()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    // 🔍 Obtener uno
    public function getProveedor($id)
    {
        return $this->find($id);
    }

    // 🔍 Buscar por RUT (útil para validar duplicados)
    public function getByRut($rut)
    {
        return $this->where('rut', $rut)->first();
    }

    // 🔍 Búsqueda tipo autocomplete (Select2)
    public function buscar($term)
    {
        return $this->like('nombre', $term)
                    ->orLike('rut', $term)
                    ->orderBy('nombre', 'ASC')
                    ->findAll(20);
    }
}