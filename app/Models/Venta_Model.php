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

    public function getVentaConDetalle($id_venta)
    {
        // 🔹 Obtener cabecera de la venta
        $venta = $this->select("
                        ventas.*,
                        clientes.nombre_cliente AS cliente,
                        DATE_FORMAT(ventas.created_at, '%d-%m-%Y') AS fecha_formateada
                    ")
                    ->join('cliente clientes', 'clientes.id_cliente = ventas.id_cliente', 'left')
                    ->where('ventas.id', $id_venta)
                    ->first();

        // 🔹 Obtener detalle de la venta
        $detalle = $this->db->table('detalle_venta dv')
                    ->select("
                        dv.id_repuesto,
                        dv.cantidad,
                        dv.precio_unitario,
                        r.nombre AS repuesto,
                        r.codigo
                    ")
                    ->join('repuesto r', 'r.id = dv.id_repuesto')
                    ->where('dv.id_venta', $id_venta)
                    ->get()
                    ->getResult();

        return [
            'venta'   => $venta,
            'detalle' => $detalle
        ];
    }
}