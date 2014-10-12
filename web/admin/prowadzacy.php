<?php

if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="prowadzacy";

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
	case 1: $mysql_sort='prowadzacy.IdProwadzacy ASC'; break;
	case 2: $mysql_sort='prowadzacy.IdProwadzacy  DESC'; break;	
	case 3: $mysql_sort='prowadzacy.Nazwisko ASC'; break;
	case 4: $mysql_sort='prowadzacy.Nazwisko DESC'; break;
}

$search_phrase="";
$search_url_addition="";
if(isset($_GET['searchword']) && strlen($_GET['searchword'])>0 && sizeof($_GET['areas'])>0)
{
	$search_phrase=" WHERE";
	$word=safesearch($_GET['searchword']);
	$search_url_addition.="&searchword=".$word;
	foreach($_GET['areas'] as $it)
	{
		$search_phrase=$search_phrase." $it LIKE '%$word%' OR";
		$search_url_addition.="&areas%5B%5D=$it";
	}
	$search_phrase=substr_replace($search_phrase ,"",-3);
	

}
/* SORTOWANIE */
$sql = "SELECT count(*) FROM prowadzacy".$search_phrase; 
$result = $db->prepare($sql); 
$result->execute(); 
$rekordow = $result->fetchColumn();
$stron=ceil($rekordow/$nastronie);
echo '

<div id="page-title">


<h2><a href="?p=home">administracja</a> &raquo; <a href="?p='.$pageuri.'">prowadzący</a> </h2> 
</div>

<div id="lang-bar">';



echo '<div id="search-panel" ';
if(!isset($_GET['searchword'])) echo ' style="display: none;"';
echo '>
<form action="" method="GET" id="searchForm">
<input type="hidden" name="p" value="'.$pageuri.'"/>
<input type="hidden" name="strona" value="'.$strona.'"/>
<div id="form_container"><div class="item-container">
				<strong>Szukaj</strong> <input type="text" name="searchword" id="search_searchword" size="30" maxlength="20" value="'.$_GET['searchword'].'" class="inputbox" />
		
				<button name="Search" onclick="this.form.submit()" class="button">Szukaj</button>
</div>
	
		
	<div class="clear"></div>
	<div class="separator"></div>
		<div class="item-container">
			<strong>Obszar wyszukiwań</strong>
				<input type="checkbox" name="areas[]" value="IdProwadzacy" id="area_1" '.(@in_array("IdProwadzacy",$_GET['areas'])? ' checked="checked")':'').' />
			<label for="area_1">
				numer ID prowadzącego	</label>
				<input type="checkbox" name="areas[]" value="Nazwisko" id="area_2" '.(@in_array("Nazwisko",$_GET['areas'])? ' checked="checked")':'').' />
			<label for="area_2">
				nazwiska			</label>
				<input type="checkbox" name="areas[]" value="Uid" id="area_3" '.(@in_array("Uid",$_GET['areas'])? ' checked="checked")':'').' />
			<label for="area_3">
				numery ID karty			</label>
				
			</div>
</div>
</form>
</div>
		
';
echo '
<ul class="tabs">
<li class="current"><a href="?p='.$pageuri.'" class="tabbutton icon-application_view_list">przeglądanie listy prowadzących</a></li>
<li><a href="?p=prowadzacy_edycja" class="tabbutton icon-add">dodaj prowadzącego</a></li>
<li><a href="#" class="tabbutton icon-application_form" id="search-panel-show">wyszukaj...</a></li>

<li><a href="#" class="tabbutton icon-delete" id="mass-action">usuń zaznaczone</a></li>
</ul>

			<div class="clear"></div>
			
			</div>';
			

if(isset($_GET['searchword']))
{
echo '
			<div class="pod-wyszukiwaniem">
			
			<span style="color: #777;">Wyniki wyszukiwania dla frazy &nbsp;&nbsp;<strong style="color: black;">'.$_GET['searchword'].'</strong> ('.$rekordow.' wyników)</span>';

echo '</span>

</div>
<div class="clear"></div>';
}

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
				$response=$response && $admin->Action_UsunProwadzacego($el);
		}
		if($response && $jest)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
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
						<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
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
			
			<strong class="question">Czy na pewno chcesz usunąć tego prowadzącego?</strong>
				
				<p>Zamierzasz usunąć prowadzącego o numerze identyfikacyjnym '.$_GET['id'].' wraz z każdą informacją na jego temat występującą w bazie danych. Jeśli jesteś pewien, że chcesz usunąć te dane bezpowrotnie, naciśnij tak.</p>
				<div class="button-holder">
					<a href="?p='.$pageuri.'&action=delete&id='.$_GET['id'].'&strona='.$strona.'&confirm=1" class="button red"><span class="icon-delete"><strong>usuń</strong></span></a>
					<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
				<div class="clear"></div>
				</div>
			
			</div>';
			}
			
			else
			{
				/* USUWANIE STUDENTA O ZADANYM ELEMENCIE */
				$response=$admin->Action_UsunProwadzacego($_GET['id']);
				if($response)
				{
					echo '
					<div class="dialog-box">
					
					<strong class="question">Usunięto poprawnie.</strong>
						<div class="button-holder">
						<a href="?p='.$pageuri.'&strona='.$strona.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
				}
				else 
				{
					echo '<div class="dialog-box">
					
					<strong class="question">Prowadzący nie istnieje lub nie może zostać usunięty.</strong>
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
					<th class="col-numerindeksu">ID</th>
					<th class="col-url" colspan="2">tytuł, imię i nazwisko</th>
					<th class="col-karta">numer ID karty</th>
					<th class="col-operacja">operacja</th>
				</tr>
			</thead>
			<tbody>
			<form id="mass-action-form" method="POST" action="">
			
			';
	$sql="SELECT prowadzacy.IdProwadzacy as IdProwadzacy, prowadzacy.Imie as Imie, prowadzacy.Nazwisko as Nazwisko, prowadzacy.Tytul as Tytul, prowadzacy.Uid as Uid FROM prowadzacy ".
				  $search_phrase
				  ." ORDER BY ".$mysql_sort."
				  LIMIT ".$limit_begin.", ".$limit_end;
				
			
			
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	echo '<tr>
						<td class="col-id"><input type="checkbox" name="mass-action-item[]" value="'.$result['IdProwadzacy'].'" id="check-'.$result['IdProwadzacy'].'"/ style="margin-right: 10px;"> <label for="check-'.$result['IdProwadzacy'].'"><code>'.$result['IdProwadzacy'].'</code></label></td>
					<td class="col-imie" colspan="2"><em>'.$result['Tytul'].'</em> '.$result['Imie'].'
					'.$result['Nazwisko'].'</td>	
					<td class="col-karta"><code>'.$result['Uid'].'</code></td>
					<td class="col-operacja">

						<a href="?p=prowadzacy_edycja&id='.$result['IdProwadzacy'].'" class="icon-page_white_edit"><span>edytuj</span></a>
						<a href="?p=prowadzacy&action=delete&id='.$result['IdProwadzacy'].'&strona='.$strona.$search_url_addition.'" class="icon-cross" rel="'.$result['id'].'"><span>usuń</span></a></td>
					
					
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
								echo '>nr. ID prowadzących &uarr;</option>
								<option value="2"';
								if($_SESSION['sort']==2) echo ' selected="selected"';
								echo '>nr. ID prowadzących &darr;</option>
								<option value="3"';
								if($_SESSION['sort']==3) echo ' selected="selected"';
								echo '>nazwiska &uarr;</option>
								<option value="4"';
								if($_SESSION['sort']==4) echo ' selected="selected"';
								echo '>nazwiska &darr;</option>
								
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
					
										<strong class="question">Nie znaleziono prowadzących</strong>
					<p>Nie odnaleziono w bazie szukanych rekordów. Wskazówka: jeżeli używasz wyszukiwarki, zmień kryteria wyszukiwania lub uogólnij frazę, której poszukujesz. Aby dodać prowadzącego do bazy, naciśnij przycisk <strong>dodaj prowadzącego</strong>.</p>
						<div class="button-holder">
						<a href="?p=prowadzacy_edycja" class="button"><span class="icon-add">dodaj prowadzącego</span></a>
						<a href="?p='.$pageuri.'" class="button"><span class="icon-arrow_left">powrót</span></a>
						<div class="clear"></div>
						</div>
					</div>';
			


?>