<?php echo view("references/header"); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<style>
    @media (max-width: 768px) {
        #tabla_ordenes .btn {
            display: block;
            width: 100%;
            margin: 4px 0;
        }

        .card {
            margin: 20px 15px !important;
        }
    }
</style>
    <?php echo view("references/navbar"); ?>    
        <div class="content-wrapper container" style="padding-right:40px"> 
            <section class="row">        
            <div class="col-12 col-lg-3">            
            </div>            
            <div class="card">
                <div class="card-header d-flex align-items-center">
                     <div class="page-heading d-flex align-items-center">
                      <!--   <a href="#" class="burger-btn d-flex align-items-center me-3">
                            <i class="bi bi-justify fs-3"></i>
                        </a> -->
                        <h4 style="margin-bottom:-32px">Pagos Pendientes</h4>
                    </div>
                    <!-- Botón al extremo derecho -->
                    <!-- <button onclick="CrearCliente()" class="btn btn-primary ms-auto">
                        <i class="fas fa-plus"></i> Nuevo
                    </button> -->
                    </div>                              
                    <div class="card-body">
                       <div class="table-responsive">
                            <table id="tabla_ordenes" class="table table-bordered table-striped">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th width="5%" scope="col">N°</th>
                                                                <th width="15%" scope="col">Fecha</th>    
                                                                <th width="15%" scope="col">Cliente</th>
                                                                <th width="20%" scope="col">Vehiculo</th>
                                                                <th width="15%" scope="col">Estado</th>
                                                                <th width="15%" scope="col">Total Neto</th>                                                                          
                                                                <th width="15%" scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                                        
                                                        </tbody>
                            </table>
                       </div> 
                    </div>
                </div>
             </div> 
           </section>
        </div> <!--Cierra el pagecontent-->
<?php echo view("references/footer"); ?>

<!-- Modal Detalles Venta -->

<div class="modal fade" id="modal_pago" tabindex="-1" aria-labelledby="modal_pagoLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="modal_pagoLabel">Orden N°<span id="num_os"></span></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            </div>

            <div class="modal-body">

                <table id="tabla_detalle_pago" class="table table-bordered table-striped">

                    <thead class="bg-light">
                        
                    </thead>

                    <tbody >                       
                        
                    </tbody>               

                </table>                         

                <!-- interfaz de pago -->
                 <div>

                    <div class="mb-4">

                        <label class="block text-gray-700 mb-2 fw-bold">Método de pago:</label>

                        <div class="space-y-2">

                            <label class="flex items-center space-x-2">
                                <input type="radio" name="metodo_pago" value="efectivo" class="accent-blue-500" checked>
                                <span>Efectivo</span>
                            </label>

                            <label class="flex items-center space-x-2">
                                <input type="radio" name="metodo_pago" value="tarjeta" class="accent-blue-500">
                                <span>Tarjeta de débito/crédito</span>
                            </label>

                            <label class="flex items-center space-x-2">
                                <input type="radio" name="metodo_pago" value="transferencia" class="accent-blue-500">
                                <span>Transferencia bancaria</span>
                            </label>

                        </div>

                        <!-- Campos comunes para todos los pagos -->
                        <div id="dte_info" class="mt-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold">Tipo DTE</td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_dte" id="boleta" value="2" checked>
                                                <label class="form-check-label" for="boleta">Boleta</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_dte" id="factura" value="1">
                                                <label class="form-check-label" for="factura">Factura</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Número DTE</td>
                                        <td>
                                            <input type="text" id="numero_dte" class="form-control" placeholder="Ingrese el número de DTE">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="opciones_pago" class="space-y-4">
                            <br>

                            <div id="opciones-efectivo" class="opciones-metodo">
                                <table class="table table-bordered">
                                    <tbody>                                        
                                        <tr>
                                            <td class="bg-light fw-bold">Efectivo</td>
                                            <td>
                                                <input type="number" id="cuanto_paga" class="form-control" placeholder="¿Con cuánto paga?" onkeyup="calcularVuelto()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light fw-bold">Vuelto</td>
                                            <td>
                                                <input type="text" id="vuelto" class="form-control" value="$0" disabled>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="opciones-tarjeta" class="opciones-metodo d-none">
                                <table class="table table-bordered">
                                    <tbody>                                        
                                        <tr>
                                            <td class="bg-light fw-bold">Tipo</td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tipo_tarjeta" id="debito" value="1" checked>
                                                    <label class="form-check-label" for="debito">Débito</label>
                                                </div>      
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tipo_tarjeta" id="credito" value="2">
                                                    <label class="form-check-label" for="credito">Crédito</label>
                                                </div>                                                                                     
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light fw-bold">Número de voucher</td>
                                            <td>
                                                <input type="text" id="numero_documento" class="form-control" placeholder="Ingrese el número de voucher">
                                            </td>
                                        </tr>                                        
                                    </tbody>
                                </table>
                            </div>

                            <div id="opciones-transferencia" class="opciones-metodo d-none">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light fw-bold">Número de operación</td>
                                            <td>
                                                <input type="text" id="numero_operacion" class="form-control" placeholder="Ingrese el número de operación">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- fin interfaz de pago -->

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                <button type="button" class="btn btn-primary" onclick="PagarOS()">Pagar <i class="fas fa-check"></i></button>                           

            </div>            

        </div>

    </div>

</div>

<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
    CargaOrdenes();
});



function CargaOrdenes() {    
    $.ajax({
        dataType: "json",
        url: "<?= base_url('/public/orden-servicio/GetOrdenesPorPagar') ?>", // Cambia por tu endpoint real
        error: function() {
            MyAlert('Imposible cargar la lista de ordenes', 'error');
        },
        success: function(data) {
            console.log(data);
            $('#tabla_ordenes').DataTable().destroy();
            $("#tabla_ordenes tbody").empty();

            $.each(data, function(i, item) {
                let vehiculo;         
                let es_vehiculo;       
                console.log('patente: ' + item.patente);
                if(item.patente==''){           
                    console.log('sin patente');         
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.codigo + ')';
                    es_vehiculo = 0;
                }else{
                    console.log('con patente');
                    vehiculo =  item.marca + '-' + item.modelo + ' (' + item.patente + ')';
                    es_vehiculo = 1;
                }

                let email_cliente = item.email_cliente;
                if(email_cliente == null){
                    email_cliente = '---';
                }
                
                let estado = TraduceEstados(item.estado);
                $("#tabla_ordenes tbody").append(
                    '<tr>' +
                        `<td><span class="badge bg-success">${item.num_os}</span></td>` +
                        `<td>${item.fecha_creacion}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td>${vehiculo}</td>` +
                        `<td><span class="badge bg-${estado.color}"><span>${estado.texto}</span></span></td>` +
                        `<td>${formatearPrecio(item.total)}</td>` +
                        `<td class="text-center">` +
                            `<a href="<?= base_url('/public/orden-servicio/') ?>${item.num_os}" 
                                class="btn btn-primary btn-sm" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" onclick="VerPago(${item.num_os})"
                                class="btn btn-success btn-sm" title="Pagar">
                                <i class="fas fa-dollar-sign"></i>
                            </a>
                            ` +                            
                        '</td>' +
                    '</tr>'
                );

            });

           $('#tabla_ordenes').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                },
                "ordering": false
            });

        }
    });
}

function VerPago(num_os) {
    $.ajax({
        dataType: "json",
        data: {
            num_os: num_os
        },
        type: "POST",                
        url: "<?= base_url('/public/orden-servicio/GetDetallePago') ?>",
        error: function() {
            MyAlert('Imposible cargar la información de la orden de servicio', 'error');
        },
        success: function(data) {
            let neto = parseInt(data.total_repuestos) + parseInt(data.total_servicios);
            let total_final =  neto * 1.19;
            let iva = total_final - neto;
            $('#tabla_detalle_pago tbody').empty();
            $('#tabla_detalle_pago tbody').append(
                    '<tr>' +
                        `<td><b>Subtotal Repuestos:</b></td>` +                        
                        `<td>${formatearPrecio(data.total_repuestos)}</td>` +
                    '</tr>' +
                    '<tr>' +
                        `<td><b>Subtotal Servicios:</b></td>` +                        
                        `<td>${formatearPrecio(data.total_servicios)}</td>` +
                    '</tr>' +
                    '<tr>' +
                        `<td><b>Total Neto:</b></td>` +                        
                        `<td>${formatearPrecio(neto)}</td>` +
                    '</tr>'  +
                    '<tr>' +
                        `<td><b>Iva:</b></td>` +                        
                        `<td>${formatearPrecio(iva)}</td>` +
                    '</tr>' +
                    '<tr class ="bg-dark">' +
                        `<td class="text-white"><b>Total a Pagar:</b></td>` +                        
                        `<td class="text-white"><input type="text" class="form-control" id="a_pagar" disabled value="${formatearPrecio(total_final)}"></td>` +
                    '</tr>'
            )
            $('#modal_pago').modal('show');
            $('#num_os').text(num_os);
        }    
    })
}

function PagarOS() {
    //Seteo los campos

    let num_os = $('#num_os').text();
    let metodo_pago = $('input[name="metodo_pago"]:checked').val();
    let monto_pago = revertirFormatoMiles($('#cuanto_paga').val());
    let a_pagar = revertirFormatoMiles($('#a_pagar').val());
    let tipo_tarjeta = $('input[name="tipo_tarjeta"]:checked').val();
    let numero_documento = $('#numero_documento').val();
    let numero_operacion = $('#numero_operacion').val();
    let tipo_dte = $('input[name="tipo_dte"]:checked').val();
    let numero_dte = $('#numero_dte').val();

        
    //Asignar valores a variables

    if (metodo_pago == 'efectivo') {

            numero_documento = null;

            numero_operacion = null;

    }else if (metodo_pago == 'tarjeta') {

            numero_documento = $('#numero_documento').val();

            numero_operacion = null;

    }else if (metodo_pago == 'transferencia') {

            numero_documento = null;

            numero_operacion = $('#numero_operacion').val();

    }else if (metodo_pago == 'Credito Interno') {

            numero_documento = null;

            numero_operacion = null;

    }



    //Validar datos básicos segun tipo de pago

    if (metodo_pago == 'efectivo') {

            if (a_pagar <= 0 || monto_pago < a_pagar) {                

                alert('El pago debe ser mayor a 0 o cubrir el total');
                $('#cuanto_paga').focus();

                return;

            }

    }else if (metodo_pago == 'tarjeta') {           

            if (numero_documento == '' || numero_documento == null) {

                alert('El numero de documento no puede estar vacio');

                return;

            }    

    }else if (metodo_pago == 'transferencia') {           

            if (numero_operacion == '' || numero_operacion == null) {

                alert('El numero de operacion no puede estar vacio');

                return;

            }

    }

    $.ajax({

            url: '<?php echo base_url('public/orden-servicio/PagarOS'); ?>',

            type: 'POST',           

            dataType: 'json',

            data: {

                id_orden_servicio: num_os,

                metodo_pago: metodo_pago,

                a_pagar: a_pagar,

                tipo_tarjeta: tipo_tarjeta,

                numero_documento: numero_documento,

                numero_operacion: numero_operacion,

                tipo_dte: tipo_dte,

                numero_dte: numero_dte

            },

            error:function(jqXHR, textStatus, errorThrown){

                MyAlert('Imposible pagar la Orden de Servicio','error');

            },

            success: function(data) {

                if (data.status == 'error') {

                    MyAlert(data.msg,'error');

                    return;

                }

                MyAlert('Orden pagada con exito','success');

                CargaOrdenes(); 

                $('#modal_pago').modal('hide');                

            }    

        });

}  


//Controla opciones de pago del modal

     $('input[name="metodo_pago"]').on('change', function () {

        const metodo = $(this).val();



        // Oculta todos los bloques

        $('.opciones-metodo').addClass('d-none');



        // Muestra el que corresponde según el valor

        if (metodo === 'efectivo') {

            $('#opciones-efectivo').removeClass('d-none');

        } else if (metodo === 'tarjeta') {

            $('#opciones-tarjeta').removeClass('d-none');

        } else if (metodo === 'transferencia') {

            $('#opciones-transferencia').removeClass('d-none');

        }    

    });

function calcularVuelto() {


        const montoTotal = parseInt($('#a_pagar').val().replace(/\./g, '').replace('$', ''));
        console.log(montoTotal);

        const pagoEfectivo = parseInt($('#cuanto_paga').val());



        if (!isNaN(pagoEfectivo) && pagoEfectivo >= montoTotal) {

            const vuelto = pagoEfectivo - montoTotal;

            $('#vuelto').val(vuelto.toLocaleString('es-CL'));

        }

}



</script>