<?php echo view("references/header"); ?>  
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php echo view("references/navbar"); ?>    

<style>
.kpi-card {
  background-color: #fff;
  border: 1px solid #e5e5e5;
  border-radius: 0.5rem;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.kpi-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transform: translateY(-2px);
}


@media (max-width: 768px) {

        .card {
            margin: 20px 15px !important;
        }
}

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

<div class="content-wrapper container"> 
    <section class="row">        
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="page-heading d-flex align-items-center">
                        <h4 class="mb-0">Resumen de Ventas</h4>
                    </div>
                    
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
                    <div class="card-body">

                        <!-- KPI Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-3">
                                <div class="kpi-card d-flex align-items-center">
                                <i class="fas fa-file-invoice-dollar fa-2x me-3" style="color:#0dcaf0;"></i>
                                <div>
                                    <div class="small text-muted">Presupuestos</div>
                                    <div class="h5 mb-0" id="kpi-presupuestos">0</div>
                                </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="kpi-card d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3" style="color:#198754;"></i>
                                <div>
                                    <div class="small text-muted">Aprobados</div>
                                    <div class="h5 mb-0" id="kpi-aprobados">0</div>
                                </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="kpi-card d-flex align-items-center">
                                <i class="fas fa-wrench fa-2x me-3" style="color:#fd7e14;"></i>
                                <div>
                                    <div class="small text-muted">Órdenes terminadas</div>
                                    <div class="h5 mb-0" id="kpi-ordenes">0</div>
                                </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="kpi-card d-flex align-items-center">
                                <i class="fas fa-coins fa-2x me-3" style="color:#6f42c1;"></i>
                                <div>
                                    <div class="small text-muted">Ingresos</div>
                                    <div class="h5 mb-0" id="kpi-ingresos">$0</div>
                                </div>
                                </div>
                            </div>
                            </div>




                        <!-- Chart Evolución: barra ancho completo -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="bg-white p-3 rounded chart-card">
                                    <canvas id="chartEvolucion" style="height:250px; width:100%"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficas de barra Top Unidades -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded chart-card">
                                    <canvas id="chartTopServicios" style="height:250px; width:100%"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded chart-card">
                                    <canvas id="chartTopRepuestos" style="height:250px; width:100%"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficas de barra Top Ingresos -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded chart-card">
                                    <canvas id="chartTopServiciosIngresos" style="height:250px; width:100%"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded chart-card">
                                    <canvas id="chartTopRepuestosIngresos" style="height:250px; width:100%"></canvas>
                                </div>
                            </div>
                        </div>

                       

                    </div>

                    
                </div>
            </div>
        </div>
    </section>
</div>

<?php echo view("references/footer"); ?>

<!-- JS -->
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Obteniendo los datos para los graficos
    
    $(document).ready(function() {
        CargaKpis();
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

            CargaKpis();

        });
    });


    function CargaKpis() {
        //Estableciendo el rango de fechas
        var fechas = obtenerRangoFechas();        
        var fechaInicioMoment = moment(fechas.fechaInicio, 'DD/MM/YYYY');
        var fechaFinMoment = moment(fechas.fechaFin, 'DD/MM/YYYY');       

        //Obteniendo datos via ajax
        $.ajax({
            data:{'fecha_inicio':fechaInicioMoment.format('YYYY-MM-DD'),
            'fecha_fin':fechaFinMoment.format('YYYY-MM-DD')
            },            
            url: "<?= base_url('/public/reportes/Get_kpis') ?>",
            type: 'POST',
            dataType: 'json',
            success: function(data) {                
                // Aplicamos rellenarTop antes de llenar demo
                const topServiciosIngresosRellenados = rellenarTop(data.top3Ingresos.topServiciosIngresos);
                const topRepuestosIngresosRellenados = rellenarTop(data.top3Ingresos.topRepuestosIngresos);
                const topServiciosRellenados = rellenarTop(data.top3.topServicios);
                const topRepuestosRellenados = rellenarTop(data.top3.topRepuestos);

                // Ahora construimos demo
                const demo = {
                    kpis: {
                        presupuestos: data.kpis.presupuestos,
                        aprobados: data.kpis.aprobados,
                        ordenes: data.kpis.ordenes,
                        ingresos: formatearPrecio(data.kpis.ingresos)
                    },
                    evolucion: {
                        fechas: data.evolucion.fechas,
                        presupuestos: data.evolucion.presupuestos,
                        ordenes: data.evolucion.ordenes
                    },
                    topServicios: {
                        nombres: topServiciosRellenados.map(s => s.nombre),
                        totales: topServiciosRellenados.map(s => s.total)
                    },
                    topRepuestos: {
                        nombres: topRepuestosRellenados.map(r => r.nombre),
                        totales: topRepuestosRellenados.map(r => r.total)
                    },
                    topServiciosIngresos: {
                        nombres: topServiciosIngresosRellenados.map(s => s.nombre),
                        totales: topServiciosIngresosRellenados.map(s => s.total)
                    },
                    topRepuestosIngresos: {
                        nombres: topRepuestosIngresosRellenados.map(r => r.nombre),
                        totales: topRepuestosIngresosRellenados.map(r => r.total)
                    }
                };

               /*  console.log(demo); */

                // Llenar KPIs
                document.getElementById('kpi-presupuestos').textContent = demo.kpis.presupuestos;
                document.getElementById('kpi-aprobados').textContent = demo.kpis.aprobados;
                document.getElementById('kpi-ordenes').textContent = demo.kpis.ordenes;
                document.getElementById('kpi-ingresos').textContent = demo.kpis.ingresos.toLocaleString('es-CL');

                

                // Chart Evolución
                new Chart(document.getElementById('chartEvolucion'), {
                    type: 'line',
                    data: {
                        labels: demo.evolucion.fechas, // ahora usamos fechas diarias
                        datasets: [
                            {
                                label: 'Presupuestos',
                                data: demo.evolucion.presupuestos,
                                borderColor: '#1f77b4',
                                backgroundColor: 'rgba(31, 119, 180, 0.2)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            },
                            {
                                label: 'Órdenes',
                                data: demo.evolucion.ordenes,
                                borderColor: '#ff7f0e',
                                backgroundColor: 'rgba(255, 127, 14, 0.2)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Evolución diaria de Presupuestos y Órdenes (últimos 30 días)',
                                font: { size: 16 }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.parsed.y}`;
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 10 // evita que las fechas se amontonen
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad'
                                }
                            }
                        }
                    }
                });


                // Top Servicios
                new Chart(document.getElementById('chartTopServicios'), {
                    type: 'bar',
                    data: {
                        labels: demo.topServicios.nombres,
                        datasets: [{
                            label: 'Servicios más ejecutados',
                            data: demo.topServicios.totales,
                            backgroundColor: demo.topServicios.totales.map((v, i) =>
                                v === 0 ? '#BDC3C7' : ['#2ECC71','#3498DB','#F1C40F','#9B59B6','#1ABC9C'][i]
                            )
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Top 5 Servicios más vendidos' }
                        },
                        scales: { x: { beginAtZero: true } }
                    }
                });

                // Top Repuestos
                new Chart(document.getElementById('chartTopRepuestos'), {
                    type: 'bar',
                    data: {
                        labels: demo.topRepuestos.nombres,
                        datasets: [{
                            label: 'Repuestos más usados',
                            data: demo.topRepuestos.totales,
                            backgroundColor: demo.topRepuestos.totales.map((v, i) =>
                                v === 0 ? '#BDC3C7' : ['#E74C3C','#9B59B6','#34495E','#F39C12','#16A085'][i]
                            )
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Top 5 Repuestos más vendidos' }
                        },
                        scales: { x: { beginAtZero: true } }
                    }
                });

                // Top Servicios Ingresos
                new Chart(document.getElementById('chartTopServiciosIngresos'), {
                    type: 'bar',
                    data: {
                        labels: demo.topServiciosIngresos.nombres,
                        datasets: [{
                            label: 'Servicios más ejecutados',
                            data: demo.topServiciosIngresos.totales,
                            backgroundColor: demo.topServiciosIngresos.totales.map((v, i) =>
                                v === 0 ? '#BDC3C7' : ['#2ECC71','#3498DB','#F1C40F','#9B59B6','#1ABC9C'][i]
                            )
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Top 5 Servicios más valiosos' }
                        },
                        scales: { x: { beginAtZero: true } }
                    }
                });

                // Top Repuestos Ingresos
                new Chart(document.getElementById('chartTopRepuestosIngresos'), {
                    type: 'bar',
                    data: {
                        labels: demo.topRepuestosIngresos.nombres,
                        datasets: [{
                            label: 'Repuestos más usados',
                            data: demo.topRepuestosIngresos.totales,
                            backgroundColor: demo.topRepuestosIngresos.totales.map((v, i) =>
                                v === 0 ? '#BDC3C7' : ['#E74C3C','#9B59B6','#34495E','#F39C12','#16A085'][i]
                            )
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Top 5 Repuestos más valiosos' }
                        },
                        scales: { x: { beginAtZero: true } }
                    }
                });

                

            }                
        });

       
    }

    // Función para rellenar hasta 5 elementos
    function rellenarTop(topArray) {
            const max = 5;
            const rellenados = [...topArray]; // copiamos el array
            while (rellenados.length < max) {
                        rellenados.push({ nombre_servicio: '-', total: 0 }); // para servicios
                        // si es repuestos, usar nombre_repuesto
            }
            return rellenados;
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


