<?php

namespace App\Models;

use CodeIgniter\Model;

//Relacion entre  inventario_item y la orden de servicio
class Os_Inventario_Model extends Base_Empresa_Model
{
    protected $table            = 'os_inventario';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'id_orden_servicio',
        'id_item',
        'presente',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

       
}
