<?
$db_host='localhost:3306';
$db_user='root';
$db_pass='aaa';
$db_name='poker';

error_reporting(0);

/*
 *  $tab_stawka array is BB in cents or simply NL in dolars/euro example:
 * 
 *    element 25     is    NL 25$     or     BB 0.25$ 
 *    element 50     is    NL 50$     or     BB 0.50$
 *    element 1000   is    NL 1000$   or     BB 10.00$
 * 
 */

$tab_stawka = array('2','5','10','25','30','50','100','200','400','1000','2000','5000','10000','else');
$tab_typ = array('holdemmanager','partypoker','pokerstars');
$tab_players = array('6','9');

$max_hands_to_add = 99999;
$redirector = "/parser/"; //przekierowanie po uploadzie
$redirector_time = 5;

$vsimpleview = 0; //uproszczony wyglad rozdania
$vsql = 0; //zapytanie sql
$vobject = 0; //sparsowany podglad obiektu
$vparsername = 0; //nazwa parsera
$vwysumuj = 0; //dzielenie zwyciezkik stawek
$vsila = 0; //podglad sily
$vprogressbar = 1; // 

$vconsole = 1; //podglad informacji uruchamianych w bashu

$zapis_sql = 1; //1 zapis dob bazy 0 same parsowanie bez zapisu do bazy
$zapis_dane = 1; // zapis kolumny dane

/*
 * $uploader_name is variable to set uploader possibilities to set:
 * 
 *     - phpfileuploader
 *     - uploadiFive
 *     - textarea
 * 
 */

$uploader_name="phpfileuploader";



$vobject = 1;
$vsql = 1; //zapytanie sql
//$vparsername = 1; //nazwa parsera
//$tab_stawka = array('2','5','10','25','30','50','100','200','400','1000','2000','5000','10000','else');
/*$tab_stawka = array(
    '1','2','3','4','5','6','8','10','12','15','20',
    '25','30','40','50','60','80','100','120','150','200','250',
    '300','400','500','600','800','1000','1200','1500','2000','2500','3000',
    '4000','5000','6000','8000','10000','12000','15000','20000','25000','30000','40000',
    '50000','60000','80000','100000','120000','150000','200000','250000','300000',
    '400000','500000','600000','800000','1000000','else');*/

$viewer_debug=1; //podglad tablicy w przegladarce bazy ukladow: /parser/testviewer/view.php?id=1&stawka=200&players=6&typ=holdemmanager



?>