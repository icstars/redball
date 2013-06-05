<?php
echo '<br>was it posted?: ' . $_POST["posted"];
echo '<br>username: ' . $_POST["username"];
echo '<br>score: ' . $_POST["score"];
echo '<br>';

if($_POST["posted"]=='1')
{
$date = date("Y-m-d");

$user = 'icstars_reduser';
$pwd = '6IL934;-[!}?';
$db = 'icstars_redball';
  $link = mysql_connect('localhost', $user, $pwd);
  if(!$link) {
    die('Could not connect: ' . mysql_error());
  } else {
    mysql_select_db($db, $link);
    $query = <<<SQL
INSERT INTO highscore (thedate, username, score)
VALUES
('$date','$_POST[username]','$_POST[score]')
SQL;

    if (!mysql_query($query,$link))
      {
      die('Error: ' . mysql_error());
      }

    mysql_close($link);

  }
  
}
?>
<html>
<head>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" media="all" />

</head>
<body>
<div id="dialog-modal" title="High scores to date!">
  <p>Good luck!</p>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="highscores">
    <thead>
      <tr>
        <th>id</th>
        <th>user</th>
        <th>score</th>
        <th>date</th>
      </tr>
    </thead>
  </table>
</div>
<div id="dialog-submit" title="Congrats! Save your High Score!">
  <form id="target" method="post">
    <input type="text" name="username" value="" />
    <input type="hidden" name="posted" value="1"/>
    <input type="hidden" name="score" id="thescore" value=""/>
    <input type="submit" value="Go" />
  </form>
</div>
<img src="http://icstars.org/files/redball.jpg" id="redball"/>

<!-- jquery and datatables -->
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script class="jsbin" src="http://datatables.net/download/build/jquery.dataTables.nightly.js"></script>

<script>
//build the dialog 
    $( "#dialog-modal" ).dialog({
      autoOpen: true,
      height: 600,
      width: 800,
      modal: true,
      close: function (event, ui) {
        start_bouncing();
      }
    });
    
    $( "#dialog-submit" ).dialog({
      autoOpen: false,
      height: 200,
      width: 300,
      modal: true
    });

//populate the table with data from the server php file  
 $(document).ready(function() {
      $('#highscores').dataTable( {
          "bProcessing": true,
          "bServerSide": true,
          "bPaginate": false,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": false,
          "bInfo": false,
          "bAutoWidth": true,
          "sAjaxSource": "highscores.php"
      } );
  } );
</script>
<script>
function gameover(){
$fakescore = '1200';
  $( "#thescore" ).val($fakescore);
  $( "#dialog-submit" ).dialog( "open" );
}
</script>
<body>
<button onclick="gameover();">Pretend Game over</button>
</body>
</html>
