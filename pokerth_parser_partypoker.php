<? 

require_once 'sila.php';
require_once 'pokerth_parser.php';

class poker_parser_partypoker extends poker_parser {

    public $line = "(\n|\r|\r\n|\n\r)";
    public $karta = "([0-9A-T][a-z])";
    public $gracz = "([A-Za-z0-9_\ \.]+)"; 
    public $ciagruchow = "([A-Za-z0-9\n\r_\[\]\$\,\-\'\.\ ]+)"; 
    public $ruch = '(calls|folds|raises|bets|checks|check-raise|is all-In )';
    public $kasa = '([0-9\.\,]+)';    
    public $waluta = '(\$|\€|)';
    public $walutas = '(USD|EUR)';
   
    public function ruchyGRC($tab,$numer_etapu){
         $id=0;foreach($tab as $value){
             if ($value=="") {continue;}
             $id++;
             preg_match("#$this->gracz $this->ruch \[$this->waluta$this->kasa $this->walutas\]#",$value,$r);   
             if ($r[1]==""){ preg_match("#$this->gracz $this->ruch#",$value,$r); }             
             if ($r[1]!=""){ $this->wy_ruchy[$numer_etapu][$id]['gracz'] = $r[1]; }
             if ($r[2]!=""){ 
                 if ($r[2]=="is all-In ") {$r[2]="allin";}
                 $this->wy_ruchy[$numer_etapu][$id]['ruch'] = $r[2];
             }
             if ($r[4]!=""){ $this->wy_ruchy[$numer_etapu][$id]['cena'] = preg_replace('{([^0-9.])}','',$r[4]); }  
         }
    }
    
    public function jakakarta($symbol){          
        $wartosc=$symbol[0];
        $kolor=$symbol[1];
        if ($wartosc=="T") {$w = 10;} else 
        if ($wartosc=="J") {$w = 11;} else 
        if ($wartosc=="Q") {$w = 12;} else 
        if ($wartosc=="K") {$w = 13;} else 
        if ($wartosc=="A") {$w = 14;} else
        {$w=$wartosc;}        
        if ($kolor=="heart" or $kolor=="h") {$k = 0;} else 
        if ($kolor=="diamond" or $kolor=="d") {$k = 13;} else 
        if ($kolor=="club" or $kolor=="c") {$k = 26;} else 
        if ($kolor=="spade" or $kolor=="s") {$k = 39;}
        return $w + $k;
    }
    
    public function __construct(){        
        $this->sila = new poker_strenght();        
    }
    
    public function info(){
         $this->wy_info['parser_name'] = "PartyPoker";
        
         preg_match( '#Total number of players : ([0-9]{1,1})#', $this->dane, $r ); 
         if ($r[1]>0){ $this->wy_info['players'] = $r[1]; }
         
         $tab = explode("\n", $this->dane);         
         foreach ($tab as $value) {             
             preg_match("#Seat ([0-9]{1,1}): $this->gracz \( $this->waluta$this->kasa $this->walutas \)#", $value, $r );             
             if ($r[2]!="") {
                 $this->wy_info["seat$r[1]"]['user']=$r[2];
                 $this->wy_info["seat$r[1]"]['cena']= preg_replace('{([^0-9.])}','',$r[4]);
             }             
         }
         
         preg_match( "#$this->gracz posts small blind \\[$this->waluta$this->kasa $this->walutas\\]#",$this->dane,$r); 
         if ($r[1]!='') { 
             $this->wy_info['sb']['gracz'] = $r[1];  
             $this->wy_info['sb']['cena'] =  preg_replace('{([^0-9.])}','',$r[3]);
         }
         if (($login_id=$this->getIdUserByLogin($r[1]))>0){      
             $this->wy_info["seat$login_id"]['status']="sb";
         }
         
         preg_match( "#$this->gracz posts big blind \\[$this->waluta$this->kasa $this->walutas\\]#",$this->dane,$r); 
         if ($r[1]!='') { 
             $this->wy_info['bb']['gracz'] = $r[1];  
             $this->wy_info['bb']['cena'] = $r[3];  
         }
         if (($login_id=$this->getIdUserByLogin($r[1]))>0){      
             $this->wy_info["seat$login_id"]['status']="bb";
         }
         
         preg_match("#Seat ([0-9]{1,1}) is the button#", $this->dane,$r);         
         if ($r[1]>0){
             $this->wy_info['button'] = $r[1];   
             $this->wy_info["seat$r[1]"]['button']="1";
         }
         
         preg_match("#$this->waluta$this->kasa $this->walutas NL#", $this->dane, $r );
         if ($r[2]>0){
             $this->wy_info['bb']['stawka'] = preg_replace('{([^0-9.])}','',$r[2]) / 100;
         }
         
         
//------------------------- KARTY ----------------------------------------------
         
         preg_match( '#\*\* Dealing Flop \*\* \[ '.$this->karta.', '.$this->karta.', '.$this->karta.' \]#', $this->dane, $r );        
         if ($this->jakakarta($r[1])>0) $this->wy_info[k3]=$this->jakakarta($r[1]);
         if ($this->jakakarta($r[2])>0) $this->wy_info[k4]=$this->jakakarta($r[2]);
         if ($this->jakakarta($r[3])>0) $this->wy_info[k5]=$this->jakakarta($r[3]);
         
         preg_match( '#\*\* Dealing Turn \*\* \[ '.$this->karta.' \]#', $this->dane, $r );        
         if ($this->jakakarta($r[1])>0) $this->wy_info[k6]=$this->jakakarta($r[1]);
         
         preg_match( "#\*\* Dealing River \*\* \[ $this->karta \]#", $this->dane, $r );        
         if ($this->jakakarta($r[1])>0) $this->wy_info['k7']=$this->jakakarta($r[1]);
                  
//------------------------- TEST ----------------------------------------------
        // preg_match('#blind '.$this->ciagruchow.'\*\* Dealing#', $this->dane, $r );  
        // $tab = explode("\n", $r[1]); 
        // foreach ($tab as $value){
             //echo "value = $value <br/>";
        // }
        // $this->ruchyGRC( split("\n", $r[1]),'start');
         
//------------------------- RUCHY ----------------------------------------------
         
         preg_match( '#\*\* Dealing down cards \*\*\n'.$this->ciagruchow.'(\*\*|Game \#|\n\n)#', $this->dane, $r );       
         $tab = explode("\n", $r[1]);   
        // echo "r[1]= $r[1]";
         foreach ($tab as $value){
             //echo "value = $value <br/>";
             preg_match( '#Dealt to '.$this->gracz.' \[ '.$this->karta.', '.$this->karta.' \]#',$value,$r);
             if ($r[1]!=""){ $this->wy_info[diler] = $r[1]; }
             if ($this->jakakarta($r[2])>0){ $this->wy_info['k1'] = $this->jakakarta($r[2]); }
             if ($this->jakakarta($r[3])>0){ $this->wy_info['k2'] = $this->jakakarta($r[3]); }
         }         
         $this->ruchyGRC($tab,'preflop');
         
         preg_match('#\*\* Dealing Flop \*\* \[ '.$this->karta.', '.$this->karta.', '.$this->karta.' \]'.$this->ciagruchow.'(\*\*|)#', $this->dane, $r );  
         $this->ruchyGRC( split("\n", $r[4]),'flop');
         
         preg_match('#\*\* Dealing Turn \*\* \[ '.$this->karta.' \]'.$this->ciagruchow.'(\*\*|)#', $this->dane, $r );  
         $this->ruchyGRC(  split("\n", $r[2]),'turn');
         
         preg_match('#\*\* Dealing River \*\* \[ '.$this->karta.' \]'.$this->ciagruchow.'#', $this->dane, $r );  
         $this->ruchyGRC( split("\n", $r[2]),'river');
                  
//------------------------- SUMMARY --------------------------------------------

         preg_match( '#(River|Turn|Flop|cards) \*\*'.$this->ciagruchow.'(\n\n|\n\r\n\r|\r\r)#',$this->dane,$r);  
         //echo "sss = $r[2] <br/>";
         $tab = split("\n", $r[2]);         
         $id = 0; foreach ($tab as $value){
             if ($value=="") {continue;}
             $this->wy_summary[$id++] = $value;            
             preg_match("#$this->gracz (shows|mucks|doesn't show) \[ $this->karta, $this->karta \]#", $value, $r );    
             //echo "= $value r3 = $r[3] | r1=$r[1] <br/>";
             if (($r[3]!="") and (($login_id=$this->getIdUserByLogin($r[1]))>0)){                 
                 $this->wy_info["seat$login_id"]['k1']=$this->jakakarta($r[3]);
                 $this->wy_info["seat$login_id"]['k2']=$this->jakakarta($r[4]);
             }
             
             preg_match("#$this->gracz wins $this->waluta$this->kasa $this->walutas#",$value,$r);   
             //echo "<br/>$value ======== $r[1] ";
             if ($r[1]!=''){
                 $this->wy_info['wygrani']+=1;                 
                 for ($xx=1;$xx<=9;$xx++){
                     if ($this->wy_info["seat$xx"]['user']==$r[1]){
                         $this->wy_info["seat$xx"]['wygrany']=1;
                     }
                 }                 
             }    
         }
         
//------------------------- LAST --------------------------------------------
if ($this->wy_ruchy!=''){
    foreach ($this->wy_ruchy as $rr => $vv){    
        foreach ($this->wy_ruchy["$rr"] as $ruch){
            $id = $this->getIdUserByLogin($ruch['gracz']);   
            $ruch['cena'] = preg_replace('{([^0-9.])}','',$ruch['cena']);
            if ($rr == "preflop"){
               $this->wy_info["seat$id"]['last_preflop_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_preflop_cena'] += $ruch['cena'];
            }
            if ($rr == "flop"){
               $this->wy_info["seat$id"]['last_flop_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_flop_cena'] += $ruch['cena'];
               //echo "s$id = $ruch[cena]  ===== ".$this->wy_info["seat$id"][last_flop_cena]." <br/>";
            }
            if ($rr == "turn"){
               $this->wy_info["seat$id"]['last_turn_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_turn_cena'] += $ruch['cena'];
            }
            if ($rr == "river"){
               $this->wy_info["seat$id"]['last_river_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_river_cena'] += $ruch['cena'];
            }
        }
    }
}   

          
}   
   

    public function rozbijianalizuj($plik){
        $this->plik = preg_replace('['.chr(13).']', '', $plik);        
        $this->tab = explode("#Game No :", $this->plik);        
        $uklad = 0;        
        foreach ($this->tab as $this->dane){   
            if (trim($this->dane)==""){continue;}
            if (strlen(trim($this->dane))<30){continue;}

            $this->resetuj();
            $this->info();
            $this->wysumuj();
            
            $this->getLine(); 
            $this->getStrength();
            $this->getSize();
            
            $uklad++;     
            $this->wy_plik[$uklad]['dane']=$this->dane;
            $this->wy_plik[$uklad]['info']= $this->wy_info;
            $this->wy_plik[$uklad]['ruchy']= $this->wy_ruchy;
            $this->wy_plik[$uklad]['summary']= $this->wy_summary;
               
        }
        return $this->wy_plik;        
    }
    
     
    
}
?>