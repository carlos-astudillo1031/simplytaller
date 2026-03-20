<?php

namespace App\Models;

use CodeIgniter\Model;

class Permisos_Especiales_Model extends Base_Empresa_Model
{
    protected $table            = 'permisos_especiales';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nombre',
        'activo'
    ];

    protected $useTimestamps    = false;

    public function obtenerPermisosEspecialesConEstadoPorUsuario($idUsuario)
    {

        $sql = "
            SELECT pe.id AS id_permiso_especial, pe.nombre, 
            CASE WHEN peu.id IS NOT NULL THEN 1 ELSE 0 END AS tiene_permiso 
            FROM permisos_especiales pe 
            LEFT JOIN permisos_especiales_usuario peu 
            ON peu.permiso_id = pe.id AND peu.id_usuario = ?
            ORDER BY pe.nombre ASC
        ";

        $query = $this->query($sql, [$idUsuario]);

        return $query->getResultArray();

    }


}
