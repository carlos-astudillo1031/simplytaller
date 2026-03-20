<?php

namespace App\Models;

use CodeIgniter\Model;

class Os_Danio_Model extends Base_Empresa_Model
{
    protected $table            = 'os_danio';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Puedes cambiar a 'object' si prefieres objetos
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_orden_servicio',
        'id_zona',
        'id_tipo',
        'comentario'
    ];

    // Dates
    protected $useTimestamps = true; // Habilitado por las columnas 'created_at', 'updated_at'
    protected $dateFormat    = 'datetime'; // Formato de fecha para las columnas de tiempo
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    function GetDetalleDanio($id_orden_servicio) {
       return $this->select(
        '
            os_danio.*,
            os_danio_zona.nombre as zona,
            os_danio_tipo.nombre as tipo'
       )
       ->join('os_danio_zona', 'os_danio_zona.id = os_danio.id_zona')
       ->join('os_danio_tipo', 'os_danio_tipo.id = os_danio.id_tipo')
       ->where('os_danio.id_orden_servicio', $id_orden_servicio)
       ->findAll();
    }

}