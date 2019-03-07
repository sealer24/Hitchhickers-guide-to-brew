<?php
include("opendb.php");
    $source = $_GET['s'];
    $system = $_GET['sys'];
    $interval='INTERVAL ' .$_GET['inter'] .' MINUTE';

    $sql = 'SELECT left(obstime, 16)';
    $sql = $sql . ' as nobstime,'; 
    $sql = $sql . ' avg(value) as nvalue '; 
    $sql = $sql . ' FROM iot '; 
    $sql = $sql . ' WHERE system = "'. $system .'" AND source = "' . $source .'" '; 
    $sql = $sql . ' AND obstime >= DATE_SUB(NOW(), "'.$interval.'") ';
    $sql = $sql . ' GROUP BY nobstime';
    
    $resultat = mysqli_query($conn, $sql);
    
    $jsonArray = array();

    while($row = mysqli_fetch_assoc($resultat)){
        $jsonArray[] = $row;
    }
    print json_encode($jsonArray);
?>