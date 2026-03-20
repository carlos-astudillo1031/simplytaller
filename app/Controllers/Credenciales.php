<?php

namespace App\Controllers;
require_once APPPATH . 'Libraries/DBManager.php';
use App\Libraries\DBManager;

class Credenciales extends BaseController{

     public function Login(){
         return view('main/login');
     }

     public function ValidaUsuario() {
        if ($this->request->isAJAX()) {   
            $model_empresas = model('Empresa_Model');           
            $taller = $this->request->getPost('taller');
            $usuario = $this->request->getPost('usuario');
            $plainPassword = $this->request->getPost('password');

            // Traemos la empresa
            $empresa = $model_empresas->getEmpresaPorCodigo($taller);

            // Validamos que exista la empresa
            if (!$empresa) {
                return $this->response->setJSON([
                    'success' => false,
                    'msg' => 'Empresa no encontrada'
                ]);
            }

            //Seteamos BD e inicalizamos conexion
            $db_nombre = $empresa['db_nombre'];
            log_message('info', $db_nombre);
            DBManager::init($db_nombre); 
            $model_users = model('Usuario_Model');    
            
            // Traemos el usuario por nombre
            $user = $model_users->getUsuarioPorNombre($usuario);

            if ($user) {
                $loginCorrecto = false;

                // Verificamos si es MD5 antiguo
                if (strlen($user->password) === 32) {
                    if (md5($plainPassword) === $user->password) {
                        $loginCorrecto = true;

                        // Re-hash automático a bcrypt
                        $nuevoHash = password_hash($plainPassword, PASSWORD_DEFAULT);
                        $model_users->update($user->id_usuario, ['password' => $nuevoHash]);
                    }
                } else {
                    // Ya es password_hash moderno
                    if (password_verify($plainPassword, $user->password)) {
                        $loginCorrecto = true;
                    }
                }

                if ($loginCorrecto) {
                    //Me traigo los permisos del usuario
                    $rutas_model =  model('Rutas_Model');                   
                    $rutas = $rutas_model->getRutasPorUsuarioTodas($user->id_usuario);           
                    //Me traigo los permsisos especiales del usuario
                    $permios_especiales_usuario_model = model('Permisos_Especiales_Usuario_Model');                             
                    $permisos_especiales = $permios_especiales_usuario_model->getPermisosByUsuario($user->id_usuario);
                    // Seteando Variables Sesion
                    session()->set([
                        'id_usuario' => $user->id_usuario,
                        'id_tipo_usuario' => $user->id_tipo_usuario,
                        'nombre_usuario' => $user->nombre,
                        'rutas' => $rutas,
                        'permisos_especiales' => $permisos_especiales,
                        'cargo' => $user->cargo,
                        'db_nombre' => $db_nombre,
                        'is_logged' => true
                    ]);

                    $url = base_url('public/agenda/');  

                    return $this->response->setJSON([
                        'success'=> true,
                        'url' => $url               
                    ]);
                }
            }

            // Usuario no encontrado o contraseña incorrecta
            return $this->response->setJSON([
                'success' => false,
                'msg' => 'Usuario o contraseña incorrectos'
            ]);
        }
    }



    public function Logout(){
       // Verificar si el usuario está logueado
       if (session()->get('is_logged')) {
           // Eliminar todos los datos de la sesión
           session()->destroy();

           // Redirigir al usuario a la página de login
           return redirect()->to(base_url('public/login'));
       } else {
           // Si no hay sesión activa, redirigir al login
           return redirect()->to(base_url('public/login'));
       }
    }

      

}
