<?
 require_once 'sql.php';

class saveToBase {
    
    var $zapytanie;
    var $licznik_dodanych;
    
    public function idRuchu($name){
        if ($name == "folds") { return 1;} else 
        if ($name == "calls") { return 2;} else     
        if ($name == "raises") { return 3;} else     
        if ($name == "bets") { return 4;} else 
        if ($name == "checks") { return 5;} else 
        if ($name == "allin") { return 6;} else   
        if ($name == "check-raise") { return 7;} else {
            return 0;
        }           
    }

    public function stawki($stawka){
        global $tab_stawka;
        foreach ($tab_stawka as $value){
            if ($stawka == $value){
                return 1;
            }
        }
        return 0;
    }
    
    public function typParsera($name){
        //echo "typParsera = $name";
        if ($name=="PokerStars"){ return "pokerstars"; }
        if ($name=="PartyPoker"){ return "partypoker"; }
        if ($name=="HoldemManager"){ return "holdemmanager"; }
        return $name;
    }    
    
    public function analizeAndSave($ob){      
        global $sqlconnector;
        global $view_object;
        global $view_simple;
        
        
        
        foreach ($ob as $value) {$ob_licznik++;
            
            
           
            //$reka_id = RandomString(32);        
            //echo "reka_id = $reka_id <br/>";            
            $reka_id = "";            
            if ($view_simple){
                viewSimplePlay($value);
            }
            if ($view_object){                
                echo "<pre class='parsed'>"; print_r($value); echo "</pre>"; 
            }            
            
            for ($xx=1;$xx<=9;$xx++){
                if ($value['info']["seat$xx"]['user']!=''){
                    $typ = $this->typParsera($value['info']['parser_name']);            
                    $bb = $value['info']['bb']['cena'] * 100;
                    $stawka = $value['info']['bb']['stawka'] * 100;
                    if ($value['info']['players'] <= 6){ $players=6; } else { $players=9; }
                    
                    $this->zapytanie = "";
                    if ($value['info']['parser_name']!=''){ $this->zapytanie.="parser_name = '{$value['info']['parser_name']}',\n"; }
                    if ($value['info']["seat$xx"]['user']!=''){
                        $this->zapytanie.="seat_user = \"{$value['info']["seat$xx"]['user']}\",\n";
                    }
                    if ($value['info']['k3']>0){ $this->zapytanie.="k3 = '{$value['info']['k3']}',\n"; }
                    if ($value['info']['k4']>0){ $this->zapytanie.="k4 = '{$value['info']['k4']}',\n"; }
                    if ($value['info']['k5']>0){ $this->zapytanie.="k5 = '{$value['info']['k5']}',\n"; }
                    if ($value['info']['k6']>0){ $this->zapytanie.="k6 = '{$value['info']['k6']}',\n"; }
                    if ($value['info']['k7']>0){ $this->zapytanie.="k7 = '{$value['info']['k7']}',\n"; } 
                    if ($value['info']["seat$xx"]['cena']!=''){
                        $this->zapytanie.="seat_price = '".(($value['info']["seat$xx"]['cena'])*100)."',\n";
                    }                    
                    if ($value['info']["seat$xx"]['status']!=''){                    
                        $val = 0;
                        if ($value['info']["seat$xx"]['status']=="sb"){ $val = 1; }
                        if ($value['info']["seat$xx"]['status']=="bb"){ $val = 2; }                    
                        $this->zapytanie.="seat_status = '$val',\n";
                    }                    
                    if ($value['info']["seat$xx"]['button']==1){
                        $this->zapytanie.="seat_button = '1',\n";
                    }                    
                    if ($value['info']["seat$xx"]['k1']>0){
                        $this->zapytanie.="seat_k1 = \"{$value['info']["seat$xx"]['k1']}\",\n";
                    }                    
                    if ($value['info']["seat$xx"]['k2']>0){
                        $this->zapytanie.="seat_k2 = \"{$value['info']["seat$xx"]['k2']}\",\n";
                    }                    
                    if ($value['info']["seat$xx"]['wygrany']==1){
                        $this->zapytanie.="seat_winner = '1',\n";                        
                    }                    
                    if (($cena = $value['info']["seat$xx"]['zarobil']*100)!=0){
                        $this->zapytanie.="seat_balance = '$cena',\n"; 
                    } else
                    if (($cena = $value['info']["seat$xx"]['stracil']*100*-1)!=0){  
                        $this->zapytanie.="seat_balance = '$cena',\n"; 
                    }                    
                    if (($gracz = $value['info']['sb']['gracz'])!=''){
                        $this->zapytanie.="sb_user = \"$gracz\",\n";   
                    }
                    if (($cena = $value['info']['sb']['cena']*100)!=0){
                        $this->zapytanie.="sb_price = \"$cena\",\n";   
                    }
                    if (($gracz = $value['info']['bb']['gracz'])!=''){
                        $this->zapytanie.="bb_user = \"$gracz\",\n";   
                    }
                    if (($cena = $value['info']['bb']['cena']*100)!=0){
                        $this->zapytanie.="bb_price = \"$cena\",\n";   
                    }

                    if (($name = $value['info']['diler'])!=''){
                        //$this->zapytanie.="diler = \"$name\",\n";   
                    }
                    if (($name = $value['info']['button'])>0){
                        $this->zapytanie.="button = \"$name\",\n";   
                    }
                    if (($name = $value['info']['players'])>0){
                        $this->zapytanie.="players = \"$name\",\n";   
                    }      
                    
                    
                    
                    
                    if ($value['info']["seat$xx"]["str_preflop"]>0){
                        $this->zapytanie.="str_preflop = '{$value['info']["seat$xx"]["str_preflop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["str_flop"]>0){
                        $this->zapytanie.="str_flop = '{$value['info']["seat$xx"]["str_flop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["str_turn"]>0){
                        $this->zapytanie.="str_turn = '{$value['info']["seat$xx"]["str_turn"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["str_river"]>0){
                        $this->zapytanie.="str_river = '{$value['info']["seat$xx"]["str_river"]}',\n";
                    }                
                    if ($value['info']["seat$xx"]["line_preflop"]!=''){
                        $this->zapytanie.="line_preflop = '{$value['info']["seat$xx"]["line_preflop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["line_flop"]!=''){
                        $this->zapytanie.="line_flop = '{$value['info']["seat$xx"]["line_flop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["line_turn"]!=''){
                        $this->zapytanie.="line_turn = '{$value['info']["seat$xx"]["line_turn"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["line_river"]!=''){
                        $this->zapytanie.="line_river = '{$value['info']["seat$xx"]["line_river"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["size_preflop"]>0){
                        $this->zapytanie.="size_preflop = '{$value[info]["seat$xx"]["size_preflop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["size_flop"]>0){
                        $this->zapytanie.="size_flop = '{$value['info']["seat$xx"]["size_flop"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["size_turn"]>0){
                        $this->zapytanie.="size_turn = '{$value['info']["seat$xx"]["size_turn"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["size_river"]>0){
                        $this->zapytanie.="size_river = '{$value['info']["seat$xx"]["size_river"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["flop_overcard"]>0){
                        $this->zapytanie.="flop_overcard = '{$value['info']["seat$xx"]["flop_overcard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["flop_undercard"]>0){
                        $this->zapytanie.="flop_undercard = '{$value['info']["seat$xx"]["flop_undercard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["flop_fd"]>0){
                        $this->zapytanie.="flop_fd = '{$value['info']["seat$xx"]["flop_fd"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["flop_gs"]>0){
                        $this->zapytanie.="flop_gs = '{$value['info']["seat$xx"]["flop_gs"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["flop_oesd"]>0){
                        $this->zapytanie.="flop_oesd = '{$value['info']["seat$xx"]["flop_oesd"]}',\n";
                    }
                    ////////////////////////////////////////////////////////////////
                    if ($value['info']["seat$xx"]["turn_overcard"]>0){
                        $this->zapytanie.="turn_overcard = '{$value['info']["seat$xx"]["turn_overcard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["turn_undercard"]>0){
                        $this->zapytanie.="turn_undercard = '{$value['info']["seat$xx"]["turn_undercard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["turn_fd"]>0){
                        $this->zapytanie.="turn_fd = '{$value['info']["seat$xx"]["turn_fd"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["turn_gs"]>0){
                        $this->zapytanie.="turn_gs = '{$value['info']["seat$xx"]["turn_gs"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["turn_oesd"]>0){
                        $this->zapytanie.="turn_oesd = '{$value['info']["seat$xx"]["turn_oesd"]}',\n";
                    }
                    ////////////////////////////////////////////////////////////////
                    if ($value['info']["seat$xx"]["river_overcard"]>0){
                        $this->zapytanie.="river_overcard = '{$value['info']["seat$xx"]["river_overcard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["river_undercard"]>0){
                        $this->zapytanie.="river_undercard = '{$value['info']["seat$xx"]["river_undercard"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["river_fd"]>0){
                        $this->zapytanie.="river_fd = '{$value['info']["seat$xx"]["river_fd"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["river_gs"]>0){
                        $this->zapytanie.="river_gs = '{$value['info']["seat$xx"]["river_gs"]}',\n";
                    }
                    if ($value['info']["seat$xx"]["river_oesd"]>0){
                        $this->zapytanie.="river_oesd = '{$value['info']["seat$xx"]["river_oesd"]}',\n";
                    }
                    
                    
                    
                    if ($reka_id == ''){
                        if ($this->stawki($stawka)) { 
                            $result = $sqlconnector->query("SELECT MAX(reka_id) AS id FROM poker_c{$stawka}_t{$typ}_p{$players}");
                        } else {
                            $result = $sqlconnector->query("SELECT MAX(reka_id) AS id FROM poker_celse_t{$typ}_p{$players}");
                        }
                        $row = mysql_fetch_array($result); 
                        $reka_id = $row['id'] + 1;
                        //echo "$reka_id";
                    }                    
                    $this->zapytanie.="seat_number = '$xx',\n";
                    $this->zapytanie.="reka_id = '$reka_id',\n";   
                    
                    global $zapis_dane;
                    if ($zapis_dane==1){
                        $value['dane'] = str_replace('"', '', $value['dane']);            
                        $this->zapytanie.="dane = \"$value[dane]\",\n";
                    }
                    
                    if ($this->stawki($stawka)) { 
                        $sql = "INSERT INTO poker_c{$stawka}_t{$typ}_p{$players} SET \n";                
                    } else {
                        $sql = "INSERT INTO poker_celse_t{$typ}_p{$players} SET \n";
                    }  
                    $sql.= "$this->zapytanie";
                    $sql.= "author = '$_SESSION[author]',\n"; 
                    $sql.= "time_create = '".time()."'";
                    global $view_sql;
                    global $zapis_sql;
                    global $console_view_sql;
                    if ($view_sql) {echo "<pre> $sql </pre>";}
                    if ($zapis_sql) {$record_id = $sqlconnector->openQuery($sql);}
                    //echo "d$this->licznik_dodanych ";
                    
                 
                    /*function flush_buffers(){ 
                         ob_end_flush(); 
                         ob_flush(); 
                         flush(); 
                         ob_start(); 
                     } */
                    
                    
                    $ob_max = count($ob);
                    $wynik =($ob_licznik/$ob_max)*100;
                    $wynik = number_format ( $wynik, 1 );
                    
                    global $vprogressbar;
                    if ($vprogressbar==1){
                        //echo "$ob_licznik / $ob_max = $wynik <br/>";
                        echo "<script>updateProgressfile($wynik);</script>";
                        echo "<!--";
                        echo RandomString(1024);                    
                        echo "-->";
                        ob_flush(); 
                    }
                    
                    $this->licznik_dodanych++;
                    if ($console_view_sql) { echo " Insert $this->licznik_dodanych record into table: poker_c{$bb}_p{$players} reka_id $reka_id \n";}
                }
            }
            
            
            global $max_hands_to_add;
            if ($max_hands_to_add<=$this->licznik_dodanych){
                //echo "this->licznik_dodanych $this->licznik_dodanych <br/>";
                //echo "max_hands_to_add = $max_hands_to_add <br/>";
                return 0;
            }            
        }        
    }
}

?>