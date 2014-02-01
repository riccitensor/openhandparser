
  $(document).ready(function()
  {
    $('#shuffle').click(shuffle);
  });


  function shuffle()
  {
    $('.card').css({left: 300, position: 'absolute', top: 300});
  
    return false;
  }