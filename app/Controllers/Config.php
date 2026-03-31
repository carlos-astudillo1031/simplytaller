<?php

namespace App\Controllers;

class Config extends BaseController
{

    public function __construct()
    {
        helper('session');
        $dbName = session()->get('db_nombre');
        if ($dbName) {
            \App\Libraries\DBManager::init($dbName);
        }
    }
    

    #####################CLIENTES######################

    #Muestra Vista Clientes
    public function Clientes(): string {
       // if(session()->is_logged){   
            return view('config/clientes');
          // }      
    }

    #Obtiene Lista de Clientes
    public function GetClientes(){
        // if(session()->is_logged){  
             $model_clientes= model('Cliente_Model');   
             $clientes = $model_clientes->findAll();
             header('Content-Type: application/json');
             echo json_encode($clientes);
           //}  
    }

    #Obtiene Registro de un cliente por su id
    public function GetRegistroCliente(){
        //if(session()->is_logged){  
            if ($this->request->isAJAX()) {  
             $model_clientes= model('Cliente_Model');   
             $id_cliente = service('request')->getPost('id_cliente');
             $cliente = $model_clientes->find($id_cliente);
             header('Content-Type: application/json');
             echo json_encode($cliente);
            }//Cierra ValidacionAjax 
         //}  
    }

   public function GetClientePorRut(){
        if ($this->request->isAJAX()) {  
            $model_clientes = model('Cliente_Model');   
            $rut_cliente = service('request')->getPost('rut_cliente');
        
            // Buscar por RUT
            $cliente = $model_clientes->where('rut_cliente', $rut_cliente)->first();

            header('Content-Type: application/json');
            if ($cliente == null) {
                echo json_encode(false);
            } else {
                echo json_encode($cliente);                
            }            
        }
   }

    

    #Guarda los clientes
    public function GuardarCliente(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                //Instancio el modelo
                $model_cliente  = model('Cliente_Model');
                //Variables para trabajar

                $nombre_cliente = service('request')->getPost('nombre_cliente');
                $rut_cliente = service('request')->getPost('rut_cliente');
                $email_cliente = service('request')->getPost('email_cliente');
                $telefono_cliente = service('request')->getPost('telefono_cliente');
                $direccion_cliente = service('request')->getPost('direccion_cliente');
 

                // Construye el nuevo array PHP
                $data = [
                    'nombre_cliente' => $nombre_cliente,
                    'rut_cliente' => $rut_cliente,
                    'telefono_cliente' => $telefono_cliente                
                ];

                if (!empty($direccion_cliente)) {
                    $data['direccion_cliente'] = $direccion_cliente;
                }

                if (!empty($email_cliente)) {
                    $data['email_cliente'] = $email_cliente;
                }
                //Guardando detalle del pedido                
                $id_insertado = $model_cliente->insert($data);

                $response =  ['success'=> true, 'id_cliente' => $id_insertado];
                header('Content-Type: application/json');
                echo json_encode($response);
             }//Cierra ajax
        // }//Cierra validacion sesion     
    }

    #Actualiza los datos del  cliente
    public function UpdateCliente(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                //Instancio el modelo
                $model_cliente  = model('Cliente_Model');
                //Variables para trabajar

                $nombre_cliente = service('request')->getPost('nombre_cliente');
                $rut_cliente = service('request')->getPost('rut_cliente');
                $email_cliente = service('request')->getPost('email_cliente');
                $telefono_cliente = service('request')->getPost('telefono_cliente');
                $direccion_cliente = service('request')->getPost('direccion_cliente');
                $id_cliente = service('request')->getPost('id_cliente');
               

                // Construye el nuevo array PHP
                $data = [
                    'rut_cliente' => $rut_cliente,
                    'nombre_cliente' => $nombre_cliente,
                    'rut_cliente' => $rut_cliente,
                    'telefono_cliente' => $telefono_cliente
                ];

                if (!empty($direccion_cliente)) {
                    $data['direccion_cliente'] = $direccion_cliente;
                }
                
                if (!empty($email_cliente)) {
                    $data['email_cliente'] = $email_cliente;
                }
                //Guardando detalle del cliente                
                $model_cliente->update($id_cliente, $data);


                $response =  ['success'=> true];
                header('Content-Type: application/json');
                echo json_encode($response);
             }//Cierra ajax
        // }//Cierra validacion sesion     
    }


     //Actualiza items del detalle pedido 
    public function EliminaCliente(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                 //Instancio el modelo
                 $model_cliente  = model('Cliente_Model');  
                 //Variables para trabajar
                 $id_cliente = service('request')->getPost('id_cliente');

                 
                 //Elimina el registro 
                 $model_cliente->delete($id_cliente);    
                 

                 $response =  ['success'=> true,'id_cliente'=>$id_cliente];   
                 header('Content-Type: application/json');
                 echo json_encode($response);  
              }//Cierra validación Ajax      
         //}//Cierra if valida sesión      
    }


    #####################SERVICIOS######################

    // Muestra vista de Servicios
    public function Servicios(): string {
        return view('config/servicios');
    }

    // Obtiene lista de todos los servicios
    public function GetServicios() {
        if ($this->request->isAJAX()) {
            $model_servicio = model('Servicio_Model');
            $servicios = $model_servicio->findAll();
            
            header('Content-Type: application/json');
            echo json_encode($servicios);
        }
    }

    // Obtiene Registro de un servicio por su id
    public function GetRegistroServicio() {
        if ($this->request->isAJAX()) {  
            $model_servicio = model('Servicio_Model');   
            $id_servicio = service('request')->getPost('id_servicio');
            $servicio = $model_servicio->find($id_servicio);

            header('Content-Type: application/json');
            echo json_encode($servicio);
        }
    }


    // Guarda un nuevo servicio
    public function GuardarServicio() {
        if ($this->request->isAJAX()) {
            $model_servicio = model('Servicio_Model');

            $nombre = service('request')->getPost('nombre');
            $precio = service('request')->getPost('precio');

            $data = [
                'nombre' => $nombre,
                'precio' => $precio
            ];

            $model_servicio->save($data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Actualiza un servicio existente
    public function UpdateServicio() {
        if ($this->request->isAJAX()) {
            $model_servicio = model('Servicio_Model');

            $id_servicio = service('request')->getPost('id_servicio');
            $nombre = service('request')->getPost('nombre');
            $precio = service('request')->getPost('precio');

            $data = [
                'nombre' => $nombre,
                'precio' => $precio
            ];

            $model_servicio->update($id_servicio, $data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Elimina un servicio
    public function EliminarServicio() {
        if ($this->request->isAJAX()) {
            $model_servicio = model('Servicio_Model');

            $id_servicio = service('request')->getPost('id_servicio');

            $model_servicio->delete($id_servicio);

            $response = ['success' => true, 'id_servicio' => $id_servicio];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }   

    #####################MARCAS######################

    // Muestra vista de Marcas
    public function Marcas(): string {
        return view('config/marcas'); // Asegúrate de que el archivo exista en app/Views/config/marca.php
    }

    // Obtiene lista de todas las marcas
    public function GetMarcas() {
        if ($this->request->isAJAX()) {
            $model = model('Marca_Model');
            $marcas = $model->findAll();

            return $this->response->setJSON($marcas);
        }
    }

    // Obtiene una marca por su ID
    public function GetRegistroMarca() {
        if ($this->request->isAJAX()) {
            $model = model('Marca_Model');
            $id = $this->request->getPost('id');
            $marca = $model->find($id);

            return $this->response->setJSON($marca);
        }
    }

    // Guarda una nueva marca
    public function GuardarMarca() {
        if ($this->request->isAJAX()) {
            $model = model('Marca_Model');

            $data = [
                'nombre' => $this->request->getPost('nombre')
            ];

            $model->save($data);
            return $this->response->setJSON(['success' => true]);
        }
    }

    // Actualiza una marca existente
    public function ActualizarMarca() {
        if ($this->request->isAJAX()) {
            $model = model('Marca_Model');

            $id = $this->request->getPost('id');
            $nombre = $this->request->getPost('nombre');

            $data = ['nombre' => $nombre];
            $model->update($id, $data);

            return $this->response->setJSON(['success' => true]);
        }
    }

    // Elimina una marca
    public function EliminarMarca() {
        if ($this->request->isAJAX()) {
            $model = model('Marca_Model');

            $id = $this->request->getPost('id');
            $model->delete($id);

            return $this->response->setJSON(['success' => true, 'id' => $id]);
        }
    }


    ##################### REPUESTOS ######################

    // Muestra vista de Repuestos
    public function Repuestos(): string {
        return view('config/repuestos');
    }

    // Obtiene lista de todos los repuestos
    public function GetRepuestos() {
        if ($this->request->isAJAX()) {
            $model_repuesto = model('Repuesto_Model');
            $repuestos = $model_repuesto->getRepuestosConUbicacion();
            header('Content-Type: application/json');
            echo json_encode($repuestos);
        }
    }

    // Obtiene registro de un repuesto por su ID
    public function GetRegistroRepuesto() {
        if ($this->request->isAJAX()) {  
            $model_repuesto = model('Repuesto_Model');   
            $id_repuesto = service('request')->getPost('id_repuesto');
            $repuesto = $model_repuesto->find($id_repuesto);

            header('Content-Type: application/json');
            echo json_encode($repuesto);
        }
    }

    // Guarda un nuevo repuesto
    public function GuardarRepuesto() {
        if ($this->request->isAJAX()) {
            $model_repuesto = model('Repuesto_Model');

            $codigo = service('request')->getPost('codigo');
            $nombre = service('request')->getPost('nombre');
            $precio = service('request')->getPost('precio');
            $stock = service('request')->getPost('stock');
            $stock_minimo = service('request')->getPost('stock_minimo');
            $id_ubicacion = service('request')->getPost('id_ubicacion');

            $data = [
                'codigo' => $codigo,
                'nombre' => $nombre,
                'precio' => $precio,
                'stock' => $stock,
                'stock_minimo' => $stock_minimo,
                'id_ubicacion' => $id_ubicacion
            ];

            $model_repuesto->save($data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Actualiza un repuesto existente
    public function UpdateRepuesto() {
        if ($this->request->isAJAX()) {
            $model_repuesto = model('Repuesto_Model');

            $id_repuesto = service('request')->getPost('id_repuesto');
            $codigo = service('request')->getPost('codigo');
            $nombre = service('request')->getPost('nombre');
            $precio = service('request')->getPost('precio');            
            $stock_minimo = service('request')->getPost('stock_minimo');
            $id_ubicacion = service('request')->getPost('id_ubicacion');

            $data = [
                'codigo' => $codigo,
                'nombre' => $nombre,
                'precio' => $precio,                
                'stock_minimo' => $stock_minimo,
                'id_ubicacion' => $id_ubicacion
            ];

            $model_repuesto->update($id_repuesto, $data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Elimina un repuesto
    public function EliminarRepuesto() {
        if ($this->request->isAJAX()) {
            $model_repuesto = model('Repuesto_Model');

            $id_repuesto = service('request')->getPost('id_repuesto');

            $model_repuesto->delete($id_repuesto);

            $response = ['success' => true, 'id_repuesto' => $id_repuesto];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function RegistrarAjusteStockRepuesto() {
        if ($this->request->isAJAX()) {
            $Ajustes_Stock_Model = model('Ajustes_Stock_Model');

            $id_repuesto = service('request')->getPost('id_repuesto');
            $stock_actual = service('request')->getPost('stock_actual');
            $stock_nuevo = service('request')->getPost('stock_nuevo');
            $motivo = service('request')->getPost('motivo');
            $id_usuario = $id_usuario =  session()->get('id_usuario');

            //Registro el ajuste de stock
            $data = [
                'id_repuesto' => $id_repuesto,
                'stock_actual' => $stock_actual,
                'stock_nuevo' => $stock_nuevo,
                'motivo' => $motivo,
                'id_usuario' => $id_usuario
            ];
            $Ajustes_Stock_Model->save($data);

            //Actualizo stock en repuesto
            $model_repuesto = model('Repuesto_Model');  
            $repuesto = $model_repuesto->find($id_repuesto);
            $data_repuesto = [
                'stock' => $stock_nuevo
            ];
            $model_repuesto->update($id_repuesto, $data_repuesto);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }


    ##################### VEHICULOS ######################

    # Muestra Vista Vehículos
    public function Vehiculos(): string {
       // if(session()->is_logged){   
            return view('config/vehiculos');
          // }      
    }

    # Obtiene Lista de Vehículos
    public function GetVehiculos(){
        // if(session()->is_logged){  
             $model_vehiculos = model('Vehiculo_Model');   
             $vehiculos = $model_vehiculos->findAll();
             header('Content-Type: application/json');
             echo json_encode($vehiculos);
           //}  
    }

    # Obtiene Registro de un vehículo por su id
    public function GetRegistroVehiculo(){
        //if(session()->is_logged){  
            if ($this->request->isAJAX()) {  
             $model_vehiculos = model('Vehiculo_Model');   
             $id_vehiculo = service('request')->getPost('id_vehiculo');
             $vehiculo = $model_vehiculos->find($id_vehiculo);
             header('Content-Type: application/json');
             echo json_encode($vehiculo);
            }//Cierra ValidacionAjax 
         //}  
    }

    public function GetVehiculoPorPatente(){
        if ($this->request->isAJAX()) {  
            $model_vehiculos = model('Vehiculo_Model');   
            $patente = service('request')->getPost('patente');

            // Buscar por patente
            $vehiculo = $model_vehiculos->GetVehiculosPorPatente($patente);

            header('Content-Type: application/json');
            if ($vehiculo == null) {
                echo json_encode(false);
            } else {
                echo json_encode($vehiculo);                
            }            
        }
   }

   public function GetVehiculosPorCliente(){
        if ($this->request->isAJAX()) {  
            $model_vehiculos = model('Vehiculo_Model');   
            $id_cliente = service('request')->getPost('id_cliente');

            // Buscar por patente
            $vehiculos = $model_vehiculos->GetVehiculosPorCliente($id_cliente);
            header('Content-Type: application/json');
            if ($vehiculos == null) {
                echo json_encode(false);
            } else {
                echo json_encode($vehiculos);                
            }            
        }
   }


    # Guarda los vehículos
    public function GuardarVehiculo(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                // Instancio el modelo
                $model_vehiculos  = model('Vehiculo_Model');

                // Variables para trabajar
                $patente     = service('request')->getPost('patente');
                $id_marca    = service('request')->getPost('id_marca');
                $id_modelo   = service('request')->getPost('id_modelo');
                $id_cliente  = service('request')->getPost('id_cliente');
                $color       = service('request')->getPost('color');
                $chasis      = service('request')->getPost('chasis');
                $anio        = service('request')->getPost('anio');

                $token = bin2hex(random_bytes(16)); // 32 caracteres hexadecimales


                // Construye el nuevo array PHP
                $data = [
                    'patente'    => $patente,
                    'id_marca'   => $id_marca,
                    'id_modelo'  => $id_modelo,
                    'id_cliente' => $id_cliente,
                    'color'      => $color,
                    'anio'       => $anio,
                    'chasis'     => $chasis,
                    'token'      => $token
                ];

                // Guardando el vehículo
                $model_vehiculos->save($data);

                $response =  ['success'=> true];
                header('Content-Type: application/json');
                echo json_encode($response);
             }//Cierra ajax
        // }//Cierra validacion sesion     
    }

    # Actualiza los datos del vehículo
    public function UpdateVehiculo(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                // Instancio el modelo
                $model_vehiculos  = model('Vehiculo_Model');

                // Variables para trabajar
                $id_vehiculo = service('request')->getPost('id_vehiculo');
                $patente     = service('request')->getPost('patente');
                $id_marca    = service('request')->getPost('marca');
                $id_modelo   = service('request')->getPost('modelo');
                $id_cliente  = service('request')->getPost('id_cliente');
                $color       = service('request')->getPost('color');
                $chasis      = service('request')->getPost('chasis');
                $anio        = service('request')->getPost('anio');

                // Construye el nuevo array PHP
                $data = [
                    'patente'    => $patente,
                    'id_marca'   => $id_marca,
                    'id_modelo'  => $id_modelo,
                    'id_cliente' => $id_cliente,
                    'color'      => $color,
                    'anio'       => $anio,
                    'chasis'     => $chasis
                ];

                // Guardando datos actualizados
                $model_vehiculos->update($id_vehiculo, $data);

                $response =  ['success'=> true];
                header('Content-Type: application/json');
                echo json_encode($response);
             }//Cierra ajax
        // }//Cierra validacion sesion     
    }

    // Elimina el vehículo
    public function EliminaVehiculo(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                 // Instancio el modelo
                 $model_vehiculos  = model('Vehiculo_Model');  

                 // Variables para trabajar
                 $id_vehiculo = service('request')->getPost('id_vehiculo');

                 // Elimina el registro 
                 $model_vehiculos->delete($id_vehiculo);    

                 $response =  ['success'=> true,'id_vehiculo'=>$id_vehiculo];   
                 header('Content-Type: application/json');
                 echo json_encode($response);  
              }//Cierra validación Ajax      
         //}//Cierra if valida sesión      
    }


    // ##################### BOMBAS ######################
    

    # Obtiene Lista de Bombas
    public function GetBombas(){
        $model_bomba = model('Bomba_Model');
        $bombas = $model_bomba->findAll();
        header('Content-Type: application/json');
        echo json_encode($bombas);
    }

    # Obtiene Registro de una bomba por su id
    public function GetRegistroBomba(){
        if ($this->request->isAJAX()) {
            $model_bomba = model('Bomba_Model');
            $id_bomba = service('request')->getPost('id_bomba');
            $bomba = $model_bomba->find($id_bomba);
            header('Content-Type: application/json');
            echo json_encode($bomba);
        }
    }

    # Obtiene las bombas por cliente
    public function GetBombasPorCliente(){
        if ($this->request->isAJAX()) {
            $model_bomba = model('Bomba_Model');
            $id_cliente = service('request')->getPost('id_cliente');            
            $bombas = $model_bomba->GetBombasPorCliente($id_cliente);
            header('Content-Type: application/json');

            if (empty($bombas)) {
                echo json_encode(false);
            } else {
                echo json_encode($bombas);
            }
        }
    }

    # Guarda nueva bomba
    public function GuardarBomba(){
        if ($this->request->isAJAX()) {
            $model_bomba = model('Bomba_Model');

            $codigo      = service('request')->getPost('codigo');
            $id_marca    = service('request')->getPost('id_marca');
            $id_modelo   = service('request')->getPost('id_modelo');
            $id_cliente  = service('request')->getPost('id_cliente');
            $token       = bin2hex(random_bytes(16));

            $data = [
                'codigo'     => $codigo,
                'id_marca'   => $id_marca,
                'id_modelo'  => $id_modelo,
                'id_cliente' => $id_cliente,
                'token'      => $token
            ];

            $model_bomba->save($data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    # Actualiza datos de bomba
    public function UpdateBomba(){
        if ($this->request->isAJAX()) {
            $model_bomba = model('Bomba_Model');

            $id_bomba   = service('request')->getPost('id_bomba');
            $codigo     = service('request')->getPost('codigo');
            $id_marca   = service('request')->getPost('id_marca');
            $id_modelo  = service('request')->getPost('id_modelo');
            $id_cliente = service('request')->getPost('id_cliente');

            $data = [
                'codigo'     => $codigo,
                'id_marca'   => $id_marca,
                'id_modelo'  => $id_modelo,
                'id_cliente' => $id_cliente
            ];

            $model_bomba->update($id_bomba, $data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    # Elimina bomba
    public function EliminarBomba(){
        if ($this->request->isAJAX()) {
            $model_bomba = model('Bomba_Model');

            $id_bomba = service('request')->getPost('id_bomba');

            $model_bomba->delete($id_bomba);

            $response = ['success' => true, 'id_bomba' => $id_bomba];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    

    // #####################USUARIOS######################

    #Muestra Vista Usuarios
    public function Usuarios(): string {
       // if(session()->is_logged){   
            return view('config/usuarios');
          // }      
    }

    #Obtiene Lista de Usuarios
    public function GetUsuarios(){
        // if(session()->is_logged){  
             $model_usuarios= model('Usuario_Model');                
             $usuarios = $model_usuarios->findAll(); 
             header('Content-Type: application/json');
             echo json_encode($usuarios);
           //}  
    }

    #Obtiene Registro de un usuario por su id
    public function GetRegistroUsuario(){
        //if(session()->is_logged){  
            if ($this->request->isAJAX()) {  
             $model_usuarios= model('Usuario_Model');   
             $id_usuario = service('request')->getPost('id_usuario');
             $usuario = $model_usuarios->find($id_usuario);
             header('Content-Type: application/json');
             echo json_encode($usuario);
            }//Cierra ValidacionAjax 
         //}  
    }
    

    #Guarda los usuarios
    public function GuardarUsuario() {
        if ($this->request->isAJAX()) {  
            // Instancio el modelo
            $model_usuario = model('Usuario_Model');

            // Variables desde el request
            $nombre = $this->request->getPost('nombre');
            $username = $this->request->getPost('rut_usuario');
            $plainPassword = $this->request->getPost('password');
            $id_tipo_usuario = $this->request->getPost('tipo_usuario');

             // Construye el array de datos
            $data = [
                'nombre' => $nombre,                
                'id_tipo_usuario' => $id_tipo_usuario,
            ];

            // Hash seguro de la contraseña y agrega parametro de username
            if ($plainPassword !== '') {                
                $data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);
                $data['username'] = $username;
            }            

            // Guardando el usuario
            $model_usuario->save($data);

            $response = ['success'=> true];
            return $this->response->setJSON($response);
        }
    }


    #Actualiza los datos del usuario
   public function UpdateUsuario() {
        if ($this->request->isAJAX()) {  
            // Instancio el modelo
            $model_usuario = model('Usuario_Model');

            // Variables desde el request
            $id_usuario = $this->request->getPost('id_usuario'); 
            $nombre = $this->request->getPost('nombre');
            $username = $this->request->getPost('rut_usuario');
            $password = $this->request->getPost('password');
            $id_tipo_usuario = $this->request->getPost('tipo_usuario');

            // Construye el array de datos básico
            $data = [
                'nombre' => $nombre,               
                'id_tipo_usuario' => $id_tipo_usuario,
            ];

            // Si se ingresó una contraseña nueva, la hashamos
            if ($password !== '') {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                $data['username'] = $username;
            }

            // Guardando los cambios
            $model_usuario->update($id_usuario, $data);

            $response = ['success' => true];
            return $this->response->setJSON($response);
        }
    }


     //Actualiza items del detalle pedido 
    public function EliminaUsuario(){
        // if(session()->is_logged){  
             if ($this->request->isAJAX()) {  
                 //Instancio el modelo
                 $model_usuario  = model('Usuario_Model');  
                 //Variables para trabajar
                 $id_usuario = service('request')->getPost('id_usuario');

                 
                 //Elimina el registro 
                 $model_usuario->delete($id_usuario);    
                 $response =  ['success'=> true,'id_usuario'=>$id_usuario];                                           
                 header('Content-Type: application/json');
                 echo json_encode($response);  
              }//Cierra validación Ajax      
         //}//Cierra if valida sesión      
    }

    
    public function CargaUsuariosMecanicos()
    {        
        $usuarioModel = model('Usuario_Model');

        // Trae solo usuarios con id_tipo_usuario = 3
        $mecanicos = $usuarioModel
            ->where('id_tipo_usuario', 3)
            ->findAll();

        if ($mecanicos) {
            // Devuelve JSON con status ok
            return $this->response->setJSON([
                'status' => 'ok',
                'mecanicos' => $mecanicos
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se encontraron mecánicos'
            ]);
        }
    }

    public function GetMenuUsuario()
    {
        $id_usuario =  session()->get('id_usuario');
        
        $rutasModel = model('Rutas_Model');

        // Traer las rutas permitidas para el usuario
        $rutas = $rutasModel->getRutasPorUsuario($id_usuario);

        // Devolver como JSON
        return $this->response->setJSON($rutas);
    }

    public function GetPermisosUsuario(){
        $permisos_model = model('Permisos_Model');
        if ($this->request->isAJAX()) {
            $id_usuario = service('request')->getPost('id_usuario');
            $permisos = $permisos_model->obtenerRutasConEstadoPorUsuario($id_usuario);
            header('Content-Type: application/json');
            echo json_encode($permisos);
        }
    }

    public function GetPermisosEspecialesUsuario(){
        $permisos_especiales_model = model('Permisos_Especiales_Model');
        if ($this->request->isAJAX()) {
            $id_usuario = service('request')->getPost('id_usuario');
            $permisos_especiales = $permisos_especiales_model->obtenerPermisosEspecialesConEstadoPorUsuario($id_usuario);
            header('Content-Type: application/json');
            echo json_encode($permisos_especiales);
        }
    }

    public function GuardarPermisosUsuario(){
        $permisos_model = model('Permisos_Model');
        $permisos_especiales_usuario_model = model('Permisos_Especiales_Usuario_Model');
        if ($this->request->isAJAX()) {
            $id_usuario = service('request')->getPost('id_usuario');            
            $permisos   = json_decode($this->request->getPost('permisos'), true);
            $permisos_especiales = json_decode($this->request->getPost('permisos_especiales'), true);
            //Guarda permisos de modulos
            $permisos_asignados = $permisos_model->sincronizarPermisos($permisos, $id_usuario);
            //Guarda permisos especiales
            $permisos_especiales_asignados = $permisos_especiales_usuario_model->sincronizarPermisosEspeciales($permisos_especiales, $id_usuario);
            header('Content-Type: application/json');
            echo json_encode($permisos_asignados);
        }
    }




    // #####################MODELOS######################

    // Muestra vista de modelos
    public function Modelos(): string {
        return view('config/modelos');
    }

    // Obtiene todos los modelos (usado por datatable)
    public function GetModelosTodos() {
        if ($this->request->isAJAX()) {
            $model_modelo = model('Modelo_Model');
            $modelos = $model_modelo->select('modelo.*, marca.nombre as marca_nombre')
                                    ->join('marca', 'marca.id = modelo.id_marca')
                                    ->findAll();

            header('Content-Type: application/json');
            echo json_encode($modelos);
        }
    }

    // Obtiene lista de modelos por marca (como ya tienes)
    public function GetModelos() {
        $model_modelo = model('Modelo_Model');
        if ($this->request->isAJAX()) {
            $id_marca = service('request')->getPost('id_marca');
            $modelos = $model_modelo->where('id_marca', $id_marca)->findAll();
            header('Content-Type: application/json');
            echo json_encode($modelos);
        }
    }

    // Obtiene un modelo por ID
    public function GetRegistroModelo() {
        if ($this->request->isAJAX()) {
            $model_modelo = model('Modelo_Model');
            $id_modelo = service('request')->getPost('id_modelo');
            $modelo = $model_modelo->find($id_modelo);

            header('Content-Type: application/json');
            echo json_encode($modelo);
        }
    }

    // Guarda nuevo modelo
    public function GuardarModelo() {
        if ($this->request->isAJAX()) {
            $model_modelo = model('Modelo_Model');
            $nombre = service('request')->getPost('nombre');
            $id_marca = service('request')->getPost('id_marca');

            $data = [
                'nombre'   => $nombre,
                'id_marca' => $id_marca
            ];

            $model_modelo->save($data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Actualiza un modelo
    public function UpdateModelo() {
        if ($this->request->isAJAX()) {
            $model_modelo = model('Modelo_Model');
            $id_modelo = service('request')->getPost('id_modelo');
            $nombre = service('request')->getPost('nombre');
            $id_marca = service('request')->getPost('id_marca');

            $data = [
                'nombre'   => $nombre,
                'id_marca' => $id_marca
            ];

            $model_modelo->update($id_modelo, $data);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Elimina un modelo
    public function EliminarModelo() {
        if ($this->request->isAJAX()) {
            $model_modelo = model('Modelo_Model');
            $id_modelo = service('request')->getPost('id_modelo');

            $model_modelo->delete($id_modelo);

            $response = ['success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    #####################UBICACIONES######################

    // Muestra vista de Ubicaciones
    public function Ubicaciones(): string {
        return view('config/ubicaciones'); // Asegúrate que exista
    }

    // Obtiene lista de todas las ubicaciones
    public function GetUbicaciones() {
        if ($this->request->isAJAX()) {
            $model = model('Ubicacion_Model');
            $ubicaciones = $model
                    ->orderBy('nombre', 'ASC')
                    ->findAll();

            return $this->response->setJSON($ubicaciones);
        }
    }

    // Obtiene una ubicación por su ID
    public function GetRegistroUbicaciones() {
        if ($this->request->isAJAX()) {
            $model = model('Ubicacion_Model');
            $id = $this->request->getPost('id');
            $ubicacion = $model->find($id);

            return $this->response->setJSON($ubicacion);
        }
    }

    // Guarda una nueva ubicación
    public function GuardarUbicaciones() {
        if ($this->request->isAJAX()) {
            $model = model('Ubicacion_Model');

            $data = [
                'nombre' => $this->request->getPost('nombre')
            ];

            $model->save($data);
            return $this->response->setJSON(['success' => true]);
        }
    }

    // Actualiza una ubicación existente
    public function ActualizarUbicaciones() {
        if ($this->request->isAJAX()) {
            $model = model('Ubicacion_Model');

            $id = $this->request->getPost('id');
            $nombre = $this->request->getPost('nombre');

            $data = ['nombre' => $nombre];
            $model->update($id, $data);

            return $this->response->setJSON(['success' => true]);
        }
    }

    // Elimina una ubicación
    public function EliminarUbicaciones() {
        if ($this->request->isAJAX()) {
            $model = model('Ubicacion_Model');

            $id = $this->request->getPost('id');
            $model->delete($id);

            return $this->response->setJSON(['success' => true, 'id' => $id]);
        }
    }

}


