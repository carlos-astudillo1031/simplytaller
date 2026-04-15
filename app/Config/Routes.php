<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Credenciales

$routes->post('credenciales/ValidaUsuario', 'Credenciales::ValidaUsuario');
$routes->get('login', 'Credenciales::Login'); 
$routes->get('logout', 'Credenciales::Logout'); 

// //Config

// //Estas rutas pasan por el filtro y el filtro determina que esta permitido y que no
$routes->group('config', ['filter' => 'role'], function ($routes) {
    $routes->get('usuarios', 'Config::Usuarios');  
    $routes->get('GetUsuarios', 'Config::GetUsuarios');  
    $routes->get('CargaUsuariosMecanicos', 'Config::CargaUsuariosMecanicos');    
    $routes->post('GetRegistroUsuario', 'Config::GetRegistroUsuario');    
    $routes->post('GuardarUsuario', 'Config::GuardarUsuario');  
    $routes->post('UpdateUsuario', 'Config::UpdateUsuario');  
    $routes->post('EliminaUsuario', 'Config::EliminaUsuario');  
    $routes->post('GetMecanicos', 'Config::GetMecanicos');
    $routes->post('GetMenuUsuario', 'Config::GetMenuUsuario');
    $routes->post('GetPermisosUsuario', 'Config::GetPermisosUsuario');
    $routes->post('GuardarPermisosUsuario', 'Config::GuardarPermisosUsuario');
    $routes->post('GetPermisosEspecialesUsuario', 'Config::GetPermisosEspecialesUsuario');
    
    $routes->get('clientes', 'Config::Clientes');  
    $routes->get('GetClientes', 'Config::GetClientes');  
    $routes->post('GetRegistroCliente', 'Config::GetRegistroCliente');  
    $routes->post('GetClientePorRut', 'Config::GetClientePorRut');      
    $routes->post('GuardarCliente', 'Config::GuardarCliente');  
    $routes->post('UpdateCliente', 'Config::UpdateCliente');  
    $routes->post('EliminaCliente', 'Config::EliminaCliente');  

    $routes->get('marcas', 'Config::Marcas');
    $routes->get('GetMarcas', 'Config::GetMarcas');
    $routes->post('GetRegistroMarca', 'Config::GetRegistroMarca');
    $routes->post('GuardarMarca', 'Config::GuardarMarca');
    $routes->post('ActualizarMarca', 'Config::ActualizarMarca');
    $routes->post('EliminarMarca', 'Config::EliminarMarca');

    $routes->get('ubicaciones', 'Config::Ubicaciones');
    $routes->get('GetUbicaciones', 'Config::GetUbicaciones');
    $routes->post('GetRegistroUbicaciones', 'Config::GetRegistroUbicaciones');
    $routes->post('GuardarUbicaciones', 'Config::GuardarUbicaciones');
    $routes->post('ActualizarUbicaciones', 'Config::ActualizarUbicaciones');
    $routes->post('EliminarUbicaciones', 'Config::EliminarUbicaciones');

    $routes->get('modelos', 'Config::Modelos');
    $routes->get('GetModelosTodos', 'Config::GetModelosTodos');
    $routes->post('GetModelos', 'Config::GetModelos'); 
    $routes->post('GetRegistroModelo', 'Config::GetRegistroModelo');
    $routes->post('GuardarModelo', 'Config::GuardarModelo');
    $routes->post('UpdateModelo', 'Config::UpdateModelo');
    $routes->post('EliminarModelo', 'Config::EliminarModelo');

    $routes->get('servicios', 'Config::Servicios');  
    $routes->get('GetServicios', 'Config::GetServicios');  
    $routes->post('GetRegistroServicio', 'Config::GetRegistroServicio');
    $routes->post('GuardarServicio', 'Config::GuardarServicio');  
    $routes->post('UpdateServicio', 'Config::UpdateServicio');  
    $routes->post('EliminarServicio', 'Config::EliminarServicio');  

    $routes->get('repuestos', 'Config::Repuestos');
    $routes->get('GetRepuestos', 'Config::GetRepuestos');
    $routes->post('GetRegistroRepuesto', 'Config::GetRegistroRepuesto');
    $routes->post('GuardarRepuesto', 'Config::GuardarRepuesto');
    $routes->post('UpdateRepuesto', 'Config::UpdateRepuesto');
    $routes->post('EliminarRepuesto', 'Config::EliminarRepuesto');
    $routes->post('RegistrarAjusteStockRepuesto', 'Config::RegistrarAjusteStockRepuesto');

    $routes->get('vehiculos', 'Config::Vehiculos');  
    $routes->get('GetVehiculos', 'Config::GetVehiculos');  
    $routes->post('GetRegistroVehiculo', 'Config::GetRegistroVehiculo');  
    $routes->post('GetVehiculosPorCliente', 'Config::GetVehiculosPorCliente');      
    $routes->post('GetVehiculoPorPatente', 'Config::GetVehiculoPorPatente');  
    $routes->post('GuardarVehiculo', 'Config::GuardarVehiculo');  
    $routes->post('UpdateVehiculo', 'Config::UpdateVehiculo');  
    $routes->post('EliminaVehiculo', 'Config::EliminaVehiculo');

    // Rutas para gestión de Bombas
    $routes->get('GetBombas', 'Config::GetBombas');  
    $routes->post('GetRegistroBomba', 'Config::GetRegistroBomba');  
    $routes->post('GetBombasPorCliente', 'Config::GetBombasPorCliente');  
    $routes->post('GuardarBomba', 'Config::GuardarBomba');  
    $routes->post('UpdateBomba', 'Config::UpdateBomba');  
    $routes->post('EliminarBomba', 'Config::EliminarBomba');


    $routes->get('proveedores', 'Config::Proveedores');
    $routes->get('GetProveedores', 'Config::GetProveedores');
    $routes->post('GetUnicoProveedores', 'Config::GetUnicoProveedores');
    $routes->post('GuardarProveedores', 'Config::GuardarProveedores');
    $routes->post('ActualizarProveedores', 'Config::ActualizarProveedores');
    $routes->post('EliminarProveedores', 'Config::EliminarProveedores');

}); 

//Dashboard
$routes->group('dashboard', ['filter' => 'role'], function ($routes) {
    $routes->get('/', 'Dashboard::Index');  
}); 

//Presupuestos
$routes->group('presupuesto', ['filter' => 'role'], function ($routes) {
    $routes->get('lista-presupuestos', 'Presupuesto::lista');
    $routes->get('GetPresupuestos', 'Presupuesto::GetPresupuestos');    
    $routes->post('GetPresupuestosByCliente', 'Presupuesto::GetPresupuestosByCliente');
    $routes->post('CrearOPresupuesto', 'Presupuesto::CrearOPresupuesto');
    $routes->post('GetDetallePresupuesto', 'Presupuesto::GetDetallePresupuesto');
    $routes->post('DesecharPresupuesto', 'Presupuesto::DesecharPresupuesto');
    $routes->get('DescargarPDF/(:num)', 'Presupuesto::DescargarPDF/$1');
    $routes->post('GuardarPresupuestoCompleto', 'Presupuesto::GuardarPresupuestoCompleto');
    $routes->get('(:segment)?', 'Presupuesto::index/$1'); // debe ir al final
});

$routes->group('orden-servicio', ['filter' => 'role'], function ($routes) {   
    $routes->get('lista-ordenes', 'Orden_Servicio::lista');
    $routes->get('pagos-pendientes', 'Orden_Servicio::pagos');
    $routes->post('ConvertirEnOS', 'Orden_Servicio::ConvertirEnOS');    
    $routes->post('GetInventarioMarcado', 'Orden_Servicio::GetInventarioMarcado');
    $routes->post('GetDaniosMarcados', 'Orden_Servicio::GetDaniosMarcados');    
    $routes->post('GetObservacionesMarcadas', 'Orden_Servicio::GetObservacionesMarcadas');
    $routes->post('GetDetallePago', 'Orden_Servicio::GetDetallePago');
    $routes->post('GuardarCambios', 'Orden_Servicio::GuardarCambios');
    $routes->post('CrearPresupuesto', 'Orden_Servicio::CrearPresupuesto');
    $routes->post('CambiaEstado', 'Orden_Servicio::CambiaEstado');    
    $routes->post('PagarOS', 'Orden_Servicio::PagarOS');
    $routes->post('GetDetalleFormaPago', 'Orden_Servicio::GetDetalleFormaPago');
    $routes->post('GetOrdenesByCliente', 'Orden_Servicio::GetOrdenesByCliente');
    $routes->get('DescargarPDF/(:num)', 'Orden_Servicio::DescargarPDF/$1');
    $routes->get('GetOrdenes', 'Orden_Servicio::GetOrdenes');
    $routes->get('GetOrdenesPorPagar', 'Orden_Servicio::GetOrdenesPorPagar');
    $routes->get('crear-os/(:segment)?', 'Orden_Servicio::CrearOS/$1');
    $routes->get('(:segment)?', 'Orden_Servicio::index/$1'); 
});

$routes->group('reportes', ['filter' => 'role'], function ($routes) {   
    $routes->post('GetTransferencias', 'Reportes::GetTransferencias');
    $routes->post('Get_kpis', 'Reportes::Get_kpis');
    $routes->get('transferencias', 'Reportes::Transferencias');    
    $routes->get('dashboard', 'Reportes::Dashboard');
});

$routes->group('agenda', ['filter' => 'role'], function ($routes) {   
    $routes->post('GuardarCita', 'Agenda::GuardarCita');
    $routes->post('ActualizarCita', 'Agenda::ActualizarCita');
    $routes->post('EliminarCita', 'Agenda::EliminarCita');
    $routes->get('GetCitas', 'Agenda::GetCitas');
    $routes->get('/', 'Agenda::index');
});
 
//Compras
$routes->group('compra', ['filter' => 'role'], function ($routes) {
    $routes->get('lista', 'Compra::lista');
    $routes->get('GetCompras', 'Compra::GetCompras');
    $routes->post('GetCompraDetalle', 'Compra::GetCompraDetalle');
    $routes->post('GuardarCompra', 'Compra::GuardarCompra');
    $routes->post('AnularCompra', 'Compra::AnularCompra');
});    

// Ventas
$routes->group('venta', ['filter' => 'role'], function ($routes) {
    $routes->get('lista', 'Venta::lista');
    $routes->get('GetVentas', 'Venta::GetVentas');
    $routes->post('GetVentaDetalle', 'Venta::GetVentaDetalle');
    $routes->post('GuardarVenta', 'Venta::GuardarVenta');
    $routes->post('AnularVenta', 'Venta::AnularVenta');
});