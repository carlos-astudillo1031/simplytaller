<?php

namespace App\Controllers;

class Agenda extends BaseController{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }
    
     public function Index(){
         return view('main/agenda/index');
     }   

     public function GetCitas()
     {
        $modelo_agenda = new \App\Models\Agenda_Model();
        $citas = $modelo_agenda->getCitasYOrdenes();      
        return $this->response->setJSON($citas);
     }

     public function GuardarCita()
     {
        $modelo_agenda = new \App\Models\Agenda_Model();

        if ($this->request->isAJAX()) {  
            // Variables
            $id_cliente   = $this->request->getPost('id_cliente');
            $marca        = $this->request->getPost('marca');
            $modelo       = $this->request->getPost('modelo');
            $fechaCita    = $this->request->getPost('fechaCita');
            $horaCitaStr  = $this->request->getPost('horaCita');
            $descripcion  = $this->request->getPost('descripcion');

            // Crear DateTime desde el string
            $horaCita = \DateTime::createFromFormat('H:i', $horaCitaStr);

            // Crear otra variable que sea 1 hora después
            $horaCitaMasUna = clone $horaCita;
            $horaCitaMasUna->modify('+1 hour');

            // Formatear como string TIME para la base de datos
            $hora_inicio = $horaCita->format('H:i:s');       // ej: "08:00:00"
            $hora_fin    = $horaCitaMasUna->format('H:i:s'); // ej: "09:00:00"

            // Arreglo de datos
            $data = [
                'id_cliente' => $id_cliente,
                'id_marca'   => $marca,
                'id_modelo'  => $modelo,
                'fecha'      => $fechaCita,
                'hora_inicio'=> $hora_inicio,
                'hora_fin'   => $hora_fin,
                'descripcion'=> $descripcion
            ];

            // Guardamos datos
            $modelo_agenda->insert($data);

            // Respuesta AJAX
            return $this->response->setJSON(['success' => true]);
        }    
    }

    public function ActualizarCita()
     {
        $modelo_agenda = new \App\Models\Agenda_Model();

        if ($this->request->isAJAX()) {  
            // Variables
            $id_cliente   = $this->request->getPost('id');           
            $fechaCita    = $this->request->getPost('fecha');
            $horaCitaStr  = $this->request->getPost('hora');            

            // Crear DateTime desde el string
            $horaCita = \DateTime::createFromFormat('H:i', $horaCitaStr);

            // Crear otra variable que sea 1 hora después
            $horaCitaMasUna = clone $horaCita;
            $horaCitaMasUna->modify('+1 hour');

            // Formatear como string TIME para la base de datos
            $hora_inicio = $horaCita->format('H:i:s');       // ej: "08:00:00"
            $hora_fin    = $horaCitaMasUna->format('H:i:s'); // ej: "09:00:00"

            // Arreglo de datos
            $data = [                            
                'fecha'      => $fechaCita,
                'hora_inicio'=> $hora_inicio,
                'hora_fin'   => $hora_fin
            ];

            // Guardamos datos
            $modelo_agenda->update($id_cliente, $data);

            // Respuesta AJAX
            return $this->response->setJSON(['success' => true]);
        }    
    }


    public function EliminarCita(){
        $modelo_agenda = new \App\Models\Agenda_Model();

        if ($this->request->isAJAX()) {  
            // Variables
            $id_cliente   = $this->request->getPost('id');           

            // Eliminamos los datos
            $modelo_agenda->delete($id_cliente);

            // Respuesta AJAX
            return $this->response->setJSON(['success' => true]);
        }
    }

}