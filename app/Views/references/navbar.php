<style>
    .layout-horizontal .header-top .logo img {
       height: 30px !important;
    }

    .layout-horizontal .main-navbar {
        background-color: #1A2E49 !important;
    }

    /* Fijar toda la cabecera (logo, avatar, navbar) */
#my-navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999;
}

.content-wrapper {
    padding-top: 130px; /* Ajusta según la altura real de tu header */
}


    
    
</style>
<div id="main" class="layout-horizontal">
            <header class="mb-2" id="my-navbar">
                <div class="header-top" style="background-color: #FFFFFF; padding: 8px 0;">
                    <div class="container">
                        <div class="logo">
                            <a href="index.html">
                                <img src="<?= base_url('public/assets/logo.png') ?>" style="height: 25px;">
                            </a>
                        </div>
                        <div class="header-top-right">

                            <div class="dropdown">
                                <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2" >
                                        <img src="https://cdn-icons-png.flaticon.com/512/3541/3541871.png" alt="Avatar">                                        
                                    </div>
                                    <div class="text">
                                        <h6 class="user-dropdown-name"><?=session()->get('nombre_usuario')?></h6>
                                        <p class="user-dropdown-status text-sm text-muted"><?=session()->get('cargo')?></p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                                 <!--  <li><a class="dropdown-item" href="#">Mis Datos</a></li>                                  
                                  <li><a class="dropdown-item" href="#">Cambiar Password</a></li>    -->
                                  <li><a class="dropdown-item" href="https://www.1031.cl/soporte" target="_blank"><i class="fas fa-hands-helping"></i> Solicitar Soporte</a></li>                                                                    
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="<?= base_url('public/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                                </ul>
                            </div>
                                                   
                        </div>
                    </div>
                </div>  
                <!-- <div class="header-top" style="background-color: #FFFFFF; padding: 10px 0;">
                    <div class="container d-flex justify-content-center align-items-center">
                        <div class="logo">
                            <a href="index.html">
                                <img src="<?= base_url('public/assets/logo.png') ?>" style="height: 30px;">
                            </a>
                        </div>
                    </div>
                </div>  -->

                <nav class="main-navbar bg-dark-blue d-none d-xl-block">
                    <div class="container" id="contenedor-menu">
                        
                    </div>
                </nav>
                <!-- Menú Móvil: visible solo en pantallas pequeñas -->                
                <nav class="main-navbar bg-dark-blue d-block d-xl-none">
                    <div class="container">
                        <!-- Botón hamburguesa -->
                        <button class="btn btn-secondary w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-expanded="false">
                            <i class="bi bi-list"></i> Menú
                        </button>

                        <!-- Menú colapsable -->
                        <div class="collapse" id="mobileMenu">
                            <ul class="list-unstyled mb-0">
                                <li><a href="<?=base_url('/public/agenda/')?>" class="d-block py-2 px-3 menu-link"><i class="fas fa-calendar"></i> Agenda</a></li>

                                <!-- Submenú Fichas -->
                                <li class="mb-1">
                                    <button class="btn btn-toggle w-100 text-start d-flex justify-content-between align-items-center py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#submenuFichas" aria-expanded="false">
                                        <span><i class="fas fa-folder-open"></i> Fichas</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="collapse" id="submenuFichas">
                                        <ul class="list-unstyled ps-4">
                                            <li><a href="<?=base_url('/public/config/clientes')?>" class="d-block py-1 menu-link">Clientes</a></li>
                                            <li><a href="<?=base_url('/public/config/vehiculos')?>" class="d-block py-1 menu-link">Vehículos</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li><a href="<?= base_url('/public/presupuesto/lista-presupuestos') ?>" class="d-block py-2 px-3 menu-link"><i class="fas fa-file-alt"></i> Presupuestos</a></li>
                                <li><a href="<?= base_url('/public/orden-servicio/lista-ordenes') ?>" class="d-block py-2 px-3 menu-link" ><i class="fas fa-wrench"></i> Ordenes de Servicio</a></li>
                                <li><a href="<?= base_url('/public/orden-servicio/pagos-pendientes') ?>" class="d-block py-2 px-3 menu-link"><i class="fas fa-file-invoice-dollar"></i> Pagos Pendientes</a></li>

                                <!-- Submenú Reportes -->
                                <li class="mb-1">
                                    <button class="btn btn-toggle w-100 text-start d-flex justify-content-between align-items-center py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#submenuReportes" aria-expanded="false">
                                        <span><i class="fas fa-chart-bar"></i> Reportes</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="collapse" id="submenuReportes">
                                        <ul class="list-unstyled ps-4">
                                            <li><a href="<?=base_url('/public/reportes/dashboard')?>" class="d-block py-1 menu-link">Resumen</a></li>
                                            <li><a href="<?=base_url('/public/reportes/transferencias')?>" class="d-block py-1 menu-link">Transferencias</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <!-- Submenú Configuración -->
                                <li class="mb-1">
                                    <button class="btn btn-toggle w-100 text-start d-flex justify-content-between align-items-center py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#submenuConfig" aria-expanded="false">
                                        <span><i class="fas fa-cog"></i> Configuración</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="collapse" id="submenuConfig">
                                        <ul class="list-unstyled ps-4">
                                            <li><a href="<?=base_url('/public/config/repuestos')?>" class="d-block py-1 menu-link">Repuestos</a></li>
                                            <li><a href="<?=base_url('/public/config/servicios')?>" class="d-block py-1 menu-link">Servicios</a></li>
                                            <li><a href="<?=base_url('/public/config/marcas')?>" class="d-block py-1 menu-link">Marcas</a></li>
                                            <li><a href="<?=base_url('/public/config/modelos')?>" class="d-block py-1 menu-link">Modelos</a></li>
                                            <li><a href="<?=base_url('/public/config/usuarios')?>" class="d-block py-1 menu-link">Usuarios</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

            </header>
<script>

$(document).ready(function() {
    CargaMenuUsuario();
});     

function CargaMenuUsuario() {
    $.ajax({
        dataType: "json",
        type: "POST",
        url: '<?= base_url() ?>public/config/GetMenuUsuario',
        error: function(jqXHR, textStatus, errorThrown){
            MyAlert('Imposible cargar el menú del usuario', 'error');
        },
        success: function(data) {
            // Generar ambos menús
            GenerarMenuEscritorio(data);
            GenerarMenuMovil(data);
        }
    });
}

function GenerarMenuEscritorio(data) {
    let html = `<ul>`;
    let padres = data.filter(item => item.id_padre === null).sort((a,b) => a.orden - b.orden);
    let hijos = data.filter(item => item.id_padre !== null);

    $.each(padres, function(i, padre) {
        let hijosDePadre = hijos.filter(h => h.id_padre === padre.id).sort((a,b) => a.orden - b.orden);

        if (hijosDePadre.length > 0) {
            html += `
                <li class="menu-item has-sub">
                    <a href="#" class="menu-link">
                        <span><i class="fas ${padre.icono}"></i> ${padre.nombre}</span>
                    </a>
                    <div class="submenu">
                        <div class="submenu-group-wrapper">
                            <ul class="submenu-group">
            `;
            $.each(hijosDePadre, function(j, hijo) {
                html += `
                    <li class="submenu-item">
                        <a href="<?= base_url() ?>${hijo.ruta}" class="submenu-link">${hijo.nombre}</a>
                    </li>
                `;
            });
            html += `
                            </ul>
                        </div>
                    </div>
                </li>
            `;
        } else {
            html += `
                <li class="menu-item">
                    <a href="<?= base_url() ?>${padre.ruta}" class="menu-link">
                        <span><i class="fas ${padre.icono}"></i> ${padre.nombre}</span>
                    </a>
                </li>
            `;
        }
    });

    html += `</ul>`;
    $("#contenedor-menu").html(html);
}

function GenerarMenuMovil(data) {
    let padres = data.filter(item => item.id_padre === null).sort((a,b) => a.orden - b.orden);
    let hijos = data.filter(item => item.id_padre !== null);

    let html = `<ul class="list-unstyled mb-0">`;

    $.each(padres, function(i, padre) {
        let hijosDePadre = hijos.filter(h => h.id_padre === padre.id).sort((a,b) => a.orden - b.orden);

        if (hijosDePadre.length > 0) {
            // Submenú colapsable
            html += `
                <li class="mb-1">
                    <button class="btn btn-toggle w-100 text-start d-flex justify-content-between align-items-center py-2 px-3" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#submenuMovil_${padre.id}" 
                            aria-expanded="false">
                        <span><i class="fas ${padre.icono}"></i> ${padre.nombre}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="collapse" id="submenuMovil_${padre.id}">
                        <ul class="list-unstyled ps-4">
            `;
            $.each(hijosDePadre, function(j, hijo) {
                html += `
                    <li><a href="<?= base_url() ?>${hijo.ruta}" class="d-block py-1 menu-link">${hijo.nombre}</a></li>
                `;
            });
            html += `
                        </ul>
                    </div>
                </li>
            `;
        } else {
            // Elemento sin hijos
            html += `
                <li>
                    <a href="<?= base_url() ?>${padre.ruta}" class="d-block py-2 px-3 menu-link">
                        <i class="fas ${padre.icono}"></i> ${padre.nombre}
                    </a>
                </li>
            `;
        }
    });

    html += `</ul>`;

    // Reemplazamos el contenido dinámicamente
    $("#mobileMenu").html(html);
}

/* function CargaMenuUsuario() {
    $.ajax({
        dataType: "json",
        type: "POST",
        url: '<?= base_url() ?>public/config/GetMenuUsuario',
        error: function(jqXHR, textStatus, errorThrown){
            MyAlert('Imposible cargar el menú del usuario', 'error');
        },
        success: function(data) {
            let html = `<ul>`;

            let padres = data.filter(item => item.id_padre === null)
                             .sort((a,b) => a.orden - b.orden);
            let hijos = data.filter(item => item.id_padre !== null);

            $.each(padres, function(i, padre) {
                let hijosDePadre = hijos.filter(h => h.id_padre === padre.id)
                                        .sort((a,b) => a.orden - b.orden);

                if (hijosDePadre.length > 0) {
                    html += `
                        <li class="menu-item has-sub">
                            <a href="#" class="menu-link">
                                <span><i class="fas ${padre.icono}"></i> ${padre.nombre}</span>
                            </a>
                            <div class="submenu">
                                <div class="submenu-group-wrapper">
                                    <ul class="submenu-group">
                    `;

                    $.each(hijosDePadre, function(j, hijo) {
                        html += `
                            <li class="submenu-item">
                                <a href="<?= base_url() ?>${hijo.ruta}" class="submenu-link">${hijo.nombre}</a>
                            </li>
                        `;
                    });

                    html += `
                                    </ul>
                                </div>
                            </div>
                        </li>
                    `;
                } else {
                    html += `
                        <li class="menu-item">
                            <a href="<?= base_url() ?>${padre.ruta}" class="menu-link">
                                <span><i class="fas ${padre.icono}"></i> ${padre.nombre}</span>
                            </a>
                        </li>
                    `;
                }
            });

            html += `</ul>`;
            $("#contenedor-menu").html(html);
        }
    });
} */


</script>