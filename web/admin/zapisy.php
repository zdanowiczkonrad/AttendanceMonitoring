<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="zapisy";

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

/* SORTOWANIE */

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
$sql = "SELECT count(*) FROM zajecia_studentow WHERE zajecia_studentow.IdZajecia='$IdZajecia'"; 
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
&raquo; <a href="?p=zapisy&id='.$IdZajecia.'">zapisy</a>
</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li><a href="?p=terminy&id='.$IdZajecia.'" class="tabbutton icon-date">lista terminów</a></li>
<li><a href="?p=terminy_obecnosci&id='.$IdZajecia.'" class="tabbutton icon-book_open">tabela obecności</a></li>
<li class="current"><a href="#" class="tabbutton icon-user">zapisani studenci</a></li>
<li><a href="#" class="tabbutton icon-delete" id="mass-action">wypisz zaznaczonych studentów</a></li>
</ul>

			<div class="clear"></div>
			
			</div>';
			

			//masowa akcja wypisywania studentow


	if(isset($_POST['mass-action-item']))
	{
	
		$response=true;
		$jest=false;
		$elems=$_POST['mass-action-item'];
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
				$response=$response && $admin->Action_WypiszStudenta($el,$_POST['IdZajecia']);
		}
		if($response && $jest)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Studentów wypisano poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
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
						<a href="?p='.$pageuri.'&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>
					';
				}
		
	}
	
	if(isset($_GET['action']))
	{
		$action=$_GET['action'];
		if($action=='delete' && isset($_GET['id']) && isset($_GET['del_id']))
		{
			if($_GET['confirm']!=1)
			{
			echo '<div class="dialog-box">
			
			<strong class="question">Czy na pewno chcesz wypisać tego studenta?</strong>
				
				<p>Zamierzasz wypisać studenta o numerze albumu '.$_GET['del_id'].' z tych zajęć. Czy chcesz kontynuować?</p>
				<div class="button-holder">
					<a href="?p='.$pageuri.'&action=delete&id='.$_GET['id'].'&del_id='.$_GET['del_id'].'&confirm=1" class="button red"><span class="icon-user_delete"><strong>wypisz</strong></span></a>
					<a href="?p='.$pageuri.'&id='.$_GET['id'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
				<div class="clear"></div>
				</div>
			
			</div>';
			}
			
			else
			{
				/* WYPISANIE STUDENTA O ZADANYM ELEMENCIE */
				$response=$admin->Action_WypiszStudenta($_GET['del_id'],$_GET['id']);
				if($response)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Studenta wypisano poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&id='.$_GET['id'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
				}
				else 
				{
					echo '<div class="dialog-box">
					
					<strong class="question">Student nie istnieje lub nie może zostać usunięty.</strong>
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
	


	
	if($rekordow>0)
	{
	echo '
					<form id="mass-action-form" method="POST" action="">
					<input type="hidden" name="IdZajecia" value="'.$_GET['id'].'"/>
			<table class="pages_table">
			<thead>
				<tr>
					<th class="col-numerindeksu">numer indeksu</th>
					<th>imię i nazwisko</th>
					<th class="col-operacja">operacja</th>
				</tr>
			</thead>
			<tbody>
	
			
			';
	//lista zajec
	$sql="SELECT 
	zajecia_studentow.NrIndeksu as NrIndeksu,
	studenci.Imie as Imie,
	studenci.Nazwisko as Nazwisko
	FROM zajecia_studentow 
	LEFT JOIN studenci ON zajecia_studentow.NrIndeksu=studenci.NrIndeksu
	WHERE zajecia_studentow.IdZajecia=$IdZajecia
	ORDER BY ".$mysql_sort."
    LIMIT ".$limit_begin.", ".$limit_end;
	

			
	$iter=0;

		$iter=0;
	foreach($db->query($sql) as $result)
	{
	echo '<tr>
						<td class="col-numerindeksu"><input type="checkbox" name="mass-action-item[]" value="'.$result['NrIndeksu'].'" id="check-'.$result['NrIndeksu'].'" style="margin-right: 10px;"> <label for="check-'.$result['NrIndeksu'].'"><code>'.$result['NrIndeksu'].'</code></label></td>
					<td>'.$result['Imie'].'
					'.$result['Nazwisko'].'</td>	
					<td class="col-operacja" style="width: 120px;">
					<a href="?p=zapisy&action=delete&del_id='.$result['NrIndeksu'].'&id='.$IdZajecia.'" class="icon-user_delete" rel="'.$result['id'].'"><span>wypisz studenta</span></a></td>
					
					
				</tr>';
				
	$iter++;
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
		<td></td><td colspan="2"><a href="?p=zapisy_dodaj&id='.$IdZajecia.'" class="button" style="margin: -4px 10px -4px; display: block;"><span class="icon-user_add" style="color: #555;">dodaj studenta do tych zajęć...</span></a>
		
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

		</tfoot>
				
			</table>
			</form>
	';
			
				
				
		}
						
		else echo ' <div class="dialog-box">
			
			<strong class="question">Do tych zajęć nie zapisano jeszce żadnego studenta.</strong>
			<p>Aby zapisać studentów do tych zajęć, naciśnij przycisk <strong>zapisz studenta</strong> poniżej.</p>
				<div class="button-holder">
				<a href="?p=zapisy_dodaj&id='.$_GET['id'].'" class="button"><span class="icon-user_add">zapisz studenta</span></a>
				<a href="?p=terminy&id='.$_GET['id'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
				
				<div class="clear"></div>
				</div>
			</div>';
		

}

?>