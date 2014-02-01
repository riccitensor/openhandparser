<?php

require_once 'variables.php';

if ($uploader_name=="textarea"){    
    require_once 'index_textarea.php';    
} else {
    echo "Uploader is not set in variables! please edit file /parser/variables.php";
}


?>
