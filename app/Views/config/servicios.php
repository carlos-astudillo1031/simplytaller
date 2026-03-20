<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/public/assets/compiled/css/table-datatable-jquery.css">

<?php echo view("references/navbar"); ?>    

<style>
     @media (max-width: 768px) { 
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
                    <h4 style="margin-bottom:-32px">Servicios</h4>
                </div>
                <!-- Botón al extremo derecho -->
                <button onclick="CrearServicio()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>                              
            <div class="card-body">
                <table id="tabla_servicios" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="60%">Nombre del Servicio</th>
                            <th width="20%">Precio (CLP)</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>   
                        <!-- Aquí DataTables cargará los datos vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>


<?php echo view("references/footer"); ?>

<!-- Modal para agregar/editar servicio -->
<div class="modal fade text-left" id="crear-editar-servicio" tabindex="-1" role="dialog" aria-labelledby="modalServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalServicioLabel">Registro de Servicio</h5>
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

                            <!-- Campo oculto para ID del servicio (para edición) -->
                            <input type="hidden" id="id_servicio" name="id_servicio">

                            <div class="col-md-4">
                                <label for="nombre_servicio">Nombre del Servicio</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="nombre_servicio" id="nombre_servicio" placeholder="Ej: Cambio de Aceite">
                            </div>

                            <div class="col-md-4">
                                <label for="precio_servicio">Precio (CLP)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" name="precio_servicio" id="precio_servicio" placeholder="Ej: 45000">
                            </div>

                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarServicio()" id="confirmEnvioButton">Guardar</button>
            </div>
        </div>
    </div>
</div> <!-- Fin del modal -->


</body>
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {          
    CargaServicios();
});

// Abre el modal vacío para crear servicio
function CrearServicio() {       
    $('#id_servicio').val('');
    $('#nombre_servicio').val('');   
    $('#precio_servicio').val('');    
    $('#crear-editar-servicio').modal('show');

    htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
                  '<button type="button" class="btn btn-primary" onclick="GuardarServicio()" id="confirmEnvioButton">Guardar</button>';
    $('#modal-footer-edit').html(htmlBotones);
}

$('#nombre_servicio').on('blur', function () {
    let texto = $(this).val();
    let textoSinAcentos = texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    let proper = textoSinAcentos.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
    $(this).val(proper);
});

// Guarda nuevo servicio
function GuardarServicio() {
    const arrCampos = [
        ['nombre_servicio', 'text'],
        ['precio_servicio', 'text']
    ];

    if(ValidaCamposObligatorios(arrCampos) != false) {
        $.ajax({
            data: {
                'nombre': $('#nombre_servicio').val().normalize("NFD").replace(/[\u0300-\u036f]/g, ""),
                'precio': $('#precio_servicio').val()
            },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/GuardarServicio') ?>",
            error: function() {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function() {
                $('#crear-editar-servicio').modal('hide');
                MyAlert('El servicio ha sido guardado con éxito', 'exito');
                CargaServicios();
            }
        });
    }
}

// Carga todos los servicios
function CargaServicios() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetServicios') ?>",
        error: function() {
            MyAlert('Imposible cargar la lista de servicios','error');
        },
        success: function(data) {
            $('#tabla_servicios').DataTable().destroy();       
            $("#tabla_servicios tbody").empty();   

            $.each(data, function(i, item) {
                $("#tabla_servicios tbody").append(
                    '<tr id="tr_'+item.id+'">' +
                    '<td>' + item.nombre + '</td>' +
                    '<td>' + formatearPrecio(item.precio) + '</td>' +
                    '<td class="text-start">' +
                    '<a class="btn icon btn-success me-1" onclick="EditarServicio(' + item.id + ')" href="#" title="Editar"><i class="fas fa-edit"></i></a>' +
                    '<a class="btn icon btn-danger" onclick="EliminarServicio(' + item.id + ')" href="#" title="Eliminar"><i class="fas fa-trash"></i></a>' +
                    '</td></tr>'
                );
            });

            $('#tabla_servicios').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }                   
    });
}

// Abre el modal con datos para editar servicio
function EditarServicio(id_servicio) {
    $.ajax({
        dataType: "json",
        type: "POST",
        data: { 'id_servicio': id_servicio },
        url: "<?= base_url('/public/config/GetRegistroServicio') ?>", // Debes crear este método si deseas esta funcionalidad
        error: function() {
            MyAlert('Imposible cargar datos del servicio','error');
        },
        success: function(data) {                  
            $('#crear-editar-servicio').modal('show');
            $('#id_servicio').val(data.id);
            $('#nombre_servicio').val(data.nombre);   
            $('#precio_servicio').val(data.precio);   

            htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
                          '<button type="button" class="btn btn-primary" onclick="UpdateServicio(' + data.id + ')" id="confirmEnvioButton">Guardar</button>';
            $('#modal-footer-edit').html(htmlBotones);
        }
    });
}

// Actualiza servicio
function UpdateServicio(id_servicio) {
    const arrCampos = [
        ['nombre_servicio', 'text'],
        ['precio_servicio', 'text']
    ];

    if(ValidaCamposObligatorios(arrCampos) != false) {
        $.ajax({
            data: {
                'nombre': $('#nombre_servicio').val().normalize("NFD").replace(/[\u0300-\u036f]/g, ""),
                'precio': $('#precio_servicio').val(),
                'id_servicio': id_servicio
            },            
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/UpdateServicio') ?>",
            error: function() {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function() {
                $('#crear-editar-servicio').modal('hide');
                MyAlert('El servicio ha sido actualizado con éxito', 'exito');
                CargaServicios();
            }
        });
    }
}

// Elimina servicio
function EliminarServicio(id_servicio) {
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), { keyboard: false });
    confirmModal.show();

    const confirmButton = document.getElementById('confirmDeleteButton');
    confirmButton.replaceWith(confirmButton.cloneNode(true));
    const newConfirmButton = document.getElementById('confirmDeleteButton');

    newConfirmButton.onclick = function () {
        confirmModal.hide();
        $.ajax({
            data: { 'id_servicio': id_servicio },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/EliminarServicio') ?>",
            error: function() {
                MyAlert('Imposible eliminar el servicio', 'error');
            },
            success: function() {
                MyAlert('El servicio ha sido eliminado con éxito', 'exito');
                CargaServicios();
            }
        });
    };
}
</script>
