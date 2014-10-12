<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="zajecia";

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
	case 1: $mysql_sort='zajecia.Tydzien ASC, zajecia.Dzien ASC, zajecia.Godzina ASC'; break;
	case 2: $mysql_sort='zajecia.Tydzien DESC, zajecia.Dzien DESC, zajecia.Godzina DESC'; break;	
	case 3: $mysql_sort='zajecia.Dzien ASC, zajecia.Godzina ASC, zajecia.Tydzien ASC'; break;
	case 4: $mysql_sort='zajecia.Dzien DESC, zajecia.Godzina DESC, zajecia.Tydzien DESC'; break;
	}

if(!isset($_GET['KodKursu']))
{
	echo '<div class="dialog-box">
				
				<strong class="question">Nie można wyświetlić listy zajęć!</strong>
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
	$KodKursu=$_GET['KodKursu'];
	$search_phrase="";

/* SORTOWANIE */
$sql = "SELECT count(*) FROM zajecia WHERE zajecia.KodKursu='$KodKursu'"; 
$result = $db->prepare($sql); 
$result->execute(); 
$rekordow = $result->fetchColumn();
$stron=ceil($rekordow/$nastronie);

$sql = "SELECT kursy.NazwaKursu as NazwaKursu,formy_kursow.NazwaFormy as NazwaFormy FROM kursy,formy_kursow WHERE kursy.KodKursu='$KodKursu' AND kursy.IdFormy=formy_kursow.IdFormy"; 

foreach($db->query($sql) as $informacja_o_kursie);
echo '

<div id="page-title">


<h2><a href="?p=home">administracja</a> &raquo; <a href="?p=kursy">kursy</a> &raquo; '.$informacja_o_kursie['NazwaKursu'].' ('.$informacja_o_kursie['NazwaFormy'].')
&raquo; <a href="?p=zajecia&KodKursu='.$KodKursu.'">zajęcia</a>
</h2> 
</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li class="current"><a href="#" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li><a href="?p=zajecia_edycja&KodKursu='.$KodKursu.'" class="tabbutton icon-add">dodaj zajęcia</a></li>
<li><a href="#" class="tabbutton icon-delete" id="mass-action">usuń zaznaczone</a></li>
</ul>

			<div class="clear"></div>
			
			</div>';
			


	//mass action
	if(isset($_POST['mass-action-items']))
	{
		$response=true;
		$jest=false;
		$elems=$_POST['mass-action-items'];
		/*for($i=0;$i<$elems && $response;$i++)
		{
			if(isset($_POST['mass-action-item-'.$i]))
			{
			$jest=true;
				$response=$response && $admin->Action_UsunStudenta($_POST['mass-action-item-'.$i]);
			}
		}*/
		foreach($_POST['mass-action-item'] as $el)
		{
		$jest=true;
				$response=$response && $admin->Action_UsunZajecia($el);
		}
		if($response && $jest)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&KodKursu='.$_GET['KodKursu'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
				}
				else 
				{
					echo '<div class="dialog-box">
					
					<strong class="question">Błąd podczas usuwania elementów.</strong>
					<p>Nie zaznaczono żadnego elementu albo wystąpił nieoczekiwany wyjątek.</p>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&KodKursu='.$_GET['KodKursu'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>
					';
				}
		
	}
	
	if(isset($_GET['action']))
	{
		$action=$_GET['action'];
		if($action=='delete' && isset($_GET['id']))
		{
			if($_GET['confirm']!=1)
			{
			echo '<div class="dialog-box">
			
			<strong class="question">Czy na pewno chcesz usunąć te zajęcia?</strong>
				
				<p>Zamierzasz usunąć zajęcia o id <strong> '.$_GET['id'].'</strong> wraz z każdą informacją na ich temat, tzn. obecnościami, relacjami student-zajęcia i prowadzący-zajęcia? Jeśli jesteś pewien, że chcesz usunąć te dane bezpowrotnie, naciśnij tak.</p>
				<div class="button-holder">
					<a href="?p='.$pageuri.'&action=delete&KodKursu='.$KodKursu.'&id='.$_GET['id'].'&strona='.$strona.'&confirm=1" class="button red"><span class="icon-delete"><strong>usuń</strong></span></a>
					<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
				<div class="clear"></div>
				</div>
			
			</div>';
			}
			
			else
			{
				/* USUWANIE STUDENTA O ZADANYM ELEMENCIE */
				$response=$admin->Action_UsunZajecia($_GET['id']);
				if($response)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&KodKursu='.$_GET['KodKursu'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
				}
				else 
				{
					echo '<div class="dialog-box">
					
					<strong class="question">Kurs nie istnieje lub nie może zostać usunięty.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>
					';
				}
			}
		}
	}

	
	if($rekordow)
	{

	echo '
			
			<table class="pages_table">
			<thead>
				<tr>
					<th class="col-kodkursu">id</th>
					
					<th class="col-nazwakursu">dzień, tydzień, godzina zajęć</th>
					<th class="col-zajeciaaddcol">ilość terminów, studentów zapisanych, prowadzący</th>
					<th class="col-operacja">operacja</th>
				</tr>
			</thead>
			<tbody>
			<form id="mass-action-form" method="POST" action="">
			
			';
			//		LEFT JOIN zajecia_studentow ON zajecia_studentow.IdZajecia=zajecia.IdZajecia
			// COUNT(zajecia_studentow.NrIndeksu) AS ZapisanychStudentow,
	$sql="SELECT
	zajecia.IdZajecia as IdZajecia,
	zajecia.Dzien as Dzien,
	zajecia.Godzina as Godzina,
	zajecia.GodzinaKoniec as GodzinaKoniec,
	zajecia.Tydzien as Tydzien,
	COUNT(terminy.IdTermin) AS LiczbaTerminow,
	(SELECT COUNT(*) FROM zajecia_studentow WHERE zajecia_studentow.IdZajecia=zajecia.IdZajecia) AS ZapisanychStudentow,
	sale.Sala,
	sale.Budynek,
	prowadzacy.Imie,
	prowadzacy.Nazwisko,
	prowadzacy.Tytul
	FROM zajecia
	LEFT JOIN terminy ON terminy.IdZajecia=zajecia.IdZajecia
	LEFT JOIN sale ON zajecia.IdSali=sale.IdSali

	LEFT JOIN prowadzacy ON prowadzacy.IdProwadzacy=zajecia.IdProwadzacy 

	WHERE zajecia.KodKursu='$KodKursu'"
	." GROUP BY zajecia.IdZajecia ORDER BY ".$mysql_sort."
	LIMIT ".$limit_begin.", ".$limit_end;
				
			
			
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	//formatowanie
	switch ($result['Dzien'])
	{
		case 'pon': $result['Dzien']="poniedziałek";break;
		case 'wt': $result['Dzien']="wtorek";break;
		case 'sr': $result['Dzien']="środa";break;
		case 'czw': $result['Dzien']="czwartek";break;
		case 'pt': $result['Dzien']="piątek";break;
		case 'so': $result['Dzien']="sobota";break;
		case 'ndz': $result['Dzien']="niedziela";break;
	}
	if($result['Tydzien']=="T") $result['Tydzien']="";
	echo '<tr>
						<td class="col-IdZajecia"><input type="checkbox" name="mass-action-item[]" value="'.$result['IdZajecia'].'" id="check-'.$result['IdZajecia'].'" style="margin-right: 10px;"/> <label for="check-'.$result['IdZajecia'].'"><code>'.$result['IdZajecia'].'</code></label></td>
						
					<td class="col-nazwakursu"><a href="?p=terminy&id='.$result['IdZajecia'].'" title="kliknij, aby otworzyć listę zapisanych studentów i terminów tych zajęć"><strong>'.$result['Dzien'].'</strong> '.$result['Tydzien'].' <span class="ikonka icon-clock">godzina: </span> '.date_format(date_create($result['Godzina']),'H:i').' - '.date_format(date_create($result['GodzinaKoniec']),'H:i').' <span class="ikonka icon-door">godzina: </span> sala '.$result['Sala'].', '.$result['Budynek'].'</span></td>
<td>					<span class="ikonka icon-date">termin: </span><span class="ile-terminow">  <strong>'.$result['LiczbaTerminow'].'</strong></span> <span class="ikonka icon-group">studentów zapisanych: </span><span class="ile-terminow">  <strong>'.$result['ZapisanychStudentow'].'</strong>';
					
					if(!isset($result['Imie'])) echo '';
					else echo '<span class="ikonka icon-user_gray">prowadzący: </span><span class="ile-terminow">'.$result['Tytul'].' '.$result['Imie'][0].'. '.$result['Nazwisko'];
					echo '</a></td>

					<td class="col-operacja">

						<a href="?p=zajecia_edycja&id='.$result['IdZajecia'].'" class="icon-page_white_edit"><span>edytuj</span></a>
						<a href="?p='.$pageuri.'&action=delete&KodKursu='.$KodKursu.'&id='.$result['IdZajecia'].'&strona='.$strona.'" class="icon-cross" rel="'.$result['id'].'"><span>usuń</span></a></td>
					
					
				</tr>';
				
	$iter++;
	}
		
			

		echo '</tbody>
		<input type="hidden" name="mass-action-items" value="'.$iter.'"/>
		</form>
				<tfoot>
				<tr>
					<td colspan="4">
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
								echo '>terminów zajęć &uarr;</option>
								<option value="2"';
								if($_SESSION['sort']==2) echo ' selected="selected"';
								echo '>terminów zajęć &darr;</option>
								<option value="3"';
								if($_SESSION['sort']==3) echo ' selected="selected"';
								echo '>dni tygodnia &uarr;</option>
								<option value="4"';
								if($_SESSION['sort']==4) echo ' selected="selected"';
								echo '>dni tygodnia &darr;</option>
								
								
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
		<span class="tytul">Strona <strong><a href="#">'.$strona.'</a></strong> z <strong><a href="?p='.$pageuri.'&strona='.$stron.'&KodKursu='.$KodKursu.'">'.$stron.'</a></strong></span>
							<ul>';
							if($strona>1)
							echo '
								<li class="prev disabled"><a href="?p='.$pageuri.'&strona='.($strona-1).'&KodKursu='.$KodKursu.'" class="icon-arrow_left">poprzednia</a></li>';

							if($strona<$stron)
							echo '
								<li class="next"><a href="?p='.$pageuri.'&strona='.($strona+1).'&KodKursu='.$KodKursu.'"  class="icon-arrow_right">następna</a></li>';
								
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
						<a href="?p=zajecia_edycja&KodKursu='.$KodKursu.'" class="button"><span class="icon-calendar_add">dodaj nowe zajęcia</span></a>
						<a href="?p=kursy" class="button"><span class="icon-arrow_left">powrót</span></a>
						
						<div class="clear"></div>
						</div>
					</div>';
			


}

?>