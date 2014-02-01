<?php
class PS_Pagination {
    var $php_self;
    var $rows_per_page = 10; //Number of records to display per page
    var $total_rows = 0; //Total number of rows returned by the query
    var $links_per_page = 5; //Number of links to display per page
    var $append = ""; //Paremeters to append to pagination links
    var $sql = "";
    var $debug = false;
    var $conn = false;
    var $page = 1;
    var $max_pages = 0;
    var $offset = 0;

function PS_Pagination($connection, $sql, $rows_per_page = 10, $links_per_page = 5, $append = "") {
    $this->conn = $connection;
    $this->sql = $sql;
    $this->rows_per_page = (int)$rows_per_page;
    if (intval($links_per_page ) > 0) {
            $this->links_per_page = (int)$links_per_page;
    } else {
            $this->links_per_page = 5;
    }
    $this->append = $append;
    $this->php_self = htmlspecialchars($_SERVER['PHP_SELF'] );
    if (isset($_GET['page'] )) {
            $this->page = intval($_GET['page'] );
  }
}

function paginate() {
 if (!$this->conn || !is_resource($this->conn)) {
  if ($this->debug) echo "MySQL connection missing<br />"; return false;
 }
 $all_rs = @mysql_query($this->sql );
 if (! $all_rs) {if ($this->debug) echo "SQL query failed. Check your query.<br /><br />Error Returned: " . mysql_error();
   return false;
 }
 $this->total_rows = mysql_num_rows($all_rs );
 @mysql_close($all_rs );
 if ($this->total_rows == 0) {
  if ($this->debug)echo "Query returned zero rows."; return FALSE;
 }
 $this->max_pages = ceil($this->total_rows / $this->rows_per_page );
 if ($this->links_per_page > $this->max_pages) {$this->links_per_page = $this->max_pages;}
 if ($this->page > $this->max_pages || $this->page <= 0) { $this->page = 1; }
 $this->offset = $this->rows_per_page * ($this->page - 1);
 $rs = @mysql_query($this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}" );
 if (! $rs) { if ($this->debug) echo "Pagination query failed. Check your query.<br /><br />Error Returned: " . mysql_error();
  return false;
 }
return $rs;
}


//================ LINK ========================================================

function renderFirst($tag = 'First') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == 1) {return "<span class='disabled'>$tag</span>";
  } else { return '<a href="'.$this->php_self.'?page=1&'.$this->append.'">'.$tag.'</a>'; }
}

function renderLast($tag = 'Last') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == $this->max_pages) { return "<span class='disabled'>$tag</span>";
  } else { return '<a href="'.$this->php_self.'?page='.$this->max_pages.'&'.$this->append.'">'.$tag.'</a>';  }
}

function renderNext($tag = '&gt;&gt;') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page < $this->max_pages) { return '<a href="'.$this->php_self.'?page='.($this->page+1).'&'.$this->append.'">'.$tag.'</a>';
  } else { return "<span class='disabled'>$tag</span>"; }
}

function renderPrev($tag = '&lt;&lt;') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page > 1) { return '<a href="'.$this->php_self.'?page='.($this->page-1).'&'.$this->append.'">'.$tag.'</a>';
  } else { return "<span class='disabled'>$tag</span>"; }
}


function renderNav($prefix = '', $suffix = '') {
  if ($this->total_rows == 0) return FALSE;
  $batch = ceil($this->page / $this->links_per_page );
  $end = $batch * $this->links_per_page;
  if ($end == $this->page) {}
  if ($end > $this->max_pages) { $end = $this->max_pages; }
  $start = $end - $this->links_per_page + 1;
  $links = '';
  for($i = $start; $i <= $end; $i ++) {
    if ($i == $this->page) {
      $links.="<span class='thispage'>$i</span>";
    } else {
      $links.='<a href="'.$this->php_self.'?page='.$i.'&'.$this->append.'">'.$i.'</a>';
    }
  }
  return $links;
}
	
function renderFullNav() {
  return $this->renderFirst().''.$this->renderPrev().''.$this->renderNav().''.$this->renderNext().''.$this->renderLast();
}

function setDebug($debug) {
  $this->debug = $debug;
}


//================ AJAX ========================================================

function renderFirstAjax($tag = 'First') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == 1) {return "<li class='$this->classes disabled'>$tag</li>";
  } else { return "<li class='$this->classes enabled' id='1'>$tag</li>"; }
}
function renderLastAjax($tag = 'Last') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == $this->max_pages) { return "<li class='$this->classes disabled'>$tag</li>";
  } else { return "<li class='$this->classes enabled' id='".$this->max_pages."'>$tag</li>";  }
}

function renderNextAjax($tag = '&gt;&gt;') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page < $this->max_pages) { return "<li class='$this->classes enabled' id='".($this->page+1)."'>$tag</li>";
  } else { return "<li class='$this->classes disabled'>$tag</li>"; }
}


function renderPrevAjax($tag = '&lt;&lt;') {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page > 1) { return "<li class='$this->classes enabled' id='".($this->page-1)."'>$tag</li>";
  } else { return "<li class='$this->classes disabled'>$tag</li>"; }
}

function renderNavAjax($prefix = '', $suffix = '') {
  if ($this->total_rows == 0) return FALSE;
  $batch = ceil($this->page / $this->links_per_page );
  $end = $batch * $this->links_per_page;
  if ($end == $this->page) {}
  if ($end > $this->max_pages) { $end = $this->max_pages; }
  $start = $end - $this->links_per_page + 1;
  $links = '';
  for($i = $start; $i <= $end; $i ++) {
    if ($i == $this->page) {
      $links.="<li class='$this->classes thispage'>$i</li>";
    } else {
      $links.="<li class='$this->classes enabled' id='$i'>$i</li>";
    }
  }
  return $links;
}


var $classes = "";

function renderAjax($classes="") {
  $this->classes = $classes;
  return $this->renderFirstAjax().''.$this->renderPrevAjax().''.$this->renderNavAjax().''.$this->renderNextAjax().''.$this->renderLastAjax();
}




//================ ID ==========================================================

function renderPrevId() { 
  if ($this->total_rows == 0) return FALSE;
  if ($this->page > 1) { return $this->page-1;;
  } else { return 0; }
}

function renderNextId() { 
  if ($this->total_rows == 0) return FALSE;
  if ($this->page < $this->max_pages) { return $this->page+1;;
  } else { return 0; }
}

function renderFirstId() {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == 1) {return 1;
  } else { return 0; }
}

function renderLastId() {
  if ($this->total_rows == 0) return FALSE;
  if ($this->page == $this->max_pages) { return 0;
  } else { return $this->max_pages;  }
}

function renderAktualneId() {
    return $this->page;
}


}?>
