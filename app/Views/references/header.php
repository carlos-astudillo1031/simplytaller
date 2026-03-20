<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simply TallerX - Software Automotriz</title>
    
    <link rel="icon" href="<?= base_url('public/assets/favicon.png') ?>" type="image/x-icon">
    
  <!-- References   -->
  <link rel="stylesheet" href="<?= base_url('public/assets/compiled/css/app.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/assets/compiled/css/app-dark.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/assets/compiled/css/iconly.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/assets/style.css') ?>">

  <!---Font Awesome-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

  <!-- Jquery -->
  <script src="<?= base_url('public/assets/extensions/jquery/jquery.min.js') ?>"></script>
  
  <!-- //Se usa para los card del dashboard -->
 <style>
    .active-card {
        border: 2px dashed #435ebe; /* Borde del card */
        position: relative; /* Necesario para posicionar el check */
        transition: all 0.3s ease; /* Animación suave */
    }

    .active-card::after {
        content: '\f00c'; /* Ícono de check (Font Awesome) */
        font-family: 'Font Awesome 5 Free'; /* Asegúrate de cargar Font Awesome */
        font-weight: 900;
        color: #fff; /* Color del check */
        background-color: #435ebe; /* Fondo del check */
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%; /* Hacerlo circular */
        position: absolute;
        top: 10px; /* Espaciado desde la parte superior */
        right: 10px; /* Espaciado desde la parte derecha */
    }

    


</style>


</head>

<body>
    <script src="<?= base_url('public/assets/static/js/initTheme.js') ?>"></script>
        <!-- Alertas -->
        <!-- Error -->
        <div id="alert-error" class="d-none alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 3055;" role="alert" >
            <span id="text-error"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- Exito -->
        <div id="alert-exito" class="d-none alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 3055;" role="alert" >
            <span id="text-exito"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    
