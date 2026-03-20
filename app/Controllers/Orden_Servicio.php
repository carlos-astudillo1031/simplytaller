<?php

namespace App\Controllers;
/* var_dump(APPPATH . 'Libraries/DompdfHelper.php');
exit; */
require_once APPPATH . 'Libraries/DompdfHelper.php';
use App\Libraries\DompdfHelper;

class Orden_Servicio extends BaseController
{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }
    //Esto Carga la Orden de Servicio en pantalla
    public function index(?string $id_orden_servicio = null): string
    {       
        //Modelos
        $model_orden_servicio = model('Orden_Servicio_Model');
        $model_presupuesto = model('Presupuesto_Model');        
        $model_repuesto_orden_servicio = model('Repuesto_Orden_Servicio_Model');
        $model_servicio_orden_servicio = model('Servicio_Orden_Servicio_Model');
        $vehiculoModel = model('Vehiculo_Model');
        $bombaModel = model('Bomba_Model');
        $ObservacionesItemModel  = model('Os_Observacion_Item_Model');
        $InventarioItemModel  = model('Os_Inventario_Item_Model');        
        $tipo_danioModel  = model('Os_Danio_Tipo_Model');
        $zona_danioModel  = model('Os_Danio_Zona_Model'); 

        //Trae Orden de Servicio
        $orden_servicio = $model_orden_servicio->find($id_orden_servicio);

        //Trae Presupuesto
        $presupuesto = $model_presupuesto->find($orden_servicio['id_presupuesto']);        
        

        //Trae el vehiculo del presupuesto
        if (isset($presupuesto['id_vehiculo'])) {
            $vehiculo = $vehiculoModel->getVehiculoConClientePorId($presupuesto['id_vehiculo']);    
        }else if (isset($orden_servicio['id_vehiculo'])) {
            $vehiculo = $vehiculoModel->getVehiculoConClientePorId($orden_servicio['id_vehiculo']);  
        }else{    
            $vehiculo = null;
        }
        

        if (!$vehiculo) {            
            //Trae la bomba del presupuesto
            if (isset($presupuesto['id_bomba'])) {
                $bomba = $bombaModel->getBombaConClientePorId($presupuesto['id_bomba']);                        
            }else if (isset($orden_servicio['id_bomba'])) {
                $bomba = $bombaModel->getBombaConClientePorId($orden_servicio['id_bomba']);
            }else{
                $bomba = null;
            }            
        }else{
            $bomba = null;
        }

        //Para validar si puede ver precios
        $permisos_especiales = session()->get('permisos_especiales');     
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe

        //Trae Repuestos
        $repuestos = $model_repuesto_orden_servicio->GetDetalleRepuestoOrdenServicio($id_orden_servicio);
        //Validamos si tiene los permisos para ver precios
        $repuestos = $this->ocultarPreciosDetalleSiNoTienePermiso($repuestos, $permisoId,['precio']);   

        //Trae Servicios
        $servicios = $model_servicio_orden_servicio->GetDetalleServicioOrden($id_orden_servicio);
        //Validamos si tiene los permisos para ver precios
        $servicios = $this->ocultarPreciosDetalleSiNoTienePermiso($servicios, $permisoId,['precio']);   

        //Trae items de observaciones para completar
        $obserbaciones = $ObservacionesItemModel->findAll();

        //Trae items de inventario para completar
        $inventario = $InventarioItemModel->findAll();

        //Trae items de danios para completar
        $danios = $tipo_danioModel->findAll();
        $zonas = $zona_danioModel->findAll();

        //Datos para la vista
        $data = [
            'orden_servicio' => $orden_servicio,
            'presupuesto' => $presupuesto,
            'repuestos' => $repuestos,
            'servicios' => $servicios,
            'vehiculo' => $vehiculo,
            'bomba' => $bomba,
            'observaciones' => $obserbaciones,
            'inventario' => $inventario,
            'danios' => $danios,
            'zonas' => $zonas
        ];      
        //Llamado a la vista
        return view('main/orden_servicio/index', $data); 
    }



    public function GuardarCambios(){
        if ($this->request->isAJAX()) {

            // Obtener datos JSON enviados
            $json = $this->request->getBody();
            $data = json_decode($json, true);


            //Instancia Modelos
            $modelo_orden_servicio = model('Orden_Servicio_Model');
            $modelo_observaciones = model('Os_Observacion_Model');
            $modelo_inventario = model('Os_Inventario_Model');
            $modelo_danio = model('Os_Danio_Model');
            $modelo_servicio_orden_servicio = model('Servicio_Orden_Servicio_Model');
            $modelo_repuesto_orden_servicio = model('Repuesto_Orden_Servicio_Model');
            
            //Variables            
            $orden_servicio  = $data['orden_servicio'] ?? null;       

           

            $observaciones   = $data['observaciones'] ?? [];
            $inventario      = $data['inventario'] ?? [];
            $combustible     = $data['combustible'] ?? [];
            $danios          = $data['danios'] ?? [];
            $servicios       = $data['servicios'] ?? [];
            $repuestos       = $data['repuestos'] ?? [];
            $id_orden_servicio = $orden_servicio['id'] ?? null;

            //Guarda Orden de Servicio
             if(isset($orden_servicio['kms'])){
                $dataOS = [
                    'kms' => $orden_servicio['kms'],
                    'total' => $orden_servicio['total'],
                    'motivo' => $orden_servicio['motivo'],
                    'diagnostico' => $orden_servicio['diagnostico'],
                    'id_mecanico' => $orden_servicio['id_mecanico'],
                    'fecha_estimada_entrega' => $orden_servicio['fecha_estimada_entrega'],
                    'nivel_combustible' => $combustible[0]['valor'],                    
                ];
            }else{
                $dataOS = [
                    'total' => $orden_servicio['total'],
                    'motivo' => $orden_servicio['motivo'],
                    'diagnostico' => $orden_servicio['diagnostico'],
                    'id_mecanico' => $orden_servicio['id_mecanico'],
                    'fecha_estimada_entrega' => $orden_servicio['fecha_estimada_entrega'],
                    'nivel_combustible' => $combustible[0]['valor'],
                ];
            }
            $okSaveOrden = $modelo_orden_servicio->update($id_orden_servicio, $dataOS);
            if(!$okSaveOrden){
                return $this->response->setJSON(['error' => 'No se pudo guardar la Orden de Servicio']);
            }
            //Guarda Observaciones            
            $modelo_observaciones->where('id_orden_servicio', $id_orden_servicio)->delete();
            foreach ($observaciones as $observacion) {
                $modelo_observaciones->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'id_item' =>(int)$observacion['id']
                ]);
                $okSaveObservaciones = $modelo_orden_servicio->update($id_orden_servicio, $dataOS);
                if(!$okSaveObservaciones){
                    return $this->response->setJSON(['error' => 'No se pudo guardar las observaciones']);
                }
            }


            //Guarda Inventario
            $modelo_inventario->where('id_orden_servicio', $id_orden_servicio)->delete();
            foreach ($inventario as $item) {
                $okSaveInventario = $modelo_inventario->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'id_item' => $item['id']
                ]);
                if(!$okSaveInventario){
                    return $this->response->setJSON(['error' => 'No se pudo guardar el inventario']);
                }
            }

            //Guarda Danios            
            $modelo_danio->where('id_orden_servicio', $id_orden_servicio)->delete();
            foreach ($danios as $danio) {
                $okSaveDanios = $modelo_danio->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'id_tipo' => $danio['id_tipo'],
                    'id_zona' => $danio['id_zona'],
                    'comentario' => $danio['comentario']
                ]);
                if(!$okSaveDanios){
                    return $this->response->setJSON(['error' => 'No se pudo guardar los daños']);
                }
            }

            /// 🧹 LIMPIAMOS los datos antiguos (si es una edición, asegura integridad)
            $modelo_servicio_orden_servicio->where('id_orden_servicio', $id_orden_servicio)->delete();
            // 🔄 AGREGAMOS los servicios actuales
            foreach ($servicios as $serv) {
                /* echo "cantidad_servicio:".$serv['cantidad']."<br>"; */
                $okSaveServicios = $modelo_servicio_orden_servicio->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'id_servicio'    => $serv['id_servicio'],
                    'precio'         => $serv['precio'],
                    'cantidad'       => $serv['cantidad'],
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
                if(!$okSaveServicios){
                    return $this->response->setJSON(['error' => 'No se pudo guardar los servicios']);
                }
            }

            $modelo_repuesto_orden_servicio->where('id_orden_servicio', $id_orden_servicio)->delete();
            // 🔄 AGREGAMOS los repuestos actuales
            foreach ($repuestos as $rep) {
                // Obtener la última consulta SQL como string                                                      
                $okSaveRepuestos = $modelo_repuesto_orden_servicio->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'id_repuesto'    => $rep['id_repuesto'],
                    'cantidad'       => $rep['cantidad'],
                    'precio'         => $rep['precio'],
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
                if (!$okSaveRepuestos) {                    
                   // Obtener el error exacto de la base de datos
                    $dbError = $modelo_repuesto_orden_servicio->db->error();

                    return $this->response->setJSON([                        
                        'error' => $dbError['message']
                    ]);
                }
            }

            echo json_encode(['status' => 'ok']);


        }
    }

    public function lista() {
        return view('main/orden_servicio/lista');
    }

    public function pagos(){
        return view('main/orden_servicio/pagos');
    }

    public function GetOrdenes() {
        $modelo_ordenes_servicio = model('Orden_Servicio_Model');
        $ordenes = $modelo_ordenes_servicio->obtenerOrdenesActivas(); 
        $permisos_especiales = session()->get('permisos_especiales');        
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
        //Pasamos por la función
        $ordenes = $this->ocultarPreciosSiNoTienePermiso($ordenes, $permisoId);
        header('Content-Type: application/json');
        echo json_encode($ordenes);
    }

    public function GetOrdenesByCliente() {
        $modelo_ordenes_servicio = model('Orden_Servicio_Model');        
        if ($this->request->isAJAX()) {
            $id_cliente = $this->request->getPost('id_cliente');
            $ordenes = $modelo_ordenes_servicio->obtenerOrdenesByCliente($id_cliente); 
        }   
        $permisos_especiales = session()->get('permisos_especiales');        
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
        //Pasamos por la función
        $ordenes = $this->ocultarPreciosSiNoTienePermiso($ordenes, $permisoId);
        header('Content-Type: application/json');
        echo json_encode($ordenes);
    }

    public function GetOrdenesPorPagar() {
        $modelo_ordenes_servicio = model('Orden_Servicio_Model');
        $ordenes = $modelo_ordenes_servicio->obtenerOrdenesPorPagar(); 
        header('Content-Type: application/json');
        echo json_encode($ordenes);
    }


    public function GetObservacionesMarcadas(){
        if ($this->request->isAJAX()) {
            $modelo_observaciones = model('Os_Observacion_Model');
            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $observaciones_marcadas = $modelo_observaciones->where('id_orden_servicio', $id_orden_servicio)->findAll();
            echo json_encode($observaciones_marcadas);
        }
    }

    public function GetInventarioMarcado(){
        if ($this->request->isAJAX()) {
            $modelo_inventario = model('Os_Inventario_Model');
            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $inventario_marcado = $modelo_inventario->where('id_orden_servicio', $id_orden_servicio)->findAll();
            echo json_encode($inventario_marcado);
        }
    }

    public function GetDaniosMarcados(){
        if ($this->request->isAJAX()) {
            $modelo_danio = model('Os_Danio_Model');
            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $danios_marcados = $modelo_danio->GetDetalleDanio($id_orden_servicio);
            echo json_encode($danios_marcados);
        }
    }

    //Crea la OS desde un vehiculo

    public function CrearOS(?string $token = null){        
            //Instancia Modelo
            $modelo_orden_servicio = model('Orden_Servicio_Model');
            $modelo_bombas = model('Bomba_Model');
            $modelo_vehiculos = model('Vehiculo_Model');
            $ObservacionesItemModel  = model('Os_Observacion_Item_Model');
            $InventarioItemModel  = model('Os_Inventario_Item_Model');        
            $tipo_danioModel  = model('Os_Danio_Tipo_Model');
            $zona_danioModel  = model('Os_Danio_Zona_Model'); 


            $vehiculo = $modelo_vehiculos->getVehiculoConClientePorToken($token);


            if (!$vehiculo) {                      
                /* log_message('error', 'No se pudo obtener el vehiculo');      */     
                $bomba = $modelo_bombas->getBombaConClientePorToken($token);  
                /* log_message('debug', print_r($bomba, true)); */
                $id_bomba = $bomba['id'];                     
                $id_cliente = $bomba['id_cliente'];
                $id_vehiculo = null;                
            }else{
                /* log_message('error', 'SI se pudo obtener el vehiculo'); */
                $id_vehiculo = $vehiculo['id'];
                $id_cliente = $vehiculo['id_cliente'];
                $id_bomba =  null;
            }                

            // Creamos la Orden
            $data = [
                        'id_cliente'     => $id_cliente,
                        'id_vehiculo'    => $id_vehiculo,
                        'id_bomba'       => $id_bomba,
                        'estado'         => 0
            ];
            /* log_message('debug', 'Data a insertar: ' . print_r($data, true)); */
            $modelo_orden_servicio->insert($data);
            $id_orden_servicio = $modelo_orden_servicio->getInsertID();
            $orden_servicio = $modelo_orden_servicio->find($id_orden_servicio);

            //Trae items de observaciones para completar
            $obserbaciones = $ObservacionesItemModel->findAll();

            //Trae items de inventario para completar
            $inventario = $InventarioItemModel->findAll();

            //Trae items de danios para completar
            $danios = $tipo_danioModel->findAll();
            $zonas = $zona_danioModel->findAll();


            //Aqui redirige a la vista de OS con datos de bomba y vehiculo            
            if($id_vehiculo == null){
                $dataView = [
                    'id_orden_servicio' => $orden_servicio['id'],
                    'bomba'             => $bomba,                
                    'orden_servicio'    => $orden_servicio,
                    'observaciones'     => $obserbaciones,
                    'inventario'        => $inventario,
                    'danios'            => $danios,
                    'zonas' => $zonas
                ];    
            }else{
                $dataView = [
                    'id_orden_servicio' => $orden_servicio['id'],
                    'vehiculo'          => $vehiculo,                
                    'orden_servicio'    => $orden_servicio,
                    'observaciones'     => $obserbaciones,
                    'inventario'        => $inventario,
                    'danios'            => $danios,
                    'zonas' => $zonas
                ];
            }
            
            return view('main/orden_servicio/index.php', $dataView);        
    }

    //Crea la OS en base a un presupuesto    
    public function ConvertirEnOS(){
        
        if ($this->request->isAJAX()) {            
            //Modelos
            $model_orden_servicio = model('Orden_Servicio_Model');
            $model_presupuesto = model('Presupuesto_Model');           
            $model_repuesto_orden_servicio = model('Repuesto_Orden_Servicio_Model');
            $model_servicio_orden_servicio = model('Servicio_Orden_Servicio_Model');
                        
            $id_presupuesto = $this->request->getPost('id_presupuesto');

            //Trae Presupuesto
            $presupuesto = $model_presupuesto->find($id_presupuesto);
            log_message('debug', 'Presupuesto encontrado: ' . print_r($presupuesto, true));     

            //Trae Orden de Servicio asociada (en caso que exista)	
            $orden_servicio = $model_orden_servicio->where('id_presupuesto', $id_presupuesto)->first();       
            log_message('debug', 'Orden de servicio encontrada: ' . print_r($orden_servicio, true));     

            //Si no existe la orden de servicio la crea
            if ($orden_servicio === null) {
                //Datos para Crear Orden
                $data = [
                    'id_presupuesto' => $id_presupuesto,
                    'id_vehiculo' => $presupuesto['id_vehiculo'],
                    'id_bomba' => $presupuesto['id_bomba'],
                    'id_cliente'=> $presupuesto['id_cliente'],
                    'kms' => $presupuesto['kms'],
                    'total' => $presupuesto['total'],                
                    'estado' => 2
                ];                  
                
                //Crea la Orden de Servicio
                $model_orden_servicio->insert($data);  

                $id_orden_servicio = $model_orden_servicio->getInsertID();  
            }else{
                $id_orden_servicio = $orden_servicio['id'];
                $data = [
                    'id_presupuesto' => $id_presupuesto,
                    'id_vehiculo' => $presupuesto['id_vehiculo'],
                    'id_bomba' => $presupuesto['id_bomba'],
                    'id_cliente'=> $presupuesto['id_cliente'],
                    'kms' => $presupuesto['kms'],
                    'total' => $presupuesto['total'],                
                    'estado' => 2
                ];             
                $model_orden_servicio->update($id_orden_servicio, $data);     
            }    
            
            //Borro los repuestos de la tabla de repuestos de la orden de servicio                 
            $model_repuesto_orden_servicio->where('id_orden_servicio', $id_orden_servicio)->delete();
    
            //Copia los repuestos del presupuesto a la tabla de repuestos de la orden de servicio
            $model_repuesto_orden_servicio->copiarDesdePresupuesto($id_presupuesto, $id_orden_servicio);
            log_message('debug', 'Repuestos copiados' );

            //Borro los servicios de la tabla de servicios de la orden de servicio            
            $model_servicio_orden_servicio->where('id_orden_servicio', $id_orden_servicio)->delete();
            //Copia los servicios del presupuesto a la tabla de servicios de la orden de servicio
            $model_servicio_orden_servicio->copiarServiciosDesdePresupuesto($id_presupuesto, $id_orden_servicio);    
            log_message('debug', 'Servicios copiados' ); 
            
            //Pasa presupuesto a confirmado
            $model_presupuesto->update($id_presupuesto, ['estado' => 2]);
            log_message('debug', 'Estado presupuesto actualizado '.$id_presupuesto );            

            return $this->response->setJSON(['success' => true, 'id_orden_servicio' => $id_orden_servicio, 'id_presupuesto' => $id_presupuesto]);
        }    
    }

    //Crea  presupuesto en base a la OS
    public function CrearPresupuesto(){
        if ($this->request->isAJAX()) {
            //Modelos
            $modelo_presupuesto = model('Presupuesto_Model');
            $modelo_orden_servicio = model('Orden_Servicio_Model');
            $model_repuesto_presupuesto = model('Repuesto_Presupuesto_Model');
            $model_servicio_presupuesto = model('Servicio_Presupuesto_Model');

            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $presupuesto = $modelo_presupuesto->where('id_orden_servicio', $id_orden_servicio)->first();

            $orden_servicio = $modelo_orden_servicio->find($id_orden_servicio);

            //No existe un presupuesto lo va a crear
            if($presupuesto == null && $orden_servicio['id_presupuesto'] == null){
                $modelo_presupuesto->insert([
                    'id_orden_servicio' => $id_orden_servicio,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'deleted_at' => null,
                    'id_vehiculo' => $orden_servicio['id_vehiculo'],
                    'id_bomba' => $orden_servicio['id_bomba'],
                    'id_cliente' => $orden_servicio['id_cliente'],
                    'kms' => $orden_servicio['kms'],
                    'total' => $orden_servicio['total'],
                    'estado' => 1
                ]);
                
                // Obtener el ID del registro insertado
                $id_presupuesto = $modelo_presupuesto->insertID();

                //Copia los servicios la orden de servicio a la tabla de servicios del presupuesto
                $model_servicio_presupuesto->copiarServiciosDesdeOrdenServicio($id_orden_servicio, $id_presupuesto);

                //Copia los repuestos de la orden de servicio a la tabla de repuestos del presupuesto
                $model_repuesto_presupuesto->copiarRepuestosDesdeOrdenServicio($id_orden_servicio, $id_presupuesto);

                //Pasa estado de la OS a En Presupuesto y le pega el id del presupuesto
                $modelo_orden_servicio->update(
                    $id_orden_servicio,
                    [
                        'estado' => 1,
                        'id_presupuesto' => $id_presupuesto
                    ]
                );

                
                
                $response = [
                    'status' => 'ok',
                    'message' => 'Presupuesto Creado correctamente'
                ];
            }else{
                $id_presupuesto = $orden_servicio['id_presupuesto'];
                $response = [
                    'status' => 'error',
                    'message' => 'Ya existe un presupuesto para esta Orden de Servicio con N° ('.$id_presupuesto.')'
                ];
            }
            echo json_encode($response);
        }
    }

    public function CambiaEstado(){
        if ($this->request->isAJAX()) {
            $modelo_orden_servicio = model('Orden_Servicio_Model');
            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $estado = $this->request->getPost('estado');
            $resultado = $modelo_orden_servicio->update($id_orden_servicio, ['estado' => $estado]);
            if($resultado == false){
                $response = [
                    'status' => 'error',
                    'message' => 'Error al cambiar el estado de la orden de servicio'
                ];
                echo json_encode($response);
            }else{
                $response = [
                    'status' => 'ok',
                    'message' => 'Estado de la orden de servicio actualizado correctamente'
                ];
                echo json_encode($response);
            }
            
        }
    }

    public function GetDetallePago(){
        if ($this->request->isAJAX()) {
            $modelo_repuestos_orden_servicio = model('Repuesto_Orden_Servicio_Model');
            $modelo_servicio_orden_servicio = model('Servicio_Orden_Servicio_Model');
            $id_orden_servicio = $this->request->getPost('num_os');
            
            $total_repuestos = $modelo_repuestos_orden_servicio->GetTotalRepuestos($id_orden_servicio);
            $total_servicios = $modelo_servicio_orden_servicio->GetTotalServicios($id_orden_servicio);
            
            $orden_servicio = [
                'total_repuestos' => $total_repuestos['total'] ?? 0,
                'total_servicios' => $total_servicios['total'] ?? 0
            ];

            echo json_encode($orden_servicio);
        }
    }

    public function PagarOS()
    {

        if ($this->request->isAJAX()) {

            $pagoModel = new \App\Models\Pago_Os_Model();

            $id_orden_servicio          = $this->request->getPost('id_orden_servicio');
            $metodo_pago       = $this->request->getPost('metodo_pago');
            $a_pagar        = $this->request->getPost('a_pagar');            
            $numero_documento  = $this->request->getPost('numero_documento');
            $numero_operacion  = $this->request->getPost('numero_operacion');
            $tipo_tarjeta = $this->request->getPost('tipo_tarjeta');
            $tipo_dte = $this->request->getPost('tipo_dte');
            $numero_dte = $this->request->getPost('numero_dte');

            // Determinar número de documento según método de pago

            $numero_final = null;

            if ($metodo_pago == 'tarjeta') {

                $numero_final = $numero_documento;

            }elseif ($metodo_pago == 'transferencia') {

                $numero_final = $numero_operacion;

                $tipo_tarjeta = null;

            }elseif ($metodo_pago == 'efectivo') {

                $tipo_tarjeta = null;

            }



            // Mapear métodos de pago a números

            $tipos_pago = [

                'efectivo'        => 1,

                'tarjeta'         => 2,

                'transferencia'   => 3,

            ];



            $tipo_pago = $tipos_pago[$metodo_pago] ?? 0;



            // Validar datos básicos

            if (empty($id_orden_servicio) || empty($a_pagar) || $tipo_pago === 0) {

                return $this->response->setJSON(['status' => 'error', 'msg' => 'Datos inválidos']);

            }



            // Insertar el pago

            $pagoModel->insert([
                'fecha_pago'        => date('Y-m-d H:i:s'),
                'monto_pago'        => $a_pagar,
                'tipo_pago'         => $tipo_pago,
                'tipo_tarjeta'      => $tipo_tarjeta,
                'numero_documento'  => $numero_final,
                'id_orden_servicio' => $id_orden_servicio,
                'tipo_dte' => $tipo_dte,
                'numero_dte' => $numero_dte
            ]);



            //Pagar la OS

            $modelo_orden_servicio = new \App\Models\Orden_Servicio_Model();

            $modelo_orden_servicio->update($id_orden_servicio, ['estado' => 6]);

            return $this->response->setJSON(['status' => 'ok']);

        }



        // Si no es AJAX
        return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'msg' => 'Método no permitido']);

    }

public function GetDetalleFormaPago(){
        if ($this->request->isAJAX()) {
            $id_orden_servicio = $this->request->getPost('id_orden_servicio');
            $modelo_os = model('Pago_Os_Model');
            $forma_pago = $modelo_os->where('id_orden_servicio', $id_orden_servicio)->first();
            echo json_encode($forma_pago);
        }
 }


public function DescargarPDF($id){
            $modelOrdenServicio = model('Orden_Servicio_Model');
            $modelDatosTaller = model('Datos_Taller_Model');

            $dataOrdenServicio = $modelOrdenServicio->getDetalleCompleto($id);
            $datosTaller = $modelDatosTaller->first(); // Asume que hay solo un registro

            // Instanciar Dompdf
            $dompdfHelper = new \App\Libraries\DompdfHelper();
            $dompdf = $dompdfHelper->getInstance();

            // Pasar ambos sets de datos a la vista
            $html = view('pdf/orden_servicio', [
                'orden_servicio' => $dataOrdenServicio,
                'taller' => $datosTaller
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Verifica si se debe forzar la descarga
            $download = $this->request->getGet('download'); // ?download=1 en la URL

            // Forzar descarga si ?download=1, si no mostrar en navegador
            $dompdf->stream("orden_servicio_{$id}.pdf", [
                "Attachment" => $download ? true : false
            ]);
}

    //Oculta precio del presupuesto en listas
    function ocultarPreciosSiNoTienePermiso(array $orden_servicio, int $permisoId): array
    {
                if ($permisoId !== 1) {
                    foreach ($orden_servicio as &$os) {
                        if (isset($os['total'])) {
                            $os['total'] = 0;
                        }
                    }
                }

                return $orden_servicio;
    }
    //Oculta el precio en detalle de servicios y repuestos ppto si no tiene permiso
    function ocultarPreciosDetalleSiNoTienePermiso(array $items, int $permisoId, array $camposPrecio = []): array
    {
            if ($permisoId !== 1) {
                foreach ($items as &$item) {
                    foreach ($camposPrecio as $campo) {
                        if (isset($item[$campo])) {
                            $item[$campo] = 0;
                        }
                    }
                }
            }

            return $items;
    }

}