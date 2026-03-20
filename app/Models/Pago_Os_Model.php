<?php



namespace App\Models;



use CodeIgniter\Model;



class Pago_Os_Model extends Base_Empresa_Model

{

    protected $table = 'pago_os';

    protected $primaryKey = 'id_pago_venta';



    // Campos permitidos para inserción/actualización

    protected $allowedFields = [

        'fecha_pago',

        'tipo_dte',

        'numero_dte',

        'monto_pago',

        'tipo_pago',

        'numero_documento',

        'tipo_tarjeta',

        'id_orden_servicio'

    ];



    // Usa timestamps y deleted_at

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $deletedField  = 'deleted_at';

    protected $useSoftDeletes = true;



    // Puedes agregar validaciones opcionales si deseas

    protected $validationRules    = [];

    protected $validationMessages = [];


    public function GetFormaPago($id_orden_servicio) {
        $pago = $this->where('id_orden_servicio', $id_orden_servicio)->first();
        return $pago;
    }

   
    public function obtenerTransferencias($fechaInicio, $fechaFin)
    {
        return $this->select("DATE_FORMAT(pago_os.fecha_pago, '%d-%m-%Y') as fecha_pago, 
                            pago_os.monto_pago, 
                            pago_os.tipo_pago, 
                            pago_os.id_orden_servicio, 
                            pago_os.numero_documento,
                            cliente.nombre_cliente")
                    ->join('orden_servicio', 'orden_servicio.id = pago_os.id_orden_servicio')
                    ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
                    ->where('pago_os.tipo_pago', 'transferencia')
                    ->where('pago_os.fecha_pago >=', $fechaInicio)
                    ->where('pago_os.fecha_pago <=', $fechaFin)
                    ->orderBy('pago_os.fecha_pago', 'ASC')
                    ->findAll();
    }




}

