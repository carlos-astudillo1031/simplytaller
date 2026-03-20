<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Base_Empresa_Model;

class Presupuesto_Model extends Base_Empresa_Model
{
    protected $table      = 'presupuesto';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id_vehiculo',
        'id_bomba',
        'id_cliente',
        'id_orden_servicio',
        'total',
        'kms',
        'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function obtenerUltimosFinalizados(int $limite = 200)
    {
        return $this->select(
                'presupuesto.id as num_presupuesto,
                presupuesto.kms,
                DATE_FORMAT(presupuesto.created_at, "%d-%m-%Y") AS fecha_creacion,
                presupuesto.total,
                cliente.nombre_cliente,
                cliente.email_cliente,
                cliente.telefono_cliente,
                bomba.codigo,
                presupuesto.id_orden_servicio,
                COALESCE(vehiculo.patente, "") as patente,
                COALESCE(vehiculo.id, bomba.id) as id_unico,
                COALESCE(mv.nombre, mb.nombre) as marca,
                COALESCE(modv.nombre, modb.nombre) as modelo'
            )
            ->join('cliente', 'cliente.id_cliente = presupuesto.id_cliente')
            ->join('vehiculo', 'vehiculo.id = presupuesto.id_vehiculo', 'left')
            ->join('bomba', 'bomba.id = presupuesto.id_bomba', 'left')
            ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
            ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
            ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
            ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
            ->where('presupuesto.estado', 0)
            ->orWhere('presupuesto.estado', 1)
            ->orderBy('presupuesto.id', 'DESC')
            ->findAll($limite);
    }

    public function getPresupuestosPorCliente(int $id_cliente)
    {
        return $this->select(
            'presupuesto.id as num_presupuesto,
            presupuesto.kms,
            DATE_FORMAT(presupuesto.created_at, "%d-%m-%Y") AS fecha_creacion,
            presupuesto.total,
            cliente.nombre_cliente,
            cliente.email_cliente,
            cliente.telefono_cliente,
            bomba.codigo,
            presupuesto.id_orden_servicio,
            COALESCE(vehiculo.patente, "") as patente,
            COALESCE(vehiculo.id, bomba.id) as id_unico,
            COALESCE(mv.nombre, mb.nombre) as marca,
            COALESCE(modv.nombre, modb.nombre) as modelo'
        )
            ->join('cliente', 'cliente.id_cliente = presupuesto.id_cliente')
            ->join('vehiculo', 'vehiculo.id = presupuesto.id_vehiculo', 'left')
            ->join('bomba', 'bomba.id = presupuesto.id_bomba', 'left')
            ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
            ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
            ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
            ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
            ->where('presupuesto.id_cliente', $id_cliente) // <-- filtro por cliente
            ->whereIn('presupuesto.estado', [2, 3])
            ->orderBy('presupuesto.id', 'DESC')
            ->findAll();
    }



    public function getDetalleCompleto($id_presupuesto)
    {
         $db = $this->db;

        // Obtener el presupuesto, junto con datos de cliente, vehículo o bomba
        $presupuesto = $this->select(
                'presupuesto.*, 
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
            ->join('cliente', 'cliente.id_cliente = presupuesto.id_cliente')
            ->join('vehiculo', 'vehiculo.id = presupuesto.id_vehiculo', 'left')
            ->join('marca marca_veh', 'marca_veh.id = vehiculo.id_marca', 'left')
            ->join('modelo modelo_veh', 'modelo_veh.id = vehiculo.id_modelo', 'left')
            ->join('bomba', 'bomba.id = presupuesto.id_bomba', 'left')
            ->join('marca marca_bom', 'marca_bom.id = bomba.id_marca', 'left')
            ->join('modelo modelo_bom', 'modelo_bom.id = bomba.id_modelo', 'left')
            ->where('presupuesto.id', $id_presupuesto)
            ->first();

        if (!$presupuesto) {
            return null;
        }

        // Obtener servicios asociados
        $servicios = $db->table('servicio_presupuesto sp')
            ->select('sp.id_servicio, sp.precio, s.nombre, sp.cantidad')
            ->join('servicio s', 's.id = sp.id_servicio')
            ->where('sp.id_presupuesto', $id_presupuesto)            
            ->get()
            ->getResultArray();

      

        // Obtener repuestos asociados
        $repuestos = $db->table('repuesto_presupuesto rp')
            ->select('rp.id_repuesto, rp.cantidad, rp.precio_unitario, r.nombre, r.codigo')
            ->join('repuesto r', 'r.id = rp.id_repuesto')
            ->where('rp.id_presupuesto', $id_presupuesto)           
            ->get()
            ->getResultArray();        


        // Agregar al array
        $presupuesto['servicios'] = $servicios;
        $presupuesto['repuestos'] = $repuestos;
       /*  $presupuesto['subtotal_servicios'] = $subtotal_servicios; */
      /*   $presupuesto['subtotal_repuestos'] = $subtotal_repuestos;
        $presupuesto['total'] = $total; */
        $presupuesto['fecha'] = date('d-m-Y', strtotime($presupuesto['created_at']));

        return $presupuesto;
    }



    
}
