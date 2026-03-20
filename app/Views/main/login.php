<?php echo view("references/header"); ?>
<input type="hidden" name="id_pedido" id="id_pedido" value="">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<style>
    /* Estilos generales para el fondo y el centrado */
    body {
        background-color: #1A2E49; /* Fondo oscuro */
        background-image: url('ruta/a/tu/patron-automotriz.png'); /* Patrón de iconos automotrices */
        background-repeat: repeat;
        background-position: center;
        background-size: 80px 80px; /* Ajusta tamaño para que el patrón no sea muy grande */
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .btn-primary-login {
        --bs-btn-color: #fff;
        --bs-btn-bg: #1A2E49; /* Color base modificado */
        --bs-btn-border-color: #1A2E49; /* Color base modificado */
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg:rgb(15, 28, 44); /* Color más oscuro para el hover */
        --bs-btn-hover-border-color: rgb(15, 28, 44); /* Color más oscuro para el borde del hover */
        --bs-btn-focus-shadow-rgb: 0, 95, 135; /* Ajuste al nuevo color base */
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #1A2E49; /* Color más oscuro para el estado activo */
        --bs-btn-active-border-color: #1A2E49; /* Color más oscuro para el borde activo */
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
        --bs-btn-disabled-color: #fff;
        --bs-btn-disabled-bg: #1A2E49; /* Color base para el botón deshabilitado */
        --bs-btn-disabled-border-color: #1A2E49; /* Color base para el borde deshabilitado */
    }


    .card {
        width: 100%;
        max-width: 500px;
        background-color: white; /* Fondo blanco para el formulario */
        padding: 20px;
        border-radius: 0px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.9);
    }

    .card-header {
        text-align: center;
        margin-bottom: 20px;
    }

   

    .form-control-icon i {
        color: #adb5bd;
    }

    /* 🔹 Adaptaciones para pantallas pequeñas */
@media (max-width: 768px) {
    .card {
        width: 80%;
        max-width: 500px;
        background-color: white; /* Fondo blanco para el formulario */
        padding: 20px;
        border-radius: 0px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.9);
    }        
}
</style>
<div class="card">
                    <div class="card-header" style="padding: 0px;margin-bottom: -10px;">
                       <img src="<?= base_url('public/assets/logo.png') ?>" width="50%">
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical">
                                <div class="form-body">
                                    <div class="row">
                                       <div class="col-12">
                                            <div class="form-group has-icon-left">                                               
                                                <label class="fw-bold" for="first-name-icon">Taller</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control"
                                                        placeholder="Taller" id="taller">
                                                    <div class="form-control-icon">
                                                        <i class="fas fa-cog"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group has-icon-left">
                                                <label class="fw-bold" for="first-name-icon">Usuario</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control"
                                                        placeholder="Nombre de usuario" id="username" onblur="validaRut(this)">
                                                    <div class="form-control-icon">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group has-icon-left">
                                                <label class="fw-bold"  for="password-id-icon">Password</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" placeholder="Password"
                                                        id="password">
                                                    <div class="form-control-icon">
                                                        <i class="fas fa-lock"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                       
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" onclick="ValidaAcceso()" class="form-control btn btn-primary me-1 mb-1 rounded-0">Aceptar</button>
                                            <span id="error_login" class="p-2"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
</div>
<?php echo view("references/footer"); ?>


<script>
    function ValidaAcceso(){
        const arrCampos = [          
                ['username', 'text'],    
                ['password', 'text'],
                ['taller', 'text']
        ];                
        if(ValidaCamposObligatorios(arrCampos)!=false){     
           $.ajax({
                        data: { 'usuario': $('#username').val(),
                         'password': $('#password').val(),
                         'taller': $('#taller').val()
                        },
                        dataType: "json",
                        type: "POST",
                        url: "<?= base_url('/public/credenciales/ValidaUsuario')?>",

                        error: function (jqXHR, textStatus, errorThrown) {
                            MyAlert('Error: Usuario y/o password invalida', 'error');
                        },
                        success: function (data) {     
                           if(data.success){
                             window.location.href = data.url;
                           }else{
                            $('#error_login').text(data.msg);
                           }
                        }
           }); //Cierra Ajax
        }//Cierra validacion de campos   
    }

$('#password').on('focus', function() {
        $('#error_login').text('');
    });
</script>