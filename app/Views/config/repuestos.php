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
                    <h4 style="margin-bottom:-32px">Repuestos</h4>
                </div>
                <!-- Botón al extremo derecho -->
                <button onclick="CrearRepuesto()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>                              
            <div class="card-body">
               <div class="table-responsive">
                <table id="tabla_repuestos" class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th width="20%">Codigo</th>
                            <th width="35%">Nombre del Repuesto</th>
                            <th width="15%">Precio (CLP)</th>
                            <th width="10%">Stock</th>
                            <th width="10%">Stock Mínimo</th>
                            <th width="15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>   
                        <!-- DataTables cargará datos vía AJAX -->
                    </tbody>
                </table>
               </div>  
            </div>
        </div>
    </section>
</div>

<?php echo view("references/footer"); ?>

<!-- Modal para agregar/editar repuesto -->
<div class="modal fade text-left" id="crear-editar-repuesto" tabindex="-1" role="dialog" aria-labelledby="modalRepuestoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRepuestoLabel">Registro de Repuesto</h5>
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

                            <input type="hidden" id="id_repuesto" name="id_repuesto">

                            <div class="col-md-4">
                                <label for="codigo_repuesto">Código del Repuesto</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" id="codigo_repuesto" placeholder="Ej: 7123-490E">
                            </div>

                            <div class="col-md-4">
                                <label for="nombre_repuesto">Nombre del Repuesto</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" id="nombre_repuesto" placeholder="Ej: Filtro de Aceite">
                            </div>

                            <div class="col-md-4">
                                <label for="precio_repuesto">Precio (CLP)</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" id="precio_repuesto" placeholder="Ej: 7500">
                            </div>

                            <div class="col-md-4">
                                <label for="stock_repuesto">Stock</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" id="stock_repuesto" placeholder="Ej: 50">
                            </div>

                            <div class="col-md-4">
                                <label for="stock_minimo">Stock Mínimo</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" id="stock_minimo" placeholder="Ej: 5">
                            </div>

                        </div>
                    </div>
                </form>                        
            </div>
            <div class="modal-footer" id="modal-footer-edit">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarRepuesto()" id="confirmEnvioButton">Guardar</button>
            </div>
        </div>
    </div>
</div> <!-- Fin del modal -->

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>

<script>
$(document).ready(function() {          
    CargaRepuestos();
});

function CrearRepuesto() {       
    $('#id_repuesto').val('');
    $('#codigo_repuesto').val('');
    $('#nombre_repuesto').val('');   
    $('#precio_repuesto').val('');    
    $('#stock_repuesto').val('');    
    $('#stock_minimo').val('');
    $('#crear-editar-repuesto').modal('show');

    let htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
                      '<button type="button" class="btn btn-primary" onclick="GuardarRepuesto()" id="confirmEnvioButton">Guardar</button>';
    $('#modal-footer-edit').html(htmlBotones);
}

function GuardarRepuesto() {
    const arrCampos = [
        ['codigo_repuesto', 'text'],
        ['nombre_repuesto', 'text'],
        ['precio_repuesto', 'text'],
        ['stock_repuesto', 'text'],
        ['stock_minimo', 'text']
    ];

    if(ValidaCamposObligatorios(arrCampos) != false) {
        $.ajax({
            data: { 
                'codigo': $('#codigo_repuesto').val(),
                'nombre': $('#nombre_repuesto').val(),
                'precio': $('#precio_repuesto').val(),
                'stock': $('#stock_repuesto').val(),
                'stock_minimo': $('#stock_minimo').val()
            },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/GuardarRepuesto') ?>",
            error: function() {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function() {
                $('#crear-editar-repuesto').modal('hide');
                MyAlert('El repuesto ha sido guardado con éxito', 'exito');
                CargaRepuestos();
            }
        });
    }
}

function CargaRepuestos() {
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/config/GetRepuestos') ?>",
        error: function() {
            MyAlert('Imposible cargar la lista de repuestos','error');
        },
        success: function(data) {
            $('#tabla_repuestos').DataTable().destroy();       
            $("#tabla_repuestos tbody").empty();   

            $.each(data, function(i, item) {
                let claseFila = (parseInt(item.stock) == 0 || parseInt(item.stock) <= parseInt(item.stock_minimo)) ? 'table-danger' : '';

                $("#tabla_repuestos tbody").append(
                    '<tr id="tr_'+item.id+'" class="'+claseFila+'">' +
                    '<td>' + item.codigo + '</td>' +
                    '<td>' + item.nombre + '</td>' +
                    '<td>' + formatearPrecio(item.precio) + '</td>' +
                    '<td>' + item.stock + '</td>' +
                    '<td>' + item.stock_minimo + '</td>' +
                    '<td class="text-start">' +
                    '<a class="btn icon btn-success me-1" onclick="EditarRepuesto(' + item.id + ')" href="#" title="Editar"><i class="fas fa-edit"></i></a>' +
                    '<a class="btn icon btn-danger" onclick="EliminarRepuesto(' + item.id + ')" href="#" title="Eliminar"><i class="fas fa-trash"></i></a>' +
                    '</td></tr>'
                );
            });

            $('#tabla_repuestos').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            });
        }                   
    });
}

$('#nombre_repuesto').on('blur', function () {
    let texto = $(this).val();
    let textoSinAcentos = texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    let proper = textoSinAcentos.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
    $(this).val(proper);
});

function EditarRepuesto(id_repuesto) {
    $.ajax({
        dataType: "json",
        type: "POST",
        data: { 'id_repuesto': id_repuesto },
        url: "<?= base_url('/public/config/GetRegistroRepuesto') ?>",
        error: function() {
            MyAlert('Imposible cargar datos del repuesto','error');
        },
        success: function(data) {                  
            $('#crear-editar-repuesto').modal('show');
            $('#id_repuesto').val(data.id);
            $('#codigo_repuesto').val(data.codigo);
            $('#nombre_repuesto').val(data.nombre);   
            $('#precio_repuesto').val(data.precio);   
            $('#stock_repuesto').val(data.stock);
            $('#stock_minimo').val(data.stock_minimo);

            let htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>' +
                              '<button type="button" class="btn btn-primary" onclick="UpdateRepuesto(' + data.id + ')" id="confirmEnvioButton">Guardar</button>';
            $('#modal-footer-edit').html(htmlBotones);
        }
    });
}

function UpdateRepuesto(id_repuesto) {
    const arrCampos = [
        ['codigo_repuesto', 'text'],
        ['nombre_repuesto', 'text'],
        ['precio_repuesto', 'text'],
        ['stock_repuesto', 'text'],
        ['stock_minimo', 'text']
    ];

    if(ValidaCamposObligatorios(arrCampos) != false) {
        $.ajax({
            data: { 
                'codigo': $('#codigo_repuesto').val(),
                'nombre': $('#nombre_repuesto').val(),
                'precio': $('#precio_repuesto').val(),
                'stock': $('#stock_repuesto').val(),
                'stock_minimo': $('#stock_minimo').val(),
                'id_repuesto': id_repuesto
            },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/UpdateRepuesto') ?>",
            error: function() {
                MyAlert('Imposible guardar los datos', 'error');
            },
            success: function() {
                $('#crear-editar-repuesto').modal('hide');
                MyAlert('El repuesto ha sido actualizado con éxito', 'exito');
                CargaRepuestos();
            }
        });
    }
}

function EliminarRepuesto(id_repuesto) {
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), { keyboard: false });
    confirmModal.show();

    const confirmButton = document.getElementById('confirmDeleteButton');
    confirmButton.replaceWith(confirmButton.cloneNode(true));
    const newConfirmButton = document.getElementById('confirmDeleteButton');

    newConfirmButton.onclick = function () {
        confirmModal.hide();
        $.ajax({
            data: { 'id_repuesto': id_repuesto },
            dataType: "json",
            type: "POST",
            url: "<?= base_url('/public/config/EliminarRepuesto') ?>",
            error: function() {
                MyAlert('Imposible eliminar el repuesto', 'error');
            },
            success: function() {
                MyAlert('El repuesto ha sido eliminado con éxito', 'exito');
                CargaRepuestos();
            }
        });
    };
}
</script>
