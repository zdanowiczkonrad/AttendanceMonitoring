<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="lista_obecnosci";


/* SZYBKI SKOK */
if(isset($_POST['quickjump']))
{
	$strona=$_POST['quickjump'];
	if($_POST['quickjump']>$_POST['allpages'])
	{
		$strona=$_POST['allpages'];
	}
}

switch($sort)
{
	case 1: $mysql_sort='studenci.Nazwisko ASC'; break;
	case 2: $mysql_sort='studenci.Nazwisko DESC'; break;
	case 3: $mysql_sort='studenci.NrIndeksu ASC'; break;
	case 4: $mysql_sort='studenci.NrIndeksu DESC'; break;	
}

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
	$IdZajecia=$_GET['IdZajecia'];
	$IdTermin=$_GET['id'];
	$result=$admin->Action_PojedynczeZajecia($_GET['IdZajecia']);
	
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
	



$sql = "SELECT kursy.NazwaKursu as NazwaKursu,formy_kursow.NazwaFormy as NazwaFormy FROM kursy,formy_kursow WHERE kursy.KodKursu='$KodKursu' AND kursy.IdFormy=formy_kursow.IdFormy"; 

foreach($db->query($sql) as $informacja_o_kursie);
echo '

<div id="page-title">


<h2><a href="?p=home">administracja</a> &raquo; <a href="?p=kursy">kursy</a> 
&raquo; <a href="?p=zajecia&KodKursu='.$KodKursu.'">'.$informacja_o_kursie['NazwaKursu'].' ('.$informacja_o_kursie['NazwaFormy'].')</a>
&raquo; <a href="?p=terminy&id='.$IdZajecia.'">'."$Dzien $Tydzien $Godzina-$GodzinaKoniec, sala $Sala, $Budynek".'</a>
&raquo; <a href="?p=lista_obecnosci&IdZajecia='.$IdZajecia.'&id='.$_GET['id'].'">lista obecności</a>
</h2>
</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li><a href="?p=terminy&id='.$IdZajecia.'" class="tabbutton icon-date">lista terminów</a></li>
<li><a href="?p=terminy_obecnosci&id='.$IdZajecia.'" class="tabbutton icon-book_open">tabela obecności</a></li>
<li class="active"><a href="#" class="tabbutton icon-page_white_text">lista obecności</a></li>
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
			


	//mass action
/* SORTOWANIE */
$sql = "SELECT count(*) FROM zajecia_studentow WHERE zajecia_studentow.IdZajecia='$IdZajecia'"; 
$result = $db->prepare($sql); 
$result->execute(); 
$rekordow = $result->fetchColumn();
$LiczbaZajec=$rekordow;
$stron=ceil($rekordow/$nastronie);
	
	if($rekordow)
	{

	echo '

			<div id="dialog-obecnosc" style="display: none;" title="Obecność">
	<p><span class="ui-icon ui-icon-comment" style="float:left; margin:0 7px 20px 0;"></span>Wybierz odpowiedni przycisk, aby zmodyfikować obecność tego studenta.</p>
</div>
			
			<table class="pages_table">
			<thead>
				<tr>
					<th style="width: 120px;">nr indeksu</th>
					<th style="width: 50px;">obecny</th>
					<th>imię i nazwisko studenta</th>
					
				</tr>
			</thead>
			<tbody>
			<form id="mass-action-form" method="POST" action="">
			
			';

	$sql="SELECT zajecia_studentow.NrIndeksu as NrIndeksu,
	studenci.Imie as Imie,
	studenci.Nazwisko as Nazwisko,
	(SELECT Typ FROM obecnosci WHERE obecnosci.NrIndeksu=zajecia_studentow.NrIndeksu AND obecnosci.IdTermin=$IdTermin) AS StudentObecny
	FROM zajecia_studentow
	LEFT JOIN studenci ON zajecia_studentow.NrIndeksu=studenci.NrIndeksu
	WHERE zajecia_studentow.IdZajecia=$IdZajecia
    ORDER BY ".$mysql_sort."
				  LIMIT ".$limit_begin.", ".$limit_end;
	
	

			
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	

	echo '<tr>
						<td><input type="checkbox" name="mass-action-item[]" value="'.$result['IdTermin'].'" id="check-'.$result['IdTermin'].'" style="margin-right: 10px;"/> <label for="check-'.$result['IdTermin'].'"><code>'.$result['NrIndeksu'].'</code></label></td>
						<td>
						';
						echo '
<a href="#" title="naciśnij, aby zmienić" class="dynamic-obecnosc {NrIndeksu: \''.$result['NrIndeksu'].'\',IdTermin: \''.$IdTermin.'\',action: \'change\'}">';
$ob=$result['StudentObecny'];
if($ob==0) echo '<span class="ikonka icon-cross" title="nieobecny">-</span>';
else if($ob==1) echo '<span class="ikonka icon-tick" title="obecny">+</span>';
else if($ob==2) echo '<span class="ikonka icon-hourglass" title="spóźnienie">.</span>';
else  echo '<span class="ikonka icon-help" title="brak informacji">?</span>';
		echo "</a>";
						
echo'
</td>
					<td>'.$result['Imie'].' '.$result['Nazwisko'].'</td>


					
					
					
				</tr>';
				
	$iter++;
	}
		
			

		echo '</tbody>
		<input type="hidden" name="mass-action-items" value="'.$iter.'"/>
		</form>
						<tfoot>
				<tr>
					<td colspan="5">
								<div class="na-stronie">
				
	
					
					<div class="rekordy">
						<form action="" method="POST">
						Pokazuj
						<select name="nastronie">
							<option value="10"';
								if($_SESSION['nastronie']==10) echo ' selected="selected"';
								echo '>10</option>
							<option value="25"';
								if($_SESSION['nastronie']==25) echo ' selected="selected"';
								echo '>25</option>
							<option value="50"';
								if($_SESSION['nastronie']==50) echo ' selected="selected"';
								echo '>50</option>
							<option value="250"';
								if($_SESSION['nastronie']==250) echo ' selected="selected"';
								echo '>250</option>
							<option value="1000"';
								if($_SESSION['nastronie']==1000) echo ' selected="selected"';
								echo '>1000</option>
						</select>
						rekordów, sortuj wg. 
						
							<select name="sortowanie">
								<option value="1"';
								if($_SESSION['sort']==1) echo ' selected="selected"';
								echo '>Nazwisko &uarr;</option>
								<option value="2"';
								if($_SESSION['sort']==2) echo ' selected="selected"';
								echo '>Nazwisko &darr;</option>
								<option value="3"';
								if($_SESSION['sort']==3) echo ' selected="selected"';
								echo '>Nr indeksu &uarr;</option>
								<option value="4"';
								if($_SESSION['sort']==4) echo ' selected="selected"';
								echo '>Nr indeksu&darr;</option>
								
						</select>
							<input type="submit" class="submit" value="ok"/>
						</form>
					</div> <!-- sortowanie -->
					
				</div> <!-- na stronie -->	
		<div class="sortowanie">
						<div class="quick-jump">
						<form action="" method="POST">
						Skocz do
						<input type="text" value="'.$strona.'" name="quickjump" class="pole"/>
						<input type="hidden" value="'.$stron.'" name="allpages" />
							<input type="submit" class="submit" value="ok"/>
						</form>
					</div> <!-- quick jump -->
		<span class="tytul">Strona <strong><a href="#">'.$strona.'</a></strong> z <strong><a href="?p='.$pageuri.'&strona='.$stron.$search_url_addition.'">'.$stron.'</a></strong></span>
							<ul>';
							if($strona>1)
							echo '
								<li class="prev disabled"><a href="?p='.$pageuri.'&strona='.($strona-1).$search_url_addition.'" class="icon-arrow_left">poprzednia</a></li>';

							if($strona<$stron)
							echo '
								<li class="next"><a href="?p='.$pageuri.'&strona='.($strona+1).$search_url_addition.'"  class="icon-arrow_right">następna</a></li>';
								
								echo'
							</ul>
							<div class="clear"></div>
						</div> <!-- sortowanie  -->
						

				
				<div class="clear"></div>
					</td>
				</tr>
				</tfoot>	
			</table>
			
			</div>';
				}
				
				else echo ' <div class="dialog-box">
					
					<strong class="question">Do tego kursu nie są przypisane żadne zajęcia.</strong>
					<p>Aby dodać zajęcia, naciśnij przycisk <strong>dodaj nowe zajęcia</strong> poniżej.</p>
						<div class="button-holder">
						<a href="?p=zajecia_edycja" class="button"><span class="icon-calendar_add">dodaj nowe zajęcia</span></a>
						<a href="?p=kursy" class="button"><span class="icon-arrow_left">powrót</span></a>
						
						<div class="clear"></div>
						</div>
					</div>';
			


}

?>