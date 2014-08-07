<?php

//LOPUSSA PITÄÄ OLLA VIELÄ KAUTTAVIIVA!!
$homeDirectory = '/home/tjukanov/public_html/tasks/';

$tasks = unserialize(file_get_contents($homeDirectory.'tmp/tasks.tmp'));
$timestamp = file_get_contents($homeDirectory.'tmp/timestamp.tmp');

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background-color: #000000; background-image:url('images/logo.png'); background-repeat:no-repeat; background-position:center; background-attachment:fixed;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="tasks.css" type="text/css" />

<!-- jQuery library (served from Google) -->
<script src="js/jquery.min.js"></script>
<!-- bxSlider Javascript file -->
<script src="js/jquery.bxslider.js"></script>
<!-- bxSlider CSS file -->
<link href="jquery.bxslider.css" rel="stylesheet" />

<title>Tehtävälista - Torvinen Showtekniikka</title>
</head>
<body style="display: none; background-image: none;">

<script>



function startScrollers(){

$('.tasks').bxSlider({
  height: 70,
  ticker: false,
  auto: true,
  controls: false,
  speed: 1000,
  pause: 5000
});

$('.task').bxSlider({
  height: 390,
  mode: 'fade',
  ticker: false,
  auto: true,
  controls: false,
  speed: 1000,
  pause: 20000
});

}



$(document).ready(function(){
$("body").css("display", "none");
$("body").fadeIn(2000);

setInterval(function(){

var header_float = $('#header_left').css("float");

if(header_float == 'left'){
$('#header_left').fadeOut(1000);
setTimeout('$("#header_left").css("float", "right");', 1000);
$("#header_left").fadeIn(1000);
$('#header_right').fadeOut(1000);
setTimeout('$("#header_right").css("float", "left");', 1000);
$("#header_right").fadeIn(1000);
}

else{
$('#header_left').fadeOut(1000);
setTimeout('$("#header_left").css("float", "left");', 1000);
$("#header_left").fadeIn(1000);
$('#header_right').fadeOut(1000);
setTimeout('$("#header_right").css("float", "right");', 1000);
$("#header_right").fadeIn(1000);
}

}, 30000);

setInterval(function(){
$('#holder').fadeOut(2000);
setTimeout("location.reload(true)", 10000); 
}, 600000);

startScrollers();
});


</script>

<div id="holder">
<div id="header_left" >
<img src="images/logo.png">
</div>

<div id="header_right" >
<p class="otsikko">Tehtävälista</p>
<p class="pvm">haettu palvelimelta <?php print date('d.n.Y', $timestamp) ?> klo <?php print date('G:i', $timestamp) ?></p>
</div>


<div id="left">
<ul id="kaikkitaskit" class="tasks">

<?php

//PELKÄT OTSIKOT

$count = 0;
while ($tasks["data"]["tasks"][$count]) {
print '<li><p class="task_luettelo">'.$tasks["data"]["tasks"][$count]["task"]["title"].'</p></li>';
$count++;
}

?>

</ul>
</div>






<div id="right">
<ul id="taski" class="task">

<?php

//KAIKKI TIEDOT

$count = 0;

while ($tasks["data"]["tasks"][$count]) {

$comments = unserialize(file_get_contents($homeDirectory.'tmp/'.$tasks["data"]["tasks"][$count]["task"]["id"].'.tmp'));


print '<li>
<table>
<tr>
<td valign="top" class="task_column">';

//print 'https://www.rapidtask.com/api/v1/'.$developerKey.'/'.$token.'/tasks/'.$tasks["data"]["tasks"][$count]["task"]["id"].'/replies';

//TEHDÄÄN VIELÄ RIVINVAIHDOT HTMLiksi

$description = $tasks["data"]["tasks"][$count]["task"]["description"];
$description = str_replace("\n", "<br />", $description);

print '<p class="task_otsikko">'.$tasks["data"]["tasks"][$count]["task"]["title"].'</p>';
print '<p class="task_leipa">'.$description.'</p>';


print '</td>
<td valign="top"><div style="width:2px;height:370px;max-height:370px;background-color:#666666;float:left;margin-left:20px;margin-right:20px;"></div></td>
<td valign="top" class="task_column">
';


//KÄÄNNETÄÄN ARRAY YMPÄRI, jotta saadaaan uusimmat kommentit ylimmiksi

$comments["data"]["messages"] = array_reverse($comments["data"]["messages"]);

$count_comment = 0;

while($comments["data"]["messages"][$count_comment]){

//VALITAAN AINOASTAAN KOMMENTIT eli KESKUSTELUT, ei mitään assingneja

if($comments["data"]["messages"][$count_comment]["message"]["type"] == 'discussion'){

//TEHDÄÄN PIENIÄ SIIVOUSOPERAATIOITA!

$message_text = $comments["data"]["messages"][$count_comment]["message"]["text"];

$message_text = str_replace("[Received via email]\r\n", "", $message_text);
$message_text = explode("Terveisin,\n\n", $message_text);
$message_text = explode("\nOn ", $message_text[0]);

print '<p class="task_comment"><strong>'.$comments["data"]["messages"][$count_comment]["message"]["author_id"].'</strong><br />';
print $message_text[0].'</p>';
}

$count_comment++;
}

print '
</td>
</tr>
</table>
</li>';

$count++;

}

?>


</ul>
</div>
</div>
</body>
</html>