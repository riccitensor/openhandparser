function del(id,info,dir,back){
  confirmed = window.confirm(info+" "+id);
  if(confirmed){
    window.location.href=dir+"delete.php?id="+id+"&back="+back;
  }
}

function del_file(file,info,dir){
  if(dir==undefined){dir='';}
  confirmed=window.confirm(info);
  if(confirmed){
    window.location.href=dir+"delete_file.php?file="+file;
  }
}

function copy(id,info,dir){
  if (dir==undefined){dir='';}
  confirmed=window.confirm(info+" "+id);
  if (confirmed){
    window.location.href=dir+"copy.php?id="+id;
  }
}

function change_status(id){
  $("#"+id).load("change_status.php?id="+id);
}

function urldecode(str){
  str=str+"";
  str=str.replace("ł","l");
  str=str.replace("Ł","L");
  return encodeURIComponent((str).replace(/\+/g,'%20'));
}

function visible(id) { $("#vis"+id).load("visible.php?id="+id);}

function formSubmit() {  document.getElementById("foremka").submit();}
order=1;
column="id";

function ajaxloadpage(page){$("#area").load("list.php?column="+column+"&order="+order+"&page="+page+"&search="+urldecode($("#search").val()));}

function sortcol(column){
  if(column==window.column){
  if(window.order==0){window.order=1;}else{window.order=0;}
  }else{
     window.column=column; window.order=0;
  }
  ajaxloadpage(1);
}

function znaczkiWM(){
  $('.lista th span').text("");
  if(order==1){
    $('#'+column).text("▼");
  }
  else{
    $('#'+column).text("▲");
  }
}

function targetBlank(url){
  blankWin=window.open("http://"+url,'_blank','menubar=yes,toolbar=yes,location=yes,directories=yes,fullscreen=no,titlebar=yes,hotkeys=yes,status=yes,scrollbars=yes,resizable=yes');
}

function url_click(url){
  okienko=window.open(url,'_self','menubar=yes,toolbar=yes,location=yes,directories=yes,fullscreen=no,titlebar=yes,hotkeys=yes,status=yes,scrollbars=yes,resizable=yes');
}

function pokerpage(stawka,typ,players,page){ 
    $("#area").load("list.php?typ="+typ+"&players="+players+"&stawka="+stawka+"&page="+page+"&search=" + urldecode($("#search").val()));
}