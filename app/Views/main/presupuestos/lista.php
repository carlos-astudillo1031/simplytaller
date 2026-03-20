<?php echo view("references/header"); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<style>
    @media (max-width: 768px) {
        #tabla_presupuestos .btn {
            display: block;
            width: 100%;
            margin: 4px 0;
        }

        .card {
            margin: 20px 15px !important;
        }
    }
</style>
<?php $permisos_especiales = session()->get('permisos_especiales', []); ?>
    <?php echo view("references/navbar"); ?>    
        <div class="content-wrapper container" style="padding-right:40px"> 
            <section class="row">                    
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="page-heading d-flex align-items-center">
                      <!--   <a href="#" class="burger-btn d-flex align-items-center me-3">
                            <i class="bi bi-justify fs-3"></i>
                        </a> -->
                        <h4 style="margin-bottom:-32px">Presupuestos</h4>
                    </div>
                    <!-- Botón al extremo derecho -->
                    <button onclick="AbreModalCliente()" class="btn btn-primary ms-auto">
                        <i class="fas fa-history"></i>
                        <span class="d-none d-md-inline"> Consultar Histórico</span>
                    </button>

                    </div>                              
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_presupuestos" class="table table-bordered table-striped">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th width="5%" scope="col">N°</th>
                                                                <th width="10%" scope="col">Fecha</th>    
                                                                <th width="15%" scope="col">Cliente</th>
                                                                <th width="20%" scope="col">Vehiculo</th>
                                                                <th width="10%" scope="col">Total Neto</th>
                                                                <th width="15%" scope="col">Email</th>
                                                                <th width="10%" scope="col">Telefono</th>                     
                                                                <th width="15%" scope="col"></th>
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

<!-- Modal Presupuesto -->
<div class="modal fade" id="modalPresupuesto" tabindex="-1" aria-labelledby="modalPresupuestoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPresupuestoLabel">Presupuesto N° <span id="modal_num_presupuesto"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

            <!-- Sección Kms -->
            <div id="card_kms" class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Kilometraje del Vehiculo                    
                </div>
                <div class="card-body">
                    <table id="tabla_kms" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kms</th>                               
                            </tr>
                            <tr>
                                <td><input type="number" class="form-control" id="kms" name="kms" placeholder="Kms"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Servicios agregados dinámicamente -->
                        </tbody>
                    </table>                   
                    </div>
            </div>

            <!-- Sección Servicios -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Servicios                
                    <button id="btn_agregar_servicio" class="btn btn-primary btn-sm" onclick="agregarServicio()">
                        <i class="fa fa-plus"></i> Agregar Servicio
                    </button>   
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla_servicios" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario Neto</th>
                                    <th>Total Neto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Servicios agregados dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <strong>Subtotal Servicios: <span id="subtotal_servicios">0</span></strong>
                    </div>
                    </div>
                </div>

            <!-- Sección Repuestos -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Repuestos            
                    <button id="btn_agregar_repuesto" class="btn btn-primary btn-sm" onclick="agregarRepuesto()">
                        <i class="fa fa-plus"></i> Agregar Repuesto
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla_repuestos" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Repuesto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario Neto</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Repuestos agregados dinámicamente -->
                            </tbody>
                        </table>
                    </div>  
                    <div class="text-end">
                        <strong>Subtotal Repuestos: <span id="subtotal_repuestos">0</span></strong>
                    </div>
                </div>
            </div>

            <!-- Sección Resumen Totales -->
            <div class="card mb-4">
                <div class="card-header fw-bold" style="font-size: 1.2rem;">Resumen Totales</div>
                <div class="card-body row">
                    <div class="col-md-4 offset-md-8">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Subtotal Servicios:</th>
                                    <td><span id="resumen_servicios">0</span></td>
                                </tr>
                                <tr>
                                    <th>Subtotal Repuestos:</th>
                                    <td><span id="resumen_repuestos">0</span></td>
                                </tr>
                                <tr>
                                    <th>Total Neto:</th>
                                    <td><strong><span id="total_general">0</span></strong></td>
                                </tr>
                                <tr>
                                    <th>Iva:</th>
                                    <td><strong><span id="iva">0</span></strong></td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td><strong><span id="total_final">0</span></strong></td>
                                </tr>
                            </table>
                        </div>   
                    </div>                    
                </div>

            </div>

      </div>
      <div class="modal-footer" id="modal-footer-presupuesto">
        
      </div>
    </div>
  </div>
</div>

<!-- Modal: Agregar Servicio al Presupuesto -->
<div class="modal fade" id="modal-agregar-servicio" tabindex="-1" aria-labelledby="labelModalServicio" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="labelModalServicio">Agregar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="form-servicio">

          <div class="mb-3">
            <label for="select_servicio" class="form-label">Servicio</label>
            <select class="form-select" id="select_servicio">
              <option selected disabled>Seleccionar servicio</option>
              <!-- Opciones se cargarán vía AJAX -->
            </select>
          </div>

          <div class="col-md-4">
                <label for="cantidad_repuesto">Cantidad</label>
          </div>
          <div class="col-md-8 form-group">
               <input type="number" min="1" step="1" class="form-control" id="cantidad_servicio" name="cantidad_servicio" placeholder="Cantidad" value="1">
          </div>

          <div class="mb-3">
            <label for="precio_servicio" class="form-label">Precio Neto</label>
            <input type="number" class="form-control" id="precio_servicio" placeholder="Ej: 45000" disabled>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="confirmarAgregarServicio()">Agregar</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal para agregar/editar repuesto -->
<div class="modal fade text-left" id="crear-editar-repuesto" tabindex="-1" role="dialog" aria-labelledby="modalRepuestoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRepuestoLabel">Agregar Repuesto</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" id="form-repuesto">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="select_repuesto">Repuesto</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select id="select_repuesto" name="select_repuesto" class="form-control select2" style="width: 100%;">
                                    <option value="" disabled selected>-- Seleccione un repuesto --</option>
                                    <!-- Opciones cargadas dinámicamente -->
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="cantidad_repuesto">Cantidad</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" min="1" step="1" class="form-control" id="cantidad_repuesto" name="cantidad_repuesto" placeholder="Cantidad" value="1">
                            </div>

                            <div class="col-md-4">
                                <label for="precio_unitario_repuesto">Precio Unitario Neto</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" min="0" step="100" class="form-control" id="precio_unitario_repuesto" name="precio_unitario_repuesto" placeholder="Precio Unitario">
                            </div>
                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit-repuesto">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarAgregarRepuesto()" id="confirmEnvioButtonRepuesto">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Este Modal permite elegir cliente -->
<div class="modal fade text-left show" id="crear-editar-registro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Consulta histórico</h5>
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
                                    <!-- Opciones se cargarán vía AJAX -->
                                    </select>                                    
                                </div>                                                               
                                <span id="error-rut" class="text-danger d-none">El rut no es válido</span>

                                
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

<?php echo view("references/footer"); ?>
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>


<script>

 
$(document).ready(function() {
    CargaPresupuestos();
});



function CargaPresupuestos() {    
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/presupuesto/GetPresupuestos') ?>", // Cambia por tu endpoint real
        error: function() {
            MyAlert('Imposible cargar la lista de presupuestos', 'error');
        },
        success: function(data) {
            console.log(data);
            $('#tabla_presupuestos').DataTable().destroy();
            $("#tabla_presupuestos tbody").empty();

            $.each(data, function(i, item) {
                let vehiculo;         
                let es_vehiculo;       
                console.log('patente: ' + item.patente);
                if(item.patente==''){           
                    console.log('sin patente');         
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.codigo + ')';
                    es_vehiculo = 0;
                }else{
                    console.log('con patente');
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.patente + ')';
                    es_vehiculo = 1;
                }

                let email_cliente = item.email_cliente;
                if(email_cliente == null){
                    email_cliente = '---';
                }
                let displayEmail = email_cliente.length > 10 ? email_cliente.substring(0, 10) + "..." : email_cliente;    
                let td_email = `<td>${displayEmail}</td>`;
                if(email_cliente != '---'){
                    td_email =  `<td>
                           <span>${displayEmail}</span>
                           <button class="btn btn-sm btn-warning" onclick="copyToClipboard('${email_cliente}')"><i class="fas fa-copy"></i></button>
                        </td>` ;
                }                            
                $("#tabla_presupuestos tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.num_presupuesto}</span></td>` +
                        `<td>${item.fecha_creacion}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td>${vehiculo}</td>` +
                        `<td>${formatearPrecio(item.total)}</td>` +
                        td_email +                       
                        `<td>${item.telefono_cliente}</td>` +
                        `<td class="text-center">` +
                            `<a href="#" onclick="VerPresupuesto(${item.num_presupuesto},'${vehiculo}','${item.kms}',${es_vehiculo},${item.id_orden_servicio})" class="btn btn-primary btn-sm" title="Ver detalle"><i class="fas fa-eye"></i></a> ` +
                            `<a href="#" onclick="verPDFPresupuesto('${item.num_presupuesto}')" class="btn btn-dark btn-sm" title="Imprimir"><i class="fas fa-print"></i></a>`+            
                            ` <a href="#" onclick="verPDFPresupuesto('${item.num_presupuesto}', true)" class="btn btn-success btn-sm" title="Descargar PDF"><i class="fas fa-download"></i></a>`+
                        '</td>' +
                    '</tr>'
                );
            });

           $('#tabla_presupuestos').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                },
                "ordering": false
            });

        }
    });
}

function AbreModalCliente(){              
        $('#crear-editar-registro').modal('show');    
        CargaClientes()     
}

//Cuando elige el cliente va a buscar los presupuestos historicos
$('#select_clientes').on('change', function() {
   $.ajax({
       url: '<?= base_url('/public/presupuesto/GetPresupuestosByCliente') ?>',
       type: 'POST',
       data: { id_cliente: $(this).val() },
       dataType: 'json', 
       success: function(data) {
           $('#tabla_presupuestos').DataTable().destroy();
           $("#tabla_presupuestos tbody").empty();           
           $.each(data, function(i, item) {
                let vehiculo;         
                let es_vehiculo;       
                console.log('patente: ' + item.patente);
                if(item.patente==''){           
                    console.log('sin patente');         
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.codigo + ')';
                    es_vehiculo = 0;
                }else{
                    console.log('con patente');
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.patente + ')';
                    es_vehiculo = 1;
                }

                let email_cliente = item.email_cliente;
                if(email_cliente == null){
                    email_cliente = '---';
                }
                let displayEmail = email_cliente.length > 10 ? email_cliente.substring(0, 10) + "..." : email_cliente;    
                let td_email = `<td>${displayEmail}</td>`;
                if(email_cliente != '---'){
                    td_email =  `<td>
                           <span>${displayEmail}</span>
                           <button class="btn btn-sm btn-warning" onclick="copyToClipboard('${email_cliente}')"><i class="fas fa-copy"></i></button>
                        </td>` ;
                }                             
               $("#tabla_presupuestos tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.num_presupuesto}</span></td>` +
                        `<td>${item.fecha_creacion}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td>${vehiculo}</td>` +
                        `<td>${formatearPrecio(item.total)}</td>` +
                        td_email +                       
                        `<td>${item.telefono_cliente}</td>` +
                        `<td class="text-center">` +
                            `<a href="#" onclick="VerPresupuesto(${item.num_presupuesto},'${vehiculo}','${item.kms}',${es_vehiculo},${item.id_orden_servicio})" class="btn btn-primary btn-sm" title="Ver detalle"><i class="fas fa-eye"></i></a> ` +
                            `<a href="#" onclick="verPDFPresupuesto('${item.num_presupuesto}')" class="btn btn-dark btn-sm" title="Imprimir"><i class="fas fa-print"></i></a>`+            
                            ` <a href="#" onclick="verPDFPresupuesto('${item.num_presupuesto}', true)" class="btn btn-success btn-sm" title="Descargar PDF"><i class="fas fa-download"></i></a>`+
                        '</td>' +
                    '</tr>'
                );
           });
           $('#tabla_presupuestos').DataTable({
               "language": {
                   "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
               },
               "ordering": false
           });
           $('#crear-editar-registro').modal('hide');   
       }
   })
});


function CargaClientes(){        
        $.ajax({

                    dataType:"json",

                    url:"<?= base_url('/public/config/GetClientes') ?>",   

                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar la lista de clientes','error');

                    },success: function(data){              
                        $('#select_clientes')
                            .empty()
                            .append('<option value="">Seleccione un cliente</option>');                    
                        $.each(data, function(index, item) {                                                                                          
                            $('#select_clientes').append(
                                `<option value="${item.id_cliente}">${item.rut_cliente} - ${item.nombre_cliente}</option>`
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


function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("Correo copiado: " + text);
    }).catch(err => {
        console.error("Error al copiar: ", err);
    });
}

function VerPresupuesto(num_presupuesto, vehiculo, kms, es_vehiculo, id_orden_servicio) {
    let estado_ppto;
    $.ajax({
        data: { num_presupuesto: num_presupuesto },
        url: "<?= base_url('/public/presupuesto/GetDetallePresupuesto') ?>",
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            //Captura estado
            estado_ppto = response.estado;
           
            // Limpiar las tablas antes de insertar
            if(es_vehiculo == 1){                              
               $('#card_kms').removeClass('d-none');
               $('#kms').val(parseInt(kms));
               
            }else{                
                $('#card_kms').addClass('d-none');
            }
            

            $("#tabla_servicios tbody").empty();
            $("#tabla_repuestos tbody").empty();

            //permisos especiales
            var permisos_especiales = <?= json_encode($permisos_especiales) ?>;

            // Insertar SERVICIOS
            let total_servicios =0;
            $.each(response.servicios, function(i, item) {                
                const total = item.precio * item.cantidad;               
                total_servicios = parseFloat(total) + parseFloat(total_servicios);
              
                //Valida permiso de eliminar
                let tienePermisoEliminar = permisos_especiales.some(p => Number(p.id) === 2 && Number(p.activo) === 1);     
                       
                                          
                let btnEliminarServicio = '';
                if (estado_ppto != '2' && tienePermisoEliminar) { // solo mostrar si estado distinto de 2
                    btnEliminarServicio = `
                        <button class="btn btn-danger btn-sm btn-eliminar-servicio" type="button" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;                                     
                }
                $("#tabla_servicios tbody").append(`
                    <tr data-id-servicio="${item.id_servicio}">
                        <td>${item.nombre_servicio}</td>
                        <td class="cantidad_servicio">${item.cantidad}</td>
                        <td>${formatearPrecio(item.precio)}</td>
                        <td class="total_servicios">${formatearPrecio(total)}</td>
                        <td>${btnEliminarServicio}</td>
                    </tr>                    
                `);
            });
            $('#subtotal_servicios').text(formatearPrecio(total_servicios));
            // Insertar REPUESTOS
            let total_repuestos = 0;
            $.each(response.repuestos, function(i, item) {
                const total = item.precio_unitario * item.cantidad;
                total_repuestos = total_repuestos + total;
                              

                //Valida permiso de eliminar
                let tienePermisoEliminar = permisos_especiales.some(p => Number(p.id) === 2 && Number(p.activo) === 1);    
                                    

                let btnEliminarRepuesto = '';
                if (estado_ppto != '2' && tienePermisoEliminar) { // solo mostrar si estado distinto de 2
                    btnEliminarRepuesto = `<button class="btn btn-danger btn-sm" onclick="eliminarRepuesto('${item.id_repuesto}')"><i class="fa fa-trash"></i></button>`;                                       
                }

                $("#tabla_repuestos tbody").append(`
                    <tr data-id="${item.id_repuesto}">
                        <td>${item.nombre_repuesto} (${item.codigo})</td>
                        <td class="cantidad">${item.cantidad}</td>
                        <td>${formatearPrecio(item.precio_unitario)}</td>
                        <td class="total">${formatearPrecio(total)}</td>
                        <td>${btnEliminarRepuesto}</td>
                    </tr>
                `);
            });            
           
            $('#subtotal_repuestos').text(formatearPrecio(total_repuestos));
            $('#resumen_servicios').text(formatearPrecio(total_servicios));
            $('#resumen_repuestos').text(formatearPrecio(total_repuestos));
            let total_general = parseFloat(total_servicios) + parseFloat(total_repuestos);
            $('#total_general').text(formatearPrecio(total_general));
            let total_final = total_general * 1.19;
            $('#total_final').text(formatearPrecio(total_final));
            let iva = total_final - total_general;
            $('#iva').text(formatearPrecio(iva));

            id_orden_servicio = id_orden_servicio == null ? 'n/a' : id_orden_servicio;
            $('#modal_num_presupuesto').text(num_presupuesto + ' | ' + vehiculo + ' | ' + kms + ' kms | Os: ' + id_orden_servicio);
            
            //permisos especiales

            var permisos_especiales = <?= json_encode($permisos_especiales) ?>;
            console.log(permisos_especiales);
            console.log(permisos_especiales.some(p => p.id === 1));
            let tienePermiso1 = Array.isArray(permisos_especiales) && permisos_especiales.some(p => Number(p.id) === 1);

            let htmlBotones = '';
            if(estado_ppto == '2' || estado_ppto == '3' || !tienePermiso1){                
                $('#btn_agregar_servicio').prop('disabled', true);
                $('#btn_agregar_repuesto').prop('disabled', true);
                $('#kms').prop('disabled', true);
                htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`;
            }else{
                htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownOpcionesPresupuesto" data-bs-toggle="dropdown" aria-expanded="false">
                    Acciones
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownOpcionesPresupuesto">            
                    <li><a class="dropdown-item text-success" href="#" onclick="guardarPresupuestoYSalir('${num_presupuesto}',${es_vehiculo})">Guardar Cambios <i class="fas fa-save me-2"></i></a></li>
                    <li><a class="dropdown-item text-primary" href="#" onclick="ConvertirEnOS('${num_presupuesto}',${es_vehiculo})">Confirmar <i class="fas fa-check"></i></a></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="DesecharPresupuesto('${num_presupuesto}')">Desechar <i class="fas fa-trash-alt me-2"></i></a></li>           
                </ul>
            </div>`;
            }
            

            $('#modal-footer-presupuesto').html(htmlBotones);

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalPresupuesto'));
            modal.show();   

        },
        error: function() {
            MyAlert('Error al cargar el detalle del presupuesto','Error');
        }
    });
    
}


function agregarServicio() {
    $('#form-servicio')[0].reset(); // limpia el formulario
    $('#select_servicio').empty().append('<option selected disabled>Cargando...</option>');
    $('#precio_servicio').val('');

    // Abrir modal
    $('#modal-agregar-servicio').modal('show');

    // Cargar lista de servicios
    $.ajax({
        url: "<?= base_url('/public/config/GetServicios') ?>",
        method: "GET",
        dataType: "json",
        success: function(servicios) {
            $('#select_servicio').empty().append('<option selected disabled>Seleccionar servicio</option>');
            servicios.forEach(function(serv) {
                $('#select_servicio').append(
                    `<option value="${serv.id}" data-precio="${serv.precio}">${serv.nombre}</option>`
                );
            });
            // Inicializa o actualiza Select2 después de cargar las opciones
            $('#select_servicio').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal-agregar-servicio')
            });
        },
        error: function() {
            MyAlert('Error al cargar los servicios disponibles', 'error');
        }
    });
}

// Al cambiar la selección, actualiza el precio automáticamente
$(document).on('change', '#select_servicio', function() {
    let precio = $(this).find(':selected').data('precio');
    $('#precio_servicio').val(precio);
});

function confirmarAgregarServicio() {
    const idServicio = $('#select_servicio').val();
    const nombreServicio = $('#select_servicio option:selected').text();
    let cantidad = parseInt($('#cantidad_servicio').val()) || 1;
    const precioServicio = parseFloat($('#precio_servicio').val());

    if (!idServicio) {
        MyAlert('Por favor, selecciona un servicio', 'error');
        return;
    }

    if (cantidad <= 0) {
        MyAlert('La cantidad debe ser mayor a cero', 'error');
        return;
    }

    if (isNaN(precioServicio) || precioServicio <= 0) {
        MyAlert('Precio inválido', 'error');
        return;
    }

    // Verificar si el servicio ya fue agregado para evitar duplicados
    if ($('#tabla_servicios tbody tr[data-id-servicio="' + idServicio + '"]').length > 0) {
        MyAlert('Este servicio ya fue agregado', 'error');
        return;
    }

    // Calcular total
    let total = cantidad * precioServicio;

    // Crear nueva fila en la tabla
     const nuevaFila = `
        <tr data-id-servicio="${idServicio}">
            <td>${nombreServicio}</td>
            <td class="cantidad_servicio">${cantidad}</td>
            <td>$${precioServicio.toLocaleString()}</td>
            <td class="total_servicios">$${total.toLocaleString()}</td>
            <td>
                <button class="btn btn-danger btn-sm btn-eliminar-servicio" type="button" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    $('#tabla_servicios tbody').append(nuevaFila);

    // Actualizar subtotal
    actualizarSubtotalServicios();

    // Cerrar modal
    $('#modal-agregar-servicio').modal('hide');
}

// Función para actualizar subtotal servicios
function actualizarSubtotalServicios() {
    let subtotal = 0;
    $('#tabla_servicios tbody tr').each(function() {
        /* const totalText = $(this).find('td:nth-child(3)').text().replace(/\$|\./g, ''); */
        let totalText = $(this).find('.total_servicios').text().trim().replace(/\$|\./g, '');   
        const precio = parseFloat(totalText) || 0;        
        subtotal += precio;
    });    
    $('#subtotal_servicios').text(subtotal.toLocaleString());
    actualizarResumenTotales()
}


// Evento para eliminar servicio desde la tabla
$(document).on('click', '.btn-eliminar-servicio', function() {
    $(this).closest('tr').remove();
    actualizarSubtotalServicios();
    actualizarResumenTotales();
});

// Abrir modal para agregar repuesto
function agregarRepuesto() {
    // Limpiar formulario
    $('#form-repuesto')[0].reset();
    $('#select_repuesto').empty().append('<option value="" disabled selected>-- Cargando repuestos... --</option>');

    // Mostrar modal
    $('#crear-editar-repuesto').modal('show');

    // Inicializar o reinicializar select2 con tema Bootstrap 5 y allowClear
    $('#select_repuesto').select2({
        dropdownParent: $('#crear-editar-repuesto'),
        theme: 'bootstrap-5',
        placeholder: '-- Seleccione un repuesto --',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
    });

    // Cargar repuestos en el select vía AJAX
    $.ajax({
        url: "<?= base_url('/public/config/GetRepuestos') ?>",
        dataType: "json",
        success: function(data) {
            const $select = $('#select_repuesto');

            $select.empty().append('<option value="" disabled selected>-- Seleccione un repuesto --</option>');

            data.forEach(function(item) {
                $select.append(`<option value="${item.id}" data-precio="${item.precio}">${item.nombre} (${item.codigo})</option>`);
            });

            // Destruir e inicializar select2 después de cargar datos
            $select.select2('destroy').select2({
                dropdownParent: $('#crear-editar-repuesto'), // Asegura que funcione dentro del modal
                theme: 'bootstrap-5',
                width: '100%'
            });
        },
        error: function() {
            $('#select_repuesto').empty().append('<option value="" disabled>-- Error cargando repuestos --</option>');
            MyAlert('No se pudo cargar la lista de repuestos', 'error');
        }
    });

    // Al cambiar el repuesto, actualizar el precio unitario automáticamente
    $('#select_repuesto').off('change').on('change', function() {
        let precio = parseFloat($('option:selected', this).data('precio')) || 0;
        $('#precio_unitario_repuesto').val(precio);
    });
}

// Confirmar agregar repuesto a la tabla
function confirmarAgregarRepuesto() {
    let idRepuesto = $('#select_repuesto').val();
    let nombreRepuesto = $('#select_repuesto option:selected').text();
    let cantidad = parseInt($('#cantidad_repuesto').val()) || 1;
    let precioUnitario = parseFloat($('#precio_unitario_repuesto').val()) || 0;

    if (!idRepuesto) {
        MyAlert('Debe seleccionar un repuesto', 'error');
        return;
    }
    if (cantidad <= 0) {
        MyAlert('La cantidad debe ser mayor a cero', 'error');
        return;
    }
    if (precioUnitario <= 0) {
        MyAlert('El precio unitario debe ser mayor a cero', 'error');
        return;
    }

    // Calcular total
    let total = cantidad * precioUnitario;

    // Buscar fila existente por data-id
    let filaExistente = $(`#tabla_repuestos tbody tr[data-id="${idRepuesto}"]`);
    if (filaExistente.length > 0) {
        // Sumar cantidades y actualizar total
        let cantActual = parseInt(filaExistente.find('.cantidad').text());
        let nuevaCant = cantActual + cantidad;
        filaExistente.find('.cantidad').text(nuevaCant);

        // Siempre usar el precio unitario del repuesto (puede ser que en UI se cambie, pero preferible mantener original)
        let nuevoTotal = nuevaCant * precioUnitario;
        filaExistente.find('.total').text(nuevoTotal.toLocaleString());
    } else {
        // Agregar fila nueva con formateo consistente
        $('#tabla_repuestos tbody').append(`
            <tr data-id="${idRepuesto}">
                <td>${nombreRepuesto}</td>
                <td class="cantidad">${cantidad}</td>
                <td>${formatearPrecio(precioUnitario)}</td>
                <td class="total">${formatearPrecio(total)}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarRepuesto('${idRepuesto}')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `);
    }

    actualizarSubtotalRepuestos();
    $('#crear-editar-repuesto').modal('hide');
}

// Función para eliminar repuesto de la tabla
function eliminarRepuesto(idRepuesto) {
    $(`#tabla_repuestos tbody tr[data-id="${idRepuesto}"]`).remove();
    actualizarSubtotalRepuestos();
    actualizarResumenTotales();
}

// Función para actualizar subtotal de repuestos
function actualizarSubtotalRepuestos() {
    let subtotal = 0;
    $('#tabla_repuestos tbody tr').each(function() {
        let totalText = $(this).find('.total').text().trim();

        // Limpiar texto: solo números, punto y coma (en caso decimal)
        totalText = totalText.replace(/[^0-9.,-]/g, '');

        // Detectar y normalizar formato decimal
        if (totalText.indexOf(',') > -1 && totalText.indexOf('.') === -1) {
            // Caso coma decimal: reemplazar por punto para parseFloat
            totalText = totalText.replace(',', '.');
        } else {
            // Eliminar puntos y comas usados como miles
            totalText = totalText.replace(/[.,]/g, '');
        }

        let total = parseFloat(totalText) || 0;
        subtotal += total;
    });

    $('#subtotal_repuestos').text(formatearPrecio(subtotal));
    actualizarResumenTotales()
}

function actualizarResumenTotales() {
    // Obtener subtotales como números (removiendo formato)
    let subtotalServiciosText = $('#subtotal_servicios').text().replace(/[^0-9.,]/g, '').replace(/\./g, '').replace(',', '.');
    let subtotalRepuestosText = $('#subtotal_repuestos').text().replace(/[^0-9.,]/g, '').replace(/\./g, '').replace(',', '.');

    let subtotalServicios = parseFloat(subtotalServiciosText) || 0;
    let subtotalRepuestos = parseFloat(subtotalRepuestosText) || 0;

    // Calcular total general
    let totalGeneral = subtotalServicios + subtotalRepuestos;    

     // Actualizar la UI con formato de miles y moneda
    $('#resumen_servicios').text(formatearPrecio(subtotalServicios));
    $('#resumen_repuestos').text(formatearPrecio(subtotalRepuestos));
    $('#total_general').text(formatearPrecio(totalGeneral));
    let total_final = totalGeneral * 1.19;
    $('#total_final').text(formatearPrecio(total_final));
    let iva = total_final - totalGeneral;
    $('#iva').text(formatearPrecio(iva));
}

function limpiarNumero(texto) {
  // Quita todo lo que no sea dígito, coma o punto
  // Primero, quitamos el signo $ y espacios
  texto = texto.replace(/\$/g, '').trim();
  // Luego quitamos puntos que son miles
  texto = texto.replace(/\./g, '');
  // Si tienes comas como decimal, reemplaza coma por punto
  texto = texto.replace(/,/g, '.');
  return texto;
}



function guardarPresupuestoYSalir(num_presupuesto, es_vehiculo) {
    guardarPresupuestoCompleto(num_presupuesto, es_vehiculo)
        .done(function(response) {
            if (response.success) {
                MyAlert('Presupuesto guardado con éxito', 'Éxito');
                window.location.href = "<?= base_url('/public/presupuesto/lista-presupuestos') ?>";
            } else {
                MyAlert('Error al guardar el presupuesto', 'Error');
            }
        })
        .fail(function() {
            MyAlert('Error en la comunicación con el servidor', 'Error');
        });
}

function guardarPresupuestoCompleto(num_presupuesto, es_vehiculo) {
    const idPresupuesto = num_presupuesto || null; 

    let presupuesto;
    if (es_vehiculo == 1) {
        presupuesto = {
            id: idPresupuesto,
            kms: parseInt(limpiarNumero($('#kms').val())) || 0,
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
        };    
    } else {
        presupuesto = {
            id: idPresupuesto,
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
        };
    }

    let servicios = [];
    $('#tabla_servicios tbody tr').each(function() {
        const id_servicio = $(this).data('idServicio');
        const cantidad = parseInt($(this).find('.cantidad_servicio').text()) || 0;
        const precioText = $(this).find('td:nth-child(3)').text().replace(/\$|,/g, '');        
        const precio = parseFloat(precioText.replace(/\./g, '')) || 0;

        if (id_servicio) {
            servicios.push({ id_servicio, cantidad, precio });
        }
    });

    let repuestos = [];
    $('#tabla_repuestos tbody tr').each(function() {
        const id_repuesto = $(this).data('id');
        const cantidad = parseInt($(this).find('.cantidad').text()) || 0;
        const precioText = $(this).find('td:nth-child(3)').text().replace(/\$|,/g, '');
        const precio = parseFloat(precioText.replace(/\./g, '')) || 0;
        if (id_repuesto) {
            repuestos.push({ id_repuesto, cantidad, precio });
        }
    });

    const payload = { presupuesto, servicios, repuestos };

    return $.ajax({
        url: "<?= base_url('/public/presupuesto/GuardarPresupuestoCompleto') ?>",
        method: "POST",
        data: JSON.stringify(payload),
        contentType: "application/json",
        dataType: "json"
    });
}

function DesecharPresupuesto(id_presupuesto) {
    if (!confirm("¿Estás seguro de que deseas desechar este presupuesto?")) {
        return;
    }

    $.ajax({
        url: "<?= base_url('/public/presupuesto/DesecharPresupuesto') ?>",
        method: "POST",
        data: { id_presupuesto: id_presupuesto },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                MyAlert("Presupuesto desechado correctamente","Exito");
                // Puedes recargar tabla o redirigir
                location.reload();
            } else {
                MyAlert("Error al desechar el presupuesto","Error");
            }
        },
        error: function() {
            MyAlert("Error en la comunicación con el servidor","Error");
        }
    });
}


function ConvertirEnOS(id_presupuesto, es_vehiculo){
    if (!confirm("Se creará o actualizará la Orden de Servicio con este presupuesto. Este proceso no se puede deshacer. ¿Deseas continuar?")) {
        return;
    }

    guardarPresupuestoCompleto(id_presupuesto, es_vehiculo)
        .done(function(resp) {
            if (!resp.success) {
                MyAlert("Error al guardar el presupuesto antes de convertir","Error");
                return;
            }

            $.ajax({
                url: "<?= base_url('/public/orden-servicio/ConvertirEnOS') ?>",
                method: "POST",
                data: { id_presupuesto: id_presupuesto },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        MyAlert("Presupuesto convertido en Orden de Servicio correctamente","Éxito");
                        window.location.href = "<?= base_url('/public/orden-servicio/') ?>" + response.id_orden_servicio;
                    } else {
                        MyAlert("Error al convertir el presupuesto en Orden de Servicio","Error");
                    }
                },
                error: function() {
                    MyAlert("Error en la comunicación con el servidor","Error");
                }
            });
        })
        .fail(function() {
            MyAlert("Error en la comunicación al guardar el presupuesto","Error");
        });
}    

function verPDFPresupuesto(id_presupuesto, descargar = false) {
    let url = `<?= base_url('/public/presupuesto/DescargarPDF') ?>/${id_presupuesto}`;
    if (descargar) {
        url += '?download=1'; // Agregamos un parámetro para indicarlo
    }
    window.open(url, '_blank');
}




</script>           