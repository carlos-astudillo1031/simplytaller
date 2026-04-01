<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/public/assets/compiled/css/table-datatable-jquery.css">

<?php echo view("references/navbar"); ?>    

<div class="content-wrapper container" style="padding-right:40px"> 
    <section class="row">        
        <div class="col-12 col-lg-3"></div>            
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4>Proveedores</h4>
                <button onclick="CrearProveedor()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>                              
            <div class="card-body">
                <table id="tabla_proveedores" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>RUT</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
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
<div class="modal fade" id="crear-editar-proveedor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="id_proveedor">

                <div class="mb-2">
                    <label>RUT</label>
                    <input type="text" class="form-control" id="rut">
                </div>

                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" class="form-control" id="nombre">
                </div>

                <div class="mb-2">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" id="telefono">
                </div>

                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email">
                </div>
            </div>

            <div class="modal-footer" id="modal-footer-proveedor"></div>
        </div>
    </div>
</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
    $(document).ready(function () {
    CargaProveedores();
});

function CargaProveedores() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetProveedores') ?>",
        success: function (data) {

            $('#tabla_proveedores').DataTable().destroy();
            $("#tabla_proveedores tbody").empty();

            $.each(data, function (i, item) {
                $("#tabla_proveedores tbody").append(`
                    <tr id="tr_${item.id}">
                        <td>${item.rut}</td>
                        <td>${item.nombre}</td>
                        <td>${item.telefono}</td>
                        <td>${item.email}</td>
                        <td>
                            <a class="btn btn-success btn-sm" onclick="EditarProveedor(${item.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" onclick="EliminarProveedor(${item.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `);
            });

            $('#tabla_proveedores').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }
    });
}

function CrearProveedor() {
    $('#id_proveedor').val('');
    $('#rut, #nombre, #telefono, #email').val('');

    $('#modal-footer-proveedor').html(`
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="GuardarProveedor()">Guardar</button>
    `);

    $('#crear-editar-proveedor').modal('show');
}

function GuardarProveedor() {
    $.post("<?= base_url('/public/config/GuardarProveedores') ?>", {
        rut: $('#rut').val(),
        nombre: $('#nombre').val(),
        telefono: $('#telefono').val(),
        email: $('#email').val()
    }, function () {
        $('#crear-editar-proveedor').modal('hide');
        MyAlert('Proveedor guardado', 'exito');
        CargaProveedores();
    }, 'json');
}

function EditarProveedor(id) {
    $.post("<?= base_url('/public/config/GetUnicoProveedores') ?>", { id: id }, function (data) {

        $('#id_proveedor').val(data.id);
        $('#rut').val(data.rut);
        $('#nombre').val(data.nombre);
        $('#telefono').val(data.telefono);
        $('#email').val(data.email);

        $('#modal-footer-proveedor').html(`
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary" onclick="ActualizarProveedor(${data.id})">Guardar</button>
        `);

        $('#crear-editar-proveedor').modal('show');

    }, 'json');
}

function ActualizarProveedor(id) {
    $.post("<?= base_url('/public/config/ActualizarProveedores') ?>", {
        id: id,
        rut: $('#rut').val(),
        nombre: $('#nombre').val(),
        telefono: $('#telefono').val(),
        email: $('#email').val()
    }, function () {
        $('#crear-editar-proveedor').modal('hide');
        MyAlert('Proveedor actualizado', 'exito');
        CargaProveedores();
    }, 'json');
}

function EliminarProveedor(id) {
    if (!confirm('¿Eliminar proveedor?')) return;

    $.post("<?= base_url('/public/config/EliminarProveedores') ?>", { id: id }, function () {
        MyAlert('Proveedor eliminado', 'exito');
        CargaProveedores();
    }, 'json');
}
</script>