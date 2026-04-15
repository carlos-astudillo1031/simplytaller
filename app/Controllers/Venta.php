<?php

namespace App\Controllers;


class Venta extends BaseController
{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }

    public function lista() {
        return view('main/venta/lista');
    }

    public function GetVentas()
    {
        $request = service('request');

        $fechaInicio = $request->getGet('fecha_inicio');
        $fechaFin    = $request->getGet('fecha_fin');

        $model = model('Venta_Model');
        $ventas = $model->getVentas($fechaInicio, $fechaFin);

        return $this->response->setJSON($ventas);
    }
}    