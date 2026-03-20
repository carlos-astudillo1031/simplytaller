<?php echo view("references/header"); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<style>
    @media (max-width: 768px) {
        #tabla_ordenes .btn {
            display: block;
            width: 100%;
            margin: 4px 0;
        }

        .card {
            margin: 20px 15px !important;
        }
    }
</style>
    <?php echo view("references/navbar"); ?>    
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
                        <h4 style="margin-bottom:-32px">Ordenes de Servicio</h4>
                    </div>
                    <!-- Botón al extremo derecho -->                  
                    <button onclick="AbreModalCliente()" class="btn btn-primary ms-auto">
                        <i class="fas fa-history"></i>
                        <span class="d-none d-md-inline"> Consultar Histórico</span>
                    </button>
                    </div>                              
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_ordenes" class="table table-bordered table-striped">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th width="5%" scope="col">N°</th>
                                                                <th width="15%" scope="col">Fecha</th>    
                                                                <th width="15%" scope="col">Cliente</th>
                                                                <th width="20%" scope="col">Vehiculo</th>
                                                                <th width="15%" scope="col">Estado</th>
                                                                <th width="15%" scope="col">Total Neto</th>                                                                          
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
    CargaOrdenes();
});



function CargaOrdenes() {    
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/orden-servicio/GetOrdenes') ?>", // Cambia por tu endpoint real
        error: function() {
            MyAlert('Imposible cargar la lista de ordenes', 'error');
        },
        success: function(data) {
 
            $('#tabla_ordenes').DataTable().destroy();
            $("#tabla_ordenes tbody").empty();

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
                
                let estado = TraduceEstados(item.estado);
                $("#tabla_ordenes tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.num_os}</span></td>` +
                        `<td>${item.fecha_creacion}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td>${vehiculo}</td>` +
                        `<td><span class="badge bg-${estado.color}"><span>${estado.texto}</span></span></td>` +
                        `<td>${formatearPrecio(item.total)}</td>` +
                        `<td class="text-center">` +
                            `<a href="<?= base_url('/public/orden-servicio/') ?>${item.num_os}" 
                                class="btn btn-primary btn-sm" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>` +                            
                        '</td>' +
                    '</tr>'
                );

            });

           $('#tabla_ordenes').DataTable({
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
       url: '<?= base_url('/public/orden-servicio/GetOrdenesByCliente') ?>',
       type: 'POST',
       data: { id_cliente: $(this).val() },
       dataType: 'json', 
       success: function(data) {
            console.log(data);
            $('#tabla_ordenes').DataTable().destroy();
            $("#tabla_ordenes tbody").empty();

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
                
                let estado = TraduceEstados(item.estado);
                $("#tabla_ordenes tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.num_os}</span></td>` +
                        `<td>${item.fecha_creacion}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td>${vehiculo}</td>` +
                        `<td><span class="badge bg-${estado.color}"><span>${estado.texto}</span></span></td>` +
                        `<td>${formatearPrecio(item.total)}</td>` +
                        `<td class="text-center">` +
                            `<a target="_blank" href="<?= base_url('/public/orden-servicio/') ?>${item.num_os}" 
                                class="btn btn-primary btn-sm" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>` +                            
                        '</td>' +
                    '</tr>'
                );
                
           });          
           $('#tabla_ordenes').DataTable({
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

</script>