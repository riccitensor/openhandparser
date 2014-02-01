<? session_start(); error_reporting(0);

$_SESSION['author'] = 'gucio';

require_once 'variables.php';
require_once 'funkcje.php';



if ($_POST['textarea']==1){
    echo "<pre>TEXTAREA</pre>";    
    $time = time();
    $hash = rand(100000, 999999);
    $_GET['folder_name'] = $time.'_'.$hash;
    mkdir('upload/'.$_GET['folder_name']);
    file_add('upload/'.$_GET['folder_name'].'/przyklad.txt',stripslashes($_POST['tekst']."\n\n\n"));
}

if ($argv[1]!=''){
    error_reporting(0);
    echo "-----------------------------------------------------------\n";
    echo "|                     POKER PARSER                        |\n";
    echo "-----------------------------------------------------------\n";
    echo "\n\n";    
    $_GET['folder_name'] = $argv[1]; echo "folder_name: $_GET[folder_name] \n\n";
    $console_view_sql = $vconsole;   
    $vprogressbar=0;
} else {
    $view_simple = $vsimpleview;
    $view_sql = $vsql;
    $view_object = $vobject;
    $view_parsername = $vparsername;
    $view_wysumuj = $vwysumuj;  
    $view_sila = $vsila;   
    //echo "<pre class='red'>folder_name = ".$_GET[folder_name]."</pre>";   
    if (($view_simple==1) or
        ($view_sql==1) or
        ($view_object==1) or
        ($view_parsername==1) or
        ($view_wysumuj==1) or
        ($view_sila==1)) {
            echo '<link rel="stylesheet" href="/parser/style.css" type="text/css" />';
    }      
    //require_once '_temp/pokerstar.php';
    //require_once '_temp/holdemmanager.php';
    require_once '_temp/rrr.php';    
}

function progressBar(){?>
<style>
    .progressbar {
        background-color: #EEEEEE; border: solid 1px silver; height: 20px; width: 250px;
        text-align: center; font-size: 20px; padding: 5px; margin: 3px;
    }
    .progressbar-line {
        background: black; height: 5px; width: 262px;margin: 3px; margin-bottom: 10px;
    }
    .progressbar-line div {
        background-color:red; width: 0%; height: 5px;  z-index: 3; position:relative;
    }
</style>

<div class="progressbar" id="progressbar-file">
    <div> 0% </div>
</div>

<div class="progressbar-line" >
    <div id="progressbar-line-file"></div>
</div>

<div class="progressbar" id="progressbar-all">    
    <div> 0% </div>
</div>

<div class="progressbar-line" >
    <div id="progressbar-line-all"></div>
</div>

<script>
    function updateProgressfile(val){        
        document.getElementById("progressbar-file").innerHTML = val+"%";
        document.getElementById("progressbar-line-file").setAttribute("style","width: "+val+"%;");
    }    
    //updateProgress(50);
    function updateProgressall(val){        
        document.getElementById("progressbar-all").innerHTML = val+"%";
        document.getElementById("progressbar-line-all").setAttribute("style","width: "+val+"%;");
    }    
    
function url_click(url){
  okienko=window.open(url,'_self','menubar=yes,toolbar=yes,location=yes,directories=yes,fullscreen=no,titlebar=yes,hotkeys=yes,status=yes,scrollbars=yes,resizable=yes');
}

var c=0; var t;
function wyzwalacz() {
    c=c+1;
    if (c><?global $redirector_time;echo $redirector_time+0;?>) {c=0; url_click('<?global $redirector; echo $redirector?>');} else {t=setTimeout("wyzwalacz()",1000);}    
}


</script>


<?}


function JakiParser($dane){
    global $view_parsername;
    $pos = strrpos($dane, "PokerStars");
    if ($pos === false) {} else {
        if ($view_parsername) {echo "<pre class='red'>PokerStars</pre>";}
        return "PokerStars";
    }
    $pos = strrpos($dane, "Cassava");
    if ($pos === false) {} else {
        if ($view_parsername) {echo "<pre class='red'>HoldemManager</pre>";}
        return "HoldemManager";
    }
    $pos = strrpos($dane, " starts.");
    if ($pos === false) {} else {
        if ($view_parsername) {echo "<pre class='red'>PartyPoker</pre>";}
        return "PartyPoker";
    }
    return "Unknown";    
}

require_once 'pokerth_parser_pokerstar.php'; $parser_pokerstar = new poker_parser_pokerstar();
require_once 'pokerth_parser_holdemmanager.php'; $parser_holdemmanager = new poker_parser_holdemmanager();
require_once 'pokerth_parser_partypoker.php'; $parser_partypoker = new poker_parser_partypoker();
require_once 'funkcje.php';
require_once 'zapis.php';

$saveToBase = new saveToBase();
$dir = $_SERVER['DOCUMENT_ROOT'].'/parser/dir/';

if ($_GET['folder_name']!=''){
    
    if ($vprogressbar==1){ progressBar(); }
    
    if ($argv[1]!=''){
        $dir = $argv[1];
    } else {
        $dir = $_SERVER['DOCUMENT_ROOT'].'/parser/upload/'.$_GET['folder_name'].'/';       
    }
    
    $list = getFiles($dir,$what='f'); 
    
    foreach ($list as $value) { 

        
        if ($console_view_sql==1) {
            echo "\n\n";
            echo "  dir: $dir \n";
            echo "  parsing file: $value";
            echo "\n\n";
        }
       // echo "<div class='files'>files: </div>";
       // echo "<div class='hands'>hands: </div>";
        
        
        
        
        $dane = file_load($dir.$value);    
        $typparsera = JakiParser($dane);
        $wyjscioweDane = "";        
        if ($typparsera=="PokerStars"){ $wyjscioweDane = $parser_pokerstar->rozbijianalizuj($dane); }        
        if ($typparsera=="HoldemManager"){ $wyjscioweDane = $parser_holdemmanager->rozbijianalizuj($dane); }
        if ($typparsera=="PartyPoker"){ $wyjscioweDane = $parser_partypoker->rozbijianalizuj($dane); }
        if ($typparsera=="Unknown") { continue; }
        $saveToBase->analizeAndSave($wyjscioweDane);
        
        $ob_licznik++;
        $ob_max = count($list);
        $wynik =($ob_licznik/$ob_max)*100;
        $wynik = number_format ( $wynik, 1 );
        
        if ($vprogressbar==1){
            echo "<script>updateProgressall($wynik);</script>";
        }
    }
    if ($vprogressbar==1){
        global $redirector_time;
        echo "<br/> za $redirector_time sekund nastąpi przekierowanie";
        echo "<script>wyzwalacz();</script>";
    }
    
} else {
    //echo "_temp/ <br/>";
        $typparsera = JakiParser($dane);
        $wyjscioweDane = "";        
        if ($typparsera=="PokerStars"){ $wyjscioweDane = $parser_pokerstar->rozbijianalizuj($dane); }
        if ($typparsera=="HoldemManager"){ $wyjscioweDane = $parser_holdemmanager->rozbijianalizuj($dane); }
        if ($typparsera=="PartyPoker"){ $wyjscioweDane = $parser_partypoker->rozbijianalizuj($dane); }
        if ($typparsera=="Unknown") { echo "unknown"; }        
        $saveToBase->analizeAndSave($wyjscioweDane);
        //echo "<pre class='parsed'>"; print_r($wyjscioweDane); echo "</pre>";
}