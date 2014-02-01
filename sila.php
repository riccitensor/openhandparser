<? 

class poker_strenght {
    public $karty=array(); //orginalna
    public $kkk=array();//tymczasowa
    public $ooo=array();//tymczasowa
    public $temp2=array();//tymczasowa
    public $tabpom1=array();//tymczasowa
    public $tabpom2=array();//tymczasowa
    public $tabpom3=array();//tymczasowa
    public $mt=0;
    public $mt2=0;
    public $mt3=0;
    public $mt4=0;
    public $returned_typ=0;
    public $returned_color=0;
    public $debug=0;
    public $ass=0;
    public $oesd=0;
    
    public function __construct(){}
    
    public function zeruj(){
        for ($xx=1;$xx<=7;$xx++){
            $this->kkk[$xx]=0;
            $this->ooo[$xx]=0;
            $this->tabpom1[$xx]=0;
            $this->tabpom2[$xx]=0;
            $this->tabpom3[$xx]=0;
        }
        for ($xx=0;$xx<=19;$xx++){
            $this->temp2[$xx]=0;
        }
        $this->mt=0;
        $this->mt2=0;
        $this->mt3=0;
        $this->mt4=0;        
        $this->returned_typ=0;
        $this->returned_color=0;        
        $this->ass=0;
        $this->oesd=0;        
    }
    
    public function wspolny_mianownik(){
      $xkkk2=array();//tablic ktora zmieni 15,16,17 na mniejsze liczby 2,3,4
       //zamiana do wspolnego mianwnika 2-14
       for ($xx=1;$xx<=7;$xx++){
         if (($this->karty[$xx]>=2) & ($this->karty[$xx]<=14)){  $xkkk2[$xx]=$this->karty[$xx];  }
         if (($this->karty[$xx]>=15) & ($this->karty[$xx]<=27)){  $xkkk2[$xx]=$this->karty[$xx]-13;  }
         if (($this->karty[$xx]>=28) & ($this->karty[$xx]<=40)){  $xkkk2[$xx]=$this->karty[$xx]-26;  }
         if (($this->karty[$xx]>=41) & ($this->karty[$xx]<=53)){  $xkkk2[$xx]=$this->karty[$xx]-39;  }
       }
       return $xkkk2;
    }
    

    public function ustawKarty($moja1,$moja2,$flop1=0,$flop2=0,$flop3=0,$turn=0,$river=0){
        $this->karty[1]=$flop1+0;
        $this->karty[2]=$flop2+0;
        $this->karty[3]=$flop3+0;
        $this->karty[4]=$turn+0;
        $this->karty[5]=$river+0;
        $this->karty[6]=$moja1+0;
        $this->karty[7]=$moja2+0;
        
        if ($this->debug==1) {
            echo "uK $moja1, $moja2, $flop1, $flop2, $flop3, $turn, $river <br/>";
            echo "karty {$this->karty[1]}, {$this->karty[2]}, {$this->karty[3]}, {$this->karty[4]}, {$this->karty[5]}, {$this->karty[6]}, {$this->karty[7]} <br/>";
        }
        if ($this->uklad1()) return 1;
        if ($this->uklad2()) return 2;
        if ($this->uklad3()) return 3;
        if ($this->uklad4()) return 4;
        if ($this->uklad5()) return 5;
        if ($this->uklad6()) return 6;
        if ($this->uklad7()) return 7;
        if ($this->uklad8()) return 8;
        if ($this->uklad9()) return 9;
        if ($this->uklad10()) return 10;        
    }
    
    public function coMa($moja1,$moja2,$flop1=0,$flop2=0,$flop3=0,$turn=0,$river=0){
        $this->karty[1]=$flop1+0;
        $this->karty[2]=$flop2+0;
        $this->karty[3]=$flop3+0;
        $this->karty[4]=$turn+0;
        $this->karty[5]=$river+0;
        $this->karty[6]=$moja1+0;
        $this->karty[7]=$moja2+0;  
        
        $tab=array();        
        $wynik11=$this->uklad11();
        $wynik12=$this->uklad12();
        $wynik13=0;        
        if ($this->uklad13()==1){
            if ($this->ass){
                $wynik13=2;
            }else{
                $wynik13=1;
            }
        }
        $wynik14=0;
        if ($this->uklad14()==1){
            if ($this->ass){
                $wynik14=2;
            }else{
                $wynik14=1;
            }
        }
        $wynik15=0;
        if ($this->uklad15()==1){//1L 2LR 3R   4L-A 5LR-A 6R-A
            if ($this->ass){
                $wynik15+=3;
            }
            $wynik15+=$this->oesd;
        }
        if ($wynik11>0) $tab[u11]=$wynik11;
        if ($wynik12>0) $tab[u12]=$wynik12;
        if ($wynik13>0) $tab[u13]=$wynik13;
        if ($wynik14>0) $tab[u14]=$wynik14;
        if ($wynik15>0) $tab[u15]=$wynik15;         
        $tab[ass]=$this->ass;        
        return $tab;
    }
    
    
/*******************************************************************************
*
*                         1. POKER KROLEWSKI
*
*******************************************************************************/
    public function uklad1(){
        $this->zeruj();
        $mmx=0;
        for ($mx=1;$mx<=4;$mx++){
            if ($mx==1){$mmx=0;}
            if ($mx==2){$mmx=-13;}
            if ($mx==3){$mmx=-26;}
            if ($mx==4){$mmx=-39;}
            $this->kkk[1]=$this->karty[1]+$mmx;
            $this->kkk[2]=$this->karty[2]+$mmx;
            $this->kkk[3]=$this->karty[3]+$mmx;
            $this->kkk[4]=$this->karty[4]+$mmx;
            $this->kkk[5]=$this->karty[5]+$mmx;
            $this->kkk[6]=$this->karty[6]+$mmx;
            $this->kkk[7]=$this->karty[7]+$mmx;
            for ($i=1;$i<=7;$i++){
                if ($this->kkk[$i]==10){
                    $this->kkk[$i]=0;
                    for ($j=1;$j<=7;$j++){
                        if ($this->kkk[$j]==11){
                            $this->kkk[$j]=0;
                            for ($k=1;$k<=7;$k++){
                                if ($this->kkk[$k]==12){
                                    $this->kkk[$k]=0;
                                    for ($l=1;$l<=7;$l++){
                                        if ($this->kkk[$l]==13){
                                            $this->kkk[$l]=0;
                                            for ($m=1;$m<=7;$m++){
                                                if ($this->kkk[$m]==14){
                                                    $this->kkk[$m]=0;
                                                    if (($this->kkk[6]==0) ||($this->kkk[7]==0)) {                              
                                                        $this->returned_typ=1;
                                                        $this->returned_color=$mx;
                                                        if ($this->debug==1) {echo " FUNCTION (1) pokerkrolewski -> true <br/>";} 
                                                        return 1;                                                                                     
                                                    } else {
                                                        if ($this->debug==1) {echo " FUNCTION (1) pokerkrolewski -> false <br/>";}
                                                        return 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($this->debug==1) {echo " FUNCTION (1) pokerkrolewski -> false <br/>";}
        return 0;
    }
    
    
/*******************************************************************************
*
*                         2. POKER
*
*******************************************************************************/
    public function uklad2(){
        $this->zeruj();
        $mmx=0;
        for ($mx=1;$mx<=4;$mx++){
            if ($mx==1){$mmx=0;}
            if ($mx==2){$mmx=-13;}
            if ($mx==3){$mmx=-26;}
            if ($mx==4){$mmx=-39;}
            for ($nn=0;$nn<=7;$nn++){
                $this->kkk[1]=$this->karty[1]+$mmx;
                $this->kkk[2]=$this->karty[2]+$mmx;
                $this->kkk[3]=$this->karty[3]+$mmx;
                $this->kkk[4]=$this->karty[4]+$mmx;
                $this->kkk[5]=$this->karty[5]+$mmx;
                $this->kkk[6]=$this->karty[6]+$mmx;
                $this->kkk[7]=$this->karty[7]+$mmx;
                for ($i=1;$i<=7;$i++){          
                    if ($this->kkk[$i]==(2+$nn)){
                        $this->ooo[2]=$this->kkk[$i];
                        $this->kkk[$i]=0;
                        for ($j=1;$j<=7;$j++){                            
                            if ($this->kkk[$j]==3+$nn){
                                $this->kkk[$j]=0;
                                for ($k=1;$k<=7;$k++){
                                    if ($this->kkk[$k]==4+$nn){
                                        $this->kkk[$k]=0;
                                        for ($l=1;$l<=7;$l++){
                                            if ($this->kkk[$l]==5+$nn){
                                                $this->kkk[$l]=0;
                                                for ($m=1;$m<=7;$m++){
                                                    if ($this->kkk[$m]==6+$nn){
                                                        $this->kkk[$m]=0;
                                                        if (($this->kkk[6]==0) || ($this->kkk[7]==0)) {
                                                            $this->returned_typ=2;
                                                            //expor[2]=2+$nn; //
                                                            //expor[3]=$mx;
                                                            //szukacz=1;
                                                            if ($this->debug==1) {echo " FUNCTION (2) poker -> true <br/>"; }
                                                            return 1;
                                                        } else {
                                                            if ($this->debug==1) {echo " FUNCTION (2) poker -> false <br/>"; }
                                                            return 0;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($this->debug==1) {echo " FUNCTION (2) poker -> false <br/>"; }
        return 0;
    }

/*******************************************************************************
*
*                         3. KARETA
*
*******************************************************************************/
    
    public function uklad3(){
        $this->zeruj();
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];

        //powzcuanie kart do odpowiednich stoisk w talbicy
        for ($zz=0;$zz<=12;$zz++){
            for ($xx=1;$xx<=7;$xx++){
                if (($this->kkk[$xx]==2+$zz) || ($this->kkk[$xx]==15+$zz) || ($this->kkk[$xx]==28+$zz) || ($this->kkk[$xx]==41+$zz)){
                    $this->temp2[$zz+1]=$this->temp2[$zz+1]+1;
                }
            }
        }
        
        //wyszukanie najwyzszego elemntu w tablicy
        for ($zz=1;$zz<=13;$zz++){
          if ($this->temp2[$zz]==4) {
              $this->mt=$zz+1;
        }}
        //zamiana do wspolnego mianwnika 2-14
        /////////System.arraycopy(wspolny_mianownik(impor), 0, tabpom1, 0,  10);
        //tabpom1=wspolny_mianownik(impor);
        $this->tabpom1=$this->wspolny_mianownik();

        //znalezienie w puli czworki i wyzerowanie jej
        for ($zz=1;$zz<=4;$zz++){
            for ($xx=1;$xx<=7;$xx++){
                if ($this->tabpom1[$xx]==$this->mt){
                    $this->tabpom1[$xx]=0;
                    break;
                }
            }
        }

        //sorotowanie od najwiekszej do najmniejszej
        $this->tabpom3[1]=$this->tabpom1[1];
        $this->tabpom3[2]=$this->tabpom1[2];
        $this->tabpom3[3]=$this->tabpom1[3];
        $this->tabpom3[4]=$this->tabpom1[4];
        $this->tabpom3[5]=$this->tabpom1[5];
        $this->tabpom3[6]=$this->tabpom1[6];
        $this->tabpom3[7]=$this->tabpom1[7];

        //znajduje najwyzsza karte poza kareta
        $pom=0;
        $el=0; //element do wymazania
        for ($a=1;$a<=7;$a++){
           if ($pom<$this->tabpom3[$a]){
                 $pom=$this->tabpom3[$a];  //pom2=i;
                 $el=$a;
           }
        }
        $this->tabpom3[$el]=0;

        //sprawdzenie czy karta 1 znajduje sie w Twoich rekach
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];

        //System.out.println("test1: "+kkk[1]+"/"+kkk[2]+"/" +kkk[3]+"/" +kkk[4]+"/" +kkk[5]+"/" +kkk[6]+"/" +kkk[7]);
        //System.out.println("test2: "+tabpom2[1]+"/"+tabpom2[2]+"/" +tabpom2[3]+"/" +tabpom2[4]+"/" +tabpom2[5]+"/" +tabpom2[6]+"/" +tabpom2[7]);
        //System.out.println("test3: "+tabpom1[1]+"/"+tabpom1[2]+"/" +tabpom1[3]+"/" +tabpom1[4]+"/" +tabpom1[5]+"/" +tabpom1[6]+"/" +tabpom1[7]);
        $kontrol2=0;
        if (($this->mt==$this->kkk[6]) || ($this->mt==$this->kkk[7])) {
           $kontrol2=1;
        }
        if (($this->tabpom3[6]==0) || ($this->tabpom3[7]==0)) { //jesli ktoras z twoich kart byla wzieta to ma teraz 0
           $kontrol2=1;
        }

        if ($this->mt!=0) {
            if ((($this->tabpom1[6]==0) || ($this->tabpom1[7]==0) || ($kontrol2 == 1)) && ($this->mt!=0))  {  
                // expor[1]=3;
                // expor[2]=mt;
                // expor[3]=pom;
                // szukacz=1;
                $this->returned_typ=3;
                if ($this->debug==1) {echo " FUNCTION (3) kareta -> true <br/>"; }
                return 1;

            } else { 
                if ($this->debug==1) {echo " FUNCTION (3) kareta -> false <br/>"; }
                return 0;
            } 
        }      
        if ($this->debug==1) {echo " FUNCTION (3) kareta -> false <br/>"; }
        return 0;
    }
/*******************************************************************************
*
*                         4. FULL
*
*******************************************************************************/
    public function uklad4(){
        $this->zeruj();
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];


        
        //powzcuanie kart do odpowiednich stoisk w talbicy
        for ($zz=2;$zz<=14;$zz++){
            for ($xx=1;$xx<=7;$xx++){
                if (($this->kkk[$xx]==$zz) || ($this->kkk[$xx]==13+$zz) || ($this->kkk[$xx]==26+$zz) || ($this->kkk[$xx]==39+$zz)){
                    $this->temp2[$zz]=$this->temp2[$zz]+1;
                }
            }
        }

        //wyszukanie par i trojek
        for ($zz=2;$zz<=14;$zz++){
                if ($this->temp2[$zz]==3) {
                $this->temp2[$zz]=0;
                $this->mt=$zz;
                break;
            }
        }

        for ($zz=2;$zz<=14;$zz++){
            if ($this->temp2[$zz]==3) {
                $this->temp2[$zz]=0;
                $this->mt3=$zz;
                break;
            }
        }

        for ($zz=2;$zz<=14;$zz++){
            if ($this->temp2[$zz]==2) {
                $this->temp2[$zz]=0;
                $this->mt2=$zz;
                break;
            }
        }

        for ($zz=2;$zz<=14;$zz++){
            if ($this->temp2[$zz]==2) {
                $this->temp2[$zz]=0;
                $this->mt4=$zz;
                break;
            }
        }

        $this->tabpom1=$this->wspolny_mianownik();

        

        
        

        if ($this->mt>$this->mt3) {
        //wyzerowanie kart ktore tworza uklad
            for ($xx=1;$xx<=7;$xx++) {
                if ($this->tabpom1[$xx]==$this->mt) $this->tabpom1[$xx]=0;
            }
            if ($this->mt3!=0) {
                //wyzerowanie kart ktore tworza uklad
                for ($xx=1,$licznik=1;$xx<=7;$xx++) {
                    if ($this->tabpom1[$xx]==$this->mt3) {
                        if ($licznik<3){
                            $this->tabpom1[$xx]=0;
                            $licznik++;
                        }
                    }
                }
            }
        } else {
            $tempa=$this->mt;  //gorsze karty
            $tempb=$this->mt3; //leszpe karty
            $this->mt=$tempb; //leszpe karty
            $this->mt2=$tempa; //gorsze karty
            //wyzerowanie kart ktore tworza uklad
            for ($xx=1;$xx<=7;$xx++) {
                if ($this->tabpom1[$xx]==$this->mt) $this->tabpom1[$xx]=0;
            }
            //wyzerowanie kart ktore tworza uklad
            for ($xx=1, $licznik=1;$xx<=7;$xx++) {
                if ($this->tabpom1[$xx]==$this->mt2){
                    if ($licznik<3) {
                        $this->tabpom1[$xx]=0;
                        $licznik++;
                    }
                }
            }
        }

        
        
        
        
        if ($this->mt3==0) {
            if ($this->mt2>$this->mt4) {
            //wyzerowanie kart ktore tworza uklad
                for ($xx=1;$xx<=7;$xx++) {
                if ($this->tabpom1[$xx]==$this->mt2)
                $this->tabpom1[$xx]=0;
                }
            } else {
                $tempb=$this->mt4; //leszpe karty
                $this->mt2=$tempb; //leszpe karty
                //wyzerowanie kart ktore tworza uklad
                for ($xx=1;$xx<=7;$xx++) {
                    if ($this->tabpom1[$xx]==$this->mt2) $this->tabpom1[$xx]=0;
                }
            }
        }

        //sprawdzenie czy karta 1 i 2 znajduje sie w Twoich rekach
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];

        
        if (($this->mt!=0) && ($this->mt2!=0)) {
            if (($this->tabpom1[6]==0) || ($this->tabpom1[7]==0))  {
                //expor[1]=4;
                //expor[2]=mt;
                //expor[3]=mt2;
                //szukacz=1;
                if ($this->debug==1) {echo " FUNCTION (4) full -> true <br/>"; }
                $this->returned_typ=4;
                return 1;
            } else {
               if ($this->debug==1) { echo " FUNCTION (4) full -> false <br/>"; }
                return 0;
            }
        }
        if ($this->debug==1) {echo " FUNCTION (4) full -> false <br/>"; }
        return 0;
    }
    
/*******************************************************************************
*
*                         5. KOLOR
*
*******************************************************************************/
    public function uklad5(){
        $this->zeruj();
        $mmx=0;
        for ($mx=1;$mx<=4;$mx++){
            if ($mx==1){$mmx=0;}
            if ($mx==2){$mmx=-13;}
            if ($mx==3){$mmx=-26;}
            if ($mx==4){$mmx=-39;}
            $this->kkk[1]=$this->karty[1]+$mmx;
            $this->kkk[2]=$this->karty[2]+$mmx;
            $this->kkk[3]=$this->karty[3]+$mmx;
            $this->kkk[4]=$this->karty[4]+$mmx;
            $this->kkk[5]=$this->karty[5]+$mmx;
            $this->kkk[6]=$this->karty[6]+$mmx;
            $this->kkk[7]=$this->karty[7]+$mmx;

            for ($n=2;$n<=9;$n++){
                $this->ooo[$n]=0;
            }
            $j=2;
            for ($i=1;$i<=7;$i++){
                if (($this->kkk[$i]>=2) & ($this->kkk[$i]<=14)){
                    $this->ooo[$j]=$this->kkk[$i];
                    $this->kkk[$i]=0;
                    $j++;
                }
            }
            
            //jesli znajduje sie 5 elementow tego samego koloru
            if ($this->ooo[6]!=0){
                $this->ooo[1]=5;
                $temp=array();
                $pom=0;
                $pom2=0; //gdzie ostatnio bylo i
                //sortowanie od najwiekszej do najmniejszej
                for ($a=2;$a<=$j;$a++){
                    $i=0;
                    for ($i=2;$i<=$j;$i++){
                        if ($pom<$this->ooo[$i]){
                            $pom=$this->ooo[$i];  $pom2=$i;
                        }
                    }
                    $this->ooo[$pom2]=0;
                    $temp[$a]=$pom;
                    $pom=0;
                }
                $this->tabpom1[1]=$temp[2];
                $this->tabpom1[2]=$temp[3];
                $this->tabpom1[3]=$temp[4];
                $this->tabpom1[4]=$temp[5];
                $this->tabpom1[5]=$temp[6];
                $this->tabpom1[6]=0;
                $this->tabpom1[7]=0;

                $this->kkk[1]=$this->karty[1]+$mmx;
                $this->kkk[2]=$this->karty[2]+$mmx;
                $this->kkk[3]=$this->karty[3]+$mmx;
                $this->kkk[4]=$this->karty[4]+$mmx;
                $this->kkk[5]=$this->karty[5]+$mmx;
                $this->kkk[6]=$this->karty[6]+$mmx;
                $this->kkk[7]=$this->karty[7]+$mmx;

                $kontrol=0;
                //sprawdzenie czy kolory znajduja sie w puli gracza
                for ($x=1;$x<=5;$x++){
                    if (($this->tabpom1[$x]==$this->kkk[6]) || ($this->tabpom1[$x]==$this->kkk[7])){
                        $kontrol=1;
                    }
                }
                if ($kontrol ==1){
                    if ($this->debug==1) {echo " FUNCTION (5) kolor -> true <br/>"; }
                    return 1;
                    //szukacz=1;
                    //expor[1]=5;
                    //expor[2]=tabpom1[1];
                    //expor[3]=tabpom1[2];
                    //expor[4]=tabpom1[3];
                    //expor[5]=tabpom1[4];
                    //expor[6]=tabpom1[5];
                    //expor[7]=mx;
                } else { 
                    if ($this->debug==1) {echo " FUNCTION (5) kolor -> false <br/>"; }
                    return 0;
                }
            }
        }
        if ($this->debug==1) {echo " FUNCTION (5) kolor -> false <br/>"; }
        return 0;
    }
    
/*******************************************************************************
*
*                         6. STRIT
*
*******************************************************************************/
    public function uklad6(){
        $this->zeruj();
$mmx=0;
for ($nn=0;$nn<=7;$nn++){
    //wspolny mianownik
    for ($zz=1;$zz<=7;$zz++){
        $pom=$this->karty[$zz];
        if (($pom>=2) & ($pom<=14)) {$mmx=0;}
        if (($pom>=15) & ($pom<=27)) {$mmx=-13;}
        if (($pom>=28) & ($pom<=40)) {$mmx=-26;}
        if (($pom>=41) & ($pom<=53)) {$mmx=-39;}
        $this->kkk[$zz]=$this->karty[$zz]+$mmx;
    }
for ($i=1;$i<=7;$i++){
  if ($this->kkk[$i]==2+$nn){
      $this->ooo[2]=$this->kkk[$i];
      $this->kkk[$i]=0;
      for ($j=1;$j<=7;$j++){
          if ($this->kkk[$j]==3+$nn){
              $this->kkk[$j]=0;
              for ($k=1;$k<=7;$k++){
                  if ($this->kkk[$k]==4+$nn){
                  $this->kkk[$k]=0;
                   for ($l=1;$l<=7;$l++){
                      if ($this->kkk[$l]==5+$nn){
                          $this->kkk[$l]=0;
                     for ($m=1;$m<=7;$m++){
                          if ($this->kkk[$m]==6+$nn){
                           $this->kkk[$m]=0;
                                      //    if (kkk[6]!=0){ooo[4]=kkk[6]; kkk[6]=0;}
                                      //    if (kkk[7]!=0){ooo[5]=kkk[7]; kkk[7]=0;}
                          if (($this->kkk[6]==0) ||($this->kkk[7]==0)) {
                                    $this->ooo[1]=6;
                                   $this->ooo[3]=0;
                                  $this->ooo[4]=0;
                                  $this->ooo[5]=0;
                                  //expor[1]=6;
                                  //expor[2]=2+nn;
                                  //szukacz=1;
                                  if ($this->debug==1) {echo " FUNCTION (6) strit -> true <br/>"; }
                                  return 1;
                                  
                            } else { 
                              if ($this->debug==1) {echo " FUNCTION (6) strit -> false <br/>"; }
                                  return 0;
                              
                              }


}}}}}}}}}}}


//---- szukanie strita z Asem
$mmx=0;
$this->kkk[1]=$this->karty[1]+$mmx;
                $this->kkk[2]=$this->karty[2]+$mmx;
                $this->kkk[3]=$this->karty[3]+$mmx;
                $this->kkk[4]=$this->karty[4]+$mmx;
                $this->kkk[5]=$this->karty[5]+$mmx;
                $this->kkk[6]=$this->karty[6]+$mmx;
                $this->kkk[7]=$this->karty[7]+$mmx;
    //wspolny mianownik
    for ($zz=1;$zz<=7;$zz++){
        $pom=$this->karty[$zz];
        if (($pom>=2) & ($pom<=14)) {$mmx=0;}
        if (($pom>=15) & ($pom<=27)) {$mmx=-13;}
        if (($pom>=28) & ($pom<=40)) {$mmx=-26;}
        if (($pom>=41) & ($pom<=53)) {$mmx=-39;}
        $this->kkk[$zz]=$this->karty[$zz]+$mmx;
    }
for ($i=1;$i<=7;$i++){
  if ($this->kkk[$i]==14){
      $this->kkk[$i]=0;
      for ($j=1;$j<=7;$j++){
          if ($this->kkk[$j]==2){
              $this->kkk[$j]=0;
              for ($k=1;$k<=7;$k++){
                  if ($this->kkk[$k]==3){
                  $this->kkk[$k]=0;
                   for ($l=1;$l<=7;$l++){
                      if ($this->kkk[$l]==4){
                          $this->kkk[$l]=0;
                    for ($m=1;$m<=7;$m++){
                      if ($this->kkk[$m]==5){
                          $this->kkk[$m]=0;

                          $this->ooo[1]=2;
                          $this->ooo[2]=1;
                          $this->ooo[3]=0;
                          $this->ooo[4]=0;
                          $this->ooo[5]=0;
                          if (($this->kkk[6]==0) ||($this->kkk[7]==0)) {
                                if ($this->debug==1) {echo " FUNCTION (6) true <br/>"; }
                                return 1;
                       } else { 
                           if ($this->debug==1) {echo " FUNCTION (6) false <br/>"; }
                                  return 0;
                           
                           
                           }
}}}}}}}}}}

if ($this->debug==1) {echo " FUNCTION (6) strit -> false  <br/>"; }
return 0;
}

/*******************************************************************************
*
*                         7.
*
*******************************************************************************/
    public function uklad7(){
        $this->zeruj();
        $this->mt=0;
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];
//powzcuanie kart do odpowiednich stoisk w talbicy
for ($zz=0;$zz<=12;$zz++){
  for ($xx=1;$xx<=7;$xx++){
    if (($this->kkk[$xx]==2+$zz) || ($this->kkk[$xx]==15+$zz) || ($this->kkk[$xx]==28+$zz) || ($this->kkk[$xx]==41+$zz)){
        $this->temp2[$zz+1]=$this->temp2[$zz+1]+1;
}}}
//wyszukanie najwyzszego elemntu w tablicy
for ($zz=1;$zz<=13;$zz++){
  if ($this->temp2[$zz]==3) {
      $this->mt=$zz+1;
}}
//zamiana do wspolnego mianwnika 2-14
$this->tabpom1=$this->wspolny_mianownik();

//znalezienie w puli trojki i wyzerowanie jej
for ($zz=1;$zz<=3;$zz++){
    for ($xx=1;$xx<=7;$xx++){
        if ($this->tabpom1[$xx]==$this->mt){
            $this->tabpom1[$xx]=0;
            break;
}}}
//sorotowanie od najwiekszej do najmniejszej
$this->tabpom3[1]=$this->tabpom1[1];
$this->tabpom3[2]=$this->tabpom1[2];
$this->tabpom3[3]=$this->tabpom1[3];
$this->tabpom3[4]=$this->tabpom1[4];
$this->tabpom3[5]=$this->tabpom1[5];
$this->tabpom3[6]=$this->tabpom1[6];
$this->tabpom3[7]=$this->tabpom1[7];

$pom=0;
$pom3=0;

for ($a=1;$a<=7;$a++){
   if ($pom<$this->tabpom3[$a]){
         $pom=$this->tabpom3[$a];
        // tabpom3[a]=0;
         $pom3=$a;
   }
}

$this->ooo[3]=0;
$this->ooo[3]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0; //po znalezieniu najwyzszej karty pierwszej wyzeruj ją

$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[4]=0;
$this->ooo[4]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;


//sprawdzenie czy karta 1 i 2 znajduje sie w Twoich rekach
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];
$kontrol2=0;
if (($this->tabpom3[6]==0) || ($this->tabpom3[7]==0)) {
   $kontrol2=1;
}

if ($this->mt!=0) {
 if (($this->tabpom1[6]==0) || ($this->tabpom1[7]==0) || $kontrol2 == 1)  {
     //expor[1]=7;
     //expor[2]=mt;
     //expor[3]=ooo[3];
     //expor[4]=ooo[4];
     //szukacz=1;
     if ($this->debug==1) {echo " FUNCTION (7) trojka -> true <br/>"; }
     return 1;
 } else { 
   if ($this->debug==1) {echo " FUNCTION (7) trojka -> false <br/>"; }
     return 1;
     }
}

if ($this->debug==1) {echo " FUNCTION (7) trojka -> false <br/>"; }
}

/*******************************************************************************
*
*                         8.
*
*******************************************************************************/
    public function uklad8(){
        $this->zeruj();
        
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];


//powzcuanie kart do odpowiednich stoisk w talbicy
for ($zz=0;$zz<=12;$zz++){
  for ($xx=1;$xx<=7;$xx++){
    if (($this->kkk[$xx]==2+$zz) || ($this->kkk[$xx]==15+$zz) || ($this->kkk[$xx]==28+$zz) || ($this->kkk[$xx]==41+$zz)){
        $this->temp2[$zz+1]=$this->temp2[$zz+1]+1;
}}}
//wyszukanie najwyzszego elemntu w tablicy
for ($zz=1;$zz<=13;$zz++){
  if ($this->temp2[$zz]==2) {
      $this->mt=$zz+1;
}}
for ($zz=1;$zz<=13;$zz++){
  if (($this->temp2[$zz]==2) && ($zz!=$this->mt-1)) {
      $this->mt2=$zz+1;
}}
//zamiana do wspolnego mianwnika 2-14
$this->tabpom1=$this->wspolny_mianownik();

//znalezienie w puli dwojki i wyzerowanie jej
for ($zz=1;$zz<=2;$zz++){
    for ($xx=1;$xx<=7;$xx++){
        if ($this->tabpom1[$xx]==$this->mt){
            $this->tabpom1[$xx]=0;
            break;
}}}
for ($zz=1;$zz<=2;$zz++){
    for ($xx=1;$xx<=7;$xx++){
        if ($this->tabpom1[$xx]==$this->mt2){
            $this->tabpom1[$xx]=0;
            break;
}}}

//sorotowanie od najwiekszej do najmniejszej
$this->tabpom3[1]=$this->tabpom1[1];
$this->tabpom3[2]=$this->tabpom1[2];
$this->tabpom3[3]=$this->tabpom1[3];
$this->tabpom3[4]=$this->tabpom1[4];
$this->tabpom3[5]=$this->tabpom1[5];
$this->tabpom3[6]=$this->tabpom1[6];
$this->tabpom3[7]=$this->tabpom1[7];
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[4]=0;
$this->ooo[4]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;

//texta.append("8@ dwie karty: test tabpom2 ab "+tabpom2[1]+"/"+tabpom2[2]+"/"+tabpom2[3]+"/"+tabpom2[4]+"/"+tabpom2[5]+"/"+tabpom2[6]+"/"+tabpom2[7]+"/"+"\n");

//sprawdzenie czy karta 1 i 2 znajduje sie w Twoich rekach
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];


$kontrol2=0;
if (($this->tabpom3[6]==0) || ($this->tabpom3[7]==0)) {
   $kontrol2=1;
}
if (($this->mt!=0) && ($this->mt2!=0) && ($this->mt2!=$this->mt)) {
 if (($this->tabpom1[6]==0) || ($this->tabpom1[7]==0) || $kontrol2 == 1)  {
     //expor[1]=8;
     if ($this->mt2>$this->mt) { //expor[2]=mt2; expor[3]=mt; 
     
     }
     if ($this->mt>$this->mt2) { //expor[2]=mt; expor[3]=mt2; 
     
     }
     //expor[4]=ooo[4];
    // szukacz=1;
     if ($this->debug==1) {echo " FUNCTION (8) dwiepary -> true <br/>"; }
     return 1;
 } else {
     if ($this->debug==1) {echo " FUNCTION (8) dwiepary -> false <br/>"; }
     return 0;
 }
}
        
        
 if ($this->debug==1) {echo " FUNCTION (8) dwiepary -> false <br/>"; }
     return 0;       
    }    
    
/*******************************************************************************
*
*                         9. para
*
*******************************************************************************/
    public function uklad9(){
        $this->zeruj();
        
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];

//nowe
$this->tabpom1[1]=0;
$this->tabpom1[2]=0;
$this->tabpom1[3]=0;
$this->tabpom1[4]=0;
$this->tabpom1[5]=0;
$this->tabpom1[6]=0;
$this->tabpom1[7]=0;

//powzcuanie kart do odpowiednich stoisk w talbicy
for ($zz=0;$zz<=12;$zz++){
  for ($xx=1;$xx<=7;$xx++){
    if (($this->kkk[$xx]==2+$zz) || ($this->kkk[$xx]==15+$zz) || ($this->kkk[$xx]==28+$zz) || ($this->kkk[$xx]==41+$zz)){
        $this->temp2[$zz+1]=$this->temp2[$zz+1]+1;
}}}
//wyszukanie najwyzszego elemntu w tablicy
for ($zz=1;$zz<=13;$zz++){
  if ($this->temp2[$zz]==2) {
      $this->mt=$zz+1;
}}



//zamiana do wspolnego mianwnika 2-14
$this->tabpom1=$this->wspolny_mianownik();

//znalezienie w puli dwojki i wyzerowanie jej
for ($zz=1;$zz<=2;$zz++){
    for ($xx=1;$xx<=7;$xx++){
        if ($this->tabpom1[$xx]==$this->mt){
            $this->tabpom1[$xx]=0;
            break;
}}}

//sorotowanie od najwiekszej do najmniejszej
$this->tabpom3[1]=$this->tabpom1[1];
$this->tabpom3[2]=$this->tabpom1[2];
$this->tabpom3[3]=$this->tabpom1[3];
$this->tabpom3[4]=$this->tabpom1[4];
$this->tabpom3[5]=$this->tabpom1[5];
$this->tabpom3[6]=$this->tabpom1[6];
$this->tabpom3[7]=$this->tabpom1[7];


$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom<$this->tabpom3[$a]){
         $pom=$this->tabpom3[$a];
        // tabpom3[a]=0;
         $pom3=$a;
   }
}
$this->ooo[3]=0;
$this->ooo[3]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0; //po znalezieniu najwyzszej karty pierwszej wyzeruj ją


$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[4]=0;
$this->ooo[4]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[5]=0;
$this->ooo[5]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;



//sprawdzenie czy karta 1 i 2 znajduje sie w Twoich rekach
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];


$kontrol2=0;
if (($this->tabpom3[6]==0) || ($this->tabpom3[7]==0)) {
   $kontrol2=1;
}

 if ($this->mt!=0) {
 if (($this->tabpom1[6]==0) || ($this->tabpom1[7]==0) || $kontrol2 == 1)  {
      // expor[1]=9;
     //  expor[2]=mt;
      // expor[3]=ooo[3];
      // expor[4]=ooo[4];
      // expor[5]=ooo[5];
      // szukacz=1;
     if ($this->debug==1) {echo " FUNCTION (9) para -> true <br/>"; }
     return 1;
  } else { 
     if ($this->debug==1) {echo " FUNCTION (9) para -> false <br/>"; }
     return 0;
      }
}

   if ($this->debug==1) {echo " FUNCTION (9) para -> false <br/>"; }
     return 0;     
        
        
    }
    
/*******************************************************************************
*
*                         10.
*
*******************************************************************************/
    public function uklad10(){
        $this->zeruj();
        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];
//zamiana do wspolnego mianwnika 2-14

$this->tabpom1=$this->wspolny_mianownik();

$this->tabpom3[1]=$this->tabpom1[1];
$this->tabpom3[2]=$this->tabpom1[2];
$this->tabpom3[3]=$this->tabpom1[3];
$this->tabpom3[4]=$this->tabpom1[4];
$this->tabpom3[5]=$this->tabpom1[5];
$this->tabpom3[6]=$this->tabpom1[6];
$this->tabpom3[7]=$this->tabpom1[7];

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom<$this->tabpom3[$a]){
         $pom=$this->tabpom3[$a];
        // tabpom3[a]=0;
         $pom3=$a;
   }
}
$this->ooo[2]=0;
$this->ooo[2]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0; //po znalezieniu najwyzszej karty pierwszej wyzeruj ją

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom<$this->tabpom3[$a]){
         $pom=$this->tabpom3[$a];
        // tabpom3[a]=0;
         $pom3=$a;
   }
}
$this->ooo[3]=0;
$this->ooo[3]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0; //po znalezieniu najwyzszej karty pierwszej wyzeruj ją

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[4]=0;
$this->ooo[4]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[5]=0;
$this->ooo[5]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;

$pom=0;
$pom2=0;
$pom3=0;
for ($a=1;$a<=7;$a++){
   if ($pom2<$this->tabpom3[$a]){
         $pom2=$this->tabpom3[$a];
         $pom3=$a;
   }
}
$this->ooo[6]=0;
$this->ooo[6]=$this->tabpom3[$pom3];
$this->tabpom3[$pom3]=0;

        $this->kkk[1]=$this->karty[1];
        $this->kkk[2]=$this->karty[2];
        $this->kkk[3]=$this->karty[3];
        $this->kkk[4]=$this->karty[4];
        $this->kkk[5]=$this->karty[5];
        $this->kkk[6]=$this->karty[6];
        $this->kkk[7]=$this->karty[7];

$kontrol=0;
for ($a=2;$a<=6;$a++){
  if ($this->tabpom3[6]==0){
       $kontrol=1;
  }
  if ($this->tabpom3[7]==0){
       $kontrol=1;
  }
}
if ($kontrol==1) {
  // expor[1]=10;
  // expor[2]=ooo[2];
  // expor[3]=ooo[3];
  // expor[4]=ooo[4];
  // expor[5]=ooo[5];
  // expor[6]=ooo[6];
    if ($this->debug==1) { echo " FUNCTION (10) wysokakarta -> true <br/>"; }
     return 1;
} else { 
    if ($this->debug==1) { echo " FUNCTION (10) wysokakarta -> false <br/>"; }
     return 0;
}

    if ($this->debug==1) { echo " FUNCTION (10) wysokakarta -> false <br/>"; }
     return 0;

    }
    
    
    
/*******************************************************************************
*
*                         11. OVERCARD'S
*
*******************************************************************************/
    
    public function uklad11(){
        $this->zeruj();        
        $this->kkk=$this->wspolny_mianownik();
        $overcard1=1;        
        for ($xx=1;$xx<=5;$xx++){
            if ($this->kkk[$xx]<=0) {continue;}
            if ($this->kkk[$xx]>=$this->kkk[6]){
                $overcard1=0; break;
            }
        }        
        $overcard2=1;        
        for ($xx=1;$xx<=5;$xx++){
            if ($this->kkk[$xx]<=0) {continue;}
            if ($this->kkk[$xx]>=$this->kkk[7]){
                $overcard2=0; break;
            }
        }
        if ($this->debug==1) { echo "FUNCTION (11) overcards=$overcard1|$overcard2   <br/>"; }
        
        $suma=$overcard1 + $overcard2;        
        return $suma;
        
    }
    
/*******************************************************************************
*
*                         12. UNDERCARD'S
*
*******************************************************************************/
    
    public function uklad12(){
        $this->zeruj();        
        $this->kkk=$this->wspolny_mianownik();        
        $undercard1=1;        
        for ($xx=1;$xx<=5;$xx++){
            if ($this->kkk[$xx]<=0) {continue;}
            if ($this->kkk[$xx]<=$this->kkk[6]){
                $undercard1=0; break;
            }
        }        
        $undercard2=1;        
        for ($xx=1;$xx<=5;$xx++){
            if ($this->kkk[$xx]<=0) {continue;}
            if ($this->kkk[$xx]<=$this->kkk[7]){
                $undercard2=0; break;
            }
        }
        if ($this->debug==1) { echo "FUNCTION (12) undercards= $undercard1|$undercard2   <br/>"; }
        $suma=$overcard1 + $overcard2;        
        return $suma;
        
    }
    
/*******************************************************************************
*
*                         13. FLUSH DRAW
*
*******************************************************************************/
    
    public function uklad13(){
        $this->zeruj();
        for ($xx=1;$xx<=4;$xx++){            
            $ileKart=0 ;            
            for ($yy=1;$yy<=7;$yy++){
                if ($xx==1) {
                    if (($this->karty[$yy]>=2) and ($this->karty[$yy]<=14)) { $ileKart++; } else { continue; } 
                }
                if ($xx==2) {
                    if (($this->karty[$yy]>=15) and ($this->karty[$yy]<=27)) { $ileKart++; } else { continue; } 
                }
                if ($xx==3) {
                    if (($this->karty[$yy]>=28) and ($this->karty[$yy]<=40)) { $ileKart++; } else { continue; } 
                }
                if ($xx==4) {
                    if (($this->karty[$yy]>=41) and ($this->karty[$yy]<=53)) { $ileKart++; } else { continue; } 
                }
            }
            if ($this->debug==1) {
                //echo "kolor: $xx | karty $ileKart <br/>";             
            }
            if ($ileKart==4){
                //sprawdzenie ASSa
                if ($xx==1) { if (($this->karty[6] == 14) or ($this->karty[7] == 14)){ $this->ass=1; } }
                if ($xx==2) { if (($this->karty[6] == 27) or ($this->karty[7] == 27)){ $this->ass=1; } }
                if ($xx==3) { if (($this->karty[6] == 40) or ($this->karty[7] == 40)){ $this->ass=1; } }
                if ($xx==4) { if (($this->karty[6] == 53) or ($this->karty[7] == 53)){ $this->ass=1; } }                
                if ($this->debug==1) { echo " FUNCTION (13) FD true <br/>"; }                
                return 1;
            }
        }
        if ($this->debug==1) { echo " FUNCTION (13) FD false  <br/>"; }
        return 0;
    }    
    
/*******************************************************************************
*
*                         14. GUT SHOT
*
*******************************************************************************/
    
    public function uklad14(){
        $this->zeruj();
        $this->kkk=$this->wspolny_mianownik();
        $tab=array();        
        for ($yy=1;$yy<=7;$yy++){
            if ($this->kkk[$yy]>0){
                $tab[$this->kkk[$yy]]=1;
            }
        }
        for ($zz=2;$zz<=10;$zz++){
            $spelnione=0;
            if ($tab[$zz]==1) {$spelnione++;}
            if ($tab[$zz+1]==1) {$spelnione++;} 
            if ($tab[$zz+2]==1) {$spelnione++;}  
            if ($tab[$zz+3]==1) {$spelnione++;}     
            if ($tab[$zz+4]==1) {$spelnione++;}           
            if ($spelnione==4){ 
               if (($tab[$zz]==1) and ($tab[$zz+4]==1)){
                   if (($this->kkk[6] == 14) or ($this->kkk[7] == 14)){ $this->ass=1; } 
                   if ($this->debug==1) { echo " FUNCTION (14) GS true A:$this->ass<br/>"; }   
                   return 1;
               }
            }
        }        
        if ($this->debug==1) { echo " FUNCTION (14) GS false <br/>"; }
    }
    
/*******************************************************************************
*
*                         15. Open Ended Straight Draw (OESD)
*
*******************************************************************************/
    
    public function uklad15(){
        $this->zeruj();
        $this->kkk=$this->wspolny_mianownik();
        $tab=array();
        
        if ($this->uklad6()==1){
            if ($this->debug==1) { echo "FUNCTION (15)=wykryty streat"; }
            return 0;
        }
        
        for ($xx=2;$xx<=14;$xx++){ $tab[$xx]=99; }
        
        for ($yy=1;$yy<=7;$yy++){
            if ($this->kkk[$yy]>0){
                $tab[$this->kkk[$yy]]=1;
            }
        }
        
        for ($zz=2;$zz<=10;$zz++){
            $spelnione=0;
            if ($tab[$zz]==1) {$spelnione++;}
            if ($tab[$zz+1]==1) {$spelnione++;} 
            if ($tab[$zz+2]==1) {$spelnione++;}  
            if ($tab[$zz+3]==1) {$spelnione++;}     
            if ($tab[$zz+4]==1) {$spelnione++;}           
            if ($spelnione==4){ 
               if (($tab[$zz]==99) or ($tab[$zz+4]==99)){
                   if (($this->kkk[6] == 14) or ($this->kkk[7] == 14)){ $this->ass=1; }
                   if ($this->oesd==0){
                       if ($tab[$zz]==99) {
                           if ($tab[$zz+5]==99) {
                               $this->oesd=2;
                           } else {
                               $this->oesd=3;
                           }
                       }
                   }
                   if ($this->oesd==0){
                       if ($tab[$zz+4]==99) {
                           if ($tab[$zz-1]==99) {
                               $this->oesd=2;
                           } else {
                               $this->oesd=1;
                           }
                       }
                   }
                   if ($this->debug==1) {                        
                       echo " FUNCTION (15) OESD true A:$this->ass   oesd: $this->oesd <br/>"; 
                       
                   }   
                   return 1;
               }
            }
        }        
        if ($this->debug==1) { echo " FUNCTION (15) OESD false <br/>"; }
        return 0;
    }
    
/*******************************************************************************
*                         TEST
*******************************************************************************/
    
    public function test_jakiekarty(){
        global $havlet;
        
       
        
        for ($xx=1;$xx<=7;$xx++){
            echo "<img src='graphics/karty/{$this->karty[$xx]}.png'/>&nbsp;";
            
            //$havlet->karta($this->karty[$xx]);
            
        }
    }    
    
    public function test($k1=0,$k2=0,$k3=0,$k4=0,$k5=0,$k6=0,$k7=0){
        $this->debug=1;
        $this->karty[1]=$k1; 
        $this->karty[2]=$k2;
        $this->karty[3]=$k3;  
        $this->karty[4]=$k4;  
        $this->karty[5]=$k5;
        $this->karty[6]=$k6; 
        $this->karty[7]=$k7;        
        
        $this->test_jakiekarty();   
        
        echo "<br/><br/><br/><br/>";    
        
        echo "<pre> * karty id         {$this->karty[1]}, {$this->karty[2]}, {$this->karty[3]}, {$this->karty[4]}, {$this->karty[5]}, {$this->karty[6]}, {$this->karty[7]} </pre>";
               
                
           
               
        $this->uklad1();
        $this->uklad2();
        $this->uklad3();
        $this->uklad4();
        $this->uklad5();
        $this->uklad6();
        $this->uklad7();
        $this->uklad8();
        $this->uklad9();
        $this->uklad10();
        $this->uklad11();
        $this->uklad12();
        $this->uklad13();
        $this->uklad14();
        $this->uklad15();
    }
    
}

    
?>