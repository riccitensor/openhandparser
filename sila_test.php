<?php

require_once 'sila.php'; $sila = new poker_strenght();
require_once 'helveticards.php';


echo '<link rel="stylesheet" href="/parser/style.css" type="text/css" />';
echo "<pre>Testing</pre>";

//95.160.5.126/parser/test.php?k1=2&k2=3&k3=22&k4=27&k5=53&k6=14&k7=13


$sila->test($_GET[k1],$_GET[k2],$_GET[k3],$_GET[k4],$_GET[k5],$_GET[k6],$_GET[k7]);



?>