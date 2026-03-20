<?php

namespace App\Models;

use CodeIgniter\Model;

class Os_Observacion_Item_Model extends Base_Empresa_Model
{
    protected $table            = 'os_observacion_item';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'nombre',
        'icono'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

       
}
