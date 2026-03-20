<?php

namespace App\Models;

use CodeIgniter\Model;

class Servicio_Model extends Base_Empresa_Model
{
    protected $table            = 'servicio';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array'; // O 'object' si prefieres objetos
    protected $useSoftDeletes   = true;

    protected $allowedFields    = ['nombre', 'precio'];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    

   

   
}
