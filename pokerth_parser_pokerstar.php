<?

require_once 'sila.php';
require_once 'pokerth_parser.php';

class poker_parser_pokerstar extends poker_parser {
    
    public $line = "(\n|\r|\r\n|\n\r)";
    public $karta = "([0-9A-T][a-z])";
    public $gracz = "([A-Za-z0-9\+-\■\¿\?\-\\\$_\'\ \.]+)"; 
    public $ciagruchow = '([\+-\■\!-\)A-Za-z0-9\¿\?\n\r_\[\]\)\(\$\,\#\.\ \:\\\']+)'; 
    public $ruch = '(calls|folds|raises|bets|checks|check-raise|allin)';
    public $kasa = '([0-9\.\,]+)';
    public $waluta = '(\$|\€|)';   

    public function ruchyGRC($tab,$numer_etapu){
         $id=0;foreach($tab as $value){ 
             if ($value=="") {continue;}
             $id++;
             preg_match("#$this->gracz: $this->ruch $this->waluta$this->kasa#",$value,$r);              
             if ($r[1]==""){ preg_match("#$this->gracz: $this->ruch#",$value,$r); }             
             if ($r[1]!=""){ $this->wy_ruchy[$numer_etapu][$id]['gracz'] = $r[1]; }
             if ($r[2]!=""){ $this->wy_ruchy[$numer_etapu][$id]['ruch'] = $r[2]; }
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
         $this->wy_info['parser_name'] = "PokerStars";  
         
         preg_match( '#([0-9]{1,1})-max Seat#', $this->dane, $r ); 
         if ($r[1]>0){
            $this->wy_info['players'] = $r[1];   
         }
       
         preg_match( "#$this->ciagruchow\*\*\* HOLE#", $this->dane, $tab ); 
         
         //echo "<pre>";
         //print_r($tab[0]);
         //echo "</pre>";
         
         $tab = explode("\n", $tab[0]);         
         foreach ($tab as $value) {          
             //echo "valuer = $value <br/>";
             preg_match("#Seat ([0-9]{1,1}): $this->gracz \\($this->waluta$this->kasa#", $value, $r );             
             if ($r[2]!="") {
                 $this->wy_info["seat$r[1]"]['user']=$r[2];
                 //echo "gracz=".$this->wy_info["seat$r[1]"][user]."<br/>";
                 $this->wy_info["seat$r[1]"]['cena']=preg_replace('{([^0-9.])}','',$r[4]);
             }             
         }
         
         preg_match( "#$this->gracz: posts small blind $this->waluta$this->kasa#", $this->dane, $r ); 
         if ($r[1]!='') {
             $this->wy_info['sb']['gracz'] = $r[1];  
             $this->wy_info['sb']['cena'] = preg_replace('{([^0-9.])}','',$r[3]); 
         }
         if (($login_id=$this->getIdUserByLogin($r[1]))>0){      
             $this->wy_info["seat$login_id"]['status']="sb";
         }

         preg_match( "#$this->gracz: posts big blind $this->waluta$this->kasa#", $this->dane, $r ); 
         if ($r[1]!='') { 
             $this->wy_info['bb']['gracz'] = $r[1];  
             $this->wy_info['bb']['cena'] = preg_replace('{([^0-9.])}','',$r[3]);
         }
         if (($login_id=$this->getIdUserByLogin($r[1]))>0){   
             $this->wy_info["seat$login_id"]['status']="bb";
         }
         
         preg_match("#([0-9]{1,1}) is the button#", $this->dane, $r );
         if ($r[1]>0){
             $this->wy_info['button'] = $r[1];
             $this->wy_info["seat$r[1]"]['button']="1";
         }
         
         preg_match("#(Fix|No|Pot) Limit \\($this->waluta$this->kasa/$this->waluta$this->kasa#", $this->dane, $r );
         if ($r[3]>0){$this->wy_info['sb']['stawka'] = preg_replace('{([^0-9.])}','',$r[3]);}
         if ($r[5]>0){$this->wy_info['bb']['stawka'] = preg_replace('{([^0-9.])}','',$r[5]);}
         
//------------------------- KARTY ----------------------------------------------
         
         preg_match("#\*\*\* FLOP \*\*\* \[$this->karta $this->karta $this->karta\]#", $this->dane, $r );        
         if ($this->jakakarta($r[1])>0) $this->wy_info['k3']=$this->jakakarta($r[1]);
         if ($this->jakakarta($r[2])>0) $this->wy_info['k4']=$this->jakakarta($r[2]);
         if ($this->jakakarta($r[3])>0) $this->wy_info['k5']=$this->jakakarta($r[3]);
         
         preg_match("#\*\*\* TURN \*\*\* \[$this->karta $this->karta $this->karta\] \[$this->karta\]#", $this->dane, $r );       
         if ($this->jakakarta($r[4])>0) $this->wy_info['k6']=$this->jakakarta($r[4]);
         
         preg_match("#\*\*\* RIVER \*\*\* \[$this->karta $this->karta $this->karta $this->karta\] \[$this->karta\]#", $this->dane, $r );        
         if ($this->jakakarta($r[5])>0) $this->wy_info['k7']=$this->jakakarta($r[5]);
         
//------------------------- TEST ----------------------------------------------
         preg_match('#blind '.$this->ciagruchow.'\*\* Dealing#', $this->dane, $r );  
         $tab = explode("\n", $r[1]); 
         foreach ($tab as $value){
             //echo "value = $value <br/>";
         }
         $this->ruchyGRC( split("\n", $r[1]),'start');         
         
         
//------------------------- RUCHY ----------------------------------------------
         preg_match( "#\*\*\* HOLE CARDS \*\*\*$this->ciagruchow\*#", $this->dane, $r ); 
         $tab = explode("\n", $r[1]);        
         $this->ruchyGRC($tab,'preflop');
         
         preg_match("#\*\*\* FLOP \*\*\* \[$this->karta $this->karta $this->karta\]$this->ciagruchow\*#", $this->dane, $r );  
         $this->ruchyGRC( split("\n", $r[4]),'flop');
         
         preg_match("#\*\*\* TURN \*\*\* \[$this->karta $this->karta $this->karta\] \[$this->karta\]$this->ciagruchow\*#", $this->dane, $r );  
         $this->ruchyGRC( split("\n", $r[5]),'turn');
         
         preg_match("#\*\*\* RIVER \*\*\* \[$this->karta $this->karta $this->karta $this->karta\] \[$this->karta\]$this->ciagruchow\*#", $this->dane, $r );  
         $this->ruchyGRC( split("\n", $r[6]),'river');
                  
//------------------------- SUMMARY --------------------------------------------

         preg_match( '#\*\*\* SUMMARY \*\*\*'.$this->ciagruchow.'#',$this->dane,$r);  
         $tab = split("\n", $r[1]);         
         $id = 0; foreach ($tab as $value){
             if ($value=="") {continue;}
             $this->wy_summary[$id++] = $value;
             preg_match("#Seat ([1-9]{1,1}): $this->gracz (folded|collected|\()#",$value, $r);
             $login_id  = $this->getUserIdByLine($value);
             preg_match("#Seat ([1-9]{1,1}): $this->gracz (\(button\) |)(\(small blind\) |\(big blind\) |)showed \[$this->karta $this->karta\]#", $value, $r );
             if (($r[5]!="") and (($login_id)>0)){  
                 $this->wy_info["seat$login_id"][k1]=$this->jakakarta($r[5]);
                 $this->wy_info["seat$login_id"][k2]=$this->jakakarta($r[6]);
             }
             if ($this->getWonCollected($value)){                 
                 $this->wy_info[wygrani]+=1;                 
                 for ($xx=1;$xx<=9;$xx++){
                     $this->wy_info["seat$login_id"][wygrany]=1;
                 }                 
             }
         }
             
//------------------------- LAST --------------------------------------------
if ($this->wy_ruchy!=''){
    foreach ($this->wy_ruchy as $rr => $vv){    
        foreach ($this->wy_ruchy["$rr"] as $ruch){
            $id = $this->getIdUserByLogin($ruch['gracz']);  
            $ruch[cena] = preg_replace('{([^0-9.])}','',$ruch['cena']);
            if ($rr == "preflop"){
               $this->wy_info["seat$id"]['last_preflop_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_preflop_cena'] = $ruch['cena'];
            }
            if ($rr == "flop"){
               $this->wy_info["seat$id"]['last_flop_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_flop_cena'] = $ruch['cena'];
            }
            if ($rr == "turn"){
               $this->wy_info["seat$id"]['last_turn_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_turn_cena'] = $ruch['cena'];
            }
            if ($rr == "river"){
               $this->wy_info["seat$id"]['last_river_ruch'] = $ruch['ruch'];
               $this->wy_info["seat$id"]['last_river_cena'] = $ruch['cena'];
            }
        }
    }
}         
         
    }   
    
    public function getWonCollected($line){
            $pos = strrpos($line, "won");
            if ($pos === false) {} else {
                return 1;
            }
            $pos = strrpos($line, "collected");
            if ($pos === false) {} else {  
                return 1;
            }
        return 0;
    }    
    
    public function getUserIdByLine($line){
        //echo "* line = $line <br/>";        
        for ($xx=1;$xx<=9;$xx++){
            $szuk = $this->wy_info["seat$xx"]['user'];
            //echo "l = ".$this->wy_info["seat$xx"][user]."<br/>";
            $pos = strrpos($line, $this->wy_info["seat$xx"]['user']);
            if ($pos === false) {} else {                
                return $xx;                
            }
        }
        return 0;        
    }

//------------------------------------------------------------------------------

    public function rozbijianalizuj($plik){
        $this->tab = explode("PokerStars Hand", $plik);        
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