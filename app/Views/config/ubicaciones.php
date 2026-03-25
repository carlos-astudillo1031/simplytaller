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
                    <h4 style="margin-bottom:-32px">Ubicaciones</h4>
                </div>
                <button onclick="CrearUbicacion()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nueva
                </button>
            </div>                              
            <div class="card-body">
                <table id="tabla_ubicaciones" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="90%">Nombre de la Ubicación</th>
                            <th width="10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php echo view("references/footer"); ?>

<!-- Modal -->
<div class="modal fade text-left" id="crear-editar-ubicacion" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Ubicación</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <input type="hidden" id="id_ubicacion">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nombre de la Ubicación</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" class="form-control" id="nombre_ubicacion" placeholder="Ej: Bodega Central">
                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="GuardarUbicacion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {
    CargaUbicaciones();
});

function CargaUbicaciones() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetUbicaciones') ?>",
        success: function(data) {
            $('#tabla_ubicaciones').DataTable().destroy();
            $("#tabla_ubicaciones tbody").empty();

            $.each(data, function(i, item) {
                $("#tabla_ubicaciones tbody").append(
                    `<tr id="tr_${item.id}">
                        <td>${item.nombre}</td>
                        <td>
                            <a class="btn icon btn-success me-1" onclick="EditarUbicacion(${item.id})"><i class="fas fa-edit"></i></a>
                            <a class="btn icon btn-danger" onclick="EliminarUbicacion(${item.id})"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>`
                );
            });

            $('#tabla_ubicaciones').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }
    });
}

function CrearUbicacion() {
    $('#id_ubicacion').val('');
    $('#nombre_ubicacion').val('');
    $('#crear-editar-ubicacion').modal('show');

    $('#modal-footer-edit').html(`
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="GuardarUbicacion()">Guardar</button>
    `);
}

function GuardarUbicacion() {
    if ($('#nombre_ubicacion').val() === '') {
        $('#nombre_ubicacion').addClass('border border-danger').focus();
        return;
    }

    $.ajax({
        data: { nombre: $('#nombre_ubicacion').val() },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/GuardarUbicaciones') ?>",
        success: function() {
            $('#crear-editar-ubicacion').modal('hide');
            MyAlert('Ubicación registrada con éxito', 'exito');
            CargaUbicaciones();
        }
    });
}

function EditarUbicacion(id) {
    $.ajax({
        dataType: "json",
        type: "POST",
        data: { id: id },
        url: "<?= base_url('/public/config/GetRegistroUbicaciones') ?>",
        success: function(data) {
            $('#id_ubicacion').val(data.id);
            $('#nombre_ubicacion').val(data.nombre);
            $('#crear-editar-ubicacion').modal('show');

            $('#modal-footer-edit').html(`
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="ActualizarUbicacion(${data.id})">Guardar</button>
            `);
        }
    });
}

function ActualizarUbicacion(id) {
    $.ajax({
        data: { id: id, nombre: $('#nombre_ubicacion').val() },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/ActualizarUbicaciones') ?>",
        success: function() {
            $('#crear-editar-ubicacion').modal('hide');
            MyAlert('Ubicación actualizada con éxito', 'exito');
            CargaUbicaciones();
        }
    });
}

function EliminarUbicacion(id) {
    if (!confirm('¿Estás seguro de eliminar esta ubicación?')) return;

    $.ajax({
        data: { id: id },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/EliminarUbicaciones') ?>",
        success: function() {
            MyAlert('Ubicación eliminada con éxito', 'exito');
            CargaUbicaciones();
        }
    });
}
</script>