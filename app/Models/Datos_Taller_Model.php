<?php

namespace App\Models;

use CodeIgniter\Model;

class Datos_Taller_Model extends Base_Empresa_Model
{
    protected $table            = 'datos_taller';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'razon_social',
        'nombre_fantasia',
        'giro',
        'celular',
        'direccion',
        'rut',
        'email',
        'url_logo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}
