<?php

namespace App\Controllers;


class Compra extends BaseController
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
        return view('main/compra/lista');
    }

    public function GetCompras()
    {
        $request = service('request');

        $fechaInicio = $request->getGet('fecha_inicio');
        $fechaFin    = $request->getGet('fecha_fin');

        $model = model('Compra_Model');
        $compras = $model->getCompras($fechaInicio, $fechaFin);

        header('Content-Type: application/json');
        echo json_encode($compras);
    }

    public function GetCompraDetalle()
    {
        // 🔹 Obtener ID desde POST
        $id_compra = $this->request->getPost('id_compra');

        // 🔹 Instanciar modelo
        $model = new \App\Models\Compra_Model();

        // 🔹 Llamar al método
        $data = $model->getCompraConDetalle($id_compra);

        // 🔹 Retornar JSON
        return $this->response->setJSON($data);
    }

    public function GuardarCompra()
    {
        $request = service('request');

        // 🔹 Datos
        $dataCompra = [
            'id_proveedor'   => $request->getPost('id_proveedor'),
            'fecha'          => $request->getPost('fecha'),
            'numero_factura' => $request->getPost('numero_factura'),
            'total_neto'     => $request->getPost('total_neto'),
            'total_iva'      => $request->getPost('total_iva'),
            'total'          => $request->getPost('total'),
            'estado'         => 1
        ];

        $detalle = $request->getPost('detalle');

        // 🔴 Validación básica
        if (empty($dataCompra['id_proveedor']) || empty($dataCompra['fecha']) || empty($detalle)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Datos incompletos'
            ]);
        }

        try {

            // 🔹 Modelos
            $compraModel   = new \App\Models\Compra_Model();
            $detalleModel  = new \App\Models\Detalle_Compra_Model();
            $repuestoModel = new \App\Models\Repuesto_Model();
            $modelAjustes  = new \App\Models\Ajustes_Stock_Model();

            // 🔹 Guardar compra
            $idCompra = $compraModel->insert($dataCompra);

            // 🔹 Guardar detalle + stock + ajustes
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
                $stockNuevo  = $stockActual + $cantidad;

                // 🔹 Insertar detalle
                $detalleModel->insert([
                    'id_compra'        => $idCompra,
                    'id_repuesto'      => $idRepuesto,
                    'cantidad'         => $cantidad,
                    'precio_unitario'  => $precio,
                    'subtotal'         => $cantidad * $precio
                ]);

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
                    'motivo'       => 'Compra #' . $idCompra
                ]);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'msg' => 'Compra guardada correctamente'
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Error al guardar',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function AnularCompra()
    {
        $request = service('request');
        $idCompra = $request->getPost('id_compra');

        if (empty($idCompra)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'ID de compra requerido'
            ]);
        }

        try {

            // 🔹 Modelos
            $compraModel   = new \App\Models\Compra_Model();
            $detalleModel  = new \App\Models\Detalle_Compra_Model();
            $repuestoModel = new \App\Models\Repuesto_Model();
            $modelAjustes  = new \App\Models\Ajustes_Stock_Model();

            // 🔹 Obtener compra
            $compra = $compraModel->find($idCompra);

            if (!$compra) {
                throw new \Exception("Compra no encontrada");
            }

            if ((int)$compra['estado'] === 2) {
                throw new \Exception("La compra ya está anulada");
            }

            // 🔹 Obtener detalle
            $detalle = $detalleModel->where('id_compra', $idCompra)->findAll();

            if (empty($detalle)) {
                throw new \Exception("La compra no tiene detalle");
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
                $stockNuevo  = $stockActual - $cantidad;


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
                    'motivo'       => 'Anulación Compra #' . $idCompra
                ]);
            }

            // 🔹 Marcar compra como anulada
            $compraModel->update($idCompra, [
                'estado' => 2,
                'fecha_anulacion' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status' => 'ok',
                'msg' => 'Compra anulada correctamente'
            ]);

        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }

}    