<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/public/assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php echo view("references/navbar"); ?>

<style>

  /* Encabezado de la OS */  
    @media (max-width: 768px) {
    .os-header-mobile {
        display: flex;
        flex-direction: column; /* título arriba, info abajo */
        gap: 8px; /* separa del elemento de arriba */
        margin-top: 12px; /* opcional, margen superior */
    }

    .os-header-mobile .os-info {
        display: flex;
        flex-wrap: wrap; /* si no caben en una línea, el presupuesto baja */
        justify-content: flex-start; /* todo alineado a la izquierda */
        gap: 8px; /* espacio entre estado y presupuesto */
        width: 100%;
    }

    .os-header-mobile .os-info span {
        display: inline-flex;
        align-items: center; /* centra verticalmente los elementos */
    }
}

/* Datos OS Header */
@media (max-width: 768px) {
    .os-datos-header {
        flex-direction: column; /* apila los elementos */
        align-items: flex-start; /* alineado a la izquierda */
        gap: 10px; /* separación vertical */
        text-align: left; /* asegura que el h5 quede a la izquierda */
    }

    .os-datos-header h5 {
        width: 100%; /* ocupa todo el ancho disponible */
        font-size: 1rem;
        text-align: left; /* fuerza alineación a la izquierda */
    }

    .os-datos-header > div {
        width: 100%;
        max-width: 100%;
    }

    .os-datos-header label {
        font-size: 0.9rem;
    }
}



</style>

 <!-- Inicio Preparar Whatsapp -->
       <?php
            $telefonoRaw = $vehiculo['cliente_telefono'] ?? $bomba['cliente_telefono'] ?? '';

            // Limpia todo lo que no sea dígito
            $telefonoLimpio = preg_replace('/\D/', '', $telefonoRaw);

            // Si ya comienza con 56, lo dejamos; si no, lo agregamos
            if (strpos($telefonoLimpio, '56') === 0) {
                $whatsapp = $telefonoLimpio;
            } else {
                $whatsapp = '56' . $telefonoLimpio;
            }
        ?>

<!-- Fin Preparar Whatsapp -->

<!-- Para validar si puede ver precios -->
<?php
    $permisos_especiales = session()->get('permisos_especiales');     
    $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
?>

<div class="content-wrapper container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="os-header-mobile">
            <h4 class="mb-0 fw-bold me-2">
                Orden de Servicio N° <?= esc($orden_servicio['id']);?> 
            </h4>

            <div class="os-info">
                <span id="estado_os_color"><span id="estado_os_text"></span></span>
                <?php if (!empty($presupuesto['id'])): ?>
                    <span class="text-muted">&nbsp;Presupuesto Asociado: <a href="#" onclick="VerPresupuesto()"> <?= esc($presupuesto['id']); ?></a></span>
                <?php endif; ?>
            </div>
        </div>


        

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="accionesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Acciones
            </button>
            <ul class="dropdown-menu" aria-labelledby="accionesDropdown">
                <?php $estado_orden = esc($orden_servicio['estado']);?>
                <?php if ($estado_orden < 6): ?>
                    <li><a class="dropdown-item" href="#" onclick="GuardarCambiosOS()"><i class="fa fa-save"></i> Guardar Cambios</a></li>
                    <?php if ($estado_orden < 2): ?>
                        <li><a class="dropdown-item" href="#" onclick="CrearPresupuesto()"><i class="fa fa-file"></i> Crear Presupuesto </a></li>
                    <?php endif; ?>
                    <?php /* if ($estado_orden >= 2): */ ?>
                        <li><a class="dropdown-item" href="#" onclick="CambiaEstado(3)"><i class="fa fa-play"></i> Empezar Trabajo</a></li>
                        <li><a class="dropdown-item" href="#" onclick="CambiaEstado(4)"><i class="fas fa-flag-checkered"></i> Terminar Trabajo </a></li>
                    <?php /* endif; */ ?>
                    <li><a class="dropdown-item" href="#" onclick="CambiaEstado(5)"><i class="fa fa-ban"></i> Anular Orden</a> </li>                    
                <?php endif; ?>    
                <?php if ($estado_orden == 6): ?>
                    <li><a class="dropdown-item" href="#" onclick="VerDetallePago()"><i class="fa fa-eye"></i> Ver Pago</a> </li>
                    <li><hr class="dropdown-divider"></li>    
                <?php endif; ?>                                
                <li><a class="dropdown-item" href="#" onclick="verPDFOrdenServicio('<?= esc($orden_servicio['id']); ?>')"><i class="fa fa-print"></i> Imprimir Orden</a> </li>                
                <li><a class="dropdown-item" href="#" onclick="verPDFOrdenServicio('<?= esc($orden_servicio['id']); ?>','true')"><i class="fa fa-download"></i> Descargar Orden</a> </li>                
                <li><a class="dropdown-item" href="#" onclick="enviarWhatsApp(<?= esc($whatsapp)?>,'Hola, le informamos que su vehiculo esta listo para ser entregado.')">
                    <i class="fab fa-whatsapp"></i> Avisar Vehículo Listo
                </a></li>

                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('/public/orden-servicio/lista-ordenes'); ?>"><i class="fa fa-sign-out"></i> Salir</a></li>
            </ul>
        </div>
    </div>

    <!-- Sección Datos del Cliente -->
    <div class="accordion mt-2 mb-4" id="acordeonCliente">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCliente">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCliente" aria-expanded="false" aria-controls="collapseCliente">
                <strong>Datos del Cliente</strong>
            </button>
            </h2>
            <div id="collapseCliente" class="accordion-collapse collapse" aria-labelledby="headingCliente" data-bs-parent="#acordeonCliente">
            <div class="accordion-body">
                <div class="row">
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
            </div>
        </div>
        </div>

    <!-- Sección Datos del Vehículo o Bomba -->
    <div class="accordion mb-4" id="acordeonEquipo">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEquipo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEquipo" aria-expanded="false" aria-controls="collapseEquipo">
            <strong><?= isset($vehiculo) ? 'Datos del Vehículo' : 'Datos de la Bomba' ?></strong>
        </button>
        </h2>
        <div id="collapseEquipo" class="accordion-collapse collapse" aria-labelledby="headingEquipo" data-bs-parent="#acordeonEquipo">
        <div class="accordion-body">
            <div class="row">
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
                <input type="text"  class="form-control" value="<?= esc($vehiculo['marca'] ?? $bomba['marca'] ?? '') ?>" disabled>
            </div>

            <!-- Modelo -->
            <div class="col-md-3">
                <label class="fw-bold">Modelo:</label>
                <input type="text" class="form-control" value="<?= esc($vehiculo['modelo'] ?? $bomba['modelo'] ?? '') ?>" disabled>
            </div>

            <!-- Solo para vehículos: Patente y Kilómetros -->
            <?php if (isset($vehiculo)): ?>
                <div class="col-md-3">
                <label class="fw-bold">Patente:</label>
                <input id="vehiculo_patente" type="text" class="form-control" value="<?= esc($vehiculo['patente']) ?>" disabled>
                </div>
                <div class="col-md-3">
                <label class="fw-bold">Kilómetros:</label>
                <input type="number" class="form-control" id="vehiculo_kms" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?>  value="<?= esc($orden_servicio['kms']) ?>">
                </div>
                <input type="hidden" id="id_vehiculo" value="<?= esc($vehiculo['id']) ?>">
            <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
    </div>


    <!-- Tabs de OS -->

    <div class="card mb-5">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center os-datos-header" style="font-size: 1.2rem;">
            <!-- Título -->
            <h5 class="fw-bold mb-3">Datos de la Orden de Servicio</h5>

            <!-- Fila con mecánico y fecha -->
            <div class="d-flex justify-content-between align-items-center" style="max-width: 500px;">
                <!-- Mecánico -->
                <div style="flex: 1; margin-right: 10px;font-size: 1rem;">
                    <label for="id_mecanico" class="form-label"><b>Mecánico</b></label>
                    <select id="id_mecanico" class="form-control">                                                
                        <!-- Opciones se llenan dinámicamente -->
                    </select>
                </div>

                <!-- Fecha -->
                <div style="flex: 0 0 200px;font-size: 1rem;">
                    <label for="fecha_estimada_entrega" class="form-label"><b>Fecha de Entrega</b></label>
                    <input type="date" 
                        id="fecha_estimada_entrega"
                        value="<?= esc($orden_servicio['fecha_estimada_entrega'] ?? date('Y-m-d')) ?>" 
                        class="form-control">
                </div>
            </div>

        </div>




    
    <div class="container">        
        <div class="accordion mt-2" id="accordionMotivo">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMotivo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMotivo" aria-expanded="false" aria-controls="collapseMotivo">
                    <b>Motivo</b>
                </button>
                </h2>
                <div id="collapseMotivo" class="accordion-collapse collapse" aria-labelledby="headingMotivo" data-bs-parent="#accordionMotivo">
                <div class="accordion-body">                    
                    <textarea class="form-control" id="Motivo" rows="3" placeholder="Escribe el motivo aquí..." <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?>><?=esc($orden_servicio['motivo'])?></textarea>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="accordion mt-2" id="accordionDiagnostico">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDiagnostico">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiagnostico" aria-expanded="false" aria-controls="collapseDiagnostico">
                    <b>Diagnóstico</b>
                </button>
                </h2>
                <div id="collapseDiagnostico" class="accordion-collapse collapse" aria-labelledby="headingDiagnostico" data-bs-parent="#accordionDiagnostico">
                <div class="accordion-body">                    
                    <textarea class="form-control" id="Diagnostico" rows="3" placeholder="Escribe el diagnóstico aquí..." <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?>><?=esc($orden_servicio['diagnostico'])?></textarea>
                </div>
                </div>
            </div>
        </div>
    </div>            

    <!-- Tabs actualizados -->
    <ul class="nav nav-tabs px-3 pt-2 mt-2" id="ordenTabs" role="tablist" style="border-bottom: 1px solid #dee2e6;">
        <li class="nav-item" role="presentation">
        <button class="nav-link active" id="observaciones-tab" data-bs-toggle="tab" data-bs-target="#observaciones" type="button" role="tab" aria-controls="observaciones" aria-selected="true">
            <i class="fas fa-exclamation"></i> Observaciones
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="inventario-tab" data-bs-toggle="tab" data-bs-target="#inventario" type="button" role="tab" aria-controls="inventario" aria-selected="false">
            <i class="fas fa-clipboard-check"></i> Inventario
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="danos-tab" data-bs-toggle="tab" data-bs-target="#danos" type="button" role="tab" aria-controls="danos" aria-selected="false">
            <i class="fa fa-car-crash"></i> Daños Previos
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios" type="button" role="tab" aria-controls="servicios" aria-selected="false">
            <i class="fa fa-tools"></i> Servicios
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="repuestos-tab" data-bs-toggle="tab" data-bs-target="#repuestos" type="button" role="tab" aria-controls="repuestos" aria-selected="false">
            <i class="fa fa-cogs"></i> Repuestos
        </button>
        </li>
        <li class="nav-item" role="presentation">
        <button class="nav-link" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen" type="button" role="tab" aria-controls="resumen" aria-selected="false">
            <i class="fa fa-list"></i> Resumen
        </button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="card-body border-top">
        <div class="tab-content" id="ordenTabsContent">

        <div class="tab-pane fade show active pt-3" id="observaciones" role="tabpanel" aria-labelledby="observaciones-tab">
            <div class="row">
               <?php if (!empty($observaciones)): ?>
                    <?php foreach ($observaciones as $index => $obs): ?>
                        <div class="col-md-6 mb-2">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="observaciones[]" 
                                    <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?>
                                    value="<?= $obs['id'] ?>" 
                                    id="obs_<?= $obs['id'] ?>"
                                >
                                <label class="form-check-label d-flex align-items-center gap-2" for="obs_<?= $obs['id'] ?>">
                                    <img 
                                        src="<?= base_url($obs['icono']) ?>" 
                                        alt="<?= esc($obs['nombre']) ?>" 
                                        style="width: 24px; height: 24px;"
                                    >
                                    <?= esc($obs['nombre']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

        </div>

        <div class="tab-pane fade pt-3" id="inventario" role="tabpanel" aria-labelledby="inventario-tab">
           <div class="row">
                <?php if (!empty($inventario)): ?>        
                    <?php foreach ($inventario as $i => $item): ?>
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="inventario[]" value="<?= esc($item['id']) ?>" id="inv_<?= $item['id'] ?>">
                                <label class="form-check-label" for="inv_<?= $item['id'] ?>">
                                    <?= esc($item['nombre']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Nivel de Combustible -->
            <div class="mt-3">
                <label class="fw-bold d-block mb-2">Nivel de Combustible:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="combustible" id="combustible_lleno" value="Lleno">
                    <label class="form-check-label" for="combustible_lleno">Lleno</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="combustible" id="combustible_3_4" value="3/4">
                    <label class="form-check-label" for="combustible_3_4">3/4</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="combustible" id="combustible_medio" value="1/2">
                    <label class="form-check-label" for="combustible_medio">1/2</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="combustible" id="combustible_1_4" value="1/4">
                    <label class="form-check-label" for="combustible_1_4">1/4</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> name="combustible" id="combustible_reserva" value="Reserva">
                    <label class="form-check-label" for="combustible_reserva">Reserva</label>
                </div>
            </div>

        </div>

        <div class="tab-pane fade pt-3" id="danos" role="tabpanel" aria-labelledby="danos-tab">
            <div class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                    <select class="form-select" id="tipo_danio" name="tipo_danio">
                        <option value="" selected>Seleccione tipo de daño</option>
                        <?php foreach ($danios as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>

                    <div class="col-md-3">
                    <select class="form-select" id="zona_danio" name="zona_danio">
                        <option value="" selected>Seleccione zona</option>
                        <?php foreach ($zonas as $z): ?>
                            <option value="<?= $z['id'] ?>"><?= $z['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Descripción del daño">
                    </div>
                    <div class="col-md-3">
                        <button type="button" onclick="AgregaDanio()" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?> class="btn btn-primary">
                        <i class="fa fa-plus"></i> Agregar
                    </button>
                    </div>
                </div>
                </div>

                <!-- Tabla donde se agregarán los ítems -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla-danios">
                        <thead>
                            <tr>
                            <th>Tipo de Daño</th>
                            <th>Zona</th>
                            <th>Comentario</th>
                            <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se agregan filas dinámicamente -->
                        </tbody>
                    </table>
                </div> 
        </div>

        <div class="tab-pane fade pt-3" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
            <!-- Sección Servicios -->
            <div class="card mb-4">                
                <div class="card-body">
                    <div class="table-responsive">
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
                                <?php $subtotal_servicios = 0; ?>
                                <?php if (!empty($servicios)): ?>
                                    <?php foreach ($servicios as $servicio): ?>
                                        <?php $subtotal_servicios += $servicio['precio'] * $servicio['cantidad']; ?>                                
                                        <tr data-id-servicio="<?= $servicio['id_servicio'] ?>">    
                                            <td><?= $servicio['nombre'] ?></td>
                                            <td class="cantidad_servicio"><?= $servicio['cantidad'] ?></td>
                                            <td>$<?= number_format($servicio['precio'], 0, '', '.');   ?></td>
                                            <td class="total_servicios">$<?= number_format($servicio['precio'] * $servicio['cantidad'], 0, '', '.'); ?></td>
                                            <td>
                                                <button type="button" <?= ($orden_servicio['estado']  > 3) ? 'disabled' : '' ?> class="btn btn-danger btn-sm btn-eliminar-servicio">
                                                    <i class="fa fa-trash"></i> 
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <strong>Subtotal Servicios: $<span id="subtotal_servicios"><?= number_format($subtotal_servicios, 0, '', '.'); ?></span></strong>
                    </div>
                    <div class="card-header d-flex justify-content-end align-items-center fw-bold" style="font-size: 1.2rem;">
                        <button class="btn btn-primary" <?= ($orden_servicio['estado'] > 3) ? 'disabled' : '' ?> onclick="agregarServicio()">
                            <i class="fa fa-plus"></i> Agregar Servicio
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade pt-3" id="repuestos" role="tabpanel" aria-labelledby="repuestos-tab">
            <!-- Sección Repuestos -->
            <div class="card mb-4">             
                <div class="card-body">
                    <div class="table-responsive">
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
                                <?php $subtotal_repuestos = 0; ?>
                                <?php if (!empty($repuestos)): ?>
                                    <?php foreach ($repuestos as $repuesto): ?>
                                        <?php $subtotal_repuestos += $repuesto['precio'] * $repuesto['cantidad']; ?>
                                        <tr data-id="<?= $repuesto['id_repuesto'] ?>">
                                            <td><?= $repuesto['nombre'] ?><?= '('. $repuesto['codigo'] . ')' ?></td>
                                            <td class="cantidad"><?= $repuesto['cantidad'] ?></td>
                                            <td>$<?= number_format($repuesto['precio'], 0, '', '.');   ?></td>
                                            <td class="total">$<?= number_format($repuesto['precio'] * $repuesto['cantidad'], 0, '', '.'); ?></td>
                                            <td>
                                                <button type="button" <?= ($orden_servicio['estado'] > 3) ? 'disabled' : '' ?> class="btn btn-danger btn-sm" onclick="eliminarRepuesto(<?= $repuesto['id_repuesto'] ?>)">
                                                    <i class="fa fa-trash"></i> 
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>    
                                <?php endif; ?>    
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <strong>Subtotal Repuestos: <span id="subtotal_repuestos"><?= number_format($subtotal_repuestos, 0, '', '.'); ?></span></strong>
                    </div>
                    <div class="card-header d-flex justify-content-end align-items-center fw-bold" style="font-size: 1.2rem;">                    
                        <button class="btn btn-primary" <?= ($orden_servicio['estado'] > 3) ? 'disabled' : '' ?> onclick="agregarRepuesto()">
                            <i class="fa fa-plus"></i> Agregar Repuesto
                        </button>
                </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade pt-3" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
            <!-- Sección Resumen Totales -->
            <div class="card mb-4">              
                <div class="card-body row">
                    <div class="col-md-6">
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
                </div>

            </div>
        </div>

        </div>
    </div>
</div>

<!-- Fin Tabs de OS -->

<!-- Modal Presupuesto -->
<div class="modal fade" id="modalPresupuesto" tabindex="-1" aria-labelledby="modalPresupuestoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPresupuestoLabel">Presupuesto N° <span id="modal_num_presupuesto"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

            <!-- Sección Kms -->
            <div id="card_kms" class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Kilometraje del Vehiculo                    
                </div>
                <div class="card-body">
                    <table id="tabla_kms" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kms</th>                               
                            </tr>
                            <tr>
                                <td><input type="number" class="form-control" id="kms" name="kms" placeholder="Kms" disabled></td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Servicios agregados dinámicamente -->
                        </tbody>
                    </table>                   
                    </div>
            </div>

            <!-- Sección Servicios -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Servicios                
                   <!--  <button class="btn btn-primary btn-sm" onclick="agregarServicio()">
                        <i class="fa fa-plus"></i> Agregar Servicio
                    </button>    -->
                </div>
                <div class="card-body">
                    <table id="tabla_servicios_ppto" class="table table-striped">
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
                        <strong>Subtotal Servicios: <span id="subtotal_servicios_ppto">0</span></strong>
                    </div>
                    </div>
                </div>

            <!-- Sección Repuestos -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center fw-bold" style="font-size: 1.2rem;">
                    Repuestos            
                   <!--  <button class="btn btn-primary btn-sm" onclick="agregarRepuesto()">
                        <i class="fa fa-plus"></i> Agregar Repuesto
                    </button> -->
                </div>
                <div class="card-body">
                    <table id="tabla_repuestos_ppto" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Repuesto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario Neto</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Repuestos agregados dinámicamente -->
                        </tbody>
                    </table>
                    <div class="text-end">
                        <strong>Subtotal Repuestos: <span id="subtotal_repuestos_ppto">0</span></strong>
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
                                <td><span id="resumen_servicios_ppto">0</span></td>
                            </tr>
                            <tr>
                                <th>Subtotal Repuestos:</th>
                                <td><span id="resumen_repuestos_ppto">0</span></td>
                            </tr>
                            <tr>
                                <th>Total Neto:</th>
                                <td><strong><span id="total_general_ppto">0</span></strong></td>
                            </tr>
                            <tr>
                                <th>Iva:</th>
                                <td><strong><span id="iva_ppto">0</span></strong></td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td><strong><span id="total_final_ppto">0</span></strong></td>
                            </tr>
                        </table>
                    </div>                    
                </div>

            </div>

      </div>
      <div class="modal-footer" id="modal-footer-presupuesto">
        
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar/editar repuesto -->
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

<!-- Modal Detalle Pago -->
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-labelledby="detallePagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="detallePagoLabel">Detalle de Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tabla-detalle-pago" class="table table-bordered table-striped">
            <thead class="bg-light">
              <tr>
                <th>Fecha</th>
                <th>Forma de Pago</th>
                <th>Número de Operación</th>
                <th>Tipo Dte</th>
                <th>Numero Dte</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>


</div> <!-- //Cierra div container -->    

<?php echo view("references/footer"); ?>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>


var id_orden_servicio = "<?= esc($orden_servicio['id']) ?>";

$(document).ready(function () {
    CargaObservacionesMarcadas(id_orden_servicio);    
    let estado = TraduceEstados(<?= esc($orden_servicio['estado']) ?>);
    $('#estado_os_text').text(estado.texto);
    let nivel_combustible = '<?= esc($orden_servicio['nivel_combustible']) ?>';
    $('input[name="combustible"][value="'+nivel_combustible+'"]').prop('checked', true);
    $('#estado_os_color').addClass('badge bg-' + estado.color);
    CargaMecanicos();
});

function CargaObservacionesMarcadas(id_orden_servicio) {
    $.ajax({
        url: "<?= base_url('/public/orden-servicio/GetObservacionesMarcadas') ?>",
        method: "POST",
        data: {
            id_orden_servicio: id_orden_servicio
        },
        dataType: "json",
        success: function(observaciones) {
            observaciones.forEach(function(item) {
                var id_observacion = item.id_item;
                $('#obs_' + id_observacion).prop('checked', true);
            });
            //Terminando la carga de Observaciones, Carga inventario
            CargaInventarioMarcado(id_orden_servicio);
        },
        error: function() {
            MyAlert('Error al cargar las observaciones marcadas', 'error');
        }
    });
}

function CargaInventarioMarcado(id_orden_servicio) {
    $.ajax({
        url: "<?= base_url('/public/orden-servicio/GetInventarioMarcado') ?>",
        method: "POST",
        data: {
            id_orden_servicio: id_orden_servicio
        },
        dataType: "json",
        success: function(inventario) {
            inventario.forEach(function(item) {
                var id_item = item.id_item;
                $('#inv_' + id_item).prop('checked', true);
            });
            CargaDaniosMarcados(id_orden_servicio);
        },
        error: function() {
            MyAlert('Error al cargar el inventario marcado', 'error');
        }
    });
}


function CargaDaniosMarcados(id_orden_servicio) {
    $.ajax({
        url: "<?= base_url('/public/orden-servicio/GetDaniosMarcados') ?>",
        method: "POST",
        data: {
            id_orden_servicio: id_orden_servicio
        },
        dataType: "json",
        success: function(danios) {
            $('#tabla-danios tbody').empty();            
           danios.forEach(function(item) {
               console.log($('#tabla-danios tbody').length); 
               $('#tabla-danios tbody').append(
                    `<tr data-id-tipo="${item.id_tipo}" data-id-zona="${item.id_zona}">
                        <td>${item.tipo}</td>
                        <td>${item.zona}</td>           
                        <td>${item.comentario}</td>
                        <td>
                            <button onclick="eliminarDanio(this);" <?= ($orden_servicio['estado'] == 6) ? 'disabled' : '' ?>  class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`
               );
            });
            actualizarResumenTotales(); 

        },
        error: function() {
            MyAlert('Error al cargar los danios marcados', 'error');
        }
    });
}

function AgregaDanio() {        
    const campos = [['tipo_danio', 'select'], ['zona_danio', 'select']];
    if (ValidaCamposObligatorios(campos) == false) {
            return false;
    }    
    var tipo_danio = $('#tipo_danio option:selected').text();
    var zona_danio = $('#zona_danio option:selected').text();
    var comentario = $('#comentario').val();
    var nuevaFila = `
    <tr data-id-tipo="${$('#tipo_danio').val()}" data-id-zona="${$('#zona_danio').val()}">
        <td>${tipo_danio}</td>
        <td>${zona_danio}</td>
        <td>${comentario}</td>
        <td>
            <button onclick="eliminarDanio(this);" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
    `;
    $('#tabla-danios tbody').append(nuevaFila);
    $('#tipo_danio').val('');
    $('#zona_danio').val('');
    $('#comentario').val('');
    actualizarResumenTotales();
}

// Función para eliminar repuesto de la tabla
function eliminarDanio(fila) {
    $(fila).closest('tr').remove();
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



function GuardarCambiosOS(){    
    let idMecanico = $('#id_mecanico').val();
    idMecanico = (idMecanico && parseInt(idMecanico) > 0) ? parseInt(idMecanico) : null;    
    
    //Es vehiculo
    if ($('#vehiculo_patente').length) {        
        const kilometros = parseInt($('#vehiculo_kms').val()) || 0;
        const arrCampos = [
            ['vehiculo_kms', 'number'],
            ['Motivo', 'textarea']
        ];
        console.log();
        if (ValidaCamposObligatorios(arrCampos) == false) {
            return false;
        }    
        // Recopilar datos de la OS        

        orden_servicio = {
            id: id_orden_servicio,
            kms: kilometros,
            motivo: $('#Motivo').val(),
            diagnostico: $('#Diagnostico').val(),           
            id_mecanico: idMecanico, 
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
            fecha_estimada_entrega: $('#fecha_estimada_entrega').val()
        };
    }else{
         // Recopilar datos de la OS      
        orden_servicio = {
            id: id_orden_servicio,
            kms: 0,            
            motivo: $('#Motivo').val(),
            diagnostico: $('#Diagnostico').val(),       
            id_mecanico: idMecanico, 
            total: parseFloat(limpiarNumero($('#total_general').text())) || 0,
            fecha_estimada_entrega: $('#fecha_estimada_entrega').val()
        };
    }    

    //Observaciones
    var observaciones = [];

    // Selecciona todos los checkboxes marcados con name="observaciones[]"
    $('input[name="observaciones[]"]:checked').each(function() {        
        observaciones.push({
            id: $(this).val(),            // lo que tienes como "id"
            texto: $('label[for="'+ this.id +'"]').text().trim(),
        });
    });

    //Inventario
    var inventario = [];

    // Selecciona todos los checkboxes marcados con name="inventario[]"
    $('input[name="inventario[]"]:checked').each(function() {
        inventario.push({
            id: $(this).val(),            // lo que tienes como "id"
            texto: $('label[for="'+ this.id +'"]').text().trim(),
        });
    });

    //Nivel de Combustible
    let combustible = [];

    // push con el valor seleccionado
    let nivel_combustible = document.querySelector('input[name="combustible"]:checked');
    combustible.push({        
        valor: nivel_combustible ? nivel_combustible.value : null
    });

    //Daños
    var danios = [];

    $('#tabla-danios tbody tr').each(function() {
        var fila = $(this);
        var id_tipo = fila.data('id-tipo');   // obtiene data-id-tipo
        var id_zona = fila.data('id-zona');   // obtiene data-id-zona
        var comentario = fila.find('td').eq(2).text();

        danios.push({
            id_tipo: id_tipo,
            id_zona: id_zona,
            comentario: comentario
        });
    });


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
        orden_servicio,
        observaciones,
        inventario,
        combustible,
        danios,
        servicios,
        repuestos
    };

    console.log(payload);


    return $.ajax({
        url: "<?= base_url('/public/orden-servicio/GuardarCambios') ?>",
        method: "POST",
        data: JSON.stringify(payload),
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            if (response.status === 'ok') {
                MyAlert('Orden de servicio actualizada', 'exito');
            } else {
                MyAlert(response.error, 'error');
            }
        },
        error: function() {
            MyAlert('Error al actualizar la orden de servicio', 'error');
        }
    });



}

function CrearPresupuesto(){
    GuardarCambiosOS().done(function() {
        // Recopilar servicios de la tabla
        let servicios = [];
        $('#tabla_servicios tbody tr').each(function() {
            const id_servicio = $(this).data('idServicio');
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
            const id_repuesto = $(this).data('id');
            const cantidad = parseInt($(this).find('.cantidad').text()) || 0;
            const precioText = $(this).find('td:nth-child(3)').text().replace(/\$|,/g, '');
            const precio = parseFloat(precioText.replace(/\./g, '')) || 0;
            if(id_repuesto) {
                repuestos.push({ id_repuesto, cantidad, precio });
            }
        });

        //Validamos que al menos tenga repuestos o servicios
        if (servicios.length === 0 && repuestos.length === 0) {
            alert("Debes agregar al menos un servicio o un repuesto.");
            return;
        }

        $.ajax({
            url: "<?= base_url('/public/orden-servicio/CrearPresupuesto') ?>",
            method: "POST",
            data: {
                id_orden_servicio: id_orden_servicio,
                servicios: servicios,
                repuestos: repuestos
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'ok') {
                    MyAlert(response.message, 'exito');
                    window.location.href = "<?= base_url('/public/presupuesto/lista-presupuestos') ?>";       
                } else {
                    MyAlert(response.message, 'error');
                }
            },
            error: function() {
                MyAlert('Error al crear el presupuesto', 'error');
            }
        });
    });
}

function CambiaEstado(estado){
     GuardarCambiosOS().done(function() {
        $.ajax({
            url: "<?= base_url('/public/orden-servicio/CambiaEstado') ?>",
            method: "POST",
            data: {
                id_orden_servicio: id_orden_servicio,
                estado: estado
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'ok') {
                    MyAlert(response.message, 'exito');
                    window.location.reload();
                } else {
                    MyAlert(response.message, 'error');
                }
            },
            error: function() {
                MyAlert('Error al cambiar el estado de la orden de servicio', 'error');
            }
        })
    });    
}

function VerDetallePago(){
    $.ajax({
        url: "<?= base_url('/public/orden-servicio/GetDetalleFormaPago') ?>",
        method: "POST",
        data: {
            id_orden_servicio: id_orden_servicio
        },
        dataType: "json",
        success: function(response) {
            $('#tabla-detalle-pago tbody').empty();            
            let fecha = response.fecha_pago.split('-'); // ["2025", "09", "11"]
            let fechaFormateada = `${fecha[2]}-${fecha[1]}-${fecha[0]}`; // "11-09-2025"
            let formaPago = response.tipo_pago.charAt(0).toUpperCase() + response.tipo_pago.slice(1);
            let numeroDocumento = response.numero_documento ? response.numero_documento : '-';
            let tipo_dte = response.tipo_dte ? response.tipo_dte : '-';
            if(tipo_dte == 1) {
                tipo_dte = 'Factura';
            }else{
                tipo_dte = 'Boleta';
            }
            let numero_dte = response.numero_dte ? response.numero_dte : '-';
            if (response.tipo_pago === 'tarjeta') {
                formaPago += ' de ' + response.tipo_tarjeta.charAt(0).toUpperCase() + response.tipo_tarjeta.slice(1);
            }
            $('#tabla-detalle-pago tbody').append(`
                    <tr>
                        <td>${fechaFormateada}</td>
                        <td>${formaPago}</td>
                        <td>${numeroDocumento}</td>
                        <td>${tipo_dte}</td>
                        <td>${numero_dte}</td>
                    </tr>
            `);
        },
        error: function() {
            MyAlert('Error al obtener el detalle de pago', 'error');
        }
    })

    $('#modalDetallePago').modal('show');    
}

function VerPresupuesto() {
    let num_presupuesto      = <?= isset($presupuesto['id']) ? json_encode($presupuesto['id']) : 'null' ?>;
    let id_orden_servicio    = <?= isset($orden_servicio['id']) ? json_encode($orden_servicio['id']) : 'null' ?>;
    let vehiculo             = <?= isset($vehiculo['marca']) ? json_encode($vehiculo['marca']) : 'null' ?>;
    let kms                  = <?= isset($orden_servicio['kms']) ? json_encode($orden_servicio['kms']) : 'null' ?>;
    let bombaId = <?= isset($bomba['id']) ? json_encode($bomba['id']) : 'null' ?>;

    let es_vehiculo = 0;
    if (bombaId == null) {
        es_vehiculo = 1;        
    }


    
    $.ajax({
        data: { num_presupuesto: num_presupuesto },
        url: "<?= base_url('/public/presupuesto/GetDetallePresupuesto') ?>",
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            // Limpiar las tablas antes de insertar
            if(es_vehiculo == 1){                              
               $('#card_kms').removeClass('d-none');
               $('#kms').val(parseInt(kms));
               
            }else{                
                $('#card_kms').addClass('d-none');
            }
            

            $("#tabla_servicios_ppto tbody").empty();
            $("#tabla_repuestos_ppto tbody").empty();

            // Insertar SERVICIOS
            let total_servicios =0;
            $.each(response.servicios, function(i, item) {                
                const total = item.precio * item.cantidad;               
                total_servicios = parseFloat(total) + parseFloat(total_servicios);
                $("#tabla_servicios_ppto tbody").append(`
                    <tr data-id-servicio="${item.id_servicio}">
                        <td>${item.nombre_servicio}</td>
                        <td class="cantidad_servicio">${item.cantidad}</td>
                        <td>${formatearPrecio(item.precio)}</td>
                        <td class="total_servicios">${formatearPrecio(total)}</td>
                        <td>
                            
                        </td>
                    </tr>                    
                `);
            });
     
            $('#subtotal_servicios_ppto').text(formatearPrecio(total_servicios));
  
            // Insertar REPUESTOS
            let total_repuestos = 0;
            $.each(response.repuestos, function(i, item) {
                const total = item.precio_unitario * item.cantidad;
                total_repuestos = total_repuestos + total;
                $("#tabla_repuestos_ppto tbody").append(`
                    <tr data-id="${item.id_repuesto}">
                        <td>${item.nombre_repuesto} (${item.codigo})</td>
                        <td class="cantidad">${item.cantidad}</td>
                        <td>${formatearPrecio(item.precio_unitario)}</td>
                        <td class="total">${formatearPrecio(total)}</td>
                       
                    </tr>
                `);
            });            
            $('#subtotal_repuestos_ppto').text(formatearPrecio(total_repuestos));
            $('#resumen_servicios_ppto').text(formatearPrecio(total_servicios));
            $('#resumen_repuestos_ppto').text(formatearPrecio(total_repuestos));
            let total_general = parseFloat(total_servicios) + parseFloat(total_repuestos);
            $('#total_general_ppto').text(formatearPrecio(total_general));
            let total_final = total_general * 1.19;
            $('#total_final_ppto').text(formatearPrecio(total_final));
            let iva = total_final - total_general;
            $('#iva_ppto').text(formatearPrecio(iva));
        },
        error: function() {
            MyAlert('Error al cargar el detalle del presupuesto','Error');
        }
    });
    id_orden_servicio = id_orden_servicio == null ? 'n/a' : id_orden_servicio;
    $('#modal_num_presupuesto').text(num_presupuesto + ' | ' + vehiculo + ' | ' + kms + ' kms | Os: ' + id_orden_servicio);
    let htmlBotones = `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`;

    $('#modal-footer-presupuesto').html(htmlBotones);

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalPresupuesto'));
    modal.show();   
}

function verPDFOrdenServicio(id, descargar = false) {
    let url = `<?= base_url('/public/orden-servicio/DescargarPDF') ?>/${id}`;
    if (descargar) {
        url += '?download=1'; // Agregamos un parámetro para indicarlo
    }
    window.open(url, '_blank');
}

function CargaMecanicos(){
    // Obtenemos el id del mecánico asignado desde PHP (si existe)
    const idMecanicoAsignado = "<?= esc($orden_servicio['id_mecanico'] ?? '') ?>";    
    $.ajax({
        url: "<?= base_url('/public/config/CargaUsuariosMecanicos') ?>",
        method: "GET",
        dataType: "json",
        success: function(response) {
            if (response.status === 'ok') {              
                $('#id_mecanico').empty().append('<option value="">-- Seleccione Mecánico --</option>');
                
                $.each(response.mecanicos, function(i, item) {
                    const selected = (item.id_usuario == idMecanicoAsignado) ? 'selected' : '';
                    $('#id_mecanico').append(`
                        <option value="${item.id_usuario}" ${selected}>${item.nombre}</option>
                    `);
                });
            } else {
                /* MyAlert(response.message, 'error'); */
            }
        },
        error: function() {
            MyAlert('Error al cargar los mecánicos', 'error');
        }
    });
}

function enviarWhatsApp(telefono, mensaje) {
    // Copiar mensaje al portapapeles
    if (navigator.clipboard) {
        navigator.clipboard.writeText(mensaje).then(() => {
            console.log('Mensaje copiado al portapapeles');
        }).catch(err => {
            console.error('No se pudo copiar el mensaje: ', err);
        });
    }

    // Detectar dispositivo: móvil o escritorio
    const url = /Mobi|Android/i.test(navigator.userAgent)
        ? `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`
        : `https://web.whatsapp.com/send?phone=${telefono}&text=${encodeURIComponent(mensaje)}`;

    // Abrir WhatsApp
    window.open(url, '_blank');
}

</script>    