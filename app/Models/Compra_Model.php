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
        'total'
    ];

    protected $useTimestamps = false; // usas solo created_at manual

    protected $createdField  = 'created_at';

    // 🔹 Obtener compras con proveedor (join)
    public function getCompras()
    {
        return $this->select('compras.*, proveedores.nombre as proveedor')
                    ->join('proveedores', 'proveedores.id = compras.id_proveedor')
                    ->orderBy('compras.id', 'DESC')
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
}