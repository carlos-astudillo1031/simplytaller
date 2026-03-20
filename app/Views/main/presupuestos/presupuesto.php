<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/public/assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php echo view("references/navbar"); ?>

<div class="content-wrapper container">
    <h4 class="mt-3 mb-4 fw-bold">Presupuesto N° <span id="id_presupuesto"></span></h4>

    <!-- Sección Datos del Cliente -->
    <div class="card mb-4">
    <div class="card-header fw-bold" style="font-size: 1.2rem;">Datos del Cliente</div>
    <div class="card-body row">
            <div class="col-md-4">
                <label class="fw-bold">Nombre:</label>
                <input type="text" class="form-control" id="cliente_nombre" value="<?= esc($vehiculo['cliente_nombre'] ?? $bomba['cliente_nombre'] ??  '') ?>" disabled>
            </div>
            <div class="col-md-4">
                <label class="fw-bold">Teléfono:</label>
                <input type="text" class="form-control" id="cliente_telefono" value="<?= esc($vehiculo['cliente_telefono'] ?? $bomba['cliente_telefono'] ?? '') ?>" disabled>
            </div>
            <div class="col-md-4">
                <label class="fw-bold">Email:</label>
                <input type="email" class="form-control" id="cliente_email" value="<?= esc($vehiculo['cliente_email'] ?? $bomba['cliente_email'] ?? '') ?>" disabled>
                <input type="hidden" id="id_cliente" value="<?= esc($vehiculo['id_cliente'] ?? $bomba['id_cliente'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- Sección Datos del Vehículo -->
    <!-- <div class="card mb-4">
        <div class="card-header fw-bold" style="font-size: 1.2rem;">Datos del Vehículo</div>
        <div class="card-body row">
            <div class="col-md-3">
                <label class="fw-bold">Patente:</label>
                <input type="text" class="form-control" id="vehiculo_patente" value="<?= esc($vehiculo['patente'] ?? '') ?>" disabled>
            </div>
            <div class="col-md-3">
                <label class="fw-bold">Marca:</label>
                <input type="text" class="form-control" id="vehiculo_marca" value="<?= esc($vehiculo['marca'] ?? '') ?>" disabled>
            </div>
            <div class="col-md-3">
                <label class="fw-bold">Modelo:</label>
                <input type="text" class="form-control" id="vehiculo_modelo" value="<?= esc($vehiculo['modelo'] ?? '') ?>" disabled>
                <input type="hidden" id="id_vehiculo" value="<?= esc($vehiculo['id'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="fw-bold">Kilómetros:</label>
                <input type="number" class="form-control" id="vehiculo_kms">
            </div>
        </div>
    </div> -->

    <!-- Sección Datos del Vehículo o Bomba -->
    <div class="card mb-4">
        <div class="card-header fw-bold" style="font-size: 1.2rem;">
            <?= isset($vehiculo) ? 'Datos del Vehículo' : 'Datos de la Bomba' ?>
        </div>
        <div class="card-body row">
            <!-- Campo Código (solo si es bomba) -->
            <?php if (isset($bomba)): ?>
                <div class="col-md-3">
                    <label class="fw-bold">Código de Bomba o Inyector:</label>
                    <input type="text" class="form-control" value="<?= esc($bomba['codigo']) ?>" disabled>
                </div>
                <input type="hidden" id="id_bomba" value="<?= esc($bomba['id']) ?>">
            <?php endif; ?>

            <!-- Marca -->
            <div class="col-md-3">
                <label class="fw-bold">Marca:</label>
                <input type="text" class="form-control"
                    value="<?= esc($vehiculo['marca'] ?? $bomba['marca'] ?? '') ?>" disabled>
            </div>

            <!-- Modelo -->
            <div class="col-md-3">
                <label class="fw-bold">Modelo:</label>
                <input type="text" class="form-control"
                    value="<?= esc($vehiculo['modelo'] ?? $bomba['modelo'] ?? '') ?>" disabled>               
            </div>

            <!-- Solo para vehículos: Patente -->
            <?php if (isset($vehiculo)): ?>
                <div class="col-md-3">
                    <label class="fw-bold">Patente:</label>
                    <input id="vehiculo_patente" type="text" class="form-control" value="<?= esc($vehiculo['patente']) ?>" disabled>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">Kilómetros:</label>
                    <input type="number" class="form-control" id="vehiculo_kms">
                </div>
                <input type="hidden" id="id_vehiculo" value="<?= esc($vehiculo['id']) ?>">
            <?php endif; ?>
        </div>
    </div>



    <!-- Sección Servicios -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
            Servicios
            <button class="btn btn-primary btn-sm" onclick="agregarServicio()">
                <i class="fa fa-plus"></i> Agregar Servicio
            </button>
        </div>
        <div class="card-body">
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
            <div class="text-end">
                <strong>Subtotal Servicios: $<span id="subtotal_servicios">0</span></strong>
            </div>
        </div>
    </div>

    <!-- Sección Repuestos -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
            Repuestos
            <button class="btn btn-primary btn-sm" onclick="agregarRepuesto()">
                <i class="fa fa-plus"></i> Agregar Repuesto
            </button>
        </div>
        <div class="card-body">
            <table id="tabla_repuestos" class="table table-striped">
                <thead>
                    <tr>
                        <th>Repuesto</th>
                        <th>Cantidad</th>
                        <th>P.Unitario Neto</th>
                        <th>Total Neto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Repuestos agregados dinámicamente -->
                </tbody>
            </table>
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
            <div class="col-12 text-end">
                <button onclick="guardarPresupuestoCompleto()" class="btn btn-primary">
                    <i class="fa fa-check"></i> Guardar Presupuesto
                </button>
            </div>
        </div>

    </div>

    
</button>


</div> <!-- Cierra content-wrapper -->

<?php echo view("references/footer"); ?>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>


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


<script>
let id_presupuesto_actual = null;

$(document).ready(function () {
    CrearOPresupuesto(); // Llama al cargar
});

function CrearOPresupuesto() {
    let datos = {};
    console.log("patente largo"+$('#vehiculo_patente').length);
    if ($('#vehiculo_patente').length) {
        // Es un vehículo
        datos.id_cliente = $('#id_cliente').val();
        datos.id_vehiculo =  $('#id_vehiculo').val();
        datos.id_bomba = 'null';
        datos.kilometros = $('#vehiculo_kms').val(); // si lo necesitás
    } else {
        // Es una bomba
        datos.id_cliente = $('#id_cliente').val();
        datos.id_vehiculo = 'null';
        datos.id_bomba = $('#id_bomba').val() ;
    }
    $.ajax({
        type: "POST",
        url: "<?= base_url('/public/presupuesto/CrearOPresupuesto') ?>",
        dataType: "json",
        data: datos,
        error: function () {
            MyAlert('Error al crear o cargar el presupuesto', 'error');
        },
        success: function (resp) {
            if (resp.success) {
                id_presupuesto_actual = resp.presupuesto.id;                
                $('#id_presupuesto').text(resp.presupuesto.id);                
            }
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

function guardarPresupuestoCompleto() {
    // Obtener ID o datos del presupuesto si ya lo tienes (o null si es nuevo)
    const idPresupuesto = id_presupuesto_actual || null; 
    let presupuesto = {};
    //Es vehiculo
    if ($('#vehiculo_patente').length) {
        console.log('vehiculo');
        const kilometros = parseInt($('#vehiculo_kms').val()) || 0;
        const arrCampos = [
            ['vehiculo_kms', 'number']
        ];

        if (ValidaCamposObligatorios(arrCampos) == false) {
            return false;
        }    
        // Recopilar datos del presupuesto
        presupuesto = {
            id: idPresupuesto,
            kms: kilometros,
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
        };
    }else{
         // Recopilar datos del presu
        console.log('bomba');
        presupuesto = {
            id: idPresupuesto,
            kms: 0,            
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
        };
    }    
    
    
    
    // Recopilar servicios de la tabla
    let servicios = [];
    $('#tabla_servicios tbody tr').each(function() {
        const id_servicio = $(this).data('idServicio'); // aquí está el cambio
        const cantidad = parseInt($(this).find('.cantidad_servicio').text()) || 0;
        const precioText = $(this).find('td:nth-child(3)').text().replace(/\$|,/g, '');        
        const precio = parseFloat(precioText.replace(/\./g, '')) || 0;

        if (id_servicio) {
            servicios.push({ id_servicio, cantidad ,precio });
        }
    });
    

    // Recopilar repuestos de la tabla
    let repuestos = [];
    $('#tabla_repuestos tbody tr').each(function() {
        const id_repuesto = $(this).data('id'); // idem, en <tr data-id="X">
        const cantidad = parseInt($(this).find('.cantidad').text()) || 0;
        const precioText = $(this).find('td:nth-child(3)').text().replace(/\$|,/g, '');
        const precio = parseFloat(precioText.replace(/\./g, '')) || 0;
        if(id_repuesto) {
            repuestos.push({ id_repuesto, cantidad, precio });
        }
    });
    
    // Armar payload
    const payload = {
        presupuesto,
        servicios,
        repuestos
    };

    console.log(payload);
    
    
    // Enviar al backend vía AJAX POST
    $.ajax({
        url: "<?= base_url('/public/presupuesto/GuardarPresupuestoCompleto') ?>",
        method: "POST",
        data: JSON.stringify(payload),
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            if(response.success) {
                MyAlert('Presupuesto guardado con éxito', 'exito');
                window.location.href = "<?= base_url('/public/presupuesto/lista-presupuestos') ?>";
            } else {
                MyAlert('Error al guardar el presupuesto', 'error');
            }
        },
        error: function() {
            MyAlert('Error: Imposible guardar los datos', 'error');            
        }
    });
}



</script>