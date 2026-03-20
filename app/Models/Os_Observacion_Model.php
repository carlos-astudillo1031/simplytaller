<?php

namespace App\Models;

use CodeIgniter\Model;

//Relacion entre  observacion_item y la orden de servicio
class Os_Observacion_Model extends Base_Empresa_Model
{
    protected $table            = 'os_observacion';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'id_orden_servicio',
        'id_item',
        'valor'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

       
}
