<?php

namespace App\Controllers;
/* var_dump(APPPATH . 'Libraries/DompdfHelper.php');
exit; */
require_once APPPATH . 'Libraries/DompdfHelper.php';
use App\Libraries\DompdfHelper;

class Presupuesto extends BaseController
{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }


    public function index(?string $token = null): string
    {
        $vehiculoModel = new \App\Models\Vehiculo_Model();
        $bombaModel = new \App\Models\Bomba_Model();         
              
        if ($token === null) {            
            return view('main/presupuestos/presupuesto');
        }

        $vehiculo = $vehiculoModel->getVehiculoConClientePorToken($token);


        if (!$vehiculo) {
            /* throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); */
            $bomba = $bombaModel->getBombaConClientePorToken($token);  
            return view('main/presupuestos/presupuesto', [
                'bomba' => $bomba
            ]);          
        }else{
            return view('main/presupuestos/presupuesto', [
                'vehiculo' => $vehiculo
            ]);
        }
       
    }

    public function lista(){
        return view('main/presupuestos/lista');
    }

   public function GetPresupuestos() {
        $model = model('Presupuesto_Model');
        $presupuestos = $model->obtenerUltimosFinalizados();        
        $permisos_especiales = session()->get('permisos_especiales');        
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
        //Pasamos por la función
        $presupuestos = $this->ocultarPreciosSiNoTienePermiso($presupuestos, $permisoId);
        header('Content-Type: application/json');
        echo json_encode($presupuestos);
    }

    public function GetPresupuestosByCliente() {
        $model_presupuesto = model('Presupuesto_Model');
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Acceso no autorizado');
        }
        $id_cliente = $this->request->getPost('id_cliente');        
        $presupuestos = $model_presupuesto->getPresupuestosPorCliente($id_cliente);        
        $permisos_especiales = session()->get('permisos_especiales');     
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
        //Pasamos por la función
        $presupuestos = $this->ocultarPreciosSiNoTienePermiso($presupuestos, $permisoId);
        header('Content-Type: application/json');
        echo json_encode($presupuestos);
    }

    public function GetDetallePresupuesto()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Acceso no autorizado');
        }

        $id_presupuesto = $this->request->getPost('num_presupuesto');

        if (!$id_presupuesto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Presupuesto no especificado']);
        }

        $modelPresupuesto          = model('Presupuesto_Model');
        $modelServicioPresupuesto = model('Servicio_Presupuesto_Model');
        $modelRepuestoPresupuesto = model('Repuesto_Presupuesto_Model');

        // Verificar que el presupuesto exista
        $presupuesto = $modelPresupuesto->find($id_presupuesto);
        if (!$presupuesto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Presupuesto no encontrado']);
        }

           
        $permisos_especiales = session()->get('permisos_especiales');     
        $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
        //Servicios        
        $servicios = $modelServicioPresupuesto->obtenerServiciosConNombre($id_presupuesto);
        //Validamos si tiene los permisos para ver precios
        $servicios = $this->ocultarPreciosDetalleSiNoTienePermiso($servicios, $permisoId,['precio']);   
        
        //Repuestos       
        $repuestos = $modelRepuestoPresupuesto->obtenerRepuestosConNombre($id_presupuesto);
        //Validamos si tiene los permisos para ver precios
        $repuestos = $this->ocultarPreciosDetalleSiNoTienePermiso($repuestos, $permisoId,['precio_unitario']);        
        

        return $this->response->setJSON([
            'success'     => true,            
            'total'       => $presupuesto['total'],
            'servicios'   => $servicios,
            'repuestos'   => $repuestos,
            'estado'      => $presupuesto['estado']  
        ]);
    }


    // Crea o carga un presupuesto vigente (estado 0) para un cliente y vehículo
        public function CrearOPresupuesto()
        {
            if ($this->request->isAJAX()) {

                $model = model('Presupuesto_Model');

                $id_cliente  = service('request')->getPost('id_cliente');
                $id_vehiculo = service('request')->getPost('id_vehiculo');
                
                if($id_vehiculo == 'null'){
                    $id_vehiculo = null;
                }
                $id_bomba    = service('request')->getPost('id_bomba');
                if($id_bomba == 'null'){
                    $id_bomba = null;
                }

                // Buscar presupuesto vigente (estado 0) para ese cliente y vehículo
                $presupuesto = $model
                    ->where('id_cliente', $id_cliente)
                    ->where('id_vehiculo', $id_vehiculo)
                    ->where('id_bomba', $id_bomba)
                    ->where('estado', 0)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                // Si ya existe uno, lo retornamos
                if ($presupuesto) {
                    $response = [
                        'success' => true,
                        'presupuesto' => $presupuesto
                    ];
                } else {
                    // Si no existe, lo creamos
                    $data = [
                        'id_cliente'     => $id_cliente,
                        'id_vehiculo'    => $id_vehiculo,
                        'id_bomba'       => $id_bomba,
                        'estado'         => 0,
                        'observaciones'  => ''
                    ];

                    $id_nuevo = $model->insert($data);
                    $nuevo_presupuesto = $model->find($id_nuevo);

                    $response = [
                        'success' => true,
                        'presupuesto' => $nuevo_presupuesto
                    ];
                }

                // Retornar JSON
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        }

        public function GuardarPresupuestoCompleto()
        {
            if (!$this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setBody('Acceso no autorizado');
            }

            // Obtener datos JSON enviados
            $json = $this->request->getBody();
            $data = json_decode($json, true);

            if (!$data) {
                return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
            }

            // Modelos
            $modelPresupuesto           = model('Presupuesto_Model');
            $modelServicioPresupuesto  = model('Servicio_Presupuesto_Model');
            $modelRepuestoPresupuesto  = model('Repuesto_Presupuesto_Model');

            $presupuestoData = $data['presupuesto'] ?? null;
            $servicios       = $data['servicios'] ?? [];
            $repuestos       = $data['repuestos'] ?? [];

            if (!$presupuestoData) {
                return $this->response->setJSON(['success' => false, 'message' => 'Falta información del presupuesto']);
            }

            $idPresupuesto = $presupuestoData['id'] ?? null;

            // CREAR O ACTUALIZAR
            if ($idPresupuesto) {
                // EDITAR: Verificamos que exista
                $existe = $modelPresupuesto->find($idPresupuesto);
                if (!$existe) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Presupuesto no encontrado']);
                }

                if(isset($presupuestoData['kms'])){
                    // Actualizamos total y estado
                    $modelPresupuesto->update($idPresupuesto, [
                        'total'  => $presupuestoData['total'],
                        'kms'    => $presupuestoData['kms'],
                        'estado' => 1,
                    ]);
                }else{
                    // Actualizamos total y estado
                    $modelPresupuesto->update($idPresupuesto, [
                        'total'  => $presupuestoData['total'],
                        'estado' => 1,
                    ]);
                }
                
            } else {
                // CREAR NUEVO
                $modelPresupuesto->insert([
                    'total'      => $presupuestoData['total'],
                    'estado'     => 1,
                    'kms'    => $presupuestoData['kms'],
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $idPresupuesto = $modelPresupuesto->getInsertID();
            }

            // 🧹 LIMPIAMOS los datos antiguos (si es una edición, asegura integridad)
            $modelServicioPresupuesto->where('id_presupuesto', $idPresupuesto)->delete();
            $modelRepuestoPresupuesto->where('id_presupuesto', $idPresupuesto)->delete();

            

            // 🔄 AGREGAMOS los servicios actuales
            foreach ($servicios as $serv) {
                /* echo "cantidad_servicio:".$serv['cantidad']."<br>"; */
                $modelServicioPresupuesto->insert([
                    'id_presupuesto' => $idPresupuesto,
                    'id_servicio'    => $serv['id_servicio'],
                    'precio'         => $serv['precio'],
                    'cantidad'       => $serv['cantidad'],
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }

            // 🔄 AGREGAMOS los repuestos actuales
            foreach ($repuestos as $rep) {
                $modelRepuestoPresupuesto->insert([
                    'id_presupuesto' => $idPresupuesto,
                    'id_repuesto'    => $rep['id_repuesto'],
                    'cantidad'       => $rep['cantidad'],
                    'precio_unitario'=> $rep['precio'],
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }

            return $this->response->setJSON([
                'success'        => true,
                'id_presupuesto' => $idPresupuesto
            ]);
        }


       public function DesecharPresupuesto()
        {
            if (!$this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
            }

            $id_presupuesto = $this->request->getPost('id_presupuesto');

            if (!$id_presupuesto) {
                return $this->response->setJSON(['success' => false, 'message' => 'ID no proporcionado']);
            }

            $model = model('Presupuesto_Model');

            $presupuesto = $model->find($id_presupuesto);

            if (!$presupuesto) {
                return $this->response->setJSON(['success' => false, 'message' => 'Presupuesto no encontrado']);
            }

            $model->update($id_presupuesto, ['estado' => 3]);

            return $this->response->setJSON(['success' => true]);
        }
 
       public function DescargarPDF($id_presupuesto)
        {
            $modelPresupuesto = model('Presupuesto_Model');
            $modelDatosTaller = model('Datos_Taller_Model');

            $dataPresupuesto = $modelPresupuesto->getDetalleCompleto($id_presupuesto);
           

            $datosTaller = $modelDatosTaller->first(); // Asume que hay solo un registro

            // Instanciar Dompdf
            $dompdfHelper = new \App\Libraries\DompdfHelper();
            $dompdf = $dompdfHelper->getInstance();

            // Pasar ambos sets de datos a la vista
            $html = view('pdf/presupuesto', [
                'presupuesto' => $dataPresupuesto,
                'taller' => $datosTaller
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Verifica si se debe forzar la descarga
            $download = $this->request->getGet('download'); // ?download=1 en la URL

            // Forzar descarga si ?download=1, si no mostrar en navegador
            $dompdf->stream("presupuesto_{$id_presupuesto}.pdf", [
                "Attachment" => $download ? true : false
            ]);
        }

        //Oculta precio del presupuesto en listas
        function ocultarPreciosSiNoTienePermiso(array $presupuestos, int $permisoId): array
        {
            if ($permisoId !== 1) {
                foreach ($presupuestos as &$p) {
                    if (isset($p['total'])) {
                        $p['total'] = 0;
                    }
                }
            }

            return $presupuestos;
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