<?php

namespace App\Controllers;

class Reportes extends BaseController{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }
    
     public function Transferencias(){
         return view('main/reportes/transferencias');
     }

     public function Dashboard(){
         return view('main/reportes/dashboard');
     }

     public function GetTransferencias(){
       $modelo_pago_os = new \App\Models\Pago_Os_Model();
       if ($this->request->isAJAX()) {
            $fecha_inicio = $this->request->getPost('fecha_inicio');
            $fecha_fin = $this->request->getPost('fecha_fin');
            $response = $modelo_pago_os->obtenerTransferencias($fecha_inicio, $fecha_fin);           
       }else{
           $response = [
               'status' => 'error',
               'message' => 'Error al obtener las transferencias'   
           ];
       }     
       header('Content-Type: application/json');
       echo json_encode($response);      
     }

    public function Get_kpis()
    {
        $kpiModel = new \App\Models\Kpi_Model();

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400, 'Bad Request');
        }

        // Obtener rango de fechas desde el frontend (opcional, se usa en KPIs globales)
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin    = $this->request->getPost('fecha_fin');

        // 1️ KPIs globales filtrados por rango de fechas
        $kpis = $kpiModel->getKpis($fecha_inicio, $fecha_fin);

        // 2️ Evolución diaria de los últimos 30 días (línea)
        $evolucion = $kpiModel->getEvolucionUltimos30Dias();

        //3 Traemos top3 de repuestos y servicios
        $top3 = $kpiModel->getTopServiciosYRepuestos();

        //4 Top3 de repuestos y servicios por ingresos
        $top3Ingresos = $kpiModel->getTopServiciosYRepuestosIngresos();

        // 4 Construimos la respuesta JSON combinando todo
        $data = [
            'kpis' => $kpis,
            'evolucion' => $evolucion,
            'top3' => $top3,
            'top3Ingresos' => $top3Ingresos
        ];

        return $this->response->setJSON($data);
    }


}     