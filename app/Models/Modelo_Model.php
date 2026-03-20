<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelo_Model extends Base_Empresa_Model
{
    protected $table            = 'modelo';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_marca', 'nombre', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}
