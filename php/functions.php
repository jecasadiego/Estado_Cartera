<?php

function json_output($json = [])
{
    header('Content-Type: application/json');
    echo json_encode($json);
    die;
}

function json_build($status = 200, $msg = 'OK', $data = [])
{
    return [
        'status' => $status,
        'msg' => $msg,
        'data' => $data
    ];
}

function buscar($nit)
{
    include('db.php');

    $sql = "SELECT nit, nombres, saldo, antiguedad, cupo_credito, condicion_pago, dias_prom_pago, ultima_venta, mas_60, cheques_dev FROM v_paz_salvo_web WHERE nit=:nit";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nit', $nit, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = [];
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {

        $cliente['nombres'] = $row['nombres'];
        $cliente['saldo'] = $row['saldo'];
        $cliente['antiguedad'] = $row['antiguedad'];
        $cliente['cupo_credito'] = $row['cupo_credito'];
        $cliente['condicion_pago'] = $row['condicion_pago'];
        $cliente['dias_prom_pago'] = $row['dias_prom_pago'];
        $cliente['ultima_venta'] = $row['ultima_venta'];
        $cliente['mas_60'] = $row['mas_60'];

        if ($row['cheques_dev'] > 0) {
            $cliente['cheques_dev'] = 'SI';
        } else {
            $cliente['cheques_dev'] = 'NO';
        }

        if ($cliente['saldo'] <= 0) {
            $cliente['respuesta'] = 1;
        } else if ($cliente['mas_60'] > 1) {
            $cliente['respuesta'] = 2;
        } else {
            $cliente['respuesta'] = 3;
        }
        return $cliente;

        $con = null;
        $stmt = null;
    } else {
        return false;
    }
}

function traerTexto($cliente, $nit)
{
    $diaAnterior = date('d', strtotime('-1 day'));
    $dia = date("d");
    setlocale(LC_TIME, "spanish");
    $mes = strftime("%B");
    $anio = date("Y");
    $nombre = $cliente['nombres'];
    $cupo = number_format($cliente['cupo_credito'], 2);
    $nueva_ultima_venta = date('d-m-Y', strtotime($cliente['ultima_venta']));
    $nueva_antiguedad = date('d-m-Y', strtotime($cliente['antiguedad']));


    if ($cliente['respuesta'] == 3) {
        $texto = ' 
            <div class="container table-container">
                <h5 class="mb-3">De acuerdo a su solicitud enviamos referencia comercial de: <strong>' . $nombre . '</strong> CC o NIT: <strong>' . $nit . '</strong></h5>
                <table class="table table-bordered table-responsive-sm ">

                    <tbody>
                        <tr>
                            <th>Cupo de crédito:</th>
                            <td>' . $cupo . '</td>
                        </tr>
                        <tr>
                            <th>Antigüedad:</th>
                            <td>' . $nueva_antiguedad . '</td>
                        </tr>
                        <tr>
                            <th>Condiciones de pago:</th>
                            <td>' . $cliente['condicion_pago'] . '</td>
                        </tr>
                        <tr>
                            <th>Promedio dias de pago:</th>
                            <td>' . $cliente['dias_prom_pago'] . '</td>
                        </tr>
                        <tr>
                            <th>Ultima venta:</th>
                            <td>' . $nueva_ultima_venta . '</td>
                        </tr>
                        <tr>
                            <th>Cheques devueltos:</th>
                            <td>' . $cliente['cheques_dev'] . '</td>
                        </tr>
                        <tr>
                            <th>Productos Suministrados:</th>
                            <td>Materiales y artículos de ferretería y contrucción</td>
                        </tr>
                    </tbody>
                </table> 
            </div>
        ';
    } elseif ($cliente['respuesta'] == 2) {
        $texto = 'Debe ponerse al día y comunicarse al departamento de cartera, los números de télefono son: 5-371 78 00 ext 176 - Célular 316 480 8230.';
    } else {
        $texto = 'Hasta la fecha se encuentra paz y salvo.';
    }

    return $texto;
}

function traerPDF($cliente, $nit)
{
    $nombre = $cliente['nombres'];
    $cupo = number_format($cliente['cupo_credito'], 2);
    $nueva_ultima_venta = date('d-m-Y', strtotime($cliente['ultima_venta']));
    $nueva_antiguedad = date('d-m-Y', strtotime($cliente['antiguedad']));

    if ($cliente['respuesta'] == 3) {
        $botonpdf = "
            <form action='php_pdf/respuesta1.php' method='POST'>
                <input type='hidden' name='action2' value='respuesta" . $cliente['respuesta'] . "'>
                <input type='hidden' name='nombre' value='" . $nombre . "'>
                <input type='hidden' name='nit' value='" . $nit . "'>
                <input type='hidden' name='cupo' value='" . $cupo . "'>
                <input type='hidden' name='antiguedad' value='" . $nueva_antiguedad . "'>
                <input type='hidden' name='condiciones' value='" . $cliente['condicion_pago'] . "'>
                <input type='hidden' name='prom_dia' value='" . $cliente['dias_prom_pago'] . "'>
                <input type='hidden' name='ultima_venta' value='" . $nueva_ultima_venta . "'>
                <input type='hidden' name='cheques_dev' value='" . $cliente['cheques_dev'] . "'>
                <input type='submit' class='swal2-confirm swal2-styled'name='submit_pdf' value='Generar PDF'></input>
            </form>";
    } else {

        $botonpdf = "
            <form action='php_pdf/respuesta1.php' method='POST'>
                <input type='hidden' name='action2' value='respuesta" . $cliente['respuesta'] . "'>
                <input type='hidden' name='nombre' value='" . $cliente['nombres'] . "'>
                <input type='hidden' name='nit' value='" . $nit . "'>
                <input type='submit' class='swal2-confirm swal2-styled'name='submit_pdf' value='Generar PDF'></input>
            </form>";
    }

    return $botonpdf;
}
