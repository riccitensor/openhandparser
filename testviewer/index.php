<? 
error_reporting(0); 
require_once '../variables.php';
?>
<script type='text/javascript' src='jquery-1.9.1.min.js'></script>
<script type='text/javascript' src='function.js'></script>
<link rel='stylesheet' type='text/css' href='original.css'/> 

<script>$(document).ready(function(){pokerpage('<?=$_GET[stawka]?>','<?=$_GET[typ]?>','<?=$_GET[players]?>',1);});</script>

<div id="area"></div>

<table>
<? foreach ($tab_stawka as $stawka){
        echo "<tr>";
        foreach ($tab_typ as $typ){
            foreach ($tab_players as $players){
                echo "<td>";                
                if ($_GET['stawka'] == $stawka) {
                    if ($_GET['typ'] == $typ) {
                        if ($_GET['players'] == $players) {
                            echo "-> ";
                        }
                    }                          
                }
                echo "<a href='index.php?typ=$typ&players=$players&stawka=$stawka'> $$stawka / $typ / p$players  </a>";     
                echo "<td>";
            }
        }
        echo "</tr>";
   }
?>
</table>

<?
//http://95.160.5.126/parser/testviewer/index.php?typ=pokerstars&players=6&stawka=400

?>