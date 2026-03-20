<?php

namespace App\Models;

use CodeIgniter\Model;

class Kpi_Model extends Base_Empresa_Model
{
    protected $DBGroup = 'default';

    public function getKpis($fechaInicio = null, $fechaFin = null)
    {
         $db = $this->db;

        // Si no se pasan fechas, se consideran todos los registros
        $filtroFechas = '';
        $params = [];

        if ($fechaInicio && $fechaFin) {
            $filtroFechas = "AND created_at >= ? AND created_at < DATE_ADD(?, INTERVAL 1 DAY)";
            $params = [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin];
        }

        $sql = "
            SELECT
                (SELECT COUNT(*) 
                FROM presupuesto 
                WHERE deleted_at IS NULL
                $filtroFechas) AS presupuestos,

                (SELECT COUNT(*) 
                FROM presupuesto 
                WHERE estado = 2 
                    AND deleted_at IS NULL
                    $filtroFechas) AS aprobados,

                (SELECT COUNT(*) 
                FROM orden_servicio 
                WHERE estado = 6 
                    AND deleted_at IS NULL
                    $filtroFechas) AS ordenes,

                (SELECT COALESCE(SUM(total), 0) 
                FROM orden_servicio 
                WHERE estado = 6 
                    AND deleted_at IS NULL
                    $filtroFechas) AS ingresos
        ";

        $query = $db->query($sql, $params);

        return $query->getRowArray();
    }

    public function getEvolucionUltimos30Dias()
    {
         $db = $this->db;

        $fechas = [];
        $presupuestos = [];
        $ordenes = [];

        // Recorremos los últimos 30 días
        for ($i = 29; $i >= 0; $i--) {
            $fecha = date('Y-m-d', strtotime("-{$i} days"));

            // Presupuestos del día (omitimos desechados, estado != 6)
            $presupuestoQuery = $db->table('presupuesto')
                ->where('deleted_at', null)
                ->where('estado !=', 6)
                ->where('DATE(created_at)', $fecha)
                ->countAllResults();

            // Ordenes del día (omitimos anuladas, estado != 5)
            $ordenQuery = $db->table('orden_servicio')
                ->where('deleted_at', null)
                ->where('estado !=', 5)
                ->where('DATE(created_at)', $fecha)
                ->countAllResults();

            $fechas[] = $fecha;
            $presupuestos[] = $presupuestoQuery;
            $ordenes[] = $ordenQuery;
        }

        return [
            'fechas' => $fechas,
            'presupuestos' => $presupuestos,
            'ordenes' => $ordenes
        ];
    }

    public function getTopServiciosYRepuestos($limite = 5)
    {
         $db = $this->db;

        // 🔹 TOP SERVICIOS
        $topServicios = $db->table('servicio_orden_servicio sos')
            ->select('s.nombre, SUM(sos.cantidad) as total')
            ->join('servicio s', 's.id = sos.id_servicio')
            ->groupBy('sos.id_servicio')
            ->orderBy('total', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();

        // 🔹 TOP REPUESTOS
        $topRepuestos = $db->table('repuesto_orden_servicio ros')
            ->select('r.nombre, SUM(ros.cantidad) as total')
            ->join('repuesto r', 'r.id = ros.id_repuesto')
            ->groupBy('ros.id_repuesto')
            ->orderBy('total', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();

        return [
            'topServicios' => $topServicios,
            'topRepuestos' => $topRepuestos
        ];
    }

    public function getTopServiciosYRepuestosIngresos($limite = 5)
    {
         $db = $this->db;

        // 🔹 TOP SERVICIOS POR INGRESOS
        $topServiciosIngresos = $db->table('servicio_orden_servicio sos')
            ->select('s.nombre as nombre, SUM(sos.precio * sos.cantidad) as total')
            ->join('servicio s', 's.id = sos.id_servicio')
            ->join('orden_servicio os', 'os.id = sos.id_orden_servicio')
            ->where('os.estado !=', 5) // omitimos ordenes anuladas
            ->groupBy('sos.id_servicio, s.nombre')
            ->orderBy('total', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();

        // 🔹 TOP REPUESTOS POR INGRESOS
        $topRepuestosIngresos = $db->table('repuesto_orden_servicio ros')
            ->select('r.nombre as nombre, SUM(ros.precio * ros.cantidad) as total')
            ->join('repuesto r', 'r.id = ros.id_repuesto')
            ->join('orden_servicio os', 'os.id = ros.id_orden_servicio')
            ->where('os.estado !=', 5)
            ->groupBy('ros.id_repuesto, r.nombre')
            ->orderBy('total', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();

        return [
            'topServiciosIngresos' => $topServiciosIngresos,
            'topRepuestosIngresos' => $topRepuestosIngresos
        ];
    }



}
