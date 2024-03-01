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

function distanciaGeodesica(float $lat1, float $long1, float $lat2, float $long2): float{ 

	$degtorad = 0.01745329; 
	$radtodeg = 57.29577951; 
	
	$dlong = ($long1 - $long2); 
	$dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad)) 
	+ (cos($lat1 * $degtorad) * cos($lat2 * $degtorad) 
	* cos($dlong * $degtorad)); 
	
	$dd = acos($dvalue) * $radtodeg; 
	
	$km = ($dd * 111.302); 
	
	return $km; 
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

    $difM1 = distanciaGeodesica($latM1_1, $lngM1_1, $latM1, $lngM1);
    $difM2 = distanciaGeodesica($latM2_1, $lngM2_1, $latM2, $lngM2);

    $difLatM1 = $latM1_1 - $latM1;
    $difLatM2 = $latM2_1 - $latM2;
    $difLngM1 = $lngM1_1 - $lngM1;
    $difLngM2 = $lngM2_1 - $lngM2;

    fputcsv($handle, [$latM1, $lngM1, $latM2, $lngM2, $difM1, $difM2], ",");
}

fclose($handle);
?>
