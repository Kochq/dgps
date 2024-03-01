<?php
$movil1 = file("./movil1.txt");
$movil2 = file("./movil2.txt");

$lineasM1 = [];
$lineasM2 = [];

$horasM1 = [];
$horasM2 = [];

$latM1 = [];
$latM2 = [];

$lngM1 = [];
$lngM2 = [];

$fullCsv = [];
$fullTxt = [];

echo "Working...\n";

foreach($movil1 as $lineas) {
    $horasM1[] = explode(",", $lineas)[8];
    $latM1[] = explode(",", $lineas)[1];
    $lngM1[] = explode(",", $lineas)[3];
}

echo "Done 1...\n";

foreach($movil2 as $lineas2) {
    $hora = explode(",", $lineas2)[8];
    $lat = explode(",", $lineas2)[1];
    $lng = explode(",", $lineas2)[3];
    for($i = 0; $i<count($horasM1); $i++) {
        if($horasM1[$i] == $hora) {
            $fullCsv[] = [$latM1[$i], $lngM1[$i], $lat, $lng];
            $fullTxt[] = $latM1[$i] .",". $lngM1[$i] .",". $lat .",". $lng .",". $hora ."\n";
        }
    }
}

foreach($fullTxt as $row) {
    file_put_contents("./out.txt", $row, FILE_APPEND);
}

$filename = 'out.csv';
$handle = fopen($filename, 'w');

foreach ($fullCsv as $row) {
    fputcsv($handle, $row);
}

fclose($handle);

echo "DONE.";
?>
