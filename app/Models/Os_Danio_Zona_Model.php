<?php

namespace App\Models;

use CodeIgniter\Model;

class Os_Danio_Zona_Model extends Base_Empresa_Model
{
    protected $table            = 'os_danio_zona';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Puedes cambiar a 'object' si prefieres objetos
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre']; // Campos que se pueden insertar/actualizar

  
}