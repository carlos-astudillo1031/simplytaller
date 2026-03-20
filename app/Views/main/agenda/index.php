<?php echo view("references/header"); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />  
<?php echo view("references/navbar"); ?>  

<style>

#calendar {
    width: 100%;
    background: white;
    padding: 10px;
    border-radius: 6px;
}
.fc-toolbar-title {
    font-size: 1.2rem;
}

 @media (max-width: 768px) { 
     .card {
            margin: 20px 15px !important;
        }
    }   
  @media (max-width: 768px) {
    #calendar {
        overflow-x: auto; /* permite scroll horizontal */
    }
}

/* Ajuste general para que no desborde en móviles */
@media (max-width: 768px) {
    #calendar .fc-toolbar-title {
        font-size: 1rem;       /* Más pequeño */
        white-space: normal;   /* Permite que haga wrap */
        text-align: left;      /* Alineado a la izquierda */
        overflow-wrap: break-word; /* Evita desbordes largos */
        max-width: 70%;        /* Ajusta según convenga */
    }

    /* Si quieres que los botones queden más compactos */
    #calendar .fc-toolbar.fc-toolbar-chunk {
        flex-wrap: wrap;
        justify-content: space-between;
    }
}

    
</style>

<div class="content-wrapper container" style="padding-right:40px"> 
    <section class="row">        
        <div class="col-12 col-lg-3"></div>            
        <div class="card w-100">
            <div class="card-header d-flex align-items-center">
                <div class="page-heading d-flex align-items-center">
                    <h4 style="margin-bottom:-32px">Agenda</h4>
                </div>
                <button onclick="AbreModalCliente()" class="btn btn-primary ms-auto">
                    <i class="fas fa-calendar"></i> Nueva Cita
                </button>
            </div>                              

            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </section>
</div>

<!-- Modal para agregar/editar cliente y agenda -->
<div class="modal fade text-left show" id="crear-editar-registro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Registro de Cliente / Agenda</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="cuerpo-modal" class="row form-group">
                    <form class="form form-horizontal" id="formAgendaCliente">
                        <div class="form-body">
                            <div class="row">
                                <!-- Select cliente -->
                                <div class="col-md-12 form-group d-flex">
                                    <select class="form-select" id="select_clientes">
                                        <option selected disabled>Buscar Cliente</option>
                                        <!-- Opciones se cargarán vía AJAX -->
                                    </select>
                                </div>
                                <span id="error-rut" class="text-danger d-none">El rut no es válido</span>

                                <!-- Campos ocultos del cliente -->
                                <div class="col-md-4 d-none"><label for="rut_cliente">Rut</label></div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="rut_cliente" id="rut_cliente" placeholder="Rut del Cliente">
                                </div>
                                <div class="col-md-4 d-none"><label for="nombre_cliente">Nombre</label></div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del Cliente">
                                </div>
                                <div class="col-md-4 d-none"><label for="email_cliente">Email</label></div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" class="form-control" name="email_cliente" id="email_cliente" placeholder="Email del Cliente">
                                </div>
                                <div class="col-md-4 d-none"><label for="telefono_cliente">Teléfono</label></div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" id="telefono_cliente" class="form-control" name="telefono_cliente" placeholder="Teléfono del Cliente">
                                </div>
                                <div class="col-md-4 d-none"><label for="direccion_cliente">Dirección</label></div>
                                <div class="col-md-8 form-group d-none">
                                    <input type="text" id="direccion_cliente" class="form-control" name="direccion_cliente" placeholder="Dirección del Cliente">
                                </div>

                                <!-- CAMPOS DE AGENDA OCULTOS -->
                                <div class="col-md-6 d-none form-group">
                                    <label for="marca">Marca</label>
                                    <select class="form-select" id="marca" name="marca">
                                        <option value="">Seleccione marca</option>
                                        <option value="1">Toyota</option>
                                        <option value="2">Honda</option>
                                    </select>
                                </div>

                                <div class="col-md-6 d-none form-group">
                                    <label for="modelo">Modelo</label>
                                    <select class="form-select" id="modelo" name="modelo">
                                        <option value="">Seleccione modelo</option>
                                        <!-- Se puede llenar dinámicamente según marca -->
                                    </select>
                                </div>

                                <div class="col-md-6 d-none form-group">
                                    <label for="fechaCita">Fecha</label>
                                    <input type="date" class="form-control" id="fechaCita" name="fechaCita">
                                </div>

                                <div class="col-md-6 d-none form-group">
                                    <label for="horaCita">Hora</label>
                                    <select class="form-select" id="horaCita" name="horaCita">                                        
                                        <option value="08:00">08:00</option>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="13:00">13:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                        <option value="18:00">18:00</option>
                                    </select>

                                </div>

                                <div class="col-md-12 d-none form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <!-- Footer vacío, los botones se agregan externamente si se desea -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para modificar o eliminar cita -->
<div class="modal fade" id="modifica-cita" tabindex="-1" aria-labelledby="modificaCitaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="modificaCitaLabel">Detalles de la Cita</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Cliente / Vehículo</label>
          <p id="tituloCita" class="mb-0 text-muted">-</p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Fecha</label>
          <input type="date" class="form-control" id="nuevafechaCita" name="nuevafechaCita">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Hora</label>
          <select class="form-select" id="nuevahoraCita" name="nuevahoraCita">                                        
                                        <option value="08:00">08:00</option>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="13:00">13:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                        <option value="18:00">18:00</option>
           </select>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Acciones
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="ActualizaCita()">Guardar</a></li>
            <li><a class="dropdown-item text-danger" href="#" onclick="EliminaCita()">Anular</a></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>


<?php echo view("references/footer"); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $.ajax({
        url: '<?php echo base_url('/public/agenda/GetCitas'); ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const citas = response;
            console.log(citas);

            var calendarEl = document.getElementById('calendar');

            // Detectar si es móvil
            var isMobile = window.innerWidth < 768;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listWeek' : 'timeGridDay',
                locale: 'es',
                height: isMobile ? 'auto' : '75vh',
                expandRows: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '18:00:00',
                slotDuration: '01:00:00',
                allDaySlot: false,
                displayEventTime: false,
                headerToolbar: {
                    left: isMobile ? 'prev,next': 'prev,next today', 
                    center: 'title',
                    right: isMobile ? '' : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: citas,
                editable: false,
                selectable: true,
                select: function(info) {
                    var cliente = prompt("Nombre del cliente:");
                    var patente = prompt("Patente del vehículo:");
                    if(cliente && patente) {
                        calendar.addEvent({
                            title: patente + " - " + cliente,
                            start: info.start
                        });
                    }
                    calendar.unselect();
                },
                eventContent: function(arg) {
                    return { html: arg.event.title };
                },
                eventClick: function(info) {
                    const evento = info.event;

                    if (evento.extendedProps.tipo === 'cita') {
                        // Mostrar título
                        var tempEl = document.createElement('div');
                        tempEl.innerHTML = evento.title;
                        $('#tituloCita').text(tempEl.textContent || tempEl.innerText || '-');

                        // Fecha
                        const fecha = evento.start.toISOString().slice(0,10);
                        $('#nuevafechaCita').val(fecha);

                        // Hora (ajustada a local)
                        const horaLocal = evento.start.toLocaleTimeString('es-CL', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        });
                        $('#nuevahoraCita').val(horaLocal);

                        // Guardar el ID del evento
                        $('#modifica-cita').data('id', evento.id);

                        // Mostrar el modal
                        $('#modifica-cita').modal('show');
                    }
                }
            });

            calendar.render();

            // Ajustar si se redimensiona ventana
            window.addEventListener('resize', function() {
                var newIsMobile = window.innerWidth < 768;
                if (newIsMobile !== isMobile) {
                    location.reload(); // recarga para recalcular vista móvil/PC
                }
            });
        }
    });
});



function AbreModalCliente(){
        //Pasamos a d-none por si no lo mantiene
        $('#rut_cliente').closest('.form-group').addClass('d-none');
        $('label[for="rut_cliente"]').parent().addClass('d-none');

        $('#nombre_cliente').closest('.form-group').addClass('d-none');
        $('label[for="nombre_cliente"]').parent().addClass('d-none');

        $('#email_cliente').closest('.form-group').addClass('d-none');
        $('label[for="email_cliente"]').parent().addClass('d-none');

        $('#telefono_cliente').closest('.form-group').addClass('d-none');
        $('label[for="telefono_cliente"]').parent().addClass('d-none');

        $('#direccion_cliente').closest('.form-group').addClass('d-none');
        $('label[for="direccion_cliente"]').parent().addClass('d-none');

         //Agregar 'd-none' de los labels e inputs de los campos ocultos de agenda
        $('#marca').closest('.form-group').addClass('d-none');
        $('label[for="marca"]').parent().addClass('d-none');

        $('#modelo').closest('.form-group').addClass('d-none');
        $('label[for="modelo"]').parent().addClass('d-none');

        $('#fechaCita').closest('.form-group').addClass('d-none');
        $('label[for="fechaCita"]').parent().addClass('d-none');

        $('#horaCita').closest('.form-group').addClass('d-none');
        $('label[for="horaCita"]').parent().addClass('d-none');

        $('#descripcion').closest('.form-group').addClass('d-none');
        $('label[for="descripcion"]').parent().addClass('d-none');


        //Limpiados los datos del cliente si fuera necesario        
        $('#nombre_cliente').val('');   
        $('#rut_cliente').val('');   
        $('#email_cliente').val('');   
        $('#telefono_cliente').val('');   
        $('#direccion_cliente').val('');            
        $('#btn_buscar').removeClass('d-none');
        $('#crear-editar-registro').modal('show');    

        //Limpiamos botones
        htmlBotones = '';
        $('#modal-footer-edit').html(htmlBotones);
        CargaClientes()     
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

                                //Agregar 'd-none' de los labels e inputs de los campos ocultos de agenda
                                $('#marca').closest('.form-group').addClass('d-none');
                                $('label[for="marca"]').parent().addClass('d-none');

                                $('#modelo').closest('.form-group').addClass('d-none');
                                $('label[for="modelo"]').parent().addClass('d-none');

                                $('#fechaCita').closest('.form-group').addClass('d-none');
                                $('label[for="fechaCita"]').parent().addClass('d-none');

                                $('#horaCita').closest('.form-group').addClass('d-none');
                                $('label[for="horaCita"]').parent().addClass('d-none');

                                $('#descripcion').closest('.form-group').addClass('d-none');
                                $('label[for="descripcion"]').parent().addClass('d-none');


                                htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="Guardar()" id="confirmEnvioButton">Guardar</button>';
                                $('#modal-footer-edit').html(htmlBotones);

                            }else{
                                // agregar 'd-none' de los labels e inputs de los campos ocultos               
                                $('#rut_cliente').closest('.form-group').addClass('d-none');
                                $('label[for="rut_cliente"]').parent().addClass('d-none');

                                $('#nombre_cliente').closest('.form-group').addClass('d-none');
                                $('label[for="nombre_cliente"]').parent().addClass('d-none');

                                $('#email_cliente').closest('.form-group').addClass('d-none');
                                $('label[for="email_cliente"]').parent().addClass('d-none');

                                $('#telefono_cliente').closest('.form-group').addClass('d-none');
                                $('label[for="telefono_cliente"]').parent().addClass('d-none');

                                $('#direccion_cliente').closest('.form-group').addClass('d-none');
                                $('label[for="direccion_cliente"]').parent().addClass('d-none');

                                

                                //Carga lista de marcas
                                CargaMarcas('marca',0);         
                                //Quitar 'd-none' de los labels e inputs de los campos ocultos de agenda                       
                                $('#marca').closest('.form-group').removeClass('d-none');
                                $('label[for="marca"]').parent().removeClass('d-none');

                                $('#modelo').closest('.form-group').removeClass('d-none');
                                $('label[for="modelo"]').parent().removeClass('d-none');

                                $('#fechaCita').closest('.form-group').removeClass('d-none');
                                $('label[for="fechaCita"]').parent().removeClass('d-none');

                                $('#horaCita').closest('.form-group').removeClass('d-none');
                                $('label[for="horaCita"]').parent().removeClass('d-none');

                                $('#descripcion').closest('.form-group').removeClass('d-none');
                                $('label[for="descripcion"]').parent().removeClass('d-none');

                                //Limpia botones
                                htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="GuardarCita('+data.id_cliente+')" id="confirmAgendaButton">Guardar</button>';
                                $('#modal-footer-edit').html(htmlBotones);                                                                
                            }
                    }                   
        });
     }

     //Guardar Cliente
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
                             // agregar 'd-none' de los labels e inputs de los campos ocultos               
                             $('#select_clientes').closest('.form-group').addClass('d-none');
                             $('label[for="select_clientes"]').parent().addClass('d-none');
                             
                             $('#rut_cliente').closest('.form-group').addClass('d-none');
                             $('label[for="rut_cliente"]').parent().addClass('d-none');

                            $('#nombre_cliente').closest('.form-group').addClass('d-none');
                            $('label[for="nombre_cliente"]').parent().addClass('d-none');

                            $('#email_cliente').closest('.form-group').addClass('d-none');
                            $('label[for="email_cliente"]').parent().addClass('d-none');

                            $('#telefono_cliente').closest('.form-group').addClass('d-none');
                            $('label[for="telefono_cliente"]').parent().addClass('d-none');

                            $('#direccion_cliente').closest('.form-group').addClass('d-none');
                            $('label[for="direccion_cliente"]').parent().addClass('d-none');

                            //Carga lista de marcas
                            CargaMarcas('marca',0);  
                                   
                            //Quitar 'd-none' de los labels e inputs de los campos ocultos de agenda                       
                            $('#marca').closest('.form-group').removeClass('d-none');
                            $('label[for="marca"]').parent().removeClass('d-none');

                            $('#modelo').closest('.form-group').removeClass('d-none');
                            $('label[for="modelo"]').parent().removeClass('d-none');

                            $('#fechaCita').closest('.form-group').removeClass('d-none');
                            $('label[for="fechaCita"]').parent().removeClass('d-none');

                            $('#horaCita').closest('.form-group').removeClass('d-none');
                            $('label[for="horaCita"]').parent().removeClass('d-none');

                            $('#descripcion').closest('.form-group').removeClass('d-none');
                            $('label[for="descripcion"]').parent().removeClass('d-none');

                            //Limpia botones
                            htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="GuardarCita('+data.id_cliente+')" id="confirmAgendaButton">Guardar</button>';
                            $('#modal-footer-edit').html(htmlBotones);                                                                    
                    }
                });
        }//Cierra if valida campos        
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
            },
            success: function(data){  
                const $select = $('#select_clientes');

                // Limpiar todas las opciones excepto el placeholder (el primero)
                $select.find('option:not(:first)').remove();

                // Agregar opción de crear nuevo cliente
                $select.append('<option value="nuevo">🆕 Crear Cliente</option>');

                // Agregar clientes desde el JSON
                $.each(data, function(index, item) {                                                                                          
                    $select.append(
                        `<option value="${item.rut_cliente}">${item.rut_cliente} - ${item.nombre_cliente}</option>`
                    );
                });

                // Inicializa o actualiza Select2 después de cargar las opciones
                $select.select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#crear-editar-registro')
                });

                // Selecciona el placeholder (primera opción)
                $select.val($select.find('option:first').val()).trigger('change');
            }                   
        });
    }

function GuardarCita(id_cliente){      
    const arrCampos = [           
            ['marca', 'select'],
            ['modelo', 'select'],           
            ['horaCita', 'select'],
            ['fechaCita', 'text']            
    ];

    //Validamos campos
    if (ValidaCamposObligatorios(arrCampos) != false) {
        const marca = $('#marca').val();
        const modelo = $('#modelo').val();
        const fechaCita = $('#fechaCita').val();
        const horaCita = $('#horaCita').val();
        const descripcion = $('#descripcion').val();

        //Guardamos la cita
        $.ajax({
            data: { 'id_cliente': id_cliente,
                'marca': marca,
                'modelo': modelo,
                'fechaCita': fechaCita,
                'horaCita': horaCita,
                'descripcion': descripcion
            },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/agenda/GuardarCita') ?>",
            error: function (jqXHR, textStatus, errorThrown) {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function (data) {
                MyAlert('Hora agendada correctamente', 'exito');
                $('#crear-editar-registro').modal('hide');                    
                location.reload();                 
            }
        })
    }
}

function ActualizaCita() {
    const id = $('#modifica-cita').data('id');
    const fecha = $('#nuevafechaCita').val();
    const hora = $('#nuevahoraCita').val();

    $.ajax({
        url: '<?= base_url('/public/agenda/ActualizarCita') ?>',
        method: 'POST',
        data: { id, fecha, hora },
        dataType: 'json',
        success: function(res) {
            MyAlert('Cita actualizada correctamente', 'exito');
            $('#modifica-cita').modal('hide');
            location.reload();
        },
        error: function() {
            MyAlert('Error al actualizar la cita', 'error');
        }
    });
}

function EliminaCita() {
    const id = $('#modifica-cita').data('id');

    if (confirm('¿Estás seguro de eliminar esta cita?')) {
        $.ajax({
            url: '<?= base_url('/public/agenda/EliminarCita') ?>',
            method: 'POST',
            data: { id },
            dataType: 'json',
            success: function(res) {
                MyAlert('Cita eliminada correctamente', 'exito');
                $('#modifica-cita').modal('hide');
                location.reload();
            },
            error: function() {
                MyAlert('Error al eliminar la cita', 'error');
            }
        });
    }
}


//Al elegir cliente setea campo rut. Esto se hizo asi para no afectar la logica original que solo consideraba este campo de rut.
$(document).on('change', '#select_clientes', function() {        
    const rut = $(this).val();

    // Ignorar si es el placeholder
    if(!rut) { 
        $('#rut_cliente').val('');
        return;
    }

    // Si es un RUT válido, continuar
    $('#rut_cliente').val(rut);
    buscarRut();
});



</script>


