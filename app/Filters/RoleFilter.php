<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Lógica para verificar si el usuario está logueado
        if (! session()->get('is_logged')) {
            // Redirigir a login si no está logueado
            return redirect()->to(base_url('public/login'));
        }

        $rutasPublicas = [
            '/config/GetMenuUsuario',           
            '/config/CargaUsuariosMecanicos',
        ];
        

        // Obtener el tipo de usuario
        $userType = session()->get('id_tipo_usuario');
        // Obtener la ruta actual (sin base_url)
        $uri = $request->getUri()->getPath();
        $uri = preg_replace('#^/qa#', '', $uri);  
        //Obtener las rutas permitidas para el tipo de usuario
        $rutasPermitidas = session()->get('rutas') ?? [];

        // Primero revisamos rutas públicas
        if (in_array($uri, $rutasPublicas)) {
            return; // dejar pasar sin validar permisos
        }

        /* log_message('debug', '#######################'.date('Y-m-d H:i:s').'#####################');  */
        //Valida rutas permitidas
        $acceso = false;
        foreach ($rutasPermitidas as $rutaPerm) {
            if (rutaCoincide($uri, $rutaPerm['nombre'])) {
               $acceso=true;
               log_message('debug', 'Acceso permitido para:'.$uri);
               break;
            }
        }

        if($acceso == false){            
            log_message('debug', 'Acceso DENEGADO para:'.$uri);
            return redirect()->to(base_url('public/login'));                     
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita hacer nada después de la solicitud
    }
}


function rutaCoincide(string $rutaActual, string $nombreModulo): bool
{
    // generar palabras clave desde nombre
    $base = strtolower($nombreModulo);
    
    $palabras = [
        $base,
        rtrim($base, 's'), // singluar
        $base . 's'        // plural
    ];

    foreach ($palabras as $palabra) {
        if ($palabra === '') continue;

        if (stripos($rutaActual, $palabra) !== false) {
            return true;
        }
    }

    return false;
}



// class RoleFilter implements FilterInterface
// {
//     public function before(RequestInterface $request, $arguments = null){
//         // Verificar si el usuario está logueado
//         if (!session()->get('is_logged')) {
//             log_message('debug', 'NO Esta logueado');
//             // Si no está logueado, redirigir al login
//             return redirect()->to(base_url('public/login'));
//         }else{
//             log_message('debug', 'Esta logueado');   
//         }

//         // Obtener el tipo de usuario
//         $userType = session()->get('id_tipo_usuario');
        
//         // Obtener la ruta actual (sin base_url)
//         $uri = $request->getUri()->getPath(); // Obtiene la parte de la ruta

//         // Rutas especiales para dashboard permitidas para perfiles 1, 2 y 3
//         $dashboardRoutes = [
//             '/dashboard',
//             '/dashboard/GetPedidos',
//             '/dashboard/ContarRegistrosPorEstado',
//             '/dashboard/GetPedidosImpresion',
//             '/dashboard/MuestraDetalleImpresion',
//             '/dashboard/GetPedidosSublimado',            
//             '/dashboard/GetPedidosDespacho', 
//         ];

//         // Verificar si la ruta actual está en la lista de rutas especiales para dashboard
//         if (in_array($uri, $dashboardRoutes)) {
//             if (in_array($userType, [1, 2, 3])) {
//                 log_message('debug', 'Acceso permitido a dashboard para usuario: ' . $userType);
//                 return; // Permitir el acceso
//             } else {
//                 log_message('debug', 'Acceso denegado a dashboard para usuario: ' . $userType);
//                 return redirect()->to(base_url('public/login')); // Redirigir si no tiene permiso
//             }
//         }

//         //Rutas Especiales Admin y Sublimado
//         $sublimadoRoutes = [           
//             '/dashboard/EmpiezaSublimado',
//             '/dashboard/VuelveAPendienteSublimado',
//             '/dashboard/DevuelveItemAcuadratura',
//             '/dashboard/DespachaItem',            
//         ];

//         // Verificar si la ruta actual está en la lista de rutas especiales para dashboard
//         if (in_array($uri, $sublimadoRoutes)) {
//             if (in_array($userType, [1,3])) {
//                 log_message('debug', 'Acceso permitido a dashboard para usuario: ' . $userType);
//                 return; // Permitir el acceso
//             } else {
//                 log_message('debug', 'Acceso denegado a dashboard para usuario: ' . $userType);
//                 return redirect()->to(base_url('public/login')); // Redirigir si no tiene permiso
//             }
//         }

//         //Rutas Especiales Admin y Despacho
//         $despachoRoutes = [           
//             '/dashboard/DespachaTrackid',
//         ];

//         // Verificar si la ruta actual está en la lista de rutas especiales para dashboard
//         if (in_array($uri, $despachoRoutes)) {
//             if (in_array($userType, [1,2])) {
//                 log_message('debug', 'Acceso permitido a dashboard para usuario: ' . $userType);
//                 return; // Permitir el acceso
//             } else {
//                 log_message('debug', 'Acceso denegado a dashboard para usuario: ' . $userType);
//                 return redirect()->to(base_url('public/login')); // Redirigir si no tiene permiso
//             }
//         }

//         //Rutas Especiales Admin y Clientes
//         $clienteRoutes = [           
//             '/home',
//             '/home/InsertaItemPedido',
//             '/home/GetDetallePedidoPendiente',
//             '/home/UpdateDetallePedido',
//             '/home/EliminaDetallePedido',
//             '/home/EnviaPedido',
//             '/home/Recepcionar',
//             '/home/GetTrackIdDespachados',
//             '/home/RecibeTrackid',
//             '/home/RechazaTrackid',
//         ];

//         // Verificar si la ruta actual está en la lista de rutas especiales para dashboard
//         if (in_array($uri, $clienteRoutes)) {
//             if (in_array($userType, [1,4])) {
//                 log_message('debug', 'Acceso permitido a dashboard para usuario: ' . $userType);
//                 return; // Permitir el acceso
//             } else {
//                 log_message('debug', 'Acceso denegado a dashboard para usuario: ' . $userType);
//                 return redirect()->to(base_url('public/login')); // Redirigir si no tiene permiso
//             }
//         }

        

//         // Rutas restringidas solo admin
//         $restrictedRoutes = [
//             '/config/GetUsuarios' => [1],           
//             '/config/GetRegistroUsuario' => [1],           
//             '/config/Usuarios' => [1],         
//             '/config/GuardarUsuario' => [1],           
//             '/config/UpdateUsuario' => [1],           
//             '/config/EliminaUsuario' => [1],
//             '/config/Telas' => [1],
//             '/config/UpdateTela' => [1],
//             '/config/EliminaTela' => [1],
//             '/config/GuardarTela' => [1],
//             '/config/Disenos' => [1],
//             '/config/UpdateDiseno' => [1],
//             '/config/EliminaDiseno' => [1],
//             '/config/GuardarDiseno' => [1],
//             '/config/Impresoras' => [1],
//             '/config/UpdateImpresora' => [1],
//             '/config/EliminaImpresora' => [1],
//             '/config/GuardarImpresora' => [1],
//             '/config/Calandras' => [1],
//             '/config/UpdateCalandra' => [1],
//             '/config/EliminaCalandra' => [1],
//             '/config/GuardarCalandra' => [1],
//             '/config/Clientes' => [1],
//             '/config/UpdateCliente' => [1],
//             '/config/EliminaCliente' => [1],
//             '/config/GuardarCliente' => [1],
//             '/dashboard/EnviarAImpresion' => [1],   
//             '/dashboard/EmpiezaImpresion' => [1],   
//             '/dashboard/VuelveAPendienteImpresion' => [1],   
//             '/dashboard/VuelveACuadratura' => [1],   
//             '/dashboard/EnviarASublimado' => [1],
//         ];

//         // Rutas libres
//         $publicRoutes = [
//             '/config/GetTelas',
//             '/config/GetCorrelativo',
//             '/config/GetRegistroTela',
//             '/config/GetDisenos',
//             '/config/GetRegistroDiseno',
//             '/config/GetImpresoras',
//             '/config/GetRegistroImpresora',
//             '/config/GetCalandras',
//             '/config/GetClientes',       
//             '/config/GetRegistroCliente',    
//         ];

//         // Verificar si la ruta está en las rutas libres
//         if (in_array($uri, $publicRoutes)) {
//             log_message('debug', 'Entra a if para ruta:'.$uri); 
//             return; // Ruta libre, continuar normalmente
//         }else{
//             log_message('debug', 'Entra a else para ruta:'.$uri); 
//         }

//         // Verificar si la ruta está en las rutas restringidas
//         if (array_key_exists($uri, $restrictedRoutes)) {
//             if (!in_array($userType, $restrictedRoutes[$uri])) {
//                 log_message('debug', 'Entra a if2 para ruta:'.$uri); 
//                 return redirect()->to(base_url('public/login')); // Redirigir si no tiene permiso
//             }else{
//                 log_message('debug', 'Entra a else2 para ruta:'.$uri); 
//             }
//         }

//         return;
//     }




//     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
//     {
//         // Aquí puedes agregar algún tipo de post-procesamiento después de que la respuesta haya sido enviada
//     }
// }
