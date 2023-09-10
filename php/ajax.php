<?php

require_once('config.php');
header('Content-Type: application/json');

$action = $_POST['action'];

switch ($action) {
    case 'buscar':
        $nit = $_POST['nit'];
        $cliente = buscar($nit);
        if ($cliente == false) {
            json_output(json_build(400, 'Cliente no registrado'));
        }
        $cliente['texto'] = traerTexto($cliente,$nit);
        $cliente['pdf'] = traerPDF($cliente,$nit);

        json_output(json_build(200, 'Listo', $cliente));


    break;
}
