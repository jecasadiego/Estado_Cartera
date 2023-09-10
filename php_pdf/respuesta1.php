<?php

require_once('../php/config.php');
include_once('../librerias/dompdf/autoload.inc.php');
header('Content-Type: application/json');

$action2 = $_POST['action2'];

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpMailer\PHPMailer\PHPMailer;

$header = "<img src='http://190.145.61.235:8092/webservice/estado_cartera/img/samir1.jpg' alt='header' width='700' style='margin-left:0px'>";

$footer = "
    <footer class='text-center text-white' style='font-size: 13px;'>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
        <img src='http://190.145.61.235:8092/webservice/estado_cartera/img/footer.png' alt='footer' style='margin-top:-50px'>
    </footer>"
;

$firma = "
    <h4 style='margin-top: -12px;margin-bottom: 160px;font-size: 17px;'>Atentamente, </h4>
	<img src='http://190.145.61.235:8092/webservice/estado_cartera/img/firma.png' style='margin-bottom:23px; margin-left:-18px;margin-top:-120px;' width='150'"
;

switch ($action2) {
    case 'respuesta1':

        $nombre = $_POST['nombre'];
        $nit = $_POST['nit'];
        $diaAnterior = date('d', strtotime('-1 day'));
        $dia = date("d");
        setlocale(LC_TIME, "spanish");
        $mes = strftime("%B");
        $anio = date("Y");

        $html = $header."
            <h3 style='margin-top:35px;text-align: center;margin-bottom: 50px;'><strong>PAZ Y SALVO</strong></h3> 
            <p>Certificamos que <strong>{$nombre}</strong> identificado(a) con CC o NIT: <strong>{$nit}</strong> a la fecha no tiene ninguna obligación pendiente con INVESAKK S.A.S</p>
            <p>Se expide la siguiente certificación a solicitud del interesado a los ".$diaAnterior." dias del mes de ".$mes." de ".$anio."</p>".$firma."
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$footer
        ;

        $filename = 'solicitud.pdf';
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = new Dompdf($options);
        $pdf->setPaper('A4');
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($filename);

        break;

    case 'respuesta2':

        $nombre = $_POST['nombre'];
        $nit = $_POST['nit'];


        $html = $header."
            <h3 style='margin-top:35px;text-align: center;margin-bottom: 50px;'><strong>ATENCIÓN</strong></h3> 
            <p>El cliente <strong>{$nombre}</strong> identificado(a) con CC o NIT: <strong>{$nit}</strong> debe ponerse al día y comunicarse al departamento de cartera.</p>
            <p>Los números de telefonos son: <strong>5-371 78 00 ext 176 - Celular 316 480 8230</strong></p>".$firma."
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$footer
        ;

        $filename = 'solicitud.pdf';
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = new Dompdf($options);
        $pdf->setPaper('A4');
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($filename);


        break;

    case 'respuesta3':
        
        $nombre = $_POST['nombre'];
        $nit = $_POST['nit'];
        $cupo = $_POST['cupo'];
        $antiguedad = $_POST['antiguedad'];
        $condiciones = $_POST['condiciones'];
        $prom_dia = $_POST['prom_dia'];
        $ultima_venta = $_POST['ultima_venta'];
        $cheques_dev = $_POST['cheques_dev'];
        
        $diaAnterior = date('d', strtotime('-1 day'));
        $dia = date("d");
        setlocale(LC_TIME, "spanish");
        $mes = strftime("%B");
        $anio = date("Y");

        $html = $header.'
            <h3 style="margin-top:35px;text-align: center;margin-bottom: 50px;"><strong>REFERENCIA COMERCIAL</strong></h3> 
                <p>De acuerdo a su solicitud enviamos referencia comercial de: <strong>' .$nombre. '</strong> CC o NIT: <strong>' . $nit . '.</strong></p>
                <table border="1">

                    <tbody>
                        <tr>
                            <th>Cupo de crédito:</th>
                            <td>' . $cupo . '</td>
                        </tr>
                        <tr>
                            <th>Antigüedad:</th>
                            <td>' . $antiguedad . '</td>
                        </tr>
                        <tr>
                            <th>Condiciones de pago:</th>
                            <td>' . $condiciones . '</td>
                        </tr>
                        <tr>
                            <th>Promedio dias de pago:</th>
                            <td>' . $prom_dia . '</td>
                        </tr>
                        <tr>
                            <th>Ultima venta:</th>
                            <td>' . $ultima_venta . '</td>
                        </tr>
                        <tr>
                            <th>Cheques devueltos:</th>
                            <td>' . $cheques_dev . '</td>
                        </tr>
                        <tr>
                            <th>Productos Suministrados:</th>
                            <td>Materiales y artículos de ferretería y contrucción</td>
                        </tr>
                    </tbody>
                </table> 
                <br>
                <p>Se expide la presente solicitud del interesado en Barranquilla, a los '.$diaAnterior.' dias del mes de '.$mes.' de '.$anio.'</p>
                <p>Para validar esta referencia deben comunicarse al 5-3717800 ext. 176.</p>'.$firma.'<br><br><br><br><br><br>'.$footer;

        $filename = 'solicitud.pdf';
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = new Dompdf($options);
        $pdf->setPaper('A4');
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream($filename);


        break;

    default:
        return 'Opción Inválida';
}

function respuesta1()
{
}

function respuesta2()
{
}

function respuesta3()
{
}
