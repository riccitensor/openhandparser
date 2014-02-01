<? error_reporting(0);

require_once '../variables.php';
require_once '../sql.php';
require_once '../funkcje.php';


$stawka = $_GET[stawka];
$players = $_GET[players]+0;
if (czyTakiParserIstnieje($_GET[typ])){
    $typ = $_GET[typ];
}
$rs = $sqlconnector->pagi("SELECT * FROM poker_c{$stawka}_t{$typ}_p{$players}
    WHERE k7>0
    GROUP BY reka_id ");

//if ($typ=="PokerStars"){ $wyjscioweDane = $parser_pokerstar->rozbijianalizuj($dane); }        
//if ($typ=="HoldemManager"){ $wyjscioweDane = $parser_holdemmanager->rozbijianalizuj($dane); }
//if ($typ=="PartyPoker"){ $wyjscioweDane = $parser_partypoker->rozbijianalizuj($dane); }


if ($rs!="") {  ?>

<link rel='stylesheet' type='text/css' href='original.css'/> 
<script type='text/javascript' src='jquery-1.9.1.min.js'></script>
<script type='text/javascript' src='function.js'></script>

<table class="lista">
 <th width="30px">reka_id</th>
 <th width="800px"> link </th>
 <th width="40px"> flop 1</th>
 <th width="40px"> flop 2</th>
 <th width="40px"> flop 3</th>
 <th width="40px"> turn</th>
 <th width="40px"> river</th>
 
 <tbody>
<?while($rek = mysql_fetch_assoc($rs)){?>
<tr>
  <td><?=$rek['reka_id'];?></td>
  <td><a href='view.php?id=<?=$rek[reka_id]?>&stawka=<?=$stawka?>&players=<?=$players?>&typ=<?=$typ?>'>link</a></td>
  <td><?=viewSimpleKarta($rek['k3']);?></td>
  <td><?=viewSimpleKarta($rek['k4']);?></td>
  <td><?=viewSimpleKarta($rek['k5']);?></td>
  <td><?=viewSimpleKarta($rek['k6']);?></td>
  <td><?=viewSimpleKarta($rek['k7']);?></td>
</tr>
<?}?>
 </tbody>
</table>
<? } else {
    echo "no items ";
} ?>

<ul class="pagi" id="area_buttons"><?=$sqlconnector->pager->renderAjax();?></ul>

<script>
$("#area_buttons li").click(function(){pokerpage('<?=$_GET[stawka]?>','<?=$_GET[typ]?>','<?=$_GET[players]?>',this.id);});
$(document).ready(function(){ znaczkiWM();});
</script>