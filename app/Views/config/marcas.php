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
                    <h4 style="margin-bottom:-32px">Marcas</h4>
                </div>
                <button onclick="CrearMarca()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nueva
                </button>
            </div>                              
            <div class="card-body">
                <table id="tabla_marcas" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="90%">Nombre de la Marca</th>
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
<div class="modal fade text-left" id="crear-editar-marca" tabindex="-1" role="dialog" aria-labelledby="modalMarcaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMarcaLabel">Registro de Marca</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <input type="hidden" id="id_marca">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nombre_marca">Nombre de la Marca</label>
                        </div>
                        <div class="col-md-8 form-group">
                            <input type="text" class="form-control" id="nombre_marca" placeholder="Ej: Toyota">
                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarMarca()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {
    CargaMarcas();
});

function CargaMarcas() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetMarcas') ?>",
        success: function(data) {
            $('#tabla_marcas').DataTable().destroy();
            $("#tabla_marcas tbody").empty();

            $.each(data, function(i, item) {
                $("#tabla_marcas tbody").append(
                    `<tr id="tr_${item.id}">
                        <td>${item.nombre}</td>
                        <td>
                            <a class="btn icon btn-success me-1" onclick="EditarMarca(${item.id})"><i class="fas fa-edit"></i></a>
                            <a class="btn icon btn-danger" onclick="EliminarMarca(${item.id})"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>`
                );
            });

            $('#tabla_marcas').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }
    });
}

function CrearMarca() {
    $('#id_marca').val('');
    $('#nombre_marca').val('');
    $('#crear-editar-marca').modal('show');
    $('#modal-footer-edit').html(`
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="GuardarMarca()">Guardar</button>
    `);
}

function GuardarMarca() {
    if ($('#nombre_marca').val() === '') {
        $('#nombre_marca').addClass('border border-danger').focus();
        return;
    }

    $.ajax({
        data: { nombre: $('#nombre_marca').val() },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/GuardarMarca') ?>",
        success: function() {
            $('#crear-editar-marca').modal('hide');
            MyAlert('Marca registrada con éxito', 'exito');
            CargaMarcas();
        }
    });
}

function EditarMarca(id_marca) {
    $.ajax({
        dataType: "json",
        type: "POST",
        data: { id: id_marca },
        url: "<?= base_url('/public/config/GetRegistroMarca') ?>",
        success: function(data) {
            $('#id_marca').val(data.id);
            $('#nombre_marca').val(data.nombre);
            $('#crear-editar-marca').modal('show');

            $('#modal-footer-edit').html(`
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="ActualizarMarca(${data.id})">Guardar</button>
            `);
        }
    });
}

function ActualizarMarca(id) {
    $.ajax({
        data: { id: id, nombre: $('#nombre_marca').val() },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/ActualizarMarca') ?>",
        success: function() {
            $('#crear-editar-marca').modal('hide');
            MyAlert('Marca actualizada con éxito', 'exito');
            CargaMarcas();
        }
    });
}

function EliminarMarca(id) {
    if (!confirm('¿Estás seguro de eliminar esta marca?')) return;

    $.ajax({
        data: { id: id },
        dataType: "json",
        type: "POST",
        url: "<?= base_url('/public/config/EliminarMarca') ?>",
        success: function() {
            MyAlert('Marca eliminada con éxito', 'exito');
            CargaMarcas();
        }
    });
}
</script>
