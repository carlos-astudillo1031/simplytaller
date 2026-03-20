<?php

namespace App\Models;

use CodeIgniter\Model;

class Empresa_Model extends Model
{
    protected $table      = 'empresas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // no tienes deleted_at
    protected $allowedFields    = [
        'codigo',
        'nombre',
        'db_nombre',
        'activo',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtener todas las empresas activas
     */
    public function getEmpresasActivas()
    {
        return $this->where('activo', 1)
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener empresa por código o nombre
     */
    public function getEmpresaPorCodigo(string $codigo)
    {
        return $this->where('codigo', $codigo)
                    ->where('activo', 1)
                    ->first();
    }
}
