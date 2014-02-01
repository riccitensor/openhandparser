<? error_reporting(0);

require_once '../variables.php';
require_once '../funkcje.php';
require_once '../sql.php';

$stawka = $_GET[stawka];
$players = $_GET[players]+0;

if (czyTakiParserIstnieje($_GET[typ])){
    $typ = $_GET[typ];
}

$id = $_GET[id]+0;
$rs = $sqlconnector->query("SELECT * FROM poker_c{$stawka}_t{$typ}_p{$players} WHERE reka_id=$id");


//$sqlconnector->view($rs);
echo '<link rel="stylesheet" href="/parser/style.css" type="text/css" />';  

while($rek=mysql_fetch_assoc($rs)){
    if ($rek!="") {
        $wynik['info']['parser_name'] = $rek['parser_name'];
        $wynik['info']['parser_name'] = $rek['parser_name'];
        $wynik['info']['k3'] = $rek['k3'];
        $wynik['info']['k4'] = $rek['k4'];
        $wynik['info']['k5'] = $rek['k5'];
        $wynik['info']['k6'] = $rek['k6'];
        $wynik['info']['k7'] = $rek['k7'];
        
        $wynik['info']['sb']['gracz'] = $rek['sb_user'];
        $wynik['info']['sb']['cena'] = $rek['sb_price'];
        $wynik['info']['bb']['gracz'] = $rek['bb_user'];
        $wynik['info']['bb']['cena'] = $rek['bb_price'];

        $seat_number = $rek['seat_number'];
    
        $wynik['info']["seat$seat_number"]['user'] = $rek['seat_user'];
        $wynik['info']["seat$seat_number"]['k1'] = $rek['seat_k1'];
        $wynik['info']["seat$seat_number"]['k2'] = $rek['seat_k2'];
        
        if ($rek['seat_balance']>0){
            $wynik['info']["seat$seat_number"]['zarobil'] = $rek['seat_balance'] / 100;
        } else {
            $wynik['info']["seat$seat_number"]['stracil'] = $rek['seat_balance'] / 100;
        }
        
        $wynik['info']["seat$seat_number"]['cena'] = $rek['seat_price'] / 100;
        $wynik['info']["seat$seat_number"]['wygrany'] = $rek['seat_winner'];     
        
        $wynik['info']["seat$seat_number"]['line_preflop'] = $rek['line_preflop']; 
        $wynik['info']["seat$seat_number"]['line_flop'] = $rek['line_flop']; 
        $wynik['info']["seat$seat_number"]['line_turn'] = $rek['line_turn']; 
        $wynik['info']["seat$seat_number"]['line_river'] = $rek['line_river']; 
    
    }
}

  echo '<link rel="stylesheet" href="/parser/style.css" type="text/css" />';  
    viewSimplePlay($wynik); 
    
    if ($viewer_debug==1){
    echo "<pre class='parsed'>"; print_r($wynik); echo "</pre>"; 
    }


if ($rs!="") {
    

    /*
    



   sb_user VARCHAR(72),
   sb_price INT,
   bb_user VARCHAR(72),
   bb_price INT,

   button TINYINT,
   diler VARCHAR(72),
   players TINYINT,

   seat_number TINYINT,

   seat_price INT,
   seat_user VARCHAR(35),
   seat_status TINYINT,
   seat_button TINYINT,
   seat_k1 TINYINT,
   seat_k2 TINYINT,   
   seat_winner TINYINT,
   seat_balance INT,

   line_preflop VARCHAR(72),
   line_flop VARCHAR(72),
   line_turn VARCHAR(72),
   line_river VARCHAR(72), 
    */
    
   
}




?>
