<?php



function RandomString($ile){
  $characters='0123456789abcdef';
  $randstring='';
  for ($x=1;$x<=$ile;$x++){
    for($i=1;$i<10;$i++){
      $randstring = $characters[rand(0, strlen($characters) - 1)];
    }
    $temp .= $randstring;
  }
  return $temp;
}

/*------------------------------------------------------------------------------
 *                      FILE MANIPULATION
 -----------------------------------------------------------------------------*/

function file_add($f,$tekst){
    $f=fopen($f,"a");
    fwrite($f,$tekst);
    fclose($f);
}

function file_load($f){
  $uchwyt = fopen($f,"a+");
  if(!$uchwyt){
    echo "file_load can't open: $f";
  }
  if (filesize($f)>0){
    return fread(fopen($f, "r"), filesize($f));
  }
}

function file_save($f,$tekst){
  $f=fopen($f,"w+");
  rewind($f);
  fwrite($f,$tekst);
  fclose($f);
}

function getFiles($dir,$what=''){
  if (!is_dir($dir)){
    echo "<pre> dir dosen't exist: $dir </pre>";
    return null;
  }
  $temp = opendir($dir);
  $files = array();
  while ($files[] = readdir($temp));
  foreach ($files as $key => $value){
    if (($value=="..") or ($value==".") or ($value=="")){unset($files[$key]);continue;}
    if ($what=='f') if (is_dir($dir.$value)){unset($files[$key]); continue;}
    if ($what=='d') if (!is_dir($dir.$value)){unset($files[$key]); continue;}
  }
  sort($files);
  closedir($temp);
  return $files;
}


//---------------------------------- POKER -------------------------------------



function viewSimplePlay($simple){
        
        echo "<div class='simple'>";
        echo "<div class='title'>\${$simple[info][sb][cena]}/\${$simple[info][bb][cena]} </div>";
        
        echo "<div class='players'>Known players: <br/>";
        echo "<table>";
        echo "<th width=200px>name:</th><th width=70px>money: </th> <th width=70px>cards:</th>";
        for ($xx=1;$xx<=9;$xx++){
            echo "<tr>";
            if ($simple[info]["seat$xx"][user]!=''){
                echo "<td><i>{$simple[info]["seat$xx"][user]}:</i></td>";                
                echo "<td>".$simple[info]["seat$xx"][cena]."</td>";
                if ($simple[info]["seat$xx"][k1]>0){
                   echo "<td>"; 
                   echo viewSimpleKarta($simple[info]["seat$xx"][k1]);
                   echo " "; 
                   echo viewSimpleKarta($simple[info]["seat$xx"][k2]);
                   echo "</td>"; 
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        
        if ($simple[ruchy][preflop]!=''){
            //------------------------ PREFLOP -------------------------------------
            echo "<div class='Preflop'><b>preflop:</b>  <br/>";

            foreach ($simple[ruchy][preflop] as $value){         
                echo "{$value[gracz]} {$value[ruch]} {$value[cena]}, ";            
            }
            echo "</div>";
        }
        
        if ($simple[ruchy][flop]!=''){
            //------------------------ FLOP ----------------------------------------
            echo "<div class='preflop'><b>flop:</b> ".viewSimpleKarta($simple[info][k3])." ".
                                                  viewSimpleKarta($simple[info][k4])." ".
                                                  viewSimpleKarta($simple[info][k5])."<br/>";
            foreach ($simple[ruchy][flop] as $value){         
                echo "{$value[gracz]} {$value[ruch]} {$value[cena]}, ";            
            }
            echo "</div>";
        }
        
        if ($simple['ruchy']['turn']!=''){
            //------------------------ TURN ----------------------------------------
            echo "<div class='preflop'><b>turn:</b> ".viewSimpleKarta($simple['info']['k6'])." <br/>";
            foreach ($simple['ruchy']['turn'] as $value){         
                echo "{$value['gracz']} {$value['ruch']} {$value['cena']}, ";            
            }
            echo "</div>";
        }
        
        if ($simple['ruchy']['river']!=''){
            //------------------------ RIVER ---------------------------------------
            echo "<div class='preflop'><b>river:</b> ".viewSimpleKarta($simple['info']['k7'])." <br/>";
            foreach ($simple['ruchy']['river'] as $value){         
                echo "{$value['gracz']} {$value['ruch']} {$value['cena']}, ";            
            }
            echo "</div>";
        }
        
        if ($simple['ruchy']==''){
            
            echo "<div class='Preflop'><b>preflop:</b>  <br/>";            
            for ($xx=1;$xx<=9;$xx++){
                if ($simple['info']["seat$xx"]['line_preflop']!=''){
                    echo $simple['info']["seat$xx"]['user'].': ';
                    $array = str_split($simple['info']["seat$xx"]['line_preflop']);                    
                    foreach ($array as $key => $value) {
                        echo getSymbol($value).' ';
                    }
                    echo '<br/>';
                }
            }
            echo '<br/>';
            
            echo "<div class='preflop'><b>flop:</b> ".viewSimpleKarta($simple[info][k3])." ".
                                                  viewSimpleKarta($simple[info][k4])." ".
                                                  viewSimpleKarta($simple[info][k5])."<br/>";       
            for ($xx=1;$xx<=9;$xx++){
                if ($simple['info']["seat$xx"]['line_flop']!=''){
                    echo $simple['info']["seat$xx"]['user'].': ';
                    $array = str_split($simple['info']["seat$xx"]['line_flop']);                    
                    foreach ($array as $key => $value) {
                        echo getSymbol($value).' ';
                    }
                    echo '<br/>';
                }
            }
            echo '<br/>';
            
            echo "<div class='preflop'><b>turn:</b> ".viewSimpleKarta($simple['info']['k6'])." <br/>";
            for ($xx=1;$xx<=9;$xx++){
                if ($simple['info']["seat$xx"]['line_turn']!=''){
                    echo $simple['info']["seat$xx"]['user'].': ';
                    $array = str_split($simple['info']["seat$xx"]['line_turn']);                    
                    foreach ($array as $key => $value) {
                        echo getSymbol($value).' ';
                    }
                    echo '<br/>';
                }
            }
            echo '<br/>';
            
            echo "<div class='preflop'><b>river:</b> ".viewSimpleKarta($simple['info']['k7'])." <br/>";
            for ($xx=1;$xx<=9;$xx++){
                if ($simple['info']["seat$xx"]['line_river']!=''){
                    echo $simple['info']["seat$xx"]['user'].': ';
                    $array = str_split($simple['info']["seat$xx"]['line_river']);                    
                    foreach ($array as $key => $value) {
                        echo getSymbol($value).' ';
                    }
                    echo '<br/>';
                }
            }
            echo '<br/>';
        }
        
        
        //------------------------ Finall ---------------------------------------
        echo "<div class='preflop'><b>finall:</b> <br/>";
        for ($xx=1;$xx<=9;$xx++){    
            if ($simple[info]["seat$xx"][user]!=''){
                if ($simple[info]["seat$xx"][wygrany]==1){
                    echo "{$simple[info]["seat$xx"][user]} +{$simple[info]["seat$xx"][zarobil]} <br/>";
                } else {
                    echo "{$simple[info]["seat$xx"][user]} {$simple[info]["seat$xx"][stracil]} <br/>";
                }
            }        
        }
        echo "</div>";
        
        echo "</div>";
}
   
    function getSymbol($symbol){
        if ($symbol=="R"){ return "raises"; }
        if ($symbol=="C"){ return "checks"; }
        if ($symbol=="X"){ return "calls"; }
        if ($symbol=="F"){ return "folds"; }
        if ($symbol=="A"){ return "all-in"; }
        if ($symbol=="B"){ return "bets"; }
    }


function viewSimpleKarta($idKarta){
         if (($idKarta=='') or ($idKarta==0)) {return 0;}
         if (($idKarta>=2) & ($idKarta<=14)){ $id=$idKarta; $kolor=1; } else
         if (($idKarta>=15) & ($idKarta<=27)){ $id=$idKarta-13; $kolor=2; } else
         if (($idKarta>=28) & ($idKarta<=40)){ $id=$idKarta-26; $kolor=3; } else
         if (($idKarta>=41) & ($idKarta<=53)){ $id=$idKarta-39; $kolor=4; }
         $symbol = $id;
         if ($symbol == 10) {$symbol = "T";} else
         if ($symbol == 11) {$symbol = "J";} else
         if ($symbol == 12) {$symbol = "Q";} else
         if ($symbol == 13) {$symbol = "K";} else
         if ($symbol == 14) {$symbol = "A";}
         
        if (file_exists("graphics/kolor/kolor-$kolor.png")){
            return "$symbol<img class='kolor' src='graphics/kolor/kolor-$kolor.png'/>";
        } else {
            return "$symbol<img class='kolor' src='../graphics/kolor/kolor-$kolor.png'/>";
        }
        
         
        
}

function czyTakiParserIstnieje($nazwa){
    global $tab_typ;
    foreach ($tab_typ as $value){
        if ($value==$nazwa){
            return 1;
        }
    }  
    return 0;
}

?>