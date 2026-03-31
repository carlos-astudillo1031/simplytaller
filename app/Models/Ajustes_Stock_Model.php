<?php

namespace App\Models;

use CodeIgniter\Model;

class Ajustes_Stock_Model extends Base_Empresa_Model
{
    protected $table = 'ajustes_stock';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_repuesto',
        'id_usuario',
        'stock_actual',
        'stock_nuevo',
        'motivo'
    ];

    protected $useTimestamps = false; // porque usamos created_at automático en BD

    protected $returnType = 'array';
}