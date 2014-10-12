<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="terminy";

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
	case 1: $mysql_sort='terminy.Data ASC'; break;
	case 2: $mysql_sort='terminy.Data DESC'; break;	
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
</h2> 
</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li class="current"><a href="#" class="tabbutton icon-date">lista terminów</a></li>
<li><a href="?p=terminy_obecnosci&id='.$IdZajecia.'" class="tabbutton icon-book_open">tabela obecności</a></li>
<li><a href="?p=zapisy&id='.$IdZajecia.'" class="tabbutton icon-user">zapisani studenci</a></li>
<li><a href="?p=terminy_edycja&IdZajecia='.$IdZajecia.'" class="tabbutton icon-add">dodaj termin</a></li>
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

		foreach($_POST['mass-action-item'] as $el)
		{
				$jest=true;
				$response=$response && $admin->Action_UsunTermin($el);
		}
		if($response && $jest)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&id='.$_GET['id'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
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
						<a href="?p='.$pageuri.'&id='.$_GET['id'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>
					';
				}
		
	}
	
	if(isset($_GET['action']))
	{
		$action=$_GET['action'];
		if($action=='delete' && isset($_GET['del_id']))
		{
			if($_GET['confirm']!=1)
			{
			echo '<div class="dialog-box">
			
			<strong class="question">Czy na pewno chcesz usunąć ten termin?</strong>
				
				<p>Zamierzasz usunąć termin o id <strong> '.$_GET['del_id'].'</strong> wraz z informacją o obecnościach w tym terminie. Jeśli jesteś pewien, że chcesz usunąć te dane bezpowrotnie, naciśnij tak.</p>
				<div class="button-holder">
					<a href="?p='.$pageuri.'&action=delete&del_id='.$_GET['del_id'].'&strona='.$strona.'&confirm=1&id='.$_GET['id'].'" class="button red"><span class="icon-delete"><strong>usuń</strong></span></a>
					<a href="?p='.$pageuri.'&strona='.$strona.'&id='.$_GET['id'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
				<div class="clear"></div>
				</div>
			
			</div>';
			}
			
			else
			{
				/* USUWANIE STUDENTA O ZADANYM ELEMENCIE */
				$response=$admin->Action_UsunTermin($_GET['del_id']);
				if($response)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&id='.$_GET['id'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
				}
				else 
				{
					echo '<div class="dialog-box">
					
					<strong class="question">Termin nie istnieje lub nie może zostać usunięty.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&id='.$_GET['id'].'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
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
					
					<th class="col-nazwakursu">data zajęć</th>
					<th style="width: 100px;">obecności</th>
					<th style="width: 100px;">otwarcie sali</th>
					<th class="col-operacja">operacja</th>
				</tr>
			</thead>
			<tbody>
			<form id="mass-action-form" method="POST" action="">
			
			';

	$sql="SELECT 
	terminy.IdTermin as IdTermin,
	terminy.Data as Data,
	(SELECT COUNT(*) FROM obecnosci WHERE obecnosci.IdTermin=terminy.IdTermin) AS ObecnychStudentow
	FROM terminy WHERE terminy.IdZajecia=$IdZajecia
	GROUP BY terminy.IdTermin ORDER BY ".$mysql_sort."
	LIMIT ".$limit_begin.", ".$limit_end;
			
	$iter=0;
	foreach($db->query($sql) as $result)
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

						<a href="?p=terminy_edycja&IdZajecia='.$IdZajecia.'&id='.$result['IdTermin'].'" class="icon-page_white_edit"><span>edytuj</span></a>
						<a href="?p='.$pageuri.'&action=delete&id='.$IdZajecia.'&del_id='.$result['IdTermin'].'&strona='.$strona.'" class="icon-cross" rel="'.$result['id'].'"><span>usuń</span></a></td>
					
					
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
								echo '>Data &uarr;</option>
								<option value="2"';
								if($_SESSION['sort']==2) echo ' selected="selected"';
								echo '>Data &darr;</option>

								
								
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
		<span class="tytul">Strona <strong><a href="#">'.$strona.'</a></strong> z <strong><a href="?p='.$pageuri.'&strona='.$stron.'&id='.$IdZajecia.'">'.$stron.'</a></strong></span>
							<ul>';
							if($strona>1)
							echo '
								<li class="prev disabled"><a href="?p='.$pageuri.'&id='.$IdZajecia.'&strona='.($strona-1).'" class="icon-arrow_left">poprzednia</a></li>';

							if($strona<$stron)
							echo '
								<li class="next"><a href="?p='.$pageuri.'&id='.$IdZajecia.'&strona='.($strona+1).'"  class="icon-arrow_right">następna</a></li>';
								
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
					
					<strong class="question">Do tych zajęć nie są przypisane żadne terminy.</strong>
					<p>Aby dodać termin, naciśnij przycisk <strong>dodaj nowy termin</strong> poniżej.</p>
						<div class="button-holder">
						<a href="?p=terminy_edycja&IdZajecia='.$_GET['id'].'" class="button"><span class="icon-calendar_add">dodaj nowy termin</span></a>
						<a href="?p=zajecia&KodKursu='.$KodKursu.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						
						<div class="clear"></div>
						</div>
					</div>';
			


}

?>