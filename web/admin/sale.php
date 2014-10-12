<?php

if($_SESSION['logged']!=1) header('Location: index.php');

$pageuri="sale";

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
	case 1: $mysql_sort='sale.Sala,sale.Budynek ASC'; break;
	case 2: $mysql_sort='sale.Sala,sale.Budynek DESC'; break;	
	case 3: $mysql_sort='sale.Budynek,sale.Sala ASC'; break;
	case 4: $mysql_sort='sale.Budynek,sale.Sala DESC'; break;
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
$sql = "SELECT count(*) FROM sale".$search_phrase; 
$result = $db->prepare($sql); 
$result->execute(); 
$rekordow = $result->fetchColumn();
$stron=ceil($rekordow/$nastronie);
echo '

<div id="page-title">


<h2><a href="?p=home">administracja</a> &raquo; <a href="?p='.$pageuri.'">sale</a></h2> 
</div>

<div id="lang-bar">';
			echo '
		
';

echo '
<ul class="tabs">
<li class="current"><a href="?p='.$pageuri.'" class="tabbutton icon-application_view_list">przeglądanie sal</a></li>
<li><a href="?p=sale_edycja" class="tabbutton icon-add">dodaj salę</a></li>

<li><a href="#" class="tabbutton icon-delete" id="mass-action">usuń zaznaczone</a></li>
</ul>

			<div class="clear"></div>
			
			</div>
			
			';
			

if(isset($_GET['searchword']) && $_GET['searchword']!="")
{
echo '<div class="clear"></div>
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
				$response=$response && $admin->Action_UsunSale($el);
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
					<p>Nie można usunąć wszystkich sal, które zaznaczono. Istnieją zajęcia, do których przypisana jest jedna z usuwanych sal. Najpierw usuń zajęcia, do których przypisana jest ta sala, a następnie powtórz operację.</p>
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
			
			<strong class="question">Czy na pewno chcesz usunąć tę salę?</strong>
				
				<p>Zamierzasz usunąć salę o nr. id '.$_GET['id'].'. Jeśli jesteś pewien, że chcesz usunąć te dane bezpowrotnie, naciśnij tak. Operacja zakończy się niepowodzeniem, jeżeli będą istniały zajęcia, do których przypisana jest ta sala.</p>
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
				$response=$admin->Action_UsunSale($_GET['id']);
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
					<strong class="question">Błąd podczas usuwania elementów.</strong>
					<p>Nie można usunąć tej sali. Istnieją zajęcia, do których przypisana jest usuwana sala. Najpierw usuń zajęcia, do których przypisana jest ta sala, a następnie powtórz operację.</p>
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
					<th style="width: 80px;">id sali</th>
					<th>sala</th>
					<th>budynek</th>
					<th class="col-operacja">operacja</th>
				</tr>
			</thead>
			<tbody>
			<form id="mass-action-form" method="POST" action="">
			
			';
	$sql="SELECT sale.IdSali as IdSali,sale.Sala as Sala,sale.Budynek as Budynek FROM sale
		 ORDER BY ".$mysql_sort."
				  LIMIT ".$limit_begin.", ".$limit_end;
				  
				
			
			
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	echo '<tr>
						<td><input type="checkbox" name="mass-action-item[]" value="'.$result['IdSali'].'" id="check-'.$result['IdSali'].'"/ style="margin-right: 10px;"> <label for="check-'.$result['IdSali'].'"><code>'.$result['IdSali'].'</code></label></td>
					<td>'.$result['Sala'].'</td>	
					<td>'.$result['Budynek'].'</td>
					<td class="col-operacja">
					
					<!--<a href="'.$result['seo_url'].'/" class="icon-book" target="_blank"><span>otwórz dziennik</span></a>
					<a href="'.$result['seo_url'].'/" class="icon-chart_curve" target="_blank"><span>zobacz statystyki obecności</span></a>-->
						<a href="?p=sale_edycja&id='.$result['IdSali'].'" class="icon-page_white_edit"><span>edytuj</span></a>
						<a href="?p='.$pageuri.'&action=delete&id='.$result['IdSali'].'&strona='.$strona.'" class="icon-cross" rel="'.$result['id'].'"><span>usuń</span></a></td>
					
					
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
								echo '>numer sali &uarr;</option>
								<option value="2"';
								if($_SESSION['sort']==2) echo ' selected="selected"';
								echo '>numer sali &darr;</option>
								<option value="3"';
								if($_SESSION['sort']==3) echo ' selected="selected"';
								echo '>budynek &uarr;</option>
								<option value="4"';
								if($_SESSION['sort']==4) echo ' selected="selected"';
								echo '>budynek &darr;</option>
								
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
					
					<strong class="question">Nie znaleziono sal.</strong>
					<p>Nie odnaleziono w bazie żadnych sal. Naciśnij przycisk <strong>dodaj salę</strong>, aby wprowadzić nową salę do systemu.</p>
						<div class="button-holder">
							
				<a href="?p=sale_edycja" class="button"><span class="icon-add">dodaj salę</span></a>

						<div class="clear"></div>
						</div>
					</div>';
			


?>