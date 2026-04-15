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
                    <h5 class="mb-0">Ventas</h5>

                    <!-- Botones -->
                    <div class="d-flex gap-2 align-items-center">

                        <input type="date" id="fecha_inicio" class="form-control">
                        <input type="date" id="fecha_fin" class="form-control">

                        <button class="btn btn-dark" onclick="FiltrarVentas()">
                            <i class="fas fa-search"></i>
                        </button>

                        <button class="btn btn-primary text-nowrap" onclick="NuevaVenta()">
                            <i class="fas fa-plus"></i> Nueva Venta
                        </button>

                    </div>

                </div>             
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_ventas" class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" scope="col">N°</th>
                                        <th width="10%" scope="col">Fecha</th>    
                                        <th width="15%" scope="col">N° Bol/Factura</th>
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

<!-- Modal Venta -->
<div class="modal fade" id="modalVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="modal_titulo_venta">Detalle de Venta</span>
                    <small id="venta_id" class="text-muted"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <!-- Encabezado -->
                <div class="row mb-3">

                    <!-- Cliente (Opcional) -->
                    <div class="col-md-4">
                        <strong>Cliente:</strong><br>
                        <span id="cliente_text">Cliente Ocasional</span>
                        <select id="cliente_input" class="form-control d-none select2"></select>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-4">
                        <strong>Fecha:</strong><br>
                        <span id="fecha_venta_text"></span>
                        <input type="date" id="fecha_venta_input" class="form-control d-none">
                    </div>

                    <!-- Documento -->
                    <div class="col-md-4">
                        <strong>Documento:</strong><br>
                        <span id="documento_text"></span>
                        <div class="d-none" id="documento_inputs">
                            <select id="tipo_documento_input" class="form-control mb-1">
                                <option value="BOLETA">Boleta</option>
                                <option value="FACTURA">Factura</option>
                            </select>
                            <input type="text" id="numero_documento_input" class="form-control" placeholder="Número de documento">
                        </div>
                    </div>

                </div>

                <!-- Formulario agregar ítem -->
                <div class="row mb-3 d-none" id="form_agregar_item_venta">

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
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" id="precio_input" disabled class="form-control text-end" value="0">
                    </div>

                    <!-- Botón agregar -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-success w-100" id="btn_agregar_item_venta" onclick="agregarFilaDetalleVenta()">
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
                            <th class="text-center d-none" id="col_acciones_venta">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="detalle_venta_body"></tbody>
                </table>

                <!-- Totales -->
                <div class="text-end">
                    <p><strong>Neto:</strong> <span id="total_neto_venta">$0</span></p>
                    <p><strong>IVA (19%):</strong> <span id="total_iva_venta">$0</span></p>
                    <p><strong>Total:</strong> <span id="total_total_venta">$0</span></p>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>

                <button type="button" class="btn btn-danger d-none" id="btn_anular_venta" onclick="AnularVenta()">
                    <i class="fas fa-ban"></i> Anular Venta
                </button>

                <!-- Botón guardar -->
                <button type="button" class="btn btn-primary d-none" id="btn_guardar_venta" onclick="GuardarVenta()">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>
<!-- Fin modal venta -->        

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

    // Carga inicial
    CargaVentas();
});

function CargaVentas(fecha_inicio = null, fecha_fin = null) {

    let parametros = {};

    if (fecha_inicio && fecha_fin) {
        parametros.fecha_inicio = fecha_inicio;
        parametros.fecha_fin = fecha_fin;
    }

    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/venta/GetVentas') ?>",
        data: parametros,
        error: function() {
            MyAlert('Imposible cargar la lista de ventas', 'error');
        },
        success: function(data) {

            $('#tabla_ventas').DataTable().destroy();
            $("#tabla_ventas tbody").empty();

            let icono_anulada;

            $.each(data, function(i, item) {

                icono_anulada = (item.estado == 2)
                    ? '<i class="fas fa-ban text-danger"></i>'
                    : '';

                $("#tabla_ventas tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.id}</span></td>` +
                        `<td>${item.fecha_formateada}</td>` +
                        `<td>${item.numero_documento}${icono_anulada}</td>` +
                        `<td class="text-end">${formatearPrecio(item.neto)}</td>` +
                        `<td class="text-end">${formatearPrecio(item.iva)}</td>` +
                        `<td class="text-end">${formatearPrecio(item.total)}</td>` +
                        `<td class="text-center">` +
                            `<a href="#" onclick="VerVenta(${item.id},${item.estado})" class="btn btn-primary btn-sm" title="Ver detalle"><i class="fas fa-eye"></i></a>` +
                        '</td>' +
                    '</tr>'
                );
            });

            $('#tabla_ventas').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                },
                ordering: false
            });
        }
    });
}

function VerVenta(id_venta, estado) {

    modoVerVenta(estado);

    $.ajax({
        url: "<?= base_url('/public/venta/GetVentaDetalle') ?>",
        type: "POST",
        dataType: "json",
        data: { id_venta: id_venta },
        success: function(data) {

            $("#modal_titulo_venta").text("Detalle de Venta");
            $("#venta_id").text("#" + data.venta.id);

            $("#cliente_text").text(data.venta.cliente ?? "Cliente Ocasional");
            $("#fecha_venta_text").text(data.venta.fecha_formateada);
            $("#documento_text").text(
                data.venta.tipo_documento + " " + data.venta.numero_documento
            );

            $("#detalle_venta_body").empty();

            $.each(data.detalle, function(i, item) {

                let subtotal = item.cantidad * item.precio_unitario;

                $("#detalle_venta_body").append(`
                    <tr>
                        <td>${item.repuesto}(${item.codigo})</td>
                        <td class="text-center">${item.cantidad}</td>
                        <td class="text-end">${formatearPrecio(item.precio_unitario)}</td>
                        <td class="text-end">${formatearPrecio(subtotal)}</td>
                    </tr>
                `);
            });

            $("#total_neto_venta").text(formatearPrecio(data.venta.neto));
            $("#total_iva_venta").text(formatearPrecio(data.venta.iva));
            $("#total_total_venta").text(formatearPrecio(data.venta.total));

            $("#modalVenta").modal("show");
        },
        error: function() {
            MyAlert("Error al cargar la venta", "error");
        }
    });
}

function modoVerVenta(estado) {

    $("#fecha_venta_input, #documento_inputs").addClass("d-none");
    $("#cliente_input").next(".select2").addClass("d-none");

    $("#cliente_text, #fecha_venta_text, #documento_text").removeClass("d-none");
    $("#form_agregar_item_venta").addClass("d-none");

    if (estado == 1) {
        $("#btn_anular_venta").removeClass("d-none");
    }

    $("#col_acciones_venta").addClass("d-none");
    $("#btn_guardar_venta").addClass("d-none");
}

function modoCrearVenta() {

    $("#modal_titulo_venta").text("Nueva Venta");
    $("#venta_id").text("");

    $("#fecha_venta_input, #documento_inputs").removeClass("d-none");
    $("#cliente_input").next(".select2").removeClass("d-none");

    $("#cliente_text, #fecha_venta_text, #documento_text").addClass("d-none");

    $("#col_acciones_venta").removeClass("d-none");
    $("#btn_guardar_venta").removeClass("d-none");
    $("#btn_anular_venta").addClass("d-none");

    $("#form_agregar_item_venta").removeClass("d-none");

    $("#cliente_input").val('').trigger('change');
    $("#fecha_venta_input").val(new Date().toISOString().split('T')[0]);
    $("#numero_documento_input").val('');
    $("#tipo_documento_input").val('BOLETA');

    $("#detalle_venta_body").empty();

    $("#total_neto_venta").text("$0");
    $("#total_iva_venta").text("$0");
    $("#total_total_venta").text("$0");
}

function NuevaVenta() {

    modoCrearVenta();

    CargarClientesSelect();
    getRepuestos();

    $("#modalVenta").modal("show");
}

function GuardarVenta() {

    let detalle = [];

    $("#detalle_venta_body tr").each(function () {

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

    if (detalle.length === 0) {
        MyAlert("Debe agregar al menos un ítem", "warning");
        return;
    }

    $.ajax({
        url: "<?= base_url('/public/venta/GuardarVenta') ?>",
        type: "POST",
        dataType: "json",
        data: {
            id_cliente: $("#cliente_input").val() || null,
            fecha: $("#fecha_venta_input").val(),
            tipo_documento: $("#tipo_documento_input").val(),
            numero_documento: $("#numero_documento_input").val(),
            neto: limpiarNumero($("#total_neto_venta").text()),
            iva: limpiarNumero($("#total_iva_venta").text()),
            total: limpiarNumero($("#total_total_venta").text()),
            detalle: detalle
        },
        success: function(resp) {

            if (resp.status === "ok") {
                $("#modalVenta").modal("hide");
                MyAlert(resp.msg || "Venta guardada", "exito");

                setTimeout(() => location.reload(), 2000);
            } else {
                MyAlert(resp.msg || "Error al guardar", "error");
            }
        },
        error: function() {
            MyAlert("Error inesperado", "error");
        }
    });
}

function CargarClientesSelect() {

    $.ajax({
        url: "<?= base_url('/public/config/GetClientes') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {

            const $select = $('#cliente_input');

            // Destruir Select2 si ya existe
            if ($select.hasClass("select2-hidden-accessible")) {
                $select.select2('destroy');
            }

            $select.empty()
                .append('<<option value="" disabled selected>-- Seleccione(Opcional) --</option>');

            $.each(data, function(index, item) {
                $select.append(
                    `<option value="${item.id_cliente}">
                        ${item.rut_cliente} - ${item.nombre_cliente}
                    </option>`
                );
            });

            // Inicializar Select2
            $select.select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalVenta'),
                width: '100%'
            });
        },
        error: function() {
            MyAlert('No fue posible cargar los clientes', 'error');
        }
    });
}

function getRepuestos() {

    $.ajax({
        url: "<?= base_url('/public/config/GetRepuestos') ?>",
        dataType: "json",
        success: function(data) {

            const $select = $('#repuesto_input');

            // Destruir Select2 si ya fue inicializado
            if ($select.hasClass("select2-hidden-accessible")) {
                $select.select2('destroy');
            }

            // Limpiar opciones
            $select.empty().append(
                '<option value="" disabled selected>-- Seleccione --</option>'
            );

            // Agregar datos
            data.forEach(function(item) {
                $select.append(`
                    <option value="${item.id}" data-precio="${item.precio}">
                        ${item.nombre} (${item.codigo})
                    </option>
                `);
            });

            // Inicializar Select2
            $select.select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalVenta'),
                width: '100%'
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

function AnularVenta() {

    const idVenta = $('#venta_id').text().replace('#', '');

    if (!confirm("¿Estás seguro de anular esta venta?")) return;

    $.post("<?= base_url('/public/venta/AnularVenta') ?>",
        { id_venta: idVenta },
        function(resp) {
            if (resp.status === "ok") {
                $("#modalVenta").modal("hide");
                MyAlert(resp.msg || "Venta anulada", "exito");
                setTimeout(() => location.reload(), 2000);
            } else {
                MyAlert(resp.msg || "Error al anular", "error");
            }
        },
        "json"
    );
}

function agregarFilaDetalleVenta() {

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
                <button class="btn btn-danger btn-sm" onclick="eliminarFilaVenta(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $("#detalle_venta_body").append(fila);

    // Limpiar inputs
    $('#repuesto_input').val(null).trigger('change');
    $('#cantidad_input').val(1);
    $('#precio_input').val(0);

    // Recalcular totales
    recalcularTotalesVenta();
}

function recalcularTotalesVenta() {

    let neto = 0;

    $("#detalle_venta_body tr").each(function () {

        const cantidad = parseFloat($(this).find("td:eq(1)").text()) || 0;

        // Quitar símbolo $ y separadores de miles
        const precioTexto = $(this)
            .find("td:eq(2)")
            .text()
            .replace(/\$/g, '')
            .replace(/\./g, '');

        const precio = parseFloat(precioTexto) || 0;

        neto += cantidad * precio;
    });

    const iva = Math.round(neto * 0.19);
    const total = neto + iva;

    $("#total_neto_venta").text(formatearPrecio(neto));
    $("#total_iva_venta").text(formatearPrecio(iva));
    $("#total_total_venta").text(formatearPrecio(total));
}

function FiltrarVentas() {

    let inicio = $('#fecha_inicio').val();
    let fin = $('#fecha_fin').val();

    if (!inicio || !fin) {
        alert('Selecciona ambas fechas');
        return;
    }

    CargaVentas(inicio, fin);
}

$('#repuesto_input').on('change', function() {
    // Obtener la opción seleccionada
    let selectedOption = $(this).find('option:selected');

    // Obtener el precio desde el atributo data-precio
    let precio = selectedOption.data('precio');

    // Asignar el precio al input
    $('#precio_input').val(precio);
});

function eliminarFilaVenta(btn) {
    $(btn).closest("tr").remove();
    recalcularTotalesVenta();
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