<?php

namespace App\Models;

use CodeIgniter\Model;

class Ubicacion_Model extends Base_Empresa_Model
{
    protected $table            = 'ubicaciones';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
}