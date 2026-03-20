<?php

namespace App\Models;

use CodeIgniter\Model;

class Orden_Servicio_Model extends Base_Empresa_Model
{
    protected $table            = 'orden_servicio';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'total',
        'id_presupuesto',
        'fecha_estimada_entrega',
        'motivo',
        'diagnostico',
        'estado',
        'id_cliente',
        'id_vehiculo',
        'id_bomba',
        'kms',
        'nivel_combustible',
        'id_mecanico'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function obtenerOrdenesActivas(int $limite = 200)
    {
        return $this->select(
                'orden_servicio.id as num_os,
                orden_servicio.kms,
                DATE_FORMAT(orden_servicio.created_at, "%d-%m-%Y") AS fecha_creacion,
                orden_servicio.total,
                cliente.nombre_cliente,
                cliente.email_cliente,
                cliente.telefono_cliente,
                orden_servicio.estado,
                bomba.codigo,
                COALESCE(vehiculo.patente, "") as patente,
                COALESCE(vehiculo.id, bomba.id) as id_unico,
                COALESCE(mv.nombre, mb.nombre) as marca,
                COALESCE(modv.nombre, modb.nombre) as modelo'
            )
            ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
            ->join('vehiculo', 'vehiculo.id = orden_servicio.id_vehiculo', 'left')
            ->join('bomba', 'bomba.id = orden_servicio.id_bomba', 'left')
            ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
            ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
            ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
            ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
            ->where('orden_servicio.estado <', 4)            
            ->orderBy('orden_servicio.id', 'DESC')
            ->findAll($limite);
    }

    public function obtenerOrdenesByCliente(int $id_cliente)
    {
        return $this->select(
                'orden_servicio.id as num_os,
                orden_servicio.kms,
                DATE_FORMAT(orden_servicio.created_at, "%d-%m-%Y") AS fecha_creacion,
                orden_servicio.total,
                cliente.nombre_cliente,
                cliente.email_cliente,
                cliente.telefono_cliente,
                orden_servicio.estado,
                bomba.codigo,
                COALESCE(vehiculo.patente, "") as patente,
                COALESCE(vehiculo.id, bomba.id) as id_unico,
                COALESCE(mv.nombre, mb.nombre) as marca,
                COALESCE(modv.nombre, modb.nombre) as modelo'
            )
            ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
            ->join('vehiculo', 'vehiculo.id = orden_servicio.id_vehiculo', 'left')
            ->join('bomba', 'bomba.id = orden_servicio.id_bomba', 'left')
            ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
            ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
            ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
            ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
            ->where('orden_servicio.id_cliente', $id_cliente)
            ->where('orden_servicio.estado >', 4)            
            ->orderBy('orden_servicio.id', 'DESC')
            ->findAll();
    }

     public function obtenerOrdenesPorPagar(int $limite = 200)
     {
        return $this->select(
                'orden_servicio.id as num_os,
                orden_servicio.kms,
                DATE_FORMAT(orden_servicio.created_at, "%d-%m-%Y") AS fecha_creacion,
                orden_servicio.total,
                cliente.nombre_cliente,
                cliente.email_cliente,
                cliente.telefono_cliente,
                orden_servicio.estado,
                bomba.codigo,
                COALESCE(vehiculo.patente, "") as patente,
                COALESCE(vehiculo.id, bomba.id) as id_unico,
                COALESCE(mv.nombre, mb.nombre) as marca,
                COALESCE(modv.nombre, modb.nombre) as modelo'
            )
            ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
            ->join('vehiculo', 'vehiculo.id = orden_servicio.id_vehiculo', 'left')
            ->join('bomba', 'bomba.id = orden_servicio.id_bomba', 'left')
            ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
            ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
            ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
            ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
            ->where('orden_servicio.estado', 4)            
            ->orderBy('orden_servicio.id', 'DESC')
            ->findAll($limite);
    }


    public function getDetalleCompleto($id)
    {
         $db = $this->db;

        // Obtener el presupuesto, junto con datos de cliente, vehículo o bomba
        $orden_servicio = $this->select(
                'orden_servicio.*, 
                vehiculo.patente AS vehiculo_patente,
                marca_veh.nombre AS vehiculo_marca,
                modelo_veh.nombre AS vehiculo_modelo,
                bomba.codigo AS bomba_codigo,
                marca_bom.nombre AS bomba_marca,
                modelo_bom.nombre AS bomba_modelo,               
                cliente.nombre_cliente AS cliente_nombre,
                cliente.telefono_cliente AS cliente_telefono,
                cliente.email_cliente AS cliente_email'
            )
            ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
            ->join('vehiculo', 'vehiculo.id = orden_servicio.id_vehiculo', 'left')
            ->join('marca marca_veh', 'marca_veh.id = vehiculo.id_marca', 'left')
            ->join('modelo modelo_veh', 'modelo_veh.id = vehiculo.id_modelo', 'left')
            ->join('bomba', 'bomba.id = orden_servicio.id_bomba', 'left')
            ->join('marca marca_bom', 'marca_bom.id = bomba.id_marca', 'left')
            ->join('modelo modelo_bom', 'modelo_bom.id = bomba.id_modelo', 'left')
            ->where('orden_servicio.id', $id)
            ->first();

        if (!$orden_servicio) {
            return null;
        }

        // Obtener servicios asociados
        $servicios = $db->table('servicio_orden_servicio sp')
            ->select('sp.id_servicio, sp.precio, s.nombre, sp.cantidad')
            ->join('servicio s', 's.id = sp.id_servicio')
            ->where('sp.id_orden_servicio', $id)            
            ->get()
            ->getResultArray();

      

        // Obtener repuestos asociados
        $repuestos = $db->table('repuesto_orden_servicio rp')
            ->select('rp.id_repuesto, rp.cantidad, rp.precio, r.nombre, r.codigo')
            ->join('repuesto r', 'r.id = rp.id_repuesto')
            ->where('rp.id_orden_servicio', $id)           
            ->get()
            ->getResultArray();        


        // Agregar al array
        $orden_servicio['servicios'] = $servicios;
        $orden_servicio['repuestos'] = $repuestos;
       /*  $presupuesto['subtotal_servicios'] = $subtotal_servicios; */
      /*   $presupuesto['subtotal_repuestos'] = $subtotal_repuestos;
        $presupuesto['total'] = $total; */
        $orden_servicio['fecha'] = date('d-m-Y', strtotime($orden_servicio['created_at']));

        return $orden_servicio;
    }

       
}
