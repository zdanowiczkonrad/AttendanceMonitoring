<?php
if($_SESSION['logged']!=1) header('Location: index.php');

$pageuri="zapisy_dodaj";


$tryb_edycji=0;

$tytul_belki="zapisz studenta do zajęć";







if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$response=$admin->Action_ZapiszStudenta($_POST['NrIndeksu'],$_POST['IdZajecia']);
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do widoku zapisów.</p>
					<div class="button-holder">
					<a href="?p=zapisy&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się wykonać operacji. Sprawdź poprawność wpisanych danych.</p>
					<div class="button-holder">
					<a href="?p=zapisy&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
}
// javascript validation

else 

{

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
&raquo; <a href="?p=zapisy&id='.$IdZajecia.'">zapisy</a>
&raquo; dodaj studenta
</h2> 
</div>

<div id="lang-bar">';
echo '
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li>
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>
<li><a href="?p=terminy&id='.$IdZajecia.'" class="tabbutton icon-date">lista terminów</a></li>
<li><a href="?p=terminy_obecnosci&id'.$IdZajecia.'" class="tabbutton icon-book_open">tabela obecności</a></li>
<li><a href="?p=zapisy&id='.$IdZajecia.'" class="tabbutton icon-user">zapisani studenci</a></li>
<li class="current"><a href=#" class="tabbutton icon-add">dodaj studenta</a></li>
</ul>

			<div class="clear"></div>
			
			</div>';
			



echo '
<script type="text/javascript">
$(document).ready(function() {
	// validate signup form on keyup and submit
	var validator = $("#form_glowny").validate({
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
		
			if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else
				error.appendTo( element.parent().next() );
		},
		// specifying a submitHandler prevents the default submit, good for the demo

		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
});
</script>';


echo '

		
	<form action="admin.php?p='.$pageuri.'" method="post" id="form_glowny">
	<input type="hidden" name="IdZajecia" value="'.$_GET['id'].'"/>
	<table class="pages_table">';

	echo '
	<thead>
		<tr>
			<td colspan="3"><strong>'.$tytul_belki.'</strong></td>
		</tr>
	</thead>
	
	<tbody>
					<tr>
			<td class="col-title-settings"><strong>Student</strong>Wpisz imię lub nazwisko studenta w dużym polu, aby wyszukać i wybrać</td>
			<td class="col-input-settings">
<input class="setting-input-text" type="hidden" id="StudenciValue" name="NrIndeksu" value=""/>
			<input class="setting-input-text {required: true,messages:{required: \'Wyszukaj i wybierz studenta z listy\'}}" type="text" id="StudenciValueDisabled" style="width: 50px; padding-left: 1px; text-align: center; padding-right: 0;" value=""/>&nbsp;<input class="setting-input-text" style="width: 300px;"  id="StudenciAutoSuggest" value=""/>

</td>
<td class="status"></td>			
		</tr>

		</tbody>
		
	';
	



echo '

</table>

	<input type="submit" class="submit-button" value="zapisz"/> 
	</form>
';
}

?>