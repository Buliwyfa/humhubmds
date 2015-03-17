<?php
/*
SCRIPT PARA EXPORTAR A OUTLOOK DESDE HUMHUB

COpiar y pegar en VIEW para exportar a outlook
<br />
<div>
<br />
	<form name="gcal" action="<?=Yii::app()->baseUrl;?>/gcal.php" method="POST">
			<input type="hidden" value="<?=$calendarEntry->title;?>" name="titulo" id="titulo">
			<input type="hidden" value="<?=$calendarEntry->description; ?>" name="descripcion" id="descripcion">
			<input type="hidden" value="<?=$calendarEntry->start_time;?>" name="start" id="start">
			<input type="hidden" value="<?=$calendarEntry->end_time;?>" name="end" id="end">
			<input type="hidden" value="<?=$calendarEntry->id;?>" name="id" id="id">
			<button type="button" class="btn btn-warning btn-xs" onClick="gcal.submit()" >Exportar a Outlook</button>
	</form>
	<?php //echo HHtml::link('Importar Calendario', Yii::app()->baseUrl.'/gcal.php', array('class' => 'btn btn-primary btn-sm')); ?>
</div>
<br />  
*/
$id=$_POST['id'];
$end=strtotime($_POST['end']. "+3hours");
$start=strtotime($_POST['start']. "+3hours");
$title=$_POST['titulo'];
$description=rip_tags($_POST['descripcion']);


function dateToCal($timestamp) {
  return date("Ymd\THis\Z", $timestamp);
}
function rip_tags($string) { 
    
    // ----- remove HTML TAGs ----- 
    $string = preg_replace ('/<[^>]*>/', ' ', $string); 
    
    // ----- remove control characters ----- 
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
    
    // ----- remove multiple spaces ----- 
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    
    return $string; 

}
    $eol = "\r\n";
    $load = "BEGIN:VCALENDAR" . $eol .
    "VERSION:2.0" . $eol .
    "PRODID:-//project/author//NONSGML v1.0//EN" . $eol .
    "CALSCALE:GREGORIAN" . $eol .
    "BEGIN:VEVENT" . $eol .
    "DTEND:" . dateToCal($end) . $eol .
    "UID:" . $id . $eol .
    "DTSTAMP:" . dateToCal(time()) . $eol .
    "DESCRIPTION:" . $description . $eol .   // descripcion del evento
    "SUMMARY:" . htmlspecialchars($title) . $eol . // asunto del evento
	"LOCATION: Marina del Sol S.A.". $eol . // ubicacion del evento
    "DTSTART:" . dateToCal($start) . $eol .
    "BEGIN:VALARM". $eol .
	"TRIGGER:-PT15M". $eol .
	"ACTION:DISPLAY". $eol .
	"DESCRIPTION:Reminder". $eol .
	"END:VALARM". $eol .
	"END:VEVENT" . $eol .
	"END:VCALENDAR";

    $filename="SocialMDS-Event-".$id.".ics";

    // Set the headers
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename=' . $filename);

    // Dump load
    echo $load;

	?>
