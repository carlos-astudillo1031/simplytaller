<?php echo view("references/header"); ?>  
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    @media (max-width: 768px) {
    /* Forzar que el input deje de tener margen automático */
    .card-header.d-flex input#rango-fechas {
        margin-left: 0 !important; /* anula ms-auto */
        width: 100%;               /* opcional: ocupa todo el ancho disponible */
        margin-top: -25px;           /* separación vertical mínima */
    }

    /* Asegurar que el título no se centre */
    .card-header.d-flex .page-heading {
        width: 100%;
    }

    /* Permitir que los elementos se apilen */
    .card-header.d-flex {
        flex-wrap: wrap;
        gap: 5px;
    }
}
</style>
    <?php echo view("references/navbar"); ?>    
        <div class="content-wrapper container"> 
            <section class="row">        
            <div class="col-12 col-lg-3">            
            </div>            
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="page-heading d-flex align-items-center">
                        <h4 class="mb-0">Historial de Transferencias</h4>
                    </div>
                    
                    <!-- Input más pequeño y pegado a la derecha -->
                    <?php $fecha_rango = date('d/m/Y', strtotime('-30 days'))." - ".date('d/m/Y', strtotime('+1 day')); ?>
                    <input type="text" 
                        class="form-control ms-auto" 
                        name="rango-fechas" 
                        id="rango-fechas" 
                        placeholder="Rango de Fechas" 
                        value="<?=$fecha_rango;?>" 
                        style="width: 220px;" />
                </div>

                    <div class="card-body">
                       <div class="table-responsive">
                        <table id="tabla_transferencias" class="table table-bordered table-striped">
                                                    <thead class="bg-light">
                                                        <tr>                                                            
                                                            <th width="15%" scope="col">Fecha</th>    
                                                            <th width="15%" scope="col">Cliente</th>
                                                            <th width="20%" scope="col">OS</th>         
                                                            <th width="15%" scope="col">N° Operacion</th>                                                   
                                                            <th width="15%" scope="col">Total Neto</th>                                                                                                                                      
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
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script>
    $(document).ready(function() {
        CargaTransferencias();


        // Establecer el valor inicial (rango de fechas)        
        var today = moment();
        var thirtyDaysAgo = moment().subtract(30, 'days');
        $('input[name="rango-fechas"]').daterangepicker({

        locale: {

            format: 'DD/MM/YYYY',

            applyLabel: 'Aplicar',

            cancelLabel: 'Cancelar',

            daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],

            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],

            firstDay: 1

        },

        startDate: thirtyDaysAgo,  // Fecha de inicio: 30 días antes

        endDate: today,            // Fecha de fin: hoy

        autoUpdateInput: false     // Evita que se actualice automáticamente al seleccionar el rango

        }, function(start, end, label) {

            // Este callback se ejecuta al aplicar también

            $('input[name="rango-fechas"]').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

            CargaTransferencias();

        });
    });

function CargaTransferencias(){
    var fechas = obtenerRangoFechas();        
    var fechaInicioMoment = moment(fechas.fechaInicio, 'DD/MM/YYYY');
    var fechaFinMoment = moment(fechas.fechaFin, 'DD/MM/YYYY');       
    $.ajax({
        data:{'fecha_inicio':fechaInicioMoment.format('YYYY-MM-DD'),
            'fecha_fin':fechaFinMoment.format('YYYY-MM-DD')
        },
        dataType: "json",
        type: "POST",        
        url: "<?= base_url('/public/reportes/GetTransferencias') ?>", // Cambia por tu endpoint real
        error: function() {
            MyAlert('Imposible cargar la lista de transferencias', 'error');
        },
        success: function(data) {
            console.log(data);
            $('#tabla_transferencias').DataTable().destroy();
            $("#tabla_transferencias tbody").empty();
            $.each(data, function(i, item) {               
                $("#tabla_transferencias tbody").append(
                    '<tr>' +
                        `<td>${item.fecha_pago}</td>` +
                        `<td>${item.nombre_cliente}</td>` +
                        `<td><a href="<?= base_url('/public/orden-servicio/') ?>${item.id_orden_servicio}" target="_blank"><span class="badge bg-success">${item.id_orden_servicio}</span></a></td>` +
                        `<td>${item.numero_documento}</td>` +
                        `<td>${formatearPrecio(item.monto_pago)}</td>` +
                    '</tr>'
                );
            });
            $('#tabla_transferencias').DataTable();
        }
    })
}


    function obtenerRangoFechas() {

        // Obtener el valor del input (el rango de fechas)        

        var rangoFechas = $('input[name="rango-fechas"]').val();

        // Separar el rango de fechas en inicio y fin

        var fechas = rangoFechas.split(' - ');

        var fechaInicio = fechas[0];  // Fecha de inicio

        var fechaFin = fechas[1];     // Fecha de fin



        // Retornar las fechas

        return {

            fechaInicio: fechaInicio,

            fechaFin: fechaFin

        };

    }

</script>