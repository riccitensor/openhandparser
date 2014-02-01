<?php

class sqlConnector{    
    public $con;
    public $pager;
    public $db_host;
    public $db_user;
    public $db_pass;
    public $db_name;    
    public $polaczono = 0;    
    public $debug = "off";
    
    private function open(){
        global $debug;
        global $db_host; $this->db_host = $db_host;
        global $db_user; $this->db_user = $db_user;
        global $db_pass; $this->db_pass = $db_pass;
        global $db_name; $this->db_name = $db_name;

        $this->con = mysql_connect($this->db_host,$this->db_user,$this->db_pass) or die( "Źle podany login lub hasło do MySQL ".mysql_error());
        mysql_select_db($this->db_name, $this->con) or die("Źle podana nazwa bazy MySQL ".mysql_error());
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        $this->polaczono=1;
    }
    
    private function close(){ 
        if ($this->polaczono==1){
            mysql_close($this->con);
            $this->polaczono=0;
        }        
    }    
    
    public function checkConnection(){
        if ($this->polaczono==0){
            $this->open();
        }
    }
    
    public function tables(){
        $this->checkConnection();        
        $tables=mysql_list_tables($this->db_name);
        $this->close();
        return $tables;
    }

    public function table_exists($table) {
        $this->checkConnection();
        $tables = mysql_list_tables ($this->db_name);
        $this->close();
        while (list($temp)=mysql_fetch_array($tables)){
            if ($temp == $table){
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insert($query){
        $this->checkConnection(); 
        mysql_query($query) or die (mysql_error());
        $id = mysql_insert_id();
        $this->close();
        return $id;
    }
    
    function select($query){
        $this->checkConnection();    
        $temp=mysql_query($query);
        $this->close();
        return $temp;
    }

    public function tables_info(){
        global $base_members;
        $this->open();
        $result = $this->query("SHOW TABLE STATUS FROM $this->db_name");
        return $result; 
    }

    public function query($query){
        if ($this->debug=="on") {echo "<br/> <pre>$query</pre> <br/>";}
        //echo "<br/> <pre>$query</pre> <br/>";

        $this->checkConnection();  
        $temp=mysql_query($query,$this->con) or die ("<pre>error2 = $query \n \n".mysql_error()."</pre>");
        $this->close();
        //echo "<br/> temp  = $temp <br/>";
        return $temp;
    }

    public function openQuery($query){
        $this->checkConnection();
        //echo "query = <pre>$query</pre>";
        
        mysql_query($query,$this->con) or die ("<pre>error2 = $query \n \n".mysql_error()."</pre>");
        return mysql_insert_id();    
    }

    public function rlo($query){ //record limit one
        if ($this->debug=="on") {echo "<br/> <pre>$query</pre> <br/>";} 
        //echo "<br/> <pre>$query</pre> <br/>";

        $this->checkConnection();
        $temp = mysql_query($query,$this->con) or die ("<pre>error2 = $query \n \n".mysql_error()."</pre>");
        $this->close();
        while($rek=mysql_fetch_assoc($temp)){$tab=$rek;}
        return $tab;
    }

    public function pagi($query,$rec=25,$max=10){
        echo "<br/> <pre>$query</pre> <br/>"; 
        require_once 'ps_pagi.php';
        $this->checkConnection();
        if ($this->debug=="on") {echo "<pre>$query</pre>";}
        $this->pager = new PS_Pagination($this->con, $query, $rec, $max);
        $temp2 = $this->pager->paginate();
        $this->close();
        return $temp2;
    }

    public function DuplicateMySQLRecord($table,$id_field,$id){
        $rs = $this->query("SELECT * FROM {$table} WHERE {$id_field}={$id}");
        $original_record = mysql_fetch_assoc($rs);
        $newid = $this->insert("INSERT INTO {$table} (`{$id_field}`) VALUES (NULL)");
        // generate the query to update the new record with the previous values
        $query = "UPDATE {$table} SET ";
        foreach ($original_record as $key => $value) {
            if($key!=$id_field){
                $query.='`'.$key.'` = "'.str_replace('"','\"',$value).'", ';
            }
        }
        $query = substr($query,0,strlen($query)-2); # lop off the extra trailing comma
        $query .= " WHERE {$id_field}={$newid}";
        $this->query($query);
        return $newid;
    }
    
    
    
    
//public function sql_update($tab){
//    $sql="UPDATE $tab[base] SET ";
//    $ile=0;
//    foreach ($tab as $key => $value){
//      if ($key=="base" or $key=="where") {continue;}
//      if ($ile>0) {$sql.=", ";}
//      $sql.="$key = '$value'";
//      $ile++;
//    }
//    $sql .= " WHERE ";
//    $sql .= " ".$tab['where'].";";
//    //echo "sql = $sql<br/>";
//    connect();
//    mysql_query($sql) or die (mysql_error());
//    global $con; mysql_close($con);
//}
    
    public function view($rs){
        while($rek=mysql_fetch_assoc($rs)){
            echo '<pre>';
                print_r($rek);
            echo '</pre>';
        }

        
       //print_r($rs);
        
    }
    
    
}

$sqlconnector = new sqlConnector();

?>