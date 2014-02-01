<?
   require_once '../variables.php';
   require_once '../sql.php';
   require_once '../funkcje.php';
   
ini_set('max_execution_time', 500);

   $start = time();
   
if (isset($_GET[typ])){
    $tab_typ = array($_GET[typ]);
}
//if (isset($_GET[gra])){
  //  $typGry = array($_GET[gra]);
//}



foreach ($tab_stawka as $stawka) {    
    foreach ($tab_typ as $typ) { 
        foreach ($tab_players as $players) {
            include "install__parser_1.php"; 
            echo "<pre>uklad: $stawka typ: $typ players: $players </pre>";
            $sqlconnector->openQuery($sql); 
        }
    }
}

$stop = time();

$ile = $stop - $start;

echo "time instalation: {$ile} s. <br/>";

//$sqlconnector->close();

?>