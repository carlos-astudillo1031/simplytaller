<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Servicio N° <?= $orden_servicio['id'] ?? '' ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        h2, h3 { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; }
        .section-title { background-color: #ddd; padding: 5px; margin-top: 20px; font-weight: bold; }
        .text-right { text-align: right; }
        .total-box { margin-top: 20px; float: right; width: 300px; }
        .total-box table { border: none; }
        .total-box td { border: none; padding: 4px 8px; }
    </style>
</head>
<!-- Para validar si puede ver precios -->
<?php
    $permisos_especiales = session()->get('permisos_especiales');     
    $permisoId = $permisos_especiales[0]['id'] ?? 0;   // 0 si no existe
    $permisoId = intval($permisoId);
?>
<body>   
    <table style="width: 100%; margin-bottom: 20px; border: none; border-collapse: collapse;">
        <tr>
            <td style="vertical-align: top; width: 40%; border: none;">
                <h2>Orden de Servicio N° <?= $orden_servicio['id'] ?? '' ?></h2>           
                <p><strong>Fecha:</strong> <?= $orden_servicio['fecha'] ?? date('d-m-Y') ?></p>
            </td>
            <td style="vertical-align: top; text-align: right; width: 60%; border: none;">                
                <span><img width="20%" src="data:image/jpeg;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/marambio/logo.jpeg')) ?>" alt=""></span><br>
                <span><strong><?= esc($taller['razon_social']) ?></strong></span><br>
                <span><?= esc($taller['direccion']) ?></span><br>
                <span><?= esc($taller['celular']) ?></span><br>                                
            </td>
        </tr>
    </table>


    <div class="section-title">Datos del Cliente</div>
    <table>
        <tr>
            <th>Nombre</th>
            <td><?= $orden_servicio['cliente_nombre'] ?? '' ?></td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td><?= $orden_servicio['cliente_telefono'] ?? '' ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= $orden_servicio['cliente_email'] ?? '' ?></td>
        </tr>
    </table>
    
    <!-- Para Bomba -->
    <? if($orden_servicio['bomba_codigo'] != null):?>
    <div class="section-title">Datos de la Bomba o Inyector</div> 
    <table>        
        <tr>
            <th>Codigo</th>
            <td><?= $orden_servicio['bomba_codigo'] ?? '' ?></td>
            <th>Marca</th>
            <td><?= $orden_servicio['bomba_marca'] ?? '' ?></td>
        </tr>
        <tr>
            <th>Modelo</th>
            <td colspan="3"><?= $orden_servicio['bomba_modelo'] ?? '' ?></td>            
        </tr>       
    </table>
    <? endif; ?>
    <!-- Para Vehiculo -->    
    <? if($orden_servicio['vehiculo_patente'] != null):?>
        <div class="section-title">Datos del Vehiculo</div> 
        <table>                
            <tr>
                <th>Patente</th>
                <td><?= $orden_servicio['vehiculo_patente'] ?? '' ?></td>
                <th>Marca</th>
                <td><?= $orden_servicio['vehiculo_marca'] ?? '' ?></td>
            </tr>
            <tr>
                <th>Modelo</th>
                <td><?= $orden_servicio['vehiculo_modelo'] ?? '' ?></td>
                <th>Kilómetros</th>
                <td><?= $orden_servicio['kms'] ?? '' ?></td>
            </tr>        
        </table>
    <? endif; ?>
    <? if($orden_servicio['servicios'] != null):?>    
    <div class="section-title">Servicios</div>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Cantidad</th>
                <th class="text-right">Precio Unitario Neto</th>
                <th>Total Neto</th>
            </tr>
        </thead>
        <tbody>            
            <?php $subtotal_servicios = 0; ?>
            <?php foreach ($orden_servicio['servicios'] as $serv): ?>            
                <?php
                    if ($permisoId !== 1) { 
                        $serv['precio'] = 0;
                      }
                ?>
                <?php $subtotal_servicios+= $serv['cantidad'] * $serv['precio'];?>         
                <tr>
                    <td><?= $serv['nombre'] ?></td>
                    <td><?= $serv['cantidad'] ?></td>
                    <td class="text-right">$<?= number_format($serv['precio'], 0, ',', '.') ?></td>         
                    <td class="text-right">$<?= number_format($serv['cantidad'] * $serv['precio'], 0, ',', '.') ?></td>           
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <? endif; ?>
    <? if($orden_servicio['repuestos'] != null):?>    
    <div class="section-title">Repuestos</div>
    <table>
        <thead>
            <tr>
                <th>Repuesto</th>
                <th>Cantidad</th>
                <th class="text-right">Precio Unitario Neto</th>
                <th class="text-right">Total Neto</th>
            </tr>
        </thead>
        <tbody>
            <?php $subtotal_repuestos = 0; ?>
            <?php foreach ($orden_servicio['repuestos'] as $rep): ?>
                <?php $subtotal_repuestos+= $rep['cantidad'] * $rep['precio']; ?>
                <?php
                    if ($permisoId !== 1) { 
                        $rep['precio'] = 0;
                      }
                ?>
                <tr>
                    <td><?= $rep['nombre']." (".$rep['codigo'].")" ?></td>
                    <td><?= $rep['cantidad'] ?></td>
                    <td class="text-right">$<?= number_format($rep['precio'], 0, ',', '.') ?></td>
                    <td class="text-right">$<?= number_format($rep['cantidad'] * $rep['precio'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <? endif; ?>
    <?php
        if($permisoId !== 1) {
            $orden_servicio['total'] = 0;
        }
    ?>                  
    <div class="total-box">
        <table>
            <? if($orden_servicio['servicios'] != null):?>    
            <tr>
                <td><strong>Subtotal Servicios:</strong></td>
                <td class="text-right">$<?= number_format($subtotal_servicios , 0, ',', '.') ?></td>
            </tr>
            <? endif; ?>
             <? if($orden_servicio['repuestos'] != null):?>  
            <tr>
                <td><strong>Subtotal Repuestos:</strong></td>
                <td class="text-right">$<?= number_format($subtotal_repuestos, 0, ',', '.') ?></td>
            </tr>
            <? endif; ?>
            <tr>
                <td><strong>Total Neto:</strong></td>
                <td class="text-right"><strong>$<?= number_format($orden_servicio['total'], 0, ',', '.') ?></strong></td>
            </tr>
            <? $total_final = $orden_servicio['total'] * 1.19;?>
            <? $iva = $total_final - $orden_servicio['total']; ?>
            <tr>
                <td><strong>Iva:</strong></td>
                <td class="text-right"><strong>$<?=number_format($iva,0,',','.')?></strong></td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>$<?=number_format($total_final,0,',','.')?></strong></td>
            </tr>
        </table>
    </div>

</body>

</html>
