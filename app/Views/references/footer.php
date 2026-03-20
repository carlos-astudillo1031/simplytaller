<style>
  .barra-ambiente-pruebas {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: red;
  color: white;
  text-align: center;
  padding: 10px 0;
  font-weight: bold;
  font-size: 16px;
  z-index: 9999;
}

</style>
<? $env = ENVIRONMENT;?>
  <? if($env == 'development'):?>
    <div class="barra-ambiente-pruebas">
        Ambiente de pruebas
    </div>
<? endif;?>  
<!-- Modal de Confirmación Eliminar -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" style="z-index: 2555;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este elemento?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
      </div>
    </div>
  </div>
</div>
</div>
    </div>   
    <script src="<?= base_url('public/assets/functions.js') ?>"></script>
    <script src="<?= base_url('public/assets/static/js/components/dark.js') ?>"></script>
    <script src="<?= base_url('public/assets/compiled/js/app.js') ?>"></script>

    
    <script>
        
    //     //################DASHBOARD#################
        $(document).ready(function () {

    //         // #Al hacer clic en un card lo marca como activo
    //         $('.card-dash').on('click', function () {
    //             // Remover la clase 'active-card' de todos los cards
    //             $('.card-dash').removeClass('active-card');

    //             // Agregar la clase 'active-card' al card seleccionado
    //             $(this).addClass('active-card');
    //         });




            //#Marca como activa la opcion del menu corresponda                        
            const currentUrl = window.location.href; // Obtener la URL actual

            // Obtener el último segmento de la URL
            const lastSegment = currentUrl.split('/').filter(Boolean).pop();

            // Lista de valores permitidos
            const validSegments = ['clientes', 'usuarios'];


            // Verificar si el último segmento es válido
            if (validSegments.includes(lastSegment)) {
                $('li[data-segment="configuracion"]').addClass('active');
            }else{
                // Buscar el <li> correspondiente y asignar la clase
                $('li[data-segment="'+lastSegment+'"]').addClass('active');
            }  
         });     
                      
</script>
    



</body>

</html>