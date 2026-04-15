<?php

namespace App\Models;

use CodeIgniter\Model;

class Venta_Model extends Base_Empresa_Model
{
    protected $table            = 'ventas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'id_cliente',
        'tipo_documento',
        'numero_documento',
        'neto',
        'iva',
        'total',
        'estado'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

   public function getVentas($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->select("
                    ventas.*,
                    cliente.nombre_cliente AS cliente,
                    DATE_FORMAT(ventas.created_at, '%d-%m-%Y') AS fecha_formateada
                ")
                ->join('cliente', 'cliente.id_cliente = ventas.id_cliente', 'left');

        // Filtrar por rango de fechas
        if ($fechaInicio && $fechaFin) {
            $builder->where('DATE(ventas.created_at) >=', $fechaInicio)
                    ->where('DATE(ventas.created_at) <=', $fechaFin);
        } else {
            // Últimos 60 días por defecto
            $builder->where('ventas.created_at >=', 'DATE_SUB(NOW(), INTERVAL 60 DAY)', false);
        }

        // Excluir registros eliminados lógicamente
        $builder->where('ventas.deleted_at', null);

        return $builder->orderBy('ventas.created_at', 'DESC')
                    ->findAll();
    }
}