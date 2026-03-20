<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/public/assets/compiled/css/table-datatable-jquery.css">

<?php echo view("references/navbar"); ?>    

 <style>
@media (max-width: 768px) {
         #tabla_vehiculos .btn {
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
        <div class="col-12 col-lg-3"></div>            
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="page-heading d-flex align-items-center">
                   <!--  <a href="#" class="burger-btn d-flex align-items-center me-3">
                        <i class="bi bi-justify fs-3"></i>
                    </a> -->
                    <h4 style="margin-bottom:-32px">Vehículos</h4>
                </div>
            </div>                              
            <div class="card-body">
               <div class="table-responsive">
                <table id="tabla_vehiculos" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="10%">Patente</th>
                            <th width="15%">Marca</th>
                            <th width="15%">Modelo</th>
                            <th width="5%">Año</th>
                            <th width="10%">Color</th>
                            <th width="20%">Chasis</th>
                            <th width="35%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>   
                    </tbody>
                </table>
               </div>  
            </div>
        </div>
    </section>
</div>

<?php echo view("references/footer"); ?>

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
                                <button type="button" id="btn_buscar" class="btn btn-primary ms-2" onclick="buscarPatente()">
                                    <i class="fas fa-search"></i>
                                </button>
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
            <div class="modal-footer" id="modal-footer-edit">
                <!-- Botones se cargan dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        AbreModalVehiculo();        
    });

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
        CargaModelos(id_marca,"0"); // Llama a la función pasando el ID
    });


    function CargaModelos(id_marca, id_modelo){        
        $.ajax({
            dataType: "json",
            type: "POST",
            url: '<?= base_url() ?>public/config/GetModelos',
            data: { 'id_marca': id_marca },
            error: function(jqXHR, textStatus, errorThrown){
                MyAlert('Imposible cargar la lista de modelos', 'error');
            },
            success: function(data){
                $("#modelo").empty();
                $("#modelo").append('<option value="" disabled selected>Seleccione un modelo</option>');
                $("#modelo").append('<option value="nuevo">🆕 Crear nuevo modelo</option>');
                $.each(data, function(i, item){                                        
                    $("#modelo").append('<option value="' + item.id + '">' + item.nombre + '</option>');     
                }); 
                if(id_modelo > 0){
                    $('#modelo').val(id_modelo);
                }                   
            }
        });
    }


    function AbreModalVehiculo() {
        $('#patente, #marca, #modelo, #anio, #color, #chasis').val('');
        $('#btn_buscar').removeClass('d-none');
        $('#crear-editar-vehiculo').modal('show');
        CargaMarcas('marca', '0');
        CargaConfig();
    }

    function GuardarVehiculo() {
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
                    'patente': $('#patente').val(),
                    'marca': $('#marca').val(),
                    'modelo': $('#modelo').val(),
                    'anio': $('#anio').val(),
                    'color': $('#color').val(),
                    'chasis': $('#chasis').val()
                },
                dataType: "json",
                type: "POST",
                url: "<?= base_url('/public/config/GuardarVehiculo') ?>", 
                error: function() {
                    MyAlert('Imposible guardar los datos', 'error');
                },
                success: function(data) {
                    buscarPatente();
                    $('#crear-editar-vehiculo').modal('hide');
                }
            });
        }
    }

    function buscarPatente() {
        let patente = $('#patente').val().trim().toUpperCase().replace(/-/g, '');
        $('#patente').val(patente);
        $.ajax({
            dataType: "json",
            type: "POST",
            data: { 'patente': patente },
            url: "<?= base_url('/public/config/GetVehiculoPorPatente') ?>",
            error: function() {
                MyAlert('Imposible encontrar el vehículo', 'error');
            },
            success: function(data) {
                if (data === false) {
                    /* $('#marca, #modelo, #anio, #color, #chasis').closest('.form-group').removeClass('d-none');
                    $('label[for="marca"], label[for="modelo"], label[for="anio"], label[for="color"], label[for="chasis"]').parent().removeClass('d-none');

                    let htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
                                      '<button type="button" class="btn btn-primary" onclick="GuardarVehiculo()">Guardar</button>';
                    $('#modal-footer-edit').html(htmlBotones); */
                    $('#vehiculo_no_encontrado').removeClass('d-none');
                } else {
                    $('#tabla_vehiculos').DataTable().destroy();
                    $("#tabla_vehiculos tbody").empty();

                    $("#tabla_vehiculos tbody").append('<tr>' +
                        '<td>' + data.patente + '</td>' +
                        '<td>' + data.marca + '</td>' +
                        '<td>' + data.modelo + '</td>' +
                        '<td>' + data.anio + '</td>' +
                        '<td>' + data.color + '</td>' +
                        '<td>' + data.chasis + '</td>' +
                        '<td class="text-start">' +
                            '<a class="btn icon btn-success me-1" onclick="Editar(\'' + data.id + '\')" href="#" data-bs-toggle="tooltip" title="Editar"><i class="fas fa-edit"></i></a>' +
                            '<a class="btn icon btn-dark" title="Crear Presupuesto" href="<?= base_url('/public/presupuesto/') ?>' + data.token + '"><i class="fas fa-file-alt"></i></a>' +
                            ' <a class="btn icon btn-primary" title="Crear Orden de Servicio" href="<?= base_url('/public/orden-servicio/crear-os/') ?>' + data.token + '"><i class="fas fa-wrench"></i></a>' +                            
                            ' <a class="btn icon btn-danger" title="Eliminar" onclick="Eliminar(\'' + data.id + '\')" href="#"><i class="fas fa-trash"></i></a>' +
                        '</td>' +
                    '</tr>');


                    $('#crear-editar-vehiculo').modal('hide');
                }
            }
        });
    }

    //Levanta modal para editar el registro de vehículo
function Editar(id_vehiculo){
    $('#btn_buscar').addClass('d-none');
    //Obteniendo registro del vehículo que quiero editar
    $.ajax({
        dataType:"json",
        type: "POST",
        data: { 'id_vehiculo': id_vehiculo },
        url:"<?= base_url('/public/config/GetRegistroVehiculo') ?>", 

        error:function(jqXHR, textStatus, errorThrown){
            MyAlert('Imposible cargar datos del vehículo','error');
        },
        success: function(data){         
            CargaMarcas('marca', data.id_marca);
            CargaModelos(data.id_marca, data.id_modelo);
            CargaConfig();         
            //Abrimos el modal
            $('#crear-editar-vehiculo').modal('show');                 

            // Quitar 'd-none' de los labels e inputs de los campos ocultos
            $('#patente').closest('.form-group').removeClass('d-none');
            $('label[for="patente"]').parent().removeClass('d-none');

            $('#marca').closest('.form-group').removeClass('d-none');
            $('label[for="marca"]').parent().removeClass('d-none');

            $('#modelo').closest('.form-group').removeClass('d-none');
            $('label[for="modelo"]').parent().removeClass('d-none');

            $('#anio').closest('.form-group').removeClass('d-none');
            $('label[for="anio"]').parent().removeClass('d-none');

            $('#color').closest('.form-group').removeClass('d-none');
            $('label[for="color"]').parent().removeClass('d-none');

            $('#chasis').closest('.form-group').removeClass('d-none');
            $('label[for="chasis"]').parent().removeClass('d-none');

            //Cargamos los datos del vehículo
            $('#patente').val(data.patente);   
            /* $('#marca').val(data.id_marca);  */  
            $('#modelo').val(data.id_modelo);   
            $('#anio').val(data.anio);   
            $('#color').val(data.color);   
            $('#chasis').val(data.chasis);            

            //Quiero que al ser update el botón cambie a evento update
           const htmlBotones = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="UpdateVehiculo(${id_vehiculo},${data.id_cliente})" id="confirmEnvioButton">
                    Guardar
                </button>
            `;
            $('#modal-footer-edit').html(htmlBotones);

        }                   
    });
}

//Actualiza los datos del registro de vehículo
function UpdateVehiculo(id_vehiculo, id_cliente){
    const arrCampos = [          
        ['patente', 'text'],    
        ['marca', 'text'],    
        ['modelo', 'text'],    
        ['anio', 'text'],    
        ['color', 'text'],
        ['chasis', 'text']
    ];        

    if(ValidaCamposObligatorios(arrCampos)!=false){    
        // Realizar la solicitud AJAX
        $.ajax({
            data: { 
                'patente': $('#patente').val(),
                'marca': $('#marca').val(),
                'modelo': $('#modelo').val(),
                'anio': $('#anio').val(),
                'color': $('#color').val(),
                'chasis': $('#chasis').val(),
                'id_vehiculo': id_vehiculo,
                'id_cliente': id_cliente
            },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/UpdateVehiculo') ?>", 

            error: function (jqXHR, textStatus, errorThrown) {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function (data) {     
                $('#crear-editar-vehiculo').modal('hide');
                MyAlert('El registro ha sido guardado con éxito', 'exito');
                buscarPatente(); // Ajusta según la función que refresque la tabla
            }
        });
    }
}

// Función para manejar la eliminación del vehículo
function Eliminar(id_vehiculo) {
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
            data: { 'id_vehiculo': id_vehiculo },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/EliminaVehiculo') ?>", 

            error: function (jqXHR, textStatus, errorThrown) {
                MyAlert('Imposible eliminar el vehículo', 'error');
            },
            success: function (data) {                           
                MyAlert('Vehículo eliminado correctamente', 'exito');              
                $('#tabla_vehiculos').DataTable().destroy();       
                $("#tabla_vehiculos tbody").empty();   
            }
        });
    };
}


</script>