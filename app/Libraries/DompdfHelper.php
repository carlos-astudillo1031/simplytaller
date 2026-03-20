<?php

namespace App\Libraries;

class DompdfHelper
{
    public function getInstance()
    {
        // Cargar Dompdf manualmente
        require_once(APPPATH . 'Libraries/dompdf/autoload.inc.php');

        // Usos después del autoload manual
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        return $dompdf;
    }
}
