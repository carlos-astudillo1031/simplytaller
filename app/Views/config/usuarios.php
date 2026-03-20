<?php echo view("references/header"); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />    
<link rel="stylesheet" href="/public/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"  href="/public/assets/compiled/css/table-datatable-jquery.css">
<?php echo view("references/navbar"); ?>    

<style>
     @media (max-width: 768px) {
        #tabla_usuarios .btn {
            display: block;
            width: 100%;
            margin: 4px 0;
        }

        .card {
            margin: 20px 15px !important;
        }
    }
</style>

<div class="content-wrapper container" style="padding-right:40px"> 
        <section class="row">        
        <div class="col-12 col-lg-3">            
        </div>            
        <div class="card">
            <div class="card-header d-flex align-items-center">
                 <div class="page-heading d-flex align-items-center">
                    <a href="#" class="burger-btn d-flex align-items-center me-3">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    <h4 style="margin-bottom:-32px">Usuarios</h4>
                </div>
                <!-- Botón al extremo derecho -->
                <button onclick="CrearUsuario()" class="btn btn-primary ms-auto">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
                </div>                              
                <div class="card-body">
                    <table id="tabla_usuarios" class="table table-bordered table-striped">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th width="20%" scope="col">Nombre</th>
                                                        <th width="20%" scope="col">Tipo Usuario</th>   
                                                        <th width="15%" scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                          
                                                </tbody>
                    </table>
                </div>
            </div>
         </div> 
       </section>
    </div> <!--Cierra el pagecontent-->
       <?php echo view("references/footer"); ?>

<!-- Este Modal permite agregar y editar registros -->
<div class="modal fade text-left show" id="crear-editar-registro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-modal="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel1">Registro de Usuario</h5>
                                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="cuerpo-modal" class="row form-group">                                               
                                         <form class="form form-horizontal">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="contact-info-horizontal">Tipo de Usuario</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-control" name="tipo_usuario" id="tipo_usuario">
                                                            <option value="" disabled selected>--Seleccionar--</option>
                                                            <option value="1">Gerente</option>
                                                            <option value="2">Administrativo</option>
                                                            <option value="3">Mecanico</option>
                                                        </select>
                                                    </div>       
                                                    <div class="col-md-4">
                                                        <label for="first-name-horizontal">Nombre</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" placeholder="Ej. Juan Perez">
                                                    </div>
                                                    <div class="col-md-4 rut-label">
                                                        <label for="email-horizontal">Rut</label>
                                                    </div>
                                                    <div class="col-md-8 form-group rut-input">
                                                        <input type="text" class="form-control" onblur="validaRut(this)" name="rut_usuario" id="rut_usuario" placeholder="Ej. 15332678-9">
                                                        <span id="error-rut" class="text-danger d-none">El rut no es valido</span>
                                                    </div>
                                                    <div class="col-md-4 pass-label">
                                                        <label for="email-horizontal">Password</label>
                                                    </div>
                                                    <div class="col-md-8 form-group pass-input">
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </form>                        
                                    </div>    
                                </div>
                                <div class="modal-footer" id="modal-footer-edit">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="Guardar()" id="confirmEnvioButton">Guardar</button>
                                  </div>
                            </div>
                        </div>                        
</div> <!-- //Fin de modal -->           
<!-- Modal Permisos -->
<div class="modal fade" id="modalPermisos" tabindex="-1" aria-labelledby="modalPermisosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalPermisosLabel">Permisos para <span id="nombreUsuario"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="tabsPermisos" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-modulos-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-modulos" type="button" role="tab">
              <i class="fas fa-th-large"></i> Módulos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-especiales-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-especiales" data-usuario="" type="button" role="tab">
             <i class="fas fa-layer-group"></i> Permisos especiales
            </button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="tabsPermisosContent">

          <!-- TAB MÓDULOS (principal) -->
          <div class="tab-pane fade show active" id="tab-modulos" role="tabpanel">

            <div class="table-responsive">
              <table id="tabla_permisos" class="table table-sm align-middle">
                <tbody>
                  <!-- Aquí se insertan dinámicamente los permisos -->
                </tbody>
              </table>
            </div>

          </div>

          <!-- TAB PERMISOS ESPECIALES -->
          <div class="tab-pane fade" id="tab-especiales" role="tabpanel">

           <div id="contenedor-permisos-especiales">
              <table id="tabla_permisos_especiales" class="table table-sm align-middle">
                   <tbody>
                      <!-- Aquí se insertan dinámicamente los permisos especiales -->
                   </tbody> 
              </table>
           </div>


          </div>

        </div>

      </div>

      <!-- Footer -->
      <div class="modal-footer" id="modal-footer-permisos"></div>

    </div>
  </div>
</div>
<!-- Fin Modal permisos -->


</body>
<script src="/public/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/public/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="/public/assets/static/js/pages/datatables.js"></script>
<script>

     $(document).ready(function() {          
        CargaUsuarios()  
        
        $('#tipo_usuario').on('change', function() {            
            FuerzaSelectTipoUsuario()
        });
        
    });

    function FuerzaSelectTipoUsuario() {
        if ($('#tipo_usuario').val() === "3") {
            $('.rut-label, .rut-input, .pass-label, .pass-input').addClass('d-none');
        } else {
            $('.rut-label, .rut-input, .pass-label, .pass-input').removeClass('d-none');
        }
    }

     function CrearUsuario(){       
        $('#nombre_usuario').val('');   
        $('#rut_usuario').val('');   
        $('#password').val('');   
        $('#tipo_usuario').val('');    
        $('#crear-editar-registro').modal('show');
         htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="Guardar()" id="confirmEnvioButton">Guardar</button>';
         $('#modal-footer-edit').html(htmlBotones);         
     }

     function Guardar(){
        let arrCampos = [];
        if($('#tipo_usuario').val()==3){
             arrCampos = [          
                ['nombre_usuario', 'text'],                  
                ['tipo_usuario', 'select']
            ];    
        }else{
            arrCampos = [          
                ['nombre_usuario', 'text'],    
                ['rut_usuario', 'text'],    
                ['password', 'text'],    
                ['tipo_usuario', 'select']
            ];
        }
                        
        if(ValidaCamposObligatorios(arrCampos)!=false){         
         // Realizar la solicitud AJAX
                $.ajax({
                    data: { 'nombre': $('#nombre_usuario').val(),
                        'rut_usuario': $('#rut_usuario').val(),
                        'password': $('#password').val(),
                        'tipo_usuario': $('#tipo_usuario').val()
                    },
                    dataType: "json",
                    type: "POST",
                    url: "<?= base_url('/public/config/GuardarUsuario')?>",

                    error: function (jqXHR, textStatus, errorThrown) {
                        MyAlert('Imposible guardar los datos', 'error');
                    },
                    success: function (data) {     
                        $('#crear-editar-registro').modal('hide');
                        MyAlert('El registro ha sido guardado con éxito', 'exito');
                        CargaUsuarios();  
                    }
                });
        }//Cierra if valida campos        
     }

     function CargaUsuarios(select, id_tela){        
        $.ajax({

                    dataType:"json",
                    url: "<?= base_url('/public/config/GetUsuarios')?>",


                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar la lista de usuarios','error');

                    },success: function(data){                             
                             // Primero destruyes la tabla
                             $('#tabla_usuarios').DataTable().destroy();       
                             $("#tabla_usuarios tbody").empty();   
                            $.each(data, function(i, item) {                            
                                   let tipo = TraduceTipoUsuario(item.id_tipo_usuario);

                                    // Si el usuario NO es mecánico, mostramos el botón de permisos
                                    let botonPermisos = '';
                                    if (tipo.nombre.toLowerCase() !== 'mecanico') {
                                        botonPermisos = `
                                            <a class="btn icon btn-primary me-1" 
                                                onclick="VerPermisos('${item.id_usuario}','${item.nombre}')" 
                                                href="#" 
                                                data-bs-toggle="tooltip" 
                                                title="Permisos">
                                                <i class="fas fa-user-shield"></i>
                                            </a>`;
                                    }

                                    $("#tabla_usuarios tbody").append(`
                                        <tr id="tr_${item.id_usuario}">
                                            <td>${item.nombre}</td>
                                            <td><span class="badge ${tipo.clase}">${tipo.nombre}</span></td>
                                            <td class="text-start">
                                                ${botonPermisos}
                                                <a class="btn icon btn-success me-1" 
                                                    onclick="Editar('${item.id_usuario}')" 
                                                    href="#" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn icon btn-danger" 
                                                    onclick="Eliminar('${item.id_usuario}')" 
                                                    href="#" 
                                                    title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    `);
                            });
                                
                             // Re-inicializas DataTables                               
                             $('#tabla_usuarios').DataTable({
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                                    }
                            });    
                    }                   
            });

    }

   function VerPermisos(id_usuario, nombre){
      $.ajax({

                    dataType:"json",

                    type: "POST",

                    data: { 'id_usuario': id_usuario},

                    url: "<?= base_url('/public/config/GetPermisosUsuario')?>",



                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar datos del usuario','error');

                    },success: function(data){          
                        //Armamos tabla con permisis de usuario       
                       /* $('#tabla_permisos').DataTable().destroy(); */
                       $("#tabla_permisos tbody").empty();
                       $.each(data, function(i, item) {                           
                           $("#tabla_permisos tbody").append(`
                                <tr>
                                    <td>${item.id_padre != null ? '- ' + item.nombre : '<b>' + item.nombre + '</b>'}</td>
                                    <td>
                                        <input class="chk_permisos" 
                                            data-ruta="${item.id_ruta}"
                                            data-permiso="${item.id_permiso}"  
                                            type="checkbox" 
                                            ${item.puede_ver == 1 ? 'checked' : ''}>
                                    </td>
                                </tr>
                           `);
                        });
                        CargarPermisosEspeciales(id_usuario);
                        //Botonera
                        $('#modal-footer-permisos').html(`
                            <button type="button" class="btn btn-primary" onclick="GuardarPermisos(${id_usuario})">Guardar</button>
                        `);
                        //Abrimos el modal                                                                                          
                       /*  $('#tab-modulos').addClass('show');
                        $('#tab-modulos-tab').addClass('active');
                        $('#tab-especiales-tab').removeClass('active'); */
                        $('#nombreUsuario').html(nombre);
                        $('#modalPermisos').modal('show');  
                    }
                });
    }                            

    //Ver Permisos Especiales   
    function CargarPermisosEspeciales(id_usuario) {

        $.ajax({
            dataType: "json",
            type: "POST",
            data: { id_usuario: id_usuario },
            url: "<?= base_url('/public/config/GetPermisosEspecialesUsuario')?>",

            error: function (jqXHR, textStatus, errorThrown) {
                MyAlert('Imposible cargar datos del usuario', 'error');
            },

            success: function (data) {
                

                // Limpiar la tabla
                $("#tabla_permisos_especiales tbody").empty();

                // Agregar filas
                $.each(data, function (i, item) {
                    $("#tabla_permisos_especiales tbody").append(`
                        <tr>
                            <td>${item.nombre}</td>
                            <td>
                                <input class="chk_perm_especial" 
                                    type="checkbox"
                                    data-permiso="${item.id_permiso_especial}"
                                    ${item.tiene_permiso == 1 ? 'checked' : ''}>
                            </td>
                        </tr>
                    `);
                });

                // Inicializar DataTable
               /*  $('#tabla_permisos_especiales').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                    },
                    pageLength: 5
                }); */
            }
        });
    }


    
     
   function GuardarPermisos(id_usuario){
        let permisos = [];
        let permisos_especiales = [];

        $(".chk_permisos").each(function() {
            permisos.push({
                ruta_id: $(this).data("ruta"),   // o item.id_ruta         
                activo: $(this).is(":checked") ? 1 : 0
            });
        });
        $(".chk_perm_especial").each(function() {
            permisos_especiales.push({
                permiso_especial_id: $(this).data("permiso"),   // o item.id_ruta         
                activo: $(this).is(":checked") ? 1 : 0
            });
        })
        $.ajax({
                    dataType:"json",
                    type: "POST",
                    data: { 'id_usuario': id_usuario,
                          'permisos': JSON.stringify(permisos),
                          'permisos_especiales':JSON.stringify(permisos_especiales) 
                    },
                    url: "<?= base_url('/public/config/GuardarPermisosUsuario')?>",
                    error: function (jqXHR, textStatus, errorThrown) {
                        MyAlert('Imposible guardar los permisos', 'error');
                    },
                    success: function (data) {     
                        console.log(data);
                        $('#modalPermisos').modal('hide');
                        MyAlert('Los permisos han sido guardados con éxito', 'exito');
                        CargaUsuarios();  
                    }
                });
   } 

   function TraduceTipoUsuario(id_tipo_usuario){        
        id_tipo_usuario = Number(id_tipo_usuario);
        switch(id_tipo_usuario) {
            case 1:
                return { nombre: 'Gerente', clase: 'bg-primary' };
            case 2:
                return { nombre: 'Administrativo', clase: 'bg-success' };
            case 3:
                return { nombre: 'Mecanico', clase: 'bg-dark' };
            default:
                return { nombre: 'Desconocido', clase: 'bg-secondary' };
        }
    }


    //Levanta modal para editar el registro
    function Editar(id_usuario){

        //Obteniendo registro del usuario que quiero editar
        $.ajax({

                    dataType:"json",

                    type: "POST",

                    data: { 'id_usuario': id_usuario},

                    url: "<?= base_url('/public/config/GetRegistroUsuario')?>",



                    error:function(jqXHR, textStatus, errorThrown){

                    MyAlert('Imposible cargar datos del usuario','error');

                    },success: function(data){                  

                          //Abrimos el modal
                          $('#crear-editar-registro').modal('show');                                       

                          //Cargamos los datos del usuario
                          $('#nombre_usuario').val(data.nombre);   
                          $('#rut_usuario').val(data.username);   
                          $('#password').val('');   
                          $('#tipo_usuario').val(data.id_tipo_usuario);   

                          FuerzaSelectTipoUsuario()       

                          //Quiero que al ser update el botón cambie a evento update
                          htmlBotones = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="Update('+id_usuario+')" id="confirmEnvioButton">Guardar</button>';
                          $('#modal-footer-edit').html(htmlBotones);                          
                    }                   
        });
    }



    //Actualiza los datos del registro
    function Update(id_usuario){
        let arrCampos = [];
        if($('#tipo_usuario').val()==3){
             arrCampos = [          
                ['nombre_usuario', 'text'],                  
                ['tipo_usuario', 'select']
            ];    
        }else{
            arrCampos = [          
                ['nombre_usuario', 'text'],    
                ['rut_usuario', 'text'],              
                ['tipo_usuario', 'select']
            ];
        }  
        if(ValidaCamposObligatorios(arrCampos)!=false){    
         // Realizar la solicitud AJAX
                $.ajax({
                    data: { 'nombre': $('#nombre_usuario').val(),
                        'rut_usuario': $('#rut_usuario').val(),
                        'password': $('#password').val(),
                        'tipo_usuario': $('#tipo_usuario').val(),
                        'id_usuario':id_usuario
                    },
                    dataType: "json",
                    type: "POST",                    
                    url: "<?= base_url('/public/config/UpdateUsuario')?>",

                    error: function (jqXHR, textStatus, errorThrown) {
                        MyAlert('Imposible guardar los datos', 'error');
                    },
                    success: function (data) {     
                        $('#crear-editar-registro').modal('hide');
                        MyAlert('El registro ha sido guardado con éxito', 'exito');
                        CargaUsuarios();  
                    }
                });
        }//Cierra if valida campos        
    }

     // Función para manejar la eliminación
    function Eliminar(id_usuario) {
         // Mostrar el modal de confirmación
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
            keyboard: false,
        });

        confirmModal.show();

        // Seleccionar el botón de confirmación
        const confirmButton = document.getElementById('confirmDeleteButton');

        // Resetear eventos previos del botón
        confirmButton.replaceWith(confirmButton.cloneNode(true)); // Esto limpia eventos previos
        const newConfirmButton = document.getElementById('confirmDeleteButton'); // Re-seleccionar el botón clonado

        // Asignar el evento de confirmación al botón de eliminar
        newConfirmButton.onclick = function () {
            confirmModal.hide(); // Cerrar el modal antes de ejecutar el AJAX

            // Realizar la solicitud AJAX
            $.ajax({
                data: { 'id_usuario': id_usuario },
                dataType: "json",
                type: "POST",                
                url: "<?= base_url('/public/config/EliminaUsuario')?>",

                error: function (jqXHR, textStatus, errorThrown) {
                    MyAlert('Imposible eliminar el item', 'error');
                },
                success: function (data) {                           
                    CargaUsuarios();                    
                }
            });
        };
    }

    
</script>

</html>
