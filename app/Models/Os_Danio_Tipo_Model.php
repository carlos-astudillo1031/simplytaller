<?php

namespace App\Models;

use CodeIgniter\Model;

class Os_Danio_Tipo_Model extends Base_Empresa_Model
{
    protected $table            = 'os_danio_tipo';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nombre']; // Campos que se pueden insertar/actualizar

    protected $useAutoIncrement = true;
    
   
}