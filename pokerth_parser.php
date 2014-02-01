<?php

class poker_parser {
    public $plik; // wszystko
    public $tab = array();
    public $dane;
    
    public $sila;
    
    public $wy_info;    
    public $wy_ruchy;
    public $wy_summary;    
    public $wy_plik = array();   
    
    public function resetuj(){
        $this->wy_info = "";
        $this->wy_ruchy = "";
        $this->wy_summary = "";
    }
    
    public function getSize(){
        //echo "getSize()<br/>";
        for ($xx=1;$xx<=9;$xx++){
            if ($this->wy_info["seat$xx"]['user']!=''){
                $size = "";
                if ($this->wy_ruchy!='') foreach ($this->wy_ruchy as $rr => $vv){                    
                    $cena = 0;
                    if ($this->wy_ruchy["$rr"]!='') foreach ($this->wy_ruchy["$rr"] as $ruch){
                        if ($ruch['gracz']==$this->wy_info["seat$xx"]['user']){
                            $cena += $ruch['cena'];
                        }
                    }
                    $size["$rr"] = $cena;                    
                }
                if ($size['preflop']!='') $this->wy_info["seat$xx"]['size_preflop'] = $size['preflop'];     
                if ($size['flop']!='') $this->wy_info["seat$xx"]['size_flop'] = $size['flop']; 
                if ($size['turn']!='') $this->wy_info["seat$xx"]['size_turn'] = $size['turn']; 
                if ($size['river']!='') $this->wy_info["seat$xx"]['size_river'] = $size['river']; 
            }            
        }
    }    
      
    public function getLine(){
        for ($xx=1;$xx<=9;$xx++){
            if ($this->wy_info["seat$xx"]['user']!=''){
                $line = "";                
                if ($this->wy_ruchy!='') foreach ($this->wy_ruchy as $rr => $vv){
                    if ($this->wy_ruchy["$rr"]!='') foreach ($this->wy_ruchy["$rr"] as $ruch){
                        if ($ruch['gracz']==$this->wy_info["seat$xx"]['user']){$line["$rr"] .= $this->getSymbol($ruch['ruch']);}
                    }
                }
                if ($line['preflop']!='') $this->wy_info["seat$xx"]['line_preflop'] = $line['preflop'];     
                if ($line['flop']!='') $this->wy_info["seat$xx"]['line_flop'] = $line['flop']; 
                if ($line['turn']!='') $this->wy_info["seat$xx"]['line_turn'] = $line['turn']; 
                if ($line['river']!='') $this->wy_info["seat$xx"]['line_river'] = $line['river']; 
            }                
        }
    }
    
    public function getSymbol($symbol){
        if ($symbol=="raises"){ return "R"; }
        if ($symbol=="checks"){ return "C"; }
        if ($symbol=="calls"){ return "X"; }
        if ($symbol=="folds"){ return "F"; }
        if ($symbol=="all-in"){ return "A"; }
        if ($symbol=="bets"){ return "B"; }
    }
    
    public function getStrength(){
        for ($xx=1;$xx<=9;$xx++){ 
             $moje1 = $this->wy_info["seat$xx"]["k1"];
             $moje2 = $this->wy_info["seat$xx"]["k2"];             
             $flop1 = $this->wy_info["k3"];
             $flop2 = $this->wy_info["k4"];
             $flop3 = $this->wy_info["k5"];             
             $turn = $this->wy_info["k6"];
             $river = $this->wy_info["k7"];
             if (($moje1>0) and ($moje2>0)){
                 $this->wy_info["seat$xx"]["str_preflop"] = $this->sila->ustawKarty($moje1,$moje2);
             } else {
                 continue;
             }
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0)){
                 $this->wy_info["seat$xx"]["str_flop"] = $this->sila->ustawKarty($moje1,$moje2,$flop1,$flop2,$flop3);
             }
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0) and ($turn>0)){
                 $this->wy_info["seat$xx"]["str_turn"] = $this->sila->ustawKarty($moje1,$moje2,$flop1,$flop2,$flop3,$turn);
             }
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0) and ($turn>0) and ($river>0)){
                 $this->wy_info["seat$xx"]["str_river"] = $this->sila->ustawKarty($moje1,$moje2,$flop1,$flop2,$flop3,$turn,$river);
             }
             ///////////////////////////////////////////////////////////////////             
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0)){
                 $tab = $this->sila->coMa($moje1,$moje2,$flop1,$flop2,$flop3);                 
                 if (isset($tab[u11])) $this->wy_info["seat$xx"]["flop_overcard"] = $tab['u11'];
                 if (isset($tab[u12])) $this->wy_info["seat$xx"]["flop_undercard"] = $tab['u12'];
                 if (isset($tab[u13])) $this->wy_info["seat$xx"]["flop_fd"] = $tab['u13'];
                 if (isset($tab[u14])) $this->wy_info["seat$xx"]["flop_gs"] = $tab['u14'];
                 if (isset($tab[u15])) $this->wy_info["seat$xx"]["flop_oesd"] = $tab['u15'];
             }             
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0) and ($turn>0)){
                 $tab = $this->sila->coMa($moje1,$moje2,$flop1,$flop2,$flop3,$turn);
                 if (isset($tab[u11])) $this->wy_info["seat$xx"]["turn_overcard"] = $tab['u11'];
                 if (isset($tab[u12])) $this->wy_info["seat$xx"]["turn_undercard"] = $tab['u12'];
                 if (isset($tab[u13])) $this->wy_info["seat$xx"]["turn_fd"] = $tab['u13'];
                 if (isset($tab[u14])) $this->wy_info["seat$xx"]["turn_gs"] = $tab['u14'];
                 if (isset($tab[u15])) $this->wy_info["seat$xx"]["turn_oesd"] = $tab['u15'];
             }             
             if (($moje1>0) and ($moje2>0) and ($flop1>0) and ($flop2>0) and ($flop3>0) and ($turn>0) and ($river>0)){
                 $tab = $this->sila->coMa($moje1,$moje2,$flop1,$flop2,$flop3,$turn,$river);
                 if (isset($tab[u11])) $this->wy_info["seat$xx"]["river_overcard"] = $tab['u11'];
                 if (isset($tab[u12])) $this->wy_info["seat$xx"]["river_undercard"] = $tab['u12'];
                 if (isset($tab[u13])) $this->wy_info["seat$xx"]["river_fd"] = $tab['u13'];
                 if (isset($tab[u14])) $this->wy_info["seat$xx"]["river_gs"] = $tab['u14'];
                 if (isset($tab[u15])) $this->wy_info["seat$xx"]["river_oesd"] = $tab['u15'];                 
             }
        }
    }
    
    public function getIdUserByLogin($login){
        $login = trim($login);
        if ($login==''){return 0;}
        for ($int=1;$int<=9;$int++){
            if ($login==$this->wy_info["seat$int"]['user']){
                 return $int;
            }
        }
    }
    
    public function wysumuj(){
        global $view_wysumuj;
        for ($xx=1;$xx<=9;$xx++){
            $user = $this->wy_info["seat$xx"]['user'];
            $cena = $this->wy_info["seat$xx"]['cena'];
            if ($user!=''){
                if ($this->wy_info["sb"]['gracz']==$user){
                    $cena = $cena - $this->wy_info["sb"]['cena'];
                    $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["sb"]['cena'];
                }
                if ($this->wy_info["bb"][gracz]==$user){
                    $cena = $cena - $this->wy_info["bb"]['cena'];
                    $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["bb"]['cena'];
                }                
                $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["seat$xx"]['last_preflop_cena'];
                $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["seat$xx"]['last_flop_cena'];
                $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["seat$xx"]['last_turn_cena'];
                $this->wy_info["seat$xx"]['wpuliodgracza'] += $this->wy_info["seat$xx"]['last_river_cena'];
            }
        }
        
        for ($zz=1;$zz<=9;$zz++){            
            if ($view_wysumuj==1) {echo "<pre> Petla ogolna $zz </pre> <br/>"; }
            $this->viewWPuli();
            $minDoDz = 0; 
            $wygrani = $this->wy_info['wygrani'];
            if ($wygrani<1){continue;}
            $licznik_wygranych = 0;
            for ($xx=1;$xx<=9;$xx++){
                if ($this->wy_info["seat$xx"]['user']==''){continue;}
                $WPuli = $this->wy_info["seat$xx"]['wpuliodgracza'];
                if ((($minDoDz==0)or($minDoDz > $WPuli)) and ($WPuli>0) and ($this->wy_info["seat$xx"]['wygrany']==1)){                    
                    $minDoDz = $WPuli;
                    $licznik_wygranych++;
                    $this->wy_info["seat$xx"]['maDostac']=1;
                } else {                    
                    $this->wy_info["seat$xx"]['maDostac']=0;
                }
            }            
            if ($minDoDz==0){continue;}            
            if ($view_wysumuj==1) {echo "licznik_wygranych = $licznik_wygranych <br/>";}            
            for ($xx=1;$xx<=9;$xx++){
                if (($this->wy_info["seat$xx"][wygrany]==1) and ($this->wy_info["seat$xx"][wpuliodgracza]!=0)){
                    $this->wy_info["seat$xx"][wpuliodgracza]-=$minDoDz;
                }
            }
            
            for ($xx=1;$xx<=9;$xx++){
                $doObrotu = 0;
                if ($this->wy_info["seat$xx"][wpuliodgracza]==0) {continue;}
                if ($this->wy_info["seat$xx"][user]=='') {continue;}
                if ($this->wy_info["seat$xx"][wygrany]==0){
                    if ($this->wy_info["seat$xx"][wpuliodgracza]>=$minDoDz){
                        $doObrotu = $minDoDz;
                        $this->wy_info["seat$xx"][wpuliodgracza] -= $minDoDz;
                        $this->wy_info["seat$xx"][stracil] += $minDoDz;
                        if ($view_wysumuj==1) {echo " {$this->wy_info["seat$xx"][user]} -> stracil = $minDoDz <br/>";}
                    } else {
                        $doObrotu = $this->wy_info["seat$xx"][wpuliodgracza];
                        $this->wy_info["seat$xx"][wpuliodgracza] = 0;
                        $this->wy_info["seat$xx"][stracil] += $doObrotu;
                        if ($view_wysumuj==1) {echo " {$this->wy_info["seat$xx"][user]} -> stracil = $doObrotu <br/>";}
                    }
                }
                if ($doObrotu == 0) {continue;}
                $ileDlaZwyciezcy = $doObrotu / $licznik_wygranych;                    
                if ($view_wysumuj==1) {echo "* ileDlaZwyciezcy = $ileDlaZwyciezcy <br/>";}
                for ($yy=1;$yy<=9;$yy++){
                    if ($this->wy_info["seat$yy"][maDostac]==1){
                        $this->wy_info["seat$yy"][zarobil]+=$ileDlaZwyciezcy;
                        if ($view_wysumuj==1) {echo " {$this->wy_info["seat$yy"][user]} -> zarobil = $ileDlaZwyciezcy <br/>";}
                    }
                }                
            }
            if ($view_wysumuj==1) {echo "<br/><br/><br/><br/>";}
        }              
        $this->viewWPuli();         
    }
    
    public function viewWPuli(){  
        global $view_wysumuj;
        if ($view_wysumuj==1) {
            echo "<table><th>=== gracz ===</th> <th> wpuli </th>";
            for ($xx=1;$xx<=9;$xx++){
                echo "<tr><td>".$this->wy_info["seat$xx"]['user']."</td>".
                "<td>".$this->wy_info["seat$xx"]['wpuliodgracza']."</td></tr>";
            }
            echo "</table>";
        }
    }
    
    public function infoZmWyciaganych($minDoDz,$wygrani,$ileMoWyc){
        global $view_wysumuj;
        if ($view_wysumuj==1) {
            echo "<table><th>=== minDoDz ===</th><th> wygrani </th><th> ile_moge_wyciagnac </th>";
            echo "<tr><td>$minDoDz</td><td>$wygrani</td><td>$ileMoWyc</td></tr>";
            echo "</table>";
        }
    } 
    
    public function viewEtykieta($xx){
        global $view_wysumuj;
        if ($view_wysumuj==1) {
            if ($this->wy_info["seat$xx"]['wygrany']==1){echo "<div style='background-color:silver'>$xx</div>";}
        }
    }
}
?>