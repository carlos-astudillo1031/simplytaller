<?php

namespace App\Models;

use CodeIgniter\Model;

class Tipo_Usuario_Model extends Base_Empresa_Model
{
    protected $table            = 'tipo_usuario';
    protected $primaryKey       = 'id';

    protected $allowedFields    = ['nombre'];

    protected $useTimestamps    = false; // Cambia a true si usas created_at / updated_at
}
