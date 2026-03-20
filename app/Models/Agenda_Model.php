<?php

namespace App\Models;

use CodeIgniter\Model;

class Agenda_Model extends Base_Empresa_Model
{
    protected $table = 'agenda';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_cliente', 'id_marca', 'id_modelo',
        'fecha', 'hora_inicio', 'hora_fin', 'descripcion',
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtiene las citas y ordenes para entregar con nombre de cliente, marca y modelo
     */
    public function getCitasYOrdenes()
    {
        $citas = $this->select('agenda.id, agenda.fecha, agenda.hora_inicio, agenda.hora_fin,
                                cliente.nombre_cliente AS cliente_nombre,
                                marca.nombre AS marca_nombre,
                                modelo.nombre AS modelo_nombre,
                                agenda.descripcion')
                    ->join('cliente', 'cliente.id_cliente = agenda.id_cliente')
                    ->join('marca', 'marca.id = agenda.id_marca', 'left')
                    ->join('modelo', 'modelo.id = agenda.id_modelo', 'left')
                    ->findAll();

        // Traemos órdenes terminadas (estado = 4)
        $ordenes_model = new \App\Models\Orden_Servicio_Model();
        $ordenes = $ordenes_model->select(
                            'orden_servicio.id,
                            orden_servicio.fecha_estimada_entrega AS fecha,
                            cliente.nombre_cliente AS cliente_nombre,
                            COALESCE(mv.nombre, mb.nombre) AS marca_nombre,
                            COALESCE(modv.nombre, modb.nombre) AS modelo_nombre'
                        )
                        ->join('cliente', 'cliente.id_cliente = orden_servicio.id_cliente')
                        ->join('vehiculo', 'vehiculo.id = orden_servicio.id_vehiculo', 'left')
                        ->join('bomba', 'bomba.id = orden_servicio.id_bomba', 'left')
                        ->join('marca mv', 'mv.id = vehiculo.id_marca', 'left')
                        ->join('modelo modv', 'modv.id = vehiculo.id_modelo', 'left')
                        ->join('marca mb', 'mb.id = bomba.id_marca', 'left')
                        ->join('modelo modb', 'modb.id = bomba.id_modelo', 'left')
                        ->whereIn('orden_servicio.estado', [4, 6])
                        ->findAll();

        $result = [];

        // Formateamos las citas como eventos de FullCalendar
        foreach ($citas as $c) {
            $result[] = [
                'id' => $c['id'], // prefijo para diferenciar
                'tipo'=>'cita',
                'title' =>  '<i class="fas fa-calendar-check"></i> '
                            .$c['cliente_nombre']
                            . ($c['marca_nombre'] ? " - ".$c['marca_nombre'] : '')
                            . ($c['modelo_nombre'] ? " ".$c['modelo_nombre'] : '')
                            . ($c['descripcion'] ? " - ".$c['descripcion'] : ''),
                'start' => $c['fecha'].'T'.$c['hora_inicio'],
                'end'   => $c['fecha'].'T'.$c['hora_fin'],
                'descripcion' => $c['descripcion'],
                'color' => '#1E90FF'
            ];
        }

        $horasPorDia = []; // clave = fecha, valor = última hora usada

        foreach ($ordenes as $o) {
            $fecha = $o['fecha'];

            // Si no hay hora iniciada para este día, empezamos a las 08:00
            if (!isset($horasPorDia[$fecha])) {
                $horasPorDia[$fecha] = 8;
            }

            $horaInicio = $horasPorDia[$fecha];
            $horaFin = $horaInicio + 1;

            $horaStr = str_pad($horaInicio, 2, '0', STR_PAD_LEFT) . ':00';
            $horaFinStr = str_pad($horaFin, 2, '0', STR_PAD_LEFT) . ':00';

            $result[] = [
                'id' => $o['id'], // prefijo OS
                'tipo' => 'os',
                'title' =>  '<i class="fas fa-wrench"></i> '
                            .$o['cliente_nombre']
                            . ($o['marca_nombre'] ? " - ".$o['marca_nombre'] : '')
                            . ($o['modelo_nombre'] ? " ".$o['modelo_nombre'] : ''),
                'start' => $fecha.'T'.$horaStr,
                'end'   => $fecha.'T'.$horaFinStr,
                'descripcion' => '',
                'color' => '#05a31aff'
            ];

            // Incrementamos la hora para la siguiente OS de este día
            $horasPorDia[$fecha]++;

            // Limite máximo por día
            if ($horasPorDia[$fecha] >= 18) {
                $horasPorDia[$fecha] = 8;
            }
        }


        return $result;
    }
}    

