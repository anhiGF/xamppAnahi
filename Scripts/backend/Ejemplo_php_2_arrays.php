<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Php2</title>
 </head>
<body>
<?php
echo "<h3>===============arrays============</h3>";
$vector_calificaciones=array();
$vector_calificaciones[0]=100;
$vector_calificaciones[1]=80;
$vector_calificaciones[2]=90;
var_dump($vector_calificaciones);//imprime el array 
echo "<br>";
 echo $vector_calificaciones[2];

$promedio=0;
for( $i=0; $i < count($vector_calificaciones); $i++ ){
    echo "<br>";
$promedio=$promedio+ $vector_calificaciones[$i];
echo $vector_calificaciones[$i];
}
echo"<br>";
$promedio=$promedio/count($vector_calificaciones);
echo $promedio;
for( $i=0; $i < count($vector_calificaciones); $i++ ){
    echo "<br>";
    if($vector_calificaciones[$i]>=$promedio){
        echo "Arriba 0 igual",$vector_calificaciones[$i];
    }else{
        echo "abajo",$vector_calificaciones[$i];
    }
}

echo "===forech===";
foreach($vector_calificaciones as $dato){
    echo $dato;
    echo "<br>";
    $color = ($dato>=70)? "green":"red";
   // echo "<span style=\" color: ". $color . ":/"". $dato. "</span>";
    echo "<br>";
}

echo "===vectores o array asosiativos===";
echo "<br>";
$vector_alumnos =array("luke"=>100,"leila"=>100,"han"=>50);
var_dump ($vector_alumnos);
echo "<br>";
foreach ($vector_alumnos as $key => $value) {
    echo "<br>";
    echo $key. ": " . $value;
}
//=========vectores bidimencionales
echo "<br>";
$vector_grupo =array(
    "luke"=>array(100,90,80),
    "han"=>array(90,80,70),
    "leila"=>array(100,100,90),
);
echo "<br>";
var_dump ($vector_grupo);
echo "<br>";
$promedioGrupo=0;
$cont=0;
$promedioMaterias=array("m1"=>0,"m2"=>0,"m3"=>0);
foreach ($vector_grupo as $key => $value) {
    $promedioI=0;
    foreach ($value as $key2 => $value2) {
        $promedioGrupo=$promedioGrupo+ $value2;
        $cont++;
        $promedioI=$promedioI+$value2;
        if ($key2==0) {
            $promedioMaterias["m1"]=$promedioMaterias["m1"]+$value2;
        }elseif ($key2==1) {
            $promedioMaterias["m2"]=$promedioMaterias["m2"]+$value2;
        }elseif ($key2==2) {
            $promedioMaterias["m3"]=$promedioMaterias["m3"]+$value2;
        }
    }
    $promedioI=$promedioI/3;
    echo $key, "=", $promedioI;
    echo "<br>";
}
$promedioMaterias["m1"]=$promedioMaterias["m1"]/3;
$promedioMaterias["m2"]=$promedioMaterias["m2"]/3;
$promedioMaterias["m3"]=$promedioMaterias["m3"]/3;
$promedioGrupo=$promedioGrupo/$cont;
echo "Promedio grupal= ",$promedioGrupo;
echo"<br>";
var_dump($promedioMaterias);
?>
</body>
</html>
