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

    public function GuardarVenta()
    {
        $request = service('request');

        // 🔹 Datos de la venta
        $dataVenta = [
            'id_cliente'        => $request->getPost('id_cliente') ?: null, // Cliente opcional
            'tipo_documento'    => $request->getPost('tipo_documento'),
            'numero_documento'  => $request->getPost('numero_documento'),
            'neto'              => $request->getPost('neto'),
            'iva'               => $request->getPost('iva'),
            'total'             => $request->getPost('total'),
            'estado'            => 1
        ];

        $detalle = $request->getPost('detalle');

        // 🔴 Validación básica
        if (empty($dataVenta['tipo_documento']) || empty($detalle)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Datos incompletos'
            ]);
        }

        try {

            // 🔹 Modelos
            $ventaModel    = new \App\Models\Venta_Model();
            $detalleModel  = new \App\Models\Detalle_Venta_Model();
            $repuestoModel = new \App\Models\Repuesto_Model();
            $modelAjustes  = new \App\Models\Ajustes_Stock_Model();

            // 🔹 Guardar venta
            $idVenta = $ventaModel->insert($dataVenta);

            if (!$idVenta) {
                throw new \Exception('No se pudo registrar la venta.');
            }

            // 🔹 Guardar detalle y actualizar stock
            foreach ($detalle as $item) {

                $idRepuesto = $item['id_repuesto'];
                $cantidad   = (int)$item['cantidad'];
                $precio     = $item['precio_unitario'];

                // 🔹 Obtener repuesto
                $repuesto = $repuestoModel->find($idRepuesto);

                if (!$repuesto) {
                    throw new \Exception("Repuesto no encontrado (ID: $idRepuesto)");
                }

                $stockActual = (int)$repuesto['stock'];


                $stockNuevo = $stockActual - $cantidad;

                // 🔹 Insertar detalle
                $detalleModel->insert([
                    'id_venta'         => $idVenta,
                    'id_repuesto'      => $idRepuesto,
                    'cantidad'         => $cantidad,
                    'precio_unitario'  => $precio,
                    'subtotal'         => $cantidad * $precio
                ]);

                // 🔹 Actualizar stock
                $repuestoModel->update($idRepuesto, [
                    'stock' => $stockNuevo
                ]);

                // 🔹 Registrar ajuste de stock
                $modelAjustes->insert([
                    'id_repuesto'  => $idRepuesto,
                    'id_usuario'   => session()->get('id_usuario'),
                    'stock_actual' => $stockActual,
                    'stock_nuevo'  => $stockNuevo,
                    'motivo'       => 'Venta #' . $idVenta
                ]);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'msg' => 'Venta guardada correctamente'
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Error al guardar la venta',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function GetVentaDetalle()
    {
        // 🔹 Obtener ID desde POST
        $id_venta = $this->request->getPost('id_venta');

        // 🔴 Validación básica
        if (empty($id_venta)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'ID de la venta no proporcionado'
            ]);
        }

        // 🔹 Instanciar modelo
        $model = new \App\Models\Venta_Model();

        // 🔹 Llamar al método del modelo
        $data = $model->getVentaConDetalle($id_venta);

        // 🔴 Validar si la venta existe
        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Venta no encontrada'
            ]);
        }

        // 🔹 Retornar JSONout
        return $this->response->setJSON($data);
    }

    public function AnularVenta()
    {
        $request = service('request');
        $idVenta = $request->getPost('id_venta');

        if (empty($idVenta)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'ID de venta requerido'
            ]);
        }

        try {

            // 🔹 Modelos
            $ventaModel    = new \App\Models\Venta_Model();
            $detalleModel  = new \App\Models\Detalle_Venta_Model();
            $repuestoModel = new \App\Models\Repuesto_Model();
            $modelAjustes  = new \App\Models\Ajustes_Stock_Model();

            // 🔹 Obtener venta
            $venta = $ventaModel->find($idVenta);

            if (!$venta) {
                throw new \Exception("Venta no encontrada");
            }

            if ((int)$venta['estado'] === 2) {
                throw new \Exception("La venta ya está anulada");
            }

            // 🔹 Obtener detalle
            $detalle = $detalleModel->where('id_venta', $idVenta)->findAll();

            if (empty($detalle)) {
                throw new \Exception("La venta no tiene detalle");
            }

            // 🔹 Revertir stock + registrar ajustes
            foreach ($detalle as $item) {

                $idRepuesto = $item['id_repuesto'];
                $cantidad   = (int)$item['cantidad'];

                // 🔹 Obtener repuesto
                $repuesto = $repuestoModel->find($idRepuesto);

                if (!$repuesto) {
                    throw new \Exception("Repuesto no encontrado (ID: $idRepuesto)");
                }

                $stockActual = (int)$repuesto['stock'];
                $stockNuevo  = $stockActual + $cantidad;

                // 🔹 Actualizar stock
                $repuestoModel->update($idRepuesto, [
                    'stock' => $stockNuevo
                ]);

                // 🔹 Registrar ajuste
                $modelAjustes->insert([
                    'id_repuesto'  => $idRepuesto,
                    'id_usuario'   => session()->get('id_usuario'),
                    'stock_actual' => $stockActual,
                    'stock_nuevo'  => $stockNuevo,
                    'motivo'       => 'Anulación Venta #' . $idVenta
                ]);
            }

            // 🔹 Marcar venta como anulada
            $ventaModel->update($idVenta, [
                'estado' => 2,
                'fecha_anulacion' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status' => 'ok',
                'msg' => 'Venta anulada correctamente'
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }
}    