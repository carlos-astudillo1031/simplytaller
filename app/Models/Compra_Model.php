<?php

namespace App\Models;

use CodeIgniter\Model;

class Compra_Model extends Base_Empresa_Model
{
    protected $table            = 'compras';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'id_proveedor',
        'fecha',
        'numero_factura',
        'total_neto',
        'total_iva',
        'total',
        'estado',
        'fecha_anulacion'
    ];

    protected $useTimestamps = false; // usas solo created_at manual

    protected $createdField  = 'created_at';

    // 🔹 Obtener compras con proveedor (join)
    public function getCompras($fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->select("
                    compras.*, 
                    proveedores.nombre as proveedor,
                    DATE_FORMAT(compras.fecha, '%d-%m-%Y') as fecha_formateada
                ")
                ->join('proveedores', 'proveedores.id = compras.id_proveedor');

        if ($fechaInicio && $fechaFin) {
            $builder->where('compras.fecha >=', $fechaInicio)
                    ->where('compras.fecha <=', $fechaFin);
        } else {
            $builder->where('compras.fecha >=', 'DATE_SUB(NOW(), INTERVAL 60 DAY)', false);
        }

        return $builder->orderBy('compras.fecha', 'DESC')
               ->findAll();
    }

    // 🔹 Obtener una compra con proveedor
    public function getCompra($id)
    {
        return $this->select('compras.*, proveedores.nombre as proveedor')
                    ->join('proveedores', 'proveedores.id = compras.id_proveedor')
                    ->where('compras.id', $id)
                    ->first();
    }

    public function getCompraConDetalle($id_compra)
    {
       
        $compra = $this->select("
                        compras.*,
                        proveedores.nombre AS proveedor,
                        DATE_FORMAT(compras.fecha, '%d-%m-%Y') as fecha_formateada
                    ")
                    ->join('proveedores', 'proveedores.id = compras.id_proveedor')
                    ->where('compras.id', $id_compra)
                    ->first();

     
        $detalle = $this->db->table('detalle_compra dc')
                    ->select("
                        dc.id_repuesto,
                        dc.cantidad,
                        dc.precio_unitario,
                        r.nombre AS repuesto,
                        r.codigo
                    ")
                    ->join('repuesto r', 'r.id = dc.id_repuesto')
                    ->where('dc.id_compra', $id_compra)
                    ->get()
                    ->getResult();

        return [
            'compra' => $compra,
            'detalle' => $detalle
        ];
    }
}