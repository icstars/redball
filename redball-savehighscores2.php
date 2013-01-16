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
<style>
#leftpaddle{
  width: 10px;
	height: 50px;
	background-color: black;
	position: absolute;
	left: 175px;
}


</style>
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
  <form id="target" action="redball-savehighscores2.php" method="post">
    <input type="text" name="username" value="" />
    <input type="hidden" name="posted" value="1"/>
    <input type="hidden" name="score" id="thescore" value=""/>
    <input type="submit" value="Go" />
  </form>
</div>
Left Team:<div id="leftteam"></div>
Right Team:<div id="rightteam"></div>
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

<script type='text/javascript'>
// requestAnim shim layer by Paul Irish
    window.requestAnimFrame = (function(){
      return  window.requestAnimationFrame       || 
              window.webkitRequestAnimationFrame || 
              window.mozRequestAnimationFrame    || 
              window.oRequestAnimationFrame      || 
              window.msRequestAnimationFrame     || 
              function(/* function */ callback, /* DOMElement */ element){
                window.setTimeout(callback, 1000 / 60);
              };
    })();
  
window.onmousemove= function(){
	leftbar.style.top = event.clientY + 'px';
	leftbar.top = event.clientY;
	log('clientX:'+event.clientX + ' clientY:'+event.clientY);
	
}

window.onload = function(){
	redball = document.getElementById("redball");	
	leftbar = document.getElementById("leftpaddle");
	leftteam = document.getElementById("leftteam");
	rightscore = document.getElementById("rightscore");
	leftteam.resetscore();
	rightteam.resetscore();
	leftbar.left = 175;
	
}

leftteam.resetscore = function(){
	leftteam.value = 0;
	leftteam.innerHTML = "0";
}

leftteam.score = function(){
	leftteam.value = leftteam.value + 1;
	leftteam.innerHTML = leftteam.value;
  redball.dx = redball.dx * 1.07;
  redball.dy = redball.dy * 1.07;
  
}

rightteam.resetscore = function(){
	rightteam.value = 0;
	rightteam.innerHTML = "0";
}

rightteam.score = function(){
	rightteam.value = rightteam.value + 1;
	rightteam.innerHTML = rightteam.value;
  if ((rightteam.value + leftteam.value)> 10){
    stop_bouncing();
    $( "#thescore" ).val(leftteam.value);
    $( "#dialog-submit" ).dialog( "open" );
  }
    
}

function animate() {    
    requestAnimFrame( animate );
    update_ball();
}

redball.draw = function(){
 	redball.style.position = 'absolute';
    redball.style.left = redball.left +'px';
    redball.style.top = redball.top +'px';
}

function start_bouncing(){
	redball.left = 500;
	redball.top = 100;
	redball.dx = -25;
	redball.dy = 25;
	animate();
}
function stop_bouncing(){
  redball.left = 500;
  redball.top = 100;
	redball.dx = 0;
	redball.dy = 0;
}

function update_ball(){
//	log('updateball - ' + redball.left + ', ' + redball.top + ' dx: ' + redball.dx + ' dy: ' + redball.dy);	
	var right = redball.left + redball.width;
	var bottom = redball.top + redball.height;
	//log ('rb width: ' + redball.width);
	
	//hit right wall
	if (screen.width < right){
		redball.dx = -redball.dx;		
		leftteam.score();
	}
	
	//hit bottom
	else if (screen.height < bottom){
		redball.dy = -redball.dy;
	}
	
	//hit left wall
	else if (redball.left < 0){
		redball.dx = -redball.dx;
		//add score for right team
		rightteam.score();
	}
	
	//hit top wall
	else if (redball.top < 0){
		redball.dy = -redball.dy;
	}
	
	//log('(redball.left + redball.dx):' + (redball.left + redball.dx) + ', leftbar.left:' + (leftbar.left)+ ', leftbar.width:' + (leftbar.width));
	
	//the ball is or is about to cross the vertical plane of the left paddle
	if ((redball.left + redball.dx) <= (leftbar.left + leftbar.width)){
		//check if the bottom of the ball is lower than the top of the paddle
		if(redball.top+redball.height >= leftbar.top){
			//check if the top of the ball is above the bottom of the paddle
			if(redball.top <= (leftbar.top+leftbar.height)){
				//paddle hit!!
				redball.dx = -redball.dx;
			}
		}
	}
	
	redball.left = redball.left + redball.dx;
	redball.top = redball.top + redball.dy;
	redball.draw();
}



function log(msg){
	document.getElementById('log').innerHTML = msg;
}

</script> 
<button onclick="stop_bouncing();">quit</button>
<img id="leftpaddle"/>
<div id="log">Log</div>
</body>

</html>
