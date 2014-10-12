<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="terminy_obecnosci";


if(!isset($_GET['id']))
{
	echo '<div class="dialog-box">
				
				<strong class="question">Niepoprawne zapytanie.</strong>
				<p>Wróć do poprzednej strony i spróbuj ponownie.</p>
					<div class="button-holder">
					<a href="?p=kursy" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>
				';
}
else
{
	$IdZajecia=$_GET['id'];
	$result=$admin->Action_PojedynczeZajecia($_GET['id']);
	
		if($result)
		{
			foreach($result as $row)
			{
				switch ($row['Dzien'])
				{
					case 'pon': $row['Dzien']="poniedziałek";break;
					case 'wt': $row['Dzien']="wtorek";break;
					case 'sr': $row['Dzien']="środa";break;
					case 'czw': $row['Dzien']="czwartek";break;
					case 'pt': $row['Dzien']="piątek";break;
					case 'so': $row['Dzien']="sobota";break;
					case 'ndz': $row['Dzien']="niedziela";break;
				}
				
				$IdZajecia=$row['IdZajecia'];
				$IdSali=$row['IdSali'];
				$Dzien=$row['Dzien'];
				$Godzina=date_format(date_create($row['Godzina']),'H:i');
				$GodzinaKoniec=date_format(date_create($row['GodzinaKoniec']),'H:i');
				$Tydzien=$row['Tydzien'];
				if($Tydzien=="T") $Tydzien="";
				$KodKursu=$row['KodKursu'];
				$IdProwadzacy=$row['IdProwadzacy'];
				$Prowadzacy=$row['Tytul'].' '.$row['Imie'].' '.$row['Nazwisko'];
				$Sala=$row['Sala'];
				$Budynek=$row['Budynek'];
				$ZapisanychStudentow=$row['ZapisanychStudentow'];
			}

			$tytul_belki="modyfikacja terminu";
			$tryb_edycji=1;
		}
	

/* SORTOWANIE */
$sql = "SELECT count(*) FROM terminy WHERE terminy.IdZajecia='$IdZajecia'"; 
$result = $db->prepare($sql); 
$result->execute(); 
$rekordow = $result->fetchColumn();
$LiczbaZajec=$rekordow;
$stron=ceil($rekordow/$nastronie);

$sql = "SELECT kursy.NazwaKursu as NazwaKursu,formy_kursow.NazwaFormy as NazwaFormy FROM kursy,formy_kursow WHERE kursy.KodKursu='$KodKursu' AND kursy.IdFormy=formy_kursow.IdFormy"; 

foreach($db->query($sql) as $informacja_o_kursie);
echo '

<div id="page-title">


<h2><a href="?p=home">administracja</a> &raquo; <a href="?p=kursy">kursy</a> 
&raquo; <a href="?p=zajecia&KodKursu='.$KodKursu.'">'.$informacja_o_kursie['NazwaKursu'].' ('.$informacja_o_kursie['NazwaFormy'].')</a>
&raquo; <a href="?p=terminy&id='.$IdZajecia.'">'."$Dzien $Tydzien $Godzina-$GodzinaKoniec, sala $Sala, $Budynek".'</a>
&raquo; <a href="?p=terminy_obecnosci&id='.$IdZajecia.'">tabela obecności</a>
</h2> 

</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li><a href="?p=terminy&id='.$IdZajecia.'" class="tabbutton icon-date">lista terminów</a></li>
<li class="current"><a href="#" class="tabbutton icon-book_open">tabela obecności</a></li>
<li><a href="?p=zapisy&id='.$IdZajecia.'" class="tabbutton icon-user">zapisani studenci</a></li>
<li><a href="?p=terminy_edycja&IdZajecia='.$IdZajecia.'&frompage=terminy_obecnosci" class="tabbutton icon-add">dodaj termin</a></li>
</ul>

			<div class="clear"></div>
			
			
			
			<!-- js dynamic check for mysql change -->
			
	 <script>

	data_najnowsza_tutaj=';
	
	
	$sql="SELECT IdTermin
	FROM terminy WHERE IdZajecia=$IdZajecia";	
	
	$do_geta="";
	$do_sqla="";
foreach($db->query($sql) as $result)
{
$do_geta.='"'.$result[0].'",';
$do_sqla.=$result[0].',';
}	
	$sql="SELECT DataObecnosci
	FROM obecnosci WHERE IdTermin IN ($do_sqla-1) ORDER BY DataObecnosci DESC LIMIT 0,1";	
	$czyjest=false;
foreach($db->query($sql) as $result) { echo "'".$result[0]."'";$czyjest=true; }
if(!$czyjest) echo "'1990-12-17 05:10:00'";
	echo ';
	function sprawdzModyfikacjeZajec()
	{
		  if(!continue_checking)
		  {
			setTimeout(sprawdzModyfikacjeZajec,timeout_petla);
			return false;
			}
	      else
			{
			$.get("admin/zajeciaDateStamp.php", { \'id[]\': ['.$do_geta.'"-1"]},
			function(data){
			try
			{

				if(Date.parse(new String(data)) > Date.parse(new String(data_najnowsza_tutaj)))
				{
					$(\'.window_alert\').fadeIn(500);
				}
				else $(\'.window_alert\').fadeOut();
				setTimeout(sprawdzModyfikacjeZajec,timeout_petla);
				}
				catch(err){}
			});
			return true;
			}
	}
       $(document).ready(function(){

			sprawdzModyfikacjeZajec();
			
 });
	
		
    </script>
		<div class="window_alert">
		<strong class="icon-error">Uwaga!</strong><a href="#" title="Naciśnij, aby wyłączyć powiadomienia na tej stronie" class="close">X</a>
		<p>Zawartość została zmodyfikowana z innego miejsca. Czy chcesz odświeżyć stronę (zalecane)?</p>
		<button id="alert_refresh">odśwież</button>
	</div>		
	
	
</div>';
			



	


	
	if($rekordow)
	{
	
	//lista zajec
	$sql="SELECT 
	terminy.IdTermin as IdTermin,
	terminy.Data as Data
	FROM terminy WHERE terminy.IdZajecia=$IdZajecia
	GROUP BY terminy.IdTermin ORDER BY Data ASC";
	
	echo '
				<div id="dialog-obecnosc" style="display: none;" title="Obecność">
	<p><span class="ui-icon ui-icon-comment" style="float:left; margin:0 7px 20px 0;"></span>Wybierz odpowiedni przycisk, aby zmodyfikować obecność tego studenta.</p>
</div>
	
	<table class="pages_table">
	
	<thead>
	<tr><th colspan="2">student</t>
	';
	foreach($db->query($sql) as $result)
	{
	echo "<th><a title=\"modyfikuj ten termin\" style=\"border-bottom: 1px #999 dotted\" href=\"?p=terminy_edycja&IdZajecia=$IdZajecia&id=".$result['IdTermin']."&frompage=terminy_obecnosci\">".date_format(date_create($result['Data']),'d.m')."</a></th>";
		$terminy[]=$result;
	}
echo '</tr>
</thead>
<tbody>
';

	
	
	
		$sql="SELECT zajecia_studentow.NrIndeksu as NrIndeksu,
	studenci.Imie as Imie,
	studenci.Nazwisko as Nazwisko
	FROM zajecia_studentow
	LEFT JOIN studenci ON zajecia_studentow.NrIndeksu=studenci.NrIndeksu
	WHERE zajecia_studentow.IdZajecia=$IdZajecia
	ORDER BY Nazwisko ASC
	";
	




			
	$iter=0;

	foreach($db->query($sql) as $result)
	{
	$Imie=$result['Imie'];
	$Nazwisko=$result['Nazwisko'];
	$NrIndeksu=$result['NrIndeksu'];
	echo "<tr><td style=\"width: 60px;\"><code>$NrIndeksu</code></td><td>$Imie&nbsp;$Nazwisko</td>";
		
		foreach($terminy as $termin)
		{
		echo '<td style="width: 10px; background-color: white;">';
		$d=$termin[0];
		$sql2="SELECT Typ FROM obecnosci WHERE NrIndeksu=$NrIndeksu AND IdTermin=$d";
		$ob=0;
		foreach($db->query($sql2) as $result2)
			{
				$ob=$result2['Typ'];
			}
echo '
<a href="#" title="naciśnij, aby zmienić" class="dynamic-obecnosc {NrIndeksu: \''.$NrIndeksu.'\',IdTermin: \''.$d.'\',action: \'change\'}">';
if($ob==0) echo '<span class="ikonka icon-cross" title="nieobecny">-</span>';
else if($ob==1) echo '<span class="ikonka icon-tick" title="obecny">+</span>';
else if($ob==2) echo '<span class="ikonka icon-hourglass" title="spóźnienie">.</span>';
else  echo '<span class="ikonka icon-help" title="brak informacji">?</span>';
		echo "</a>";
		
		echo "</td>";
		}
		
	

	echo "</tr>";
	}
	/*
	{
	
	
	echo '<tr>
						<td class="col-IdZajecia"><input type="checkbox" name="mass-action-item[]" value="'.$result['IdTermin'].'" id="check-'.$result['IdTermin'].'" style="margin-right: 10px;"/> <label for="check-'.$result['IdTermin'].'"><code>'.$result['IdTermin'].'</code></label></td>
						
					<td class="col-nazwakursu"><a href="?p=lista_obecnosci&IdZajecia='.$IdZajecia.'&id='.$result['IdTermin'].'" title="kliknij, aby otworzyć listę obecności dla tego terminu"><strong>'.date_format(date_create($result['Data']),'d.m.Y').'</strong></td>
<td>
<span class="ikonka icon-group">obecnych studentów: </span> <strong>'.$result['ObecnychStudentow'].'</strong> / '.$ZapisanychStudentow.'
</td>
<td>
<span class="ikonka icon-clock">godzina otwarcia:</span>'.$spoznienie.' '.date_format(date_create($result['Data']),'H:i:s').'
</td>
					<td class="col-operacja">

						<a href="?p=zajecia_edycja&IdZajecia='.$IdZajecia.'&IdTermin='.$result['IdTermin'].'" class="icon-page_white_edit"><span>edytuj</span></a>
						<a href="?p='.$pageuri.'&action=delete&id='.$IdZajecia.'&del_id='.$result['IdTermin'].'&strona='.$strona.'" class="icon-cross" rel="'.$result['id'].'"><span>usuń</span></a></td>
					
					
				</tr>';
				
	$iter++;
	}
		}
			
*/
		echo '</tbody>
		<tfoot>
		<tr>
		<td></td><td><a href="?p=zapisy_dodaj&id='.$IdZajecia.'" class="button" style="margin: -4px 10px;"><span class="icon-user_add" style="color: #555;">dodaj&nbsp;studenta&nbsp;do&nbsp;tych&nbsp;zajeć...</span></a></td><td colspan="'.(sizeof($terminy)).'"><a href="?p=terminy_edycja&IdZajecia='.$IdZajecia.'&frompage=terminy_obecnosci" class="button" style="margin: -4px 10px;"><span class="icon-add" style="color: #555;">dodaj&nbsp;termin...</span></a></td>
		</tr>
		</tfoot>
				
			</table>
	';
			
				
				
		}
				else echo ' <div class="dialog-box">
					
					<strong class="question">Do tych zajęć nie są przypisane żadne terminy.</strong>
					<p>Aby dodać termin, naciśnij przycisk <strong>dodaj nowy termin</strong> poniżej.</p>
						<div class="button-holder">
						<a href="?p=terminy_edycja&IdZajecia='.$_GET['id'].'&frompage=terminy_obecnosci" class="button"><span class="icon-calendar_add">dodaj nowy termin</span></a>
						<a href="?p=zajecia&KodKursu='.$KodKursu.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						
						<div class="clear"></div>
						</div>
					</div>';


}

?>