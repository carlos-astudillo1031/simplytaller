
<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
    <?php echo view("references/navbar"); ?>    
       <style>
@media (max-width: 768px) {
         #tabla_clientes .btn {
            display: block;
            width: 100%;
            margin: 4px 0;
        }
     .card {
            margin: 20px 15px !important;
        }
    }        
</style>

<div class="content-wrapper container" style="padding-right:40px"> 
            <section class="row">        
            <div class="col-12 col-lg-3">            
            </div>            
            <div class="card">
                <div class="card-header d-flex align-items-center">
                     <div class="page-heading d-flex align-items-center">
                      <!--   <a href="#" class="burger-btn d-flex align-items-center me-3">
                            <i class="bi bi-justify fs-3"></i>
                        </a> -->
                        <h4 style="margin-bottom:-32px">Clientes</h4>
                    </div>
                    <!-- Botón al extremo derecho -->
                    <!-- <button onclick="CrearCliente()" class="btn btn-primary ms-auto">
                        <i class="fas fa-plus"></i> Nuevo
                    </button> -->
                    </div>                              
                    <div class="card-body">
                       <div class="table-responsive">
                        <table id="tabla_clientes" class="table table-bordered table-striped">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th width="20%" scope="col">Cliente</th>
                                                            <th width="15%" scope="col">Rut</th>
                                                            <th width="20%" scope="col">Email</th>
                                                            <th width="20%" scope="col">Telefono</th>                     
                                                            <th width="30%" scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                                                      
                                                    </tbody>
                        </table>
                       </div>  
                    </div>
                </div>
             </div> 
           </section>
        </div> <!--Cierra el pagecontent-->
           <?php echo view("references/footer"); ?>

<!-- Este Modal permite agregar y editar registros -->
<div class="modal fade text-left show" id="crear-editar-registro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Registro de Cliente</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="cuerpo-modal" class="row form-group">
                    <form class="form form-horizontal">
                        <div class="form-body">
                            <div class="row">
                                <!-- Campo RUT con botón lupa -->
                                <!-- <div class="col-md-4">
                                    <label for="rut_cliente">Rut de</label>
                                </div> -->
                                <div class="col-md-12 form-group d-flex">
                                    <select class="form-select" id="select_clientes">
                                        <option selected disabled>Buscar Cliente</option>
                                    <!-- Opciones se cargarán vía AJAX -->
                                    </select>                                    
                                </div>                                
                               <!--  <div class="col-md-2 form-group d-flex">
                                       <button type="button" id="btn_buscar"  class="btn btn-primary" onclick="buscarRut()">
                                        <i class="fas fa-search"></i>
                                    </button> 
                                </div> -->
                                <span id="error-rut" class="text-danger d-none">El rut no es válido</span>

                                <!-- Campos ocultos -->
                                <div class="col-md-4 d-none">
                                    <label for="rut_cliente">Rut</label>
                                </div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="rut_cliente" id="rut_cliente" placeholder="Rut del Cliente">
                                </div> 
                                <div class="col-md-4 d-none">
                                    <label for="nombre_cliente">Nombre</label>
                                </div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del Cliente">
                                </div>

                                <div class="col-md-4 d-none">
                                    <label for="email_cliente">Email</label>
                                </div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="email_cliente" id="email_cliente" placeholder="Email del Cliente">
                                </div>

                                <div class="col-md-4 d-none">
                                    <label for="telefono_cliente">Teléfono</label>
                                </div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" id="telefono_cliente" class="form-control" name="telefono_cliente" placeholder="Teléfono del Cliente">
                                </div>

                                <div class="col-md-4 d-none">
                                    <label for="direccion_cliente">Dirección</label>
                                </div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" id="direccion_cliente" class="form-control" name="direccion_cliente" placeholder="Dirección del Cliente">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="Guardar()" id="confirmEnvioButton">Guardar</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar vehículo -->
<div class="modal fade text-left" id="crear-editar-vehiculo" tabindex="-1" role="dialog" aria-labelledby="vehiculoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Vehículo</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">

                            <div class="col-md-4">
                                <label for="patente">Patente</label>
                            </div>
                            <div class="col-md-8 form-group d-flex">
                                <input type="text" class="form-control" name="patente" id="patente" placeholder="Patente del Vehículo">
                               <!--  <button type="button" id="btn_buscar" class="btn btn-primary ms-2" onclick="buscarPatente()">
                                    <i class="fas fa-search"></i>
                                </button> -->
                            </div>
                            <span id="error-patente" class="text-danger d-none">La patente no es válida</span>

                            <span id="vehiculo_no_encontrado" class="text-danger d-none">Vehiculo no encontrado. 
                                Para continuar debe asociarlo a un cliente haciendo <a href="<?= base_url('public/config/clientes') ?>">click aqui</a>
                            </span>

                            <div class="col-md-4 d-none">
                                <label for="marca">Marca</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <select id="marca"  class="select2 form-select" ></select>
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="modelo">Modelo</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <select id="modelo"  class="select2 form-select" ></select>
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="anio">Año</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <input type="number" class="form-control" name="anio" id="anio" placeholder="Año del Vehículo">
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="color">Color</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <input type="text" class="form-control" name="color" id="color" placeholder="Color del Vehículo">
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="chasis">Chasis</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <input type="text" class="form-control" name="chasis" id="chasis" placeholder="Número de Chasis">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="modal-footer-vehiculo">
                <!-- Botones se cargan dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Lista Vehiculos -->
 <div class="modal fade text-left show" id="lista-vehiculos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Lista de Vehiculos</h4>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>        
            <div class="modal-body">                    
                    <table id="tabla_vehiculos" class="table table-striped">
                        <thead>
                            <tr class="bg-light">
                                <th>Patente</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Año</th>
                                <th>Color</th>
                                <th>Chasis</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
            <div class="modal-footer" id="modal-footer-lista-vehiculos">
                
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar bomba -->
<div class="modal fade text-left" id="crear-editar-bomba" tabindex="-1" role="dialog" aria-labelledby="bombaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Bomba o Inyector</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">

                            <div class="col-md-4">
                                <label for="codigo">Código de Bomba</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Código de la bomba o inyector">
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="marca">Marca</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select id="marca_bomba" class="select2 form-select"></select>
                            </div>

                            <div class="col-md-4 d-none">
                                <label for="modelo">Modelo</label>
                            </div>
                            <div class="col-md-8 form-group d-none">
                                <select id="modelo_bomba" class="select2 form-select"></select>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="modal-footer-bomba">
                <!-- Botones se cargan dinámicamente -->
            </div>
        </div>
    </div>
</div>


<!-- Modal Lista Bombas -->
<div class="modal fade text-left" id="lista-bombas" tabindex="-1" role="dialog" aria-labelledby="myModalLabelBomba" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabelBomba">Lista de Bombas o Inyectores</h4>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>        
            <div class="modal-body">                    
                <table id="tabla_bombas" class="table table-striped">
                    <thead>
                        <tr class="bg-light">
                            <th>Código</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer" id="modal-footer-lista-bombas">
                <!-- Opcional: botones o información extra -->
            </div>
        </div>
    </div>
</div>


</body>
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>

     $(document).ready(function() {                  
        AbreModalCliente()        
    });

    //Al elegir cliente setea campo rut. Esto se hizo asi para no afectar la logica original que solo consideraba este campo de rut.
    $(document).on('change', '#select_clientes', function() {        
        $('#rut_cliente').val($(this).val());
        buscarRut();
    });


    function AbreModalCliente(){
        //Limpiados los datos del cliente si fuera necesario
        $('#nombre_cliente').val('');   
        $('#rut_cliente').val('');   
        $('#email_cliente').val('');   
        $('#telefono_cliente').val('');   
        $('#direccion_cliente').val('');            
        $('#btn_buscar').removeClass('d-none');


        $('#crear-editar-registro').modal('show');    
        CargaClientes()     
    }

     function CrearCliente(){        
        //Limpiados los datos del cliente si fuera necesario
        $('#nombre_cliente').val('');   
        $('#rut_cliente').val('');   
        $('#email_cliente').val('');   
        $('#telefono_cliente').val('');   
        $('#direccion_cliente').val('');            


         $('#crear-editar-registro').modal('show');        
     }

     function Guardar(){
        let valdacion_de_rut = (validaRut($('#rut_cliente')[0]));
        if (valdacion_de_rut == false) {
                alert('El rut ingresado no es valido');
                return false;
        }
        const arrCampos = [          
                ['nombre_cliente', 'text'],    
                ['rut_cliente', 'text'],                    
                ['telefono_cliente', 'text']

        ];                
        if(ValidaCamposObligatorios(arrCampos)!=false){         
         // Realizar la solicitud AJAX
                $.ajax({
                    data: { 'nombre_cliente': $('#nombre_cliente').val(),
                        'rut_cliente': $('#rut_cliente').val(),
                        'email_cliente': $('#email_cliente').val(),
                        'telefono_cliente': $('#telefono_cliente').val(),
                        'direccion_cliente': $('#direccion_cliente').val()
                    },
                    dataType: "json",
                    type: "POST",
                    url: "<?= base_url('/public/config/GuardarCliente') ?>",

                    error: function (jqXHR, textStatus, errorThrown) {
                        MyAlert('Imposible guardar los datos', 'error');
                    },
                    success: function (data) {     
                        buscarRut();
                        $('#crear-editar-registro').modal('hide');                        
                    }
                });
        }//Cierra if valida campos        
     }


     function buscarRut(){        
        if($('#rut_cliente').val()!='nuevo'){
            let valdacion_de_rut = (validaRut($('#rut_cliente')[0]));
            if (valdacion_de_rut == false) {
                alert('El rut ingresado no es valido');
                return false;
            }
        }
        let rut_cliente = $('#rut_cliente').val();    
        $.ajax({
                    dataType:"json",

                    type: "POST",

                    data: { 'rut_cliente': rut_cliente},

                    url:"<?= base_url('/public/config/GetClientePorRut') ?>",                     

                    error:function(jqXHR, textStatus, errorThrown){                  
                        MyAlert('Imposible encontrar el cliente','error');

                    },success: function(data){               
                            if(data === false){
                                // Quitar 'd-none' de los labels e inputs de los campos ocultos               
                                $('#rut_cliente').closest('.form-group').removeClass('d-none');
                                $('label[for="rut_cliente"]').parent().removeClass('d-none');

                                $('#nombre_cliente').closest('.form-group').removeClass('d-none');
                                $('label[for="nombre_cliente"]').parent().removeClass('d-none');

                                $('#email_cliente').closest('.form-group').removeClass('d-none');
                                $('label[for="email_cliente"]').parent().removeClass('d-none');

                                $('#telefono_cliente').closest('.form-group').removeClass('d-none');
                                $('label[for="telefono_cliente"]').parent().removeClass('d-none');

                                $('#direccion_cliente').closest('.form-group').removeClass('d-none');
                                $('label[for="direccion_cliente"]').parent().removeClass('d-none');

                                htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="Guardar()" id="confirmEnvioButton">Guardar</button>';
                                $('#modal-footer-edit').html(htmlBotones);

                            }else{
                                             
                                // destruir e inicializar DataTable (opcional si es necesario)
                                $('#tabla_clientes').DataTable().destroy();       
                                $("#tabla_clientes tbody").empty();   
                                
                                $("#tabla_clientes tbody").append('<tr id="tr_'+data.id_cliente+'">'+
                                    '<td>'+data.nombre_cliente+'</td>'+
                                    '<td>'+data.rut_cliente+'</td>'+
                                    '<td>' + (data.email_cliente ? data.email_cliente : '---') + '</td>' +
                                    '<td>'+data.telefono_cliente+'</td>'+
                                    '<td class="text-start">'+
                                        '<a class="btn icon btn-success me-1" onclick="Editar(\'' + data.id_cliente + '\')" href="#" data-bs-toggle="tooltip" title="Editar"><i class="fas fa-edit"></i></a>'+
                                        '<a class="btn icon btn-primary" title="Vehiculos" onclick="MuestraVehiculos(\'' + data.id_cliente + '\')" href="#"><i class="fas fa-car"></i></a>'+
                                        ' <a class="btn icon btn-info" title="Bombas o Inyectores" onclick="MuestraBombas(\'' + data.id_cliente + '\')" href="#"><i class="fas fa-cogs"></i></a>'+
                                        ' <a class="btn icon btn-danger" title="Eliminar" onclick="Eliminar(\'' + data.id_cliente + '\')" href="#"><i class="fas fa-trash"></i></a>'+
                                    '</td>'+
                                '</tr>');     

                                $('#crear-editar-registro').modal('hide');                                
                            }
  
                    }                   
        });
     }

     function MuestraVehiculos(id_cliente){
         $.ajax({
                    dataType:"json",

                    type: "POST",

                    data: { 'id_cliente': id_cliente},

                    url:"<?= base_url('/public/config/GetVehiculosPorCliente') ?>",

                    error:function(jqXHR, textStatus, errorThrown){

                        MyAlert('Imposible encontrar los vehiculos','error');

                    },success: function(data){                            
                            if(data === false){            
                                //No hay vehiculos ofrece crear uno               
                                $('#crear-editar-vehiculo').modal('show');
                                $('#marca, #modelo, #anio, #color, #chasis').closest('.form-group').removeClass('d-none');
                                $('label[for="marca"], label[for="modelo"], label[for="anio"], label[for="color"], label[for="chasis"]').parent().removeClass('d-none');

                                let htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="GuardarVehiculo('${id_cliente}')">Guardar</button>`;

                                $('#modal-footer-vehiculo').html(htmlBotones);
                                CargaConfig();
                                CargaMarcas('marca',0);                                
                            }else{
                                //Muestra lista de vehiculos y ofrece crear uno
                                $('#tabla_vehiculos').DataTable().destroy();       
                                $("#tabla_vehiculos tbody").empty(); 
                                $.each(data, function(index, value) {
                                   $("#tabla_vehiculos tbody").append('<tr>' +
                                    '<td>' + value.patente + '</td>' +
                                    '<td>' + value.marca + '</td>' +
                                    '<td>' + value.modelo + '</td>' +
                                    '<td>' + value.anio + '</td>' +
                                    '<td>' + value.color + '</td>' +
                                    '<td>' + value.chasis + '</td>' +
                                    '<td class="text-start">' +
                                        '<a class="btn icon btn-dark" title="Crear Presupuesto" href="<?= base_url('/public/presupuesto') ?>/' + value.token + '">' +
                                            '<i class="fas fa-file-alt"></i>' +
                                        '</a>' +
                                         ' <a class="btn icon btn-primary" title="Crear Orden de Servicio" href="<?= base_url('/public/orden-servicio/crear-os/') ?>' + value.token + '"><i class="fas fa-wrench"></i></a>' +                            
                                    '</td>' +
                                    '</tr>');
                                });
                                let htmlBotones = `<button type="button" class="btn btn-primary" onclick="AgregarVehiculo('${id_cliente}')">Agregar Vehiculo <i class="fas fa-plus"></i></button>`;

                                $('#modal-footer-lista-vehiculos').html(htmlBotones);
                                console.log('antes vehiculo');
                                $('#lista-vehiculos').modal('show');
                                console.log('despues vehiculo');
                                 $('#tabla_vehiculos').DataTable({
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                                    },
                                    "pageLength": 4
                                });
                            }
                    }
                });        
     }

     function AgregarVehiculo(id_cliente){
          $('#lista-vehiculos').modal('hide');
          $('#crear-editar-vehiculo').modal('show');
          $('#marca, #modelo, #anio, #color, #chasis').closest('.form-group').removeClass('d-none');
          $('label[for="marca"], label[for="modelo"], label[for="anio"], label[for="color"], label[for="chasis"]').parent().removeClass('d-none');

          let htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="GuardarVehiculo('${id_cliente}')">Guardar</button>`;

          $('#modal-footer-vehiculo').html(htmlBotones);
          CargaConfig();
          CargaMarcas('marca',0);                                
     }

     function GuardarVehiculo(id_cliente) {
        const arrCampos = [
            ['patente', 'text'],
            ['marca', 'text'],
            ['modelo', 'text'],
            ['anio', 'text'],
            ['color', 'text'],
            ['chasis', 'text']
        ];

        if (ValidaCamposObligatorios(arrCampos) != false) {
            $.ajax({
                data: {
                    'patente': $('#patente').val().toUpperCase(),
                    'id_marca': $('#marca').val(),
                    'id_modelo': $('#modelo').val(),
                    'anio': $('#anio').val(),
                    'color': $('#color').val(),
                    'chasis': $('#chasis').val(),
                    'id_cliente': id_cliente
                },
                dataType: "json",
                type: "POST",
                url: "<?= base_url('/public/config/GuardarVehiculo') ?>",
                error: function() {
                    MyAlert('Imposible guardar los datos', 'error');
                },
                success: function(data) {                    
                    $('#crear-editar-vehiculo').modal('hide');
                    MuestraVehiculos(id_cliente)
                }
            });
        }
    }

    function MuestraBombas(id_cliente){
        $.ajax({
            dataType: "json",
            type: "POST",
            data: { 'id_cliente': id_cliente },
            url: "<?= base_url('/public/config/GetBombasPorCliente') ?>",
            error: function(jqXHR, textStatus, errorThrown){
                MyAlert('Imposible encontrar las bombas o inyectores', 'error');
            },
            success: function(data){
                if(data === false){
                    // No hay bombas, ofrece crear una
                    $('#crear-editar-bomba').modal('show');
                    // Mostrar solo los campos que correspondan para bomba
                    $('#codigo, #marca_bomba, #modelo_bomba').closest('.form-group').removeClass('d-none');
                    $('label[for="codigo"], label[for="marca"], label[for="modelo"]').parent().removeClass('d-none');

                    let htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="GuardarBomba('${id_cliente}')">Guardar</button>`;

                    $('#modal-footer-bomba').html(htmlBotones);
                    // Carga marcas y demás configuración necesaria
                    CargaConfig();
                    CargaMarcas('marca_bomba', 0);
                } else {
                    console.log('else bomba');
                    // Muestra lista de bombas y ofrece crear una nueva
                    $('#tabla_bombas').DataTable().destroy();
                    $("#tabla_bombas tbody").empty();

                    $.each(data, function(index, value) {
                        $("#tabla_bombas tbody").append('<tr>' +
                            '<td>' + value.codigo + '</td>' +
                            '<td>' + value.marca + '</td>' +
                            '<td>' + value.modelo + '</td>' +
                            '<td class="text-start">' +
                                '<a class="btn icon btn-dark" title="Crear Presupuesto" href="<?= base_url('/public/presupuesto') ?>/' + value.token + '">' +
                                            '<i class="fas fa-file-alt"></i>' +
                                        '</a>' + 
                                 ' <a class="btn icon btn-primary" title="Crear Orden de Servicio" href="<?= base_url('/public/orden-servicio/crear-os/') ?>' + value.token + '"><i class="fas fa-wrench"></i></a>' +                                   
                                // Puedes agregar más botones aquí (editar, eliminar, etc)
                            '</td>' +
                        '</tr>');
                    });

                    let htmlBotones = `<button type="button" class="btn btn-primary" onclick="AgregarBomba('${id_cliente}')">Agregar Bomba o Inyector <i class="fas fa-plus"></i></button>`;

                    $('#modal-footer-lista-bombas').html(htmlBotones);
           
                    $('#lista-bombas').modal('show');
             

                    $('#tabla_bombas').DataTable({
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                        },
                        "pageLength": 4
                    });
                }
            }
        });
    }

    function AgregarBomba(id_cliente){
        $('#lista-bombas').modal('hide');
        $('#crear-editar-bomba').modal('show');

        $('#codigo, #marca_bomba, #modelo_bomba').closest('.form-group').removeClass('d-none');
        $('label[for="codigo"], label[for="marca"], label[for="modelo"]').parent().removeClass('d-none');

        let htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="GuardarBomba('${id_cliente}')">Guardar</button>`;

        $('#modal-footer-bomba').html(htmlBotones);
        CargaConfig();
        CargaMarcas('marca_bomba', 0);
    }


    function GuardarBomba(id_cliente) {
        const arrCampos = [
            ['codigo', 'text'],
            ['marca_bomba', 'select'],
            ['modelo_bomba', 'select']
        ];

        if (ValidaCamposObligatorios(arrCampos) != false) {
            $.ajax({
                data: {
                    'codigo': $('#codigo').val().trim(),
                    'id_marca': $('#marca_bomba').val(),
                    'id_modelo': $('#modelo_bomba').val(),
                    'id_cliente': id_cliente
                },
                dataType: "json",
                type: "POST",
                url: "<?= base_url('/public/config/GuardarBomba') ?>",
                error: function() {
                    MyAlert('Imposible guardar los datos', 'error');
                },
                success: function(data) {
                    $('#crear-editar-bomba').modal('hide');
                    MuestraBombas(id_cliente);
                }
            });
        }
    }




    function CargaConfig() {
        // Buscar todos los elementos con la clase 'select2'
        $('.select2').each(function() {            
            $(this).select2({
                theme: "bootstrap-5",
                dropdownParent: $(this).closest('.modal'), // 👈 importante para que funcione en modals
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style'
            });
        });
    }


     function CargaMarcas(select, id_marca){        
        $.ajax({
            dataType: "json",
            url: '<?= base_url() ?>public/config/GetMarcas',
            error: function(jqXHR, textStatus, errorThrown){
                MyAlert('Imposible cargar la lista de marcas', 'error');
            },
            success: function(data){
                $("#" + select).empty();
                $("#" + select).append('<option value="" disabled selected>Seleccione una marca</option>');
                $("#" + select).append('<option value="nuevo">🆕 Crear nueva marca</option>');
                $.each(data, function(i, item){                                        
                    $("#" + select).append('<option value="' + item.id + '">' + item.nombre + '</option>');     
                });    
                if(id_marca > 0){
                    $("#" + select).val(id_marca);
                }
            }
        });
    }

    //Cuando elige marca se carga la lista de modelos
    $('#marca').on('change', function() {
        let id_marca = $(this).val(); // Obtiene el id de la marca seleccionada
        CargaModelos(id_marca,"0","modelo"); // Llama a la función pasando el ID
    });

    //Cuando elige marca se carga la lista de modelos
    $('#marca_bomba').on('change', function() {
        let id_marca = $(this).val(); // Obtiene el id de la marca seleccionada
        CargaModelos(id_marca,"0","modelo_bomba"); // Llama a la función pasando el ID
    });


    function CargaModelos(id_marca, id_modelo, select){        
        $.ajax({
            dataType: "json",
            type: "POST",
            url: '<?= base_url() ?>public/config/GetModelos',
            data: { 'id_marca': id_marca },
            error: function(jqXHR, textStatus, errorThrown){
                MyAlert('Imposible cargar la lista de modelos', 'error');
            },
            success: function(data){

                $("#"+select).empty();
                $("#"+select).append('<option value="" disabled selected>Seleccione un modelo</option>');
                $("#"+select).append('<option value="nuevo">🆕 Crear nuevo modelo</option>');
                $.each(data, function(i, item){                                        
                    $("#"+select).append('<option value="' + item.id + '">' + item.nombre + '</option>');     
                }); 
                if(id_modelo > 0){
                    $('#'+select).val(id_modelo);
                }                   
            }
        });
    }



     function CargaClientes(){        
        $.ajax({

                    dataType:"json",

                    url:"<?= base_url('/public/config/GetClientes') ?>",   

                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar la lista de clientes','error');

                    },success: function(data){                                  
                        $('#select_clientes').append('<option value="nuevo">🆕 Crear Cliente</option>');
                        $.each(data, function(index, item) {                                                                                          
                            $('#select_clientes').append(
                                `<option value="${item.rut_cliente}">${item.rut_cliente} - ${item.nombre_cliente}</option>`
                            );
                        });                        
                        // Inicializa o actualiza Select2 después de cargar las opciones
                        $('#select_clientes').select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $('#crear-editar-registro')
                        });
                    }                   
            });

    }


    //Levanta modal para editar el registro
    function Editar(id_cliente){
        $('#btn_buscar').addClass('d-none');
        //Obteniendo registro del cliente que quiero editar
        $.ajax({

                    dataType:"json",

                    type: "POST",

                    data: { 'id_cliente': id_cliente},

                    url:"<?= base_url('/public/config/GetRegistroCliente') ?>",



                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar datos del cliente','error');

                    },success: function(data){                  
                                 
                          //Abrimos el modal
                          $('#crear-editar-registro').modal('show');                 

                          //Ocultamos el select                   
                          $('#select_clientes').closest('.form-group').addClass('d-none');
                          
                          // Quitar 'd-none' de los labels e inputs de los campos ocultos
                          $('#rut_cliente').closest('.form-group').removeClass('d-none');
                          $('label[for="rut_cliente"]').parent().removeClass('d-none');  

                          $('#nombre_cliente').closest('.form-group').removeClass('d-none');
                          $('label[for="nombre_cliente"]').parent().removeClass('d-none');

                          $('#email_cliente').closest('.form-group').removeClass('d-none');
                          $('label[for="email_cliente"]').parent().removeClass('d-none');

                          $('#telefono_cliente').closest('.form-group').removeClass('d-none');
                          $('label[for="telefono_cliente"]').parent().removeClass('d-none');

                          $('#direccion_cliente').closest('.form-group').removeClass('d-none');
                          $('label[for="direccion_cliente"]').parent().removeClass('d-none');

                          //Cargamos los datos del cliente
                          $('#nombre_cliente').val(data.nombre_cliente);   
                          $('#rut_cliente').val(data.rut_cliente);   
                          $('#email_cliente').val(data.email_cliente);   
                          $('#telefono_cliente').val(data.telefono_cliente);   
                          $('#direccion_cliente').val(data.direccion_cliente);            

                          //Quiero que al ser update el botón cambie a evento update
                          htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="Update('+id_cliente+')" id="confirmEnvioButton">Guardar</button>';
                          $('#modal-footer-edit').html(htmlBotones);
                    }                   
        });
    }



    //Actualiza los datos del registro
    function Update(id_cliente){
         const arrCampos = [          
                ['rut_cliente', 'text'],   
                ['nombre_cliente', 'text'],    
                ['rut_cliente', 'text'],      
                ['telefono_cliente', 'text']

        ];        
        if(ValidaCamposObligatorios(arrCampos)!=false){    
         // Realizar la solicitud AJAX
                $.ajax({
                    data: { 'nombre_cliente': $('#nombre_cliente').val(),
                        'rut_cliente': $('#rut_cliente').val(),
                        'email_cliente': $('#email_cliente').val(),
                        'telefono_cliente': $('#telefono_cliente').val(),
                        'direccion_cliente': $('#direccion_cliente').val(),
                        'id_cliente': id_cliente,
                        'password': $('#password').val()
                    },
                    dataType: "json",
                    type: "POST",
                    url: "<?= base_url('/public/config/UpdateCliente') ?>",

                    error: function (jqXHR, textStatus, errorThrown) {
                        MyAlert('Imposible guardar los datos', 'error');
                    },
                    success: function (data) {     
                        $('#crear-editar-registro').modal('hide');
                        MyAlert('El registro ha sido guardado con éxito', 'exito');
                        buscarRut();   
                    }
                });
        }//Cierra if valida campos        
    }

     // Función para manejar la eliminación
    function Eliminar(id_cliente) {
         // Mostrar el modal de confirmación
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
            keyboard: false,
        });

        confirmModal.show();

        // Seleccionar el botón de confirmación
        const confirmButton = document.getElementById('confirmDeleteButton');

        // Resetear eventos previos del botón
        confirmButton.replaceWith(confirmButton.cloneNode(true)); // Esto limpia eventos previos
        const newConfirmButton = document.getElementById('confirmDeleteButton'); // Re-seleccionar el botón clonado

        // Asignar el evento de confirmación al botón de eliminar
        newConfirmButton.onclick = function () {
            confirmModal.hide(); // Cerrar el modal antes de ejecutar el AJAX

            // Realizar la solicitud AJAX
            $.ajax({
                data: { 'id_cliente': id_cliente },
                dataType: "json",
                type: "POST",
                url: "<?= base_url('/public/config/EliminaCliente') ?>",

                error: function (jqXHR, textStatus, errorThrown) {
                    MyAlert('Imposible eliminar el item', 'error');
                },
                success: function (data) {                           
                    MyAlert('Registro eliminado correctamente', 'exito');              
                    $('#tabla_clientes').DataTable().destroy();       
                    $("#tabla_clientes tbody").empty();   
                }
            });
        };
    }
</script>

</html>