<?php 
function procesar_pos(string $pos): float {
    $signo = -1;

    $grados = floatVal(substr($pos,strpos($pos,".") - 4 , 2));
    $minutos = floatVal(substr($pos,strpos($pos,".") - 2 , 8));
    $decimas_de_grado = $minutos / 60;

    $pos_procesada = $signo * ($grados + $decimas_de_grado);
    $pos_procesada = substr($pos_procesada, 0, 15);

    return $pos_procesada;
}

$csv = file("./out.csv");

$filename = 'out2.csv';
$handle = fopen($filename, 'w');

$latM1_1 = 0;
$lngM1_1 = 0;
$latM2_1 = 0;
$lngM2_1 = 0;

for($i = 0; $i<count($csv); $i++) {
    $datos = explode(",", $csv[$i]);

    if($i == 0) {
        $latM1_1 = procesar_pos($datos[0]);
        $lngM1_1 = procesar_pos($datos[1]);
        $latM2_1 = procesar_pos($datos[2]);
        $lngM2_1 = procesar_pos($datos[3]);

        fputcsv($handle, [$latM1_1, $lngM1_1, $latM2_1, $lngM2_1]);

        continue;
    }

    $latM1 = procesar_pos($datos[0]);
    $lngM1 = procesar_pos($datos[1]);
    $latM2 = procesar_pos($datos[2]);
    $lngM2 = procesar_pos($datos[3]);

    $difLatM1 = $latM1_1 - $latM1;
    $difLatM2 = $latM2_1 - $latM2;
    $difLngM1 = $lngM1_1 - $lngM1;
    $difLngM2 = $lngM2_1 - $lngM2;

    fputcsv($handle, [$latM1, $lngM1, $latM2, $lngM2, $difLatM1, $difLngM1, $difLatM2, $difLngM2], ",");
}

fclose($handle);
?>
