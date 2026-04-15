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
                <div class="card-header d-flex justify-content-between align-items-center">

                    <!-- Título -->
                    <h5 class="mb-0">Compras</h5>

                    <!-- Botones -->
                    <div class="d-flex gap-2 align-items-center">

                        <input type="date" id="fecha_inicio" class="form-control">
                        <input type="date" id="fecha_fin" class="form-control">

                        <button class="btn btn-dark" onclick="FiltrarCompras()">
                            <i class="fas fa-search"></i>
                        </button>

                        <button class="btn btn-primary text-nowrap" onclick="NuevaCompra()">
                            <i class="fas fa-plus"></i> Nueva Compra
                        </button>

                    </div>

                </div>             
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_compras" class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" scope="col">N°</th>
                                        <th width="10%" scope="col">Fecha</th>    
                                        <th width="20%" scope="col">Proveedor</th>
                                        <th width="15%" scope="col">N° Factura</th>
                                        <th width="10%" scope="col">Total Neto</th>
                                        <th width="10%" scope="col">IVA</th>
                                        <th width="10%" scope="col">Total</th>                     
                                        <th width="5%" scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos dinámicos -->
                                </tbody>
                            </table>
                         </div>   
                    </div>
                </div>
             </div> 
           </section>
        </div> <!--Cierra el pagecontent-->
<!-- Modal Compra -->
<div class="modal fade" id="modalCompra" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="modal_titulo">Detalle de Compra</span>
                    <small id="compra_id" class="text-muted"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <!-- Encabezado -->
                <div class="row mb-3">

                    <!-- Proveedor -->
                    <div class="col-md-4">
                        <strong>Proveedor:</strong><br>
                        <span id="proveedor_text"></span>
                        <select id="proveedor_input" class="form-control d-none select2"></select>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-4">
                        <strong>Fecha:</strong><br>
                        <span id="fecha_text"></span>
                        <input type="date" id="fecha_input" class="form-control d-none">
                    </div>

                    <!-- Factura -->
                    <div class="col-md-4">
                        <strong>Factura:</strong><br>
                        <span id="factura_text"></span>
                        <input type="text" id="factura_input" class="form-control d-none">
                    </div>

                </div>

                <!-- Formulario agregar ítem -->
                <div class="row mb-3 d-none" id="form_agregar_item">

                    <!-- Repuesto -->
                    <div class="col-md-5">
                        <label class="form-label">Repuesto</label>
                        <select id="repuesto_input" class="form-control select2"></select>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="cantidad_input" class="form-control text-center" value="1" min="1">
                    </div>

                    <!-- Precio -->
                    <div class="col-md-3">
                        <label class="form-label">Neto Unitario</label>
                        <input type="number" id="precio_input" class="form-control text-end" value="0">
                    </div>

                    <!-- Botón agregar -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-success w-100" id="btn_agregar_item" onclick="agregarFilaDetalle()">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>

                </div>

                <!-- Tabla detalle -->
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Repuesto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center d-none" id="col_acciones">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_compra_body"></tbody>
                </table>

                

                <!-- Totales -->
                <div class="text-end">
                    <p><strong>Neto:</strong> <span id="total_neto">$0</span></p>
                    <p><strong>IVA:</strong> <span id="total_iva">$0</span></p>
                    <p><strong>Total:</strong> <span id="total_total">$0</span></p>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                <button type="button" class="btn btn-danger d-none" id="btn_anular_compra" onclick="AnularCompra()">
                    <i class="fas fa-ban"></i> Anular Compra
                </button>

                <!-- Botón guardar -->
                <button type="button" class="btn btn-primary d-none" id="btn_guardar_compra" onclick="GuardarCompra()">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>
<!-- Fin modal compra -->
<?php echo view("references/footer"); ?>
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>

$(document).ready(function() {

    let hoy = new Date();
    let hace60 = new Date();

    hace60.setDate(hoy.getDate() - 60);

    // Formatear a YYYY-MM-DD
    let formato = (fecha) => {
        let y = fecha.getFullYear();
        let m = String(fecha.getMonth() + 1).padStart(2, '0');
        let d = String(fecha.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    };

    $('#fecha_inicio').val(formato(hace60));
    $('#fecha_fin').val(formato(hoy));

    // 👉 Carga inicial (igual que antes)
    CargaCompras();
});

function CargaCompras(fecha_inicio = null, fecha_fin = null) {

    let parametros = {};

    // 👉 Solo enviar fechas si existen
    if (fecha_inicio && fecha_fin) {
        parametros.fecha_inicio = fecha_inicio;
        parametros.fecha_fin = fecha_fin;
    }

    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/compra/GetCompras') ?>",
        data: parametros, 
        error: function() {
            MyAlert('Imposible cargar la lista de compras', 'error');
        },
        success: function(data) {

            $('#tabla_compras').DataTable().destroy();
            $("#tabla_compras tbody").empty();

            let icono_anulada;

            $.each(data, function(i, item) {

                if(item.estado == 2) {
                    icono_anulada = '<i class="fas fa-ban text-danger"></i>';
                } else {
                    icono_anulada = '';
                }

                $("#tabla_compras tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.id}</span></td>` +
                        `<td>${item.fecha_formateada}</td>` +
                        `<td>${item.proveedor}</td>` +
                        `<td>${item.numero_factura}${icono_anulada}</td>` +
                        `<td class="text-end">${formatearPrecio(item.total_neto)}</td>` +
                        `<td class="text-end">${formatearPrecio(item.total_iva)}</td>` +
                        `<td class="text-end">${formatearPrecio(item.total)}</td>` +
                        `<td class="text-center">` +
                            `<a href="#" onclick="VerCompra(${item.id},${item.estado})" class="btn btn-primary btn-sm" title="Ver detalle"><i class="fas fa-eye"></i></a> ` +
                        '</td>' +
                    '</tr>'
                );
            });

            $('#tabla_compras').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                },
                "ordering": false
            });

        }
    });
}



function VerCompra(id_compra,estado) {

    // 🔹 Cambiar a modo VER
    modoVer(estado);

    $.ajax({
        url: "<?= base_url('/public/compra/GetCompraDetalle') ?>",
        type: "POST",
        dataType: "json",
        data: {
            id_compra: id_compra
        },
        success: function(data) {

            console.log(data);

            // Encabezado
            $("#modal_titulo").text("Detalle de Compra");
            $("#compra_id").text("#" + data.compra.id);

            $("#proveedor_text").text(data.compra.proveedor);
            $("#fecha_text").text(data.compra.fecha_formateada);
            $("#factura_text").text(data.compra.numero_factura);

            // 🧹 Limpiar detalle
            $("#detalle_compra_body").empty();

            //Llenar detalle
            $.each(data.detalle, function(i, item) {

                let subtotal = item.cantidad * item.precio_unitario;

                $("#detalle_compra_body").append(`
                    <tr>
                        <td>${item.repuesto}</td>
                        <td class="text-center">${item.cantidad}</td>
                        <td class="text-end">${formatearPrecio(item.precio_unitario)}</td>
                        <td class="text-end">${formatearPrecio(subtotal)}</td>
                    </tr>
                `);
            });

            //Totales
            $("#total_neto").text(formatearPrecio(data.compra.total_neto));
            $("#total_iva").text(formatearPrecio(data.compra.total_iva));
            $("#total_total").text(formatearPrecio(data.compra.total));

            // Mostrar modal
            $("#modalCompra").modal("show");
        },
        error: function() {
            MyAlert("Error al cargar la compra", "error");
        }
    });
}




//Para ver con que modo uso modal de compra
function modoVer(estado) {

    // Ocultar inputs
    $("#fecha_input, #factura_input").addClass("d-none");
    $("#proveedor_input").next(".select2").addClass("d-none");

    // Mostrar textos
    $("#proveedor_text, #fecha_text, #factura_text").removeClass("d-none");

      // mostrar formulario de ítem
    $("#form_agregar_item").addClass("d-none");

    //Mostrar anular si el estado es 1
    if (estado == 1) {
        $("#btn_anular_compra").removeClass("d-none");
    }

    // Ocultar acciones
    $("#col_acciones").addClass("d-none");
    $("#btn_agregar_container").addClass("d-none");
    $("#btn_guardar_compra").addClass("d-none");
}


//Para cuando uso el modal de compra para crear
function modoCrear() {

    // 🔹 Título
    $("#modal_titulo").text("Nueva Compra");
    $("#compra_id").text("");

    // 🔹 Inputs visibles
    $("#fecha_input, #factura_input").removeClass("d-none");
    $("#proveedor_input").next(".select2").removeClass("d-none");

    // 🔹 Textos ocultos
    $("#proveedor_text, #fecha_text, #factura_text").addClass("d-none");

    // 🔹 Acciones visibles / No Visibles
    $("#col_acciones").removeClass("d-none");
    $("#btn_agregar_container").removeClass("d-none");
    $("#btn_guardar_compra").removeClass("d-none");
    $("#btn_anular_compra").addClass("d-none");

    // mostrar formulario de ítem
    $("#form_agregar_item").removeClass("d-none");

    // 🔹 Limpiar valores
    $("#proveedor_input").val('');
    $("#fecha_input").val(new Date().toISOString().split('T')[0]);
    $("#factura_input").val('');

    $("#detalle_compra_body").empty();

    // 🔹 Totales
    $("#total_neto").text("$0");
    $("#total_iva").text("$0");
    $("#total_total").text("$0");
}

function NuevaCompra() {

    modoCrear();

    // Cargar selects
    CargarProveedoresSelect("#proveedor_input");
    getRepuestos();

    // Mostrar modal
    $("#modalCompra").modal("show");
}


function GuardarCompra() {

    let detalle = [];

    $("#detalle_compra_body tr").each(function () {

        const id_repuesto = $(this).data("id");

        const cantidad = parseFloat($(this).find("td:eq(1)").text()) || 0;

        const precioTexto = $(this).find("td:eq(2)").text()
            .replace(/\$/g, '')
            .replace(/\./g, '');

        const precio = parseFloat(precioTexto) || 0;

        detalle.push({
            id_repuesto: id_repuesto,
            cantidad: cantidad,
            precio_unitario: precio
        });
    });

    // VALIDACIONES FRONT
    if (!$("#proveedor_input").val()) {
        MyAlert("Debe seleccionar un proveedor", "warning");
        return;
    }

    if (!$("#fecha_input").val()) {
        MyAlert("Debe ingresar una fecha", "warning");
        return;
    }

    if (detalle.length === 0) {
        MyAlert("Debe agregar al menos un ítem", "warning");
        return;
    }

    // 🔹 AJAX
    $.ajax({
        url: "<?= base_url('/public/compra/GuardarCompra') ?>",
        type: "POST",
        dataType: "json",
        data: {
            id_proveedor: $("#proveedor_input").val(),
            fecha: $("#fecha_input").val(),
            numero_factura: $("#factura_input").val(),
            total_neto: limpiarNumero($("#total_neto").text()),
            total_iva: limpiarNumero($("#total_iva").text()),
            total: limpiarNumero($("#total_total").text()),
            detalle: detalle
        },
        success: function (resp) {

            // 🔥 Manejo de respuesta backend
            if (resp.status === "ok") {

                $("#modalCompra").modal("hide");
                MyAlert(resp.msg || "Compra guardada", "exito");

                setTimeout(() => {
                    location.reload();
                }, 3000);

            } else {

                MyAlert(resp.msg || "Error al guardar", "error");

            }
        },
        error: function (xhr) {

            // 🔴 Error real (servidor, red, etc.)
            let msg = "Error inesperado";

            if (xhr.responseJSON && xhr.responseJSON.msg) {
                msg = xhr.responseJSON.msg;
            }

            MyAlert(msg, "error");
        }
    });
}

function CargarProveedoresSelect(){

    $.ajax({
        url: "<?= base_url('/public/config/GetProveedores') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#proveedor_input')
                            .empty()
                            .append('<option value="">Seleccione un proveedor</option>');

            $.each(data, function(index, item) {                                                                                          
                            $('#proveedor_input').append(
                                `<option value="${item.id}">${item.rut} - ${item.nombre}</option>`
                            );
            });                        
                        
            // Inicializa o actualiza Select2 después de cargar las opciones
            $('#proveedor_input').select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $('#modalCompra')
            });
        }
    });
}

function getRepuestos() {

    $.ajax({
        url: "<?= base_url('/public/config/GetRepuestos') ?>",
        dataType: "json",
        success: function(data) {

            const $select = $('#repuesto_input');

            $select.empty().append('<option value="" disabled selected>-- Seleccione un repuesto --</option>');

            data.forEach(function(item) {
                $select.append(`
                    <option value="${item.id}" data-precio="${item.precio}">
                        ${item.nombre} (${item.codigo})
                    </option>
                `);
            });

            // Inicializa o actualiza Select2 después de cargar las opciones
             $select.select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $('#modalCompra')
            });
        },
        error: function() {
            $('#repuesto_input')
                .empty()
                .append('<option value="" disabled>-- Error cargando repuestos --</option>');

            MyAlert('No se pudo cargar la lista de repuestos', 'error');
        }
    });

}

function agregarFilaDetalle() {

    const repuestoSelect = $('#repuesto_input');
    const repuestoId = repuestoSelect.val();
    const repuestoTexto = repuestoSelect.find("option:selected").text();

    const cantidad = parseFloat($('#cantidad_input').val()) || 0;
    const precio = parseFloat($('#precio_input').val()) || 0;

    // 🔴 Validaciones básicas
    if (!repuestoId) {
        MyAlert('Debe seleccionar un repuesto', 'warning');
        return;
    }

    if (cantidad <= 0) {
        MyAlert('Cantidad inválida', 'warning');
        return;
    }

    if (precio < 0) {
        MyAlert('Precio inválido', 'warning');
        return;
    }

    const subtotal = cantidad * precio;

    // Crear fila
    const fila = `
        <tr data-id="${repuestoId}">
            <td>${repuestoTexto}</td>
            <td class="text-center">${cantidad}</td>
            <td class="text-end">${formatearPrecio(precio)}</td>
            <td class="text-end">${formatearPrecio(subtotal)}</td>
            <td class="text-center">
                <button class="btn btn-danger btn-sm" onclick="eliminarFila(this)"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `;

    $("#detalle_compra_body").append(fila);

    // Limpiar inputs (opcional pero recomendable)
    $('#repuesto_input').val(null).trigger('change');
    $('#cantidad_input').val(1);
    $('#precio_input').val(0);

    // Recalcular totales
    recalcularTotales();
}


function eliminarFila(btn) {
    $(btn).closest("tr").remove();
    recalcularTotales();
}

function AnularCompra() {

    const idCompra = $('#compra_id').text().replace('#', '');

    if (!idCompra) {
        MyAlert("No se encontró la compra", "error");
        return;
    }

    // 🔴 Confirmación
    if (!confirm("¿Estás seguro de anular esta compra? Esta acción no se puede deshacer.")) {
        return;
    }

    $.ajax({
        url: "<?= base_url('/public/compra/AnularCompra') ?>",
        type: "POST",
        dataType: "json",
        data: {
            id_compra: idCompra
        },
        success: function (resp) {

            // 🔥 Manejo de respuesta backend
            if (resp.status === "ok") {

                $("#modalCompra").modal("hide");
                MyAlert(resp.msg || "Compra anulada", "exito");

                setTimeout(() => {
                    location.reload();
                }, 3000);

            } else {

                MyAlert(resp.msg || "Error al anular", "error");

            }
        },
        error: function (xhr) {

            let msg = "Error inesperado";

            if (xhr.responseJSON && xhr.responseJSON.msg) {
                msg = xhr.responseJSON.msg;
            }

            MyAlert(msg, "error");
        }
    });
}

function FiltrarCompras() {

    let inicio = $('#fecha_inicio').val();
    let fin = $('#fecha_fin').val();

    if (!inicio || !fin) {
        alert('Selecciona ambas fechas');
        return;
    }

    let diff = (new Date(fin) - new Date(inicio)) / (1000 * 60 * 60 * 24);

    if (diff > 90) {
        alert('Máximo 90 días por consulta');
        return;
    }

    // 🔥 reutilizas la misma función
    CargaCompras(inicio, fin);
}

function recalcularTotales() {

    let neto = 0;

    $("#detalle_compra_body tr").each(function () {

        const cantidad = parseFloat($(this).find("td:eq(1)").text()) || 0;

        // Quitar $ y separadores de miles
        const precioTexto = $(this).find("td:eq(2)").text().replace(/\$/g, '').replace(/\./g, '');
        const precio = parseFloat(precioTexto) || 0;

        neto += cantidad * precio;
    });

    const iva = Math.round(neto * 0.19);
    const total = neto + iva;

    $("#total_neto").text(formatearPrecio(neto));
    $("#total_iva").text(formatearPrecio(iva));
    $("#total_total").text(formatearPrecio(total));
}

function limpiarNumero(valor) {

    if (!valor) return 0;

    return parseFloat(
        valor
            .toString()
            .replace(/\$/g, '')   // quitar $
            .replace(/\./g, '')   // quitar separadores de miles
            .replace(/,/g, '.')   // por si viene con coma decimal
    ) || 0;
}

</script>