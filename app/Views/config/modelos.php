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
                    <h4 style="margin-bottom:-32px">Modelos</h4>
                </div>
                <button onclick="CrearModelo()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>                              
            <div class="card-body">
                <table id="tabla_modelos" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="60%">Nombre del Modelo</th>
                            <th width="20%">Marca</th>
                            <th width="20%">Acciones</th>
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
<div class="modal fade text-left" id="crear-editar-modelo" tabindex="-1" role="dialog" aria-labelledby="modalModeloLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalModeloLabel">Registro de Modelo</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" id="id_modelo" name="id_modelo">

                            <div class="col-md-4">
                                <label for="marca_modelo">Marca</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-control" id="marca_modelo" name="marca_modelo">
                                    <!-- Las marcas deben cargarse dinámicamente -->
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="nombre_modelo">Nombre del Modelo</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" name="nombre_modelo" id="nombre_modelo" placeholder="Ej: Hilux, Accent">
                            </div>
                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarModelo()" id="confirmEnvioButton">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {
    CargaModelos();
});

// Cargar modelos
function CargaModelos() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetModelosTodos') ?>",
        success: function(data) {
            $('#tabla_modelos').DataTable().destroy();
            $("#tabla_modelos tbody").empty();

            $.each(data, function(i, item) {
                $("#tabla_modelos tbody").append(
                    '<tr>' +
                        '<td>' + item.nombre + '</td>' +
                        '<td>' + item.marca_nombre + '</td>' +
                        '<td>' +
                            '<a class="btn icon btn-success me-1" onclick="EditarModelo(' + item.id + ')"><i class="fas fa-edit"></i></a>' +
                            '<a class="btn icon btn-danger" onclick="EliminarModelo(' + item.id + ')"><i class="fas fa-trash"></i></a>' +
                        '</td>' +
                    '</tr>'
                );
            });

            $('#tabla_modelos').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }
    });
}

// Crear modelo
function CrearModelo() {
    $('#id_modelo').val('');
    $('#nombre_modelo').val('');
    $('#marca_modelo').empty();

    // Cargar marcas al abrir el modal
    $.getJSON("<?= base_url('/public/config/GetMarcas') ?>", function(data) {
        $.each(data, function(i, marca) {
            $('#marca_modelo').append('<option value="' + marca.id + '">' + marca.nombre + '</option>');
        });
    });

    $('#crear-editar-modelo').modal('show');
    $('#modal-footer-edit').html(`
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="GuardarModelo()">Guardar</button>
    `);
}

// Guardar nuevo modelo
function GuardarModelo() {
    const campos = [['nombre_modelo', 'text'], ['marca_modelo', 'select']];
    if (ValidaCamposObligatorios(campos) !== false) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('/public/config/GuardarModelo') ?>",
            data: {
                nombre: $('#nombre_modelo').val(),
                id_marca: $('#marca_modelo').val()
            },
            dataType: "json",
            success: function () {
                $('#crear-editar-modelo').modal('hide');
                MyAlert('Modelo guardado correctamente', 'exito');
                CargaModelos();
            },
            error: function () {
                MyAlert('Error al guardar el modelo', 'error');
            }
        });
    }
}

// Editar modelo
function EditarModelo(id_modelo) {
    $.ajax({
        type: "POST",
        url: "<?= base_url('/public/config/GetRegistroModelo') ?>",
        data: { id_modelo: id_modelo },
        dataType: "json",
        success: function (data) {
            $('#id_modelo').val(data.id);
            $('#nombre_modelo').val(data.nombre);

            // Cargar marcas y seleccionar la correspondiente
            $('#marca_modelo').empty();
            $.getJSON("<?= base_url('/public/config/GetMarcas') ?>", function(marcas) {
                $.each(marcas, function(i, marca) {
                    const selected = (marca.id == data.id_marca) ? 'selected' : '';
                    $('#marca_modelo').append('<option value="' + marca.id + '" ' + selected + '>' + marca.nombre + '</option>');
                });

                $('#crear-editar-modelo').modal('show');
                $('#modal-footer-edit').html(`
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="UpdateModelo(${data.id})">Guardar</button>
                `);
            });
        },
        error: function () {
            MyAlert('Error al obtener datos del modelo', 'error');
        }
    });
}

// Actualizar modelo
function UpdateModelo(id_modelo) {
    const campos = [['nombre_modelo', 'text'], ['marca_modelo', 'select']];
    if (ValidaCamposObligatorios(campos) !== false) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('/public/config/UpdateModelo') ?>",
            data: {
                id_modelo: id_modelo,
                nombre: $('#nombre_modelo').val(),
                id_marca: $('#marca_modelo').val()
            },
            dataType: "json",
            success: function () {
                $('#crear-editar-modelo').modal('hide');
                MyAlert('Modelo actualizado correctamente', 'exito');
                CargaModelos();
            },
            error: function () {
                MyAlert('Error al actualizar el modelo', 'error');
            }
        });
    }
}

// Eliminar modelo
function EliminarModelo(id_modelo) {
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), { keyboard: false });
    confirmModal.show();

    const confirmButton = document.getElementById('confirmDeleteButton');
    confirmButton.replaceWith(confirmButton.cloneNode(true));
    const newConfirmButton = document.getElementById('confirmDeleteButton');

    newConfirmButton.onclick = function () {
        confirmModal.hide();
        $.ajax({
            type: "POST",
            url: "<?= base_url('/public/config/EliminarModelo') ?>",
            data: { id_modelo: id_modelo },
            dataType: "json",
            success: function () {
                MyAlert('Modelo eliminado correctamente', 'exito');
                CargaModelos();
            },
            error: function () {
                MyAlert('Error al eliminar el modelo', 'error');
            }
        });
    };
}

</script>
