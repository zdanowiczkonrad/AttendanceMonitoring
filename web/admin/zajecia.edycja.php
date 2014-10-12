<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="zajecia_edycja";
$tryb_edycji=0;
// znaczy że edycja

if(isset($_GET['id']))
{
		$result=$admin->Action_PojedynczeZajecia($_GET['id']);
		if($result)
		{
			foreach($result as $row)
			{
				$IdZajecia=$row['IdZajecia'];
				$IdSali=$row['IdSali'];
				$Dzien=$row['Dzien'];
				$Godzina=$row['Godzina'];
				$GodzinaKoniec=$row['GodzinaKoniec'];
				$Tydzien=$row['Tydzien'];
				$KodKursu=$row['KodKursu'];
				$IdProwadzacy=$row['IdProwadzacy'];
				$Prowadzacy=$row['Tytul'].' '.$row['Imie'].' '.$row['Nazwisko'];
				$Sala=$row['Sala'];
				$Budynek=$row['Budynek'];
				$ZapisanychStudentow=$row['ZapisanychStudentow'];
			}
			$tytul_belki="modyfikacja zajęć";
			$tryb_edycji=1;
		}
		
}
if(!$tryb_edycji)
{
				$tytul_belki="dodaj zajęcia";
				$IdZajecia="(przydzielany automatycznie)";
				$IdSali="";
				$Dzien="pon";
				$Godzina="";
				$GodzinaKoniec="";
				$Tydzien="T";
				$KodKursu=$_GET['KodKursu'];
				$IdProwadzacy="";
			$ECTS='';
}

	$sql="SELECT * FROM sale";
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	 $sale[$iter++]=$result;
	}
	
	

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$_POST['Godzina']=$_POST['GodzinaGodziny'].':'.$_POST['GodzinaMinuty'].':00';
	$_POST['GodzinaKoniec']=$_POST['GodzinaKoniecGodziny'].':'.$_POST['GodzinaKoniecMinuty'].':00';
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujZajecia($_POST);
	}
	else
	{
		
		$response=$admin->Action_DodajZajecia($_POST);
	}
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy zajęć kursu.</p>
					<div class="button-holder">
					<a href="?p=zajecia&KodKursu='.$_POST['KodKursu'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się dodać/zmodyfikować zajęć. Sprawdź, czy wypełniłeś poprawnie wszystkie pola.</p>
					<div class="button-holder">
					<a href="?p=zajecia&KodKursu='.$_POST['KodKursu'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
}
// javascript validation

else 

{


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

$sql = "SELECT kursy.NazwaKursu as NazwaKursu,formy_kursow.NazwaFormy as NazwaFormy FROM kursy,formy_kursow WHERE kursy.KodKursu='$KodKursu' AND kursy.IdFormy=formy_kursow.IdFormy"; 

foreach($db->query($sql) as $informacja_o_kursie);
echo '


<div id="page-title">
	<h2>
		<a href="?p=home">administracja</a> 
		&raquo; <a href="?p=kursy">kursy</a>
		&raquo; <a href="?p=zajecia&KodKursu='.$KodKursu.'">'.$informacja_o_kursie['NazwaKursu'].' ('.$informacja_o_kursie['NazwaFormy'].')</a>
		&raquo; '.$tytul_belki.'
		</h2>
		
				<!--<div class="submit_all_forms_button_container no_margin">
					<span></span>
					<button class="submit_all_forms" class="icon-save">Zapisz wszystkie zmiany</button>

			
				</div>-->
	</div>';
	
	
	
	
	echo '
<div id="lang-bar">
<ul class="tabs">
<li><a href="?p=kursy" class="tabbutton icon-application_view_list">przeglądanie listy kursów</a></li> 
<li><a href="?p=zajecia&KodKursu='.$KodKursu.'" class="tabbutton icon-calendar">przeglądanie listy zajęć</a></li>


<li class="current"><a href="#" class="tabbutton icon-add">'.$tytul_belki.'</a></li>
</ul>

			<div class="clear"></div>
			
			</div>
';	

echo '

		
	<form action="admin.php?p='.$pageuri.'" method="post" id="form_glowny">

	<table class="pages_table">';

	echo '
	<thead>
		<tr>
			<td colspan="3"><strong>'.$tytul_belki.'</strong></td>
		</tr>
	</thead>
	
	<tbody>
	<tr>
			<td class="col-title-settings"><strong>Numer ID zajęć</strong> Wartość jest przydzielana automatycznie<br/>';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '</td>
			<td class="col-input-settings">
<input class="setting-input-text" style="width: 170px;" disabled="disabled" name="IdZajecia" value="'.$IdZajecia.'"/>';
if($tryb_edycji) echo '<input type="hidden" name="IdZajecia" value="'.$IdZajecia.'"/>';
echo '

</td>
<td class="status"></td>

			
		</tr>
		<tr>
			<td class="col-title-settings"><strong>Prowadzący</strong> ID prowadzącego zajęcia<br/>';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '</td>
			<td class="col-input-settings">
			<input class="setting-input-text" type="hidden" id="ProwadzacyValue" name="IdProwadzacy" value="'.$IdProwadzacy.'"/>
			<input class="setting-input-text" disabled="disabled" type="text" id="ProwadzacyValueDisabled" style="width: 30px; padding-left: 1px; text-align: center; padding-right: 0;" value="'.$IdProwadzacy.'"/>&nbsp;<input class="setting-input-text {required: true,messages:{required: \'Wybierz prowadzącego\'}}" style="width: 200px;"  id="ProwadzacyAutoSuggest" value="'.$Prowadzacy.'"/>';
echo '

</td>
<td class="status"></td>

			
		</tr>
			<tr>
			<td class="col-title-settings"><strong>Sala</strong>Sala, w której odbywają się zajęcia</td>
						
			<td class="col-input-settings">
			
<select name="IdSali" style="width: 170px;" class="setting-input-select {required:true,messages:{required:\'Wybierz salę z listy \'}}">
			<option value="">-- wybierz salę --</option>
			';
			foreach($sale as $el)
			{
			echo '	<option value="'.$el[0].'" class="sala-'.$el[0].'"';
			if($IdSali==$el[0]) echo ' selected="selected"';
			echo '>'.$el[1].", ".$el[2].'</option>';
			}
			echo '</select>
</td>
<td class="status"></td>			
		</tr>
		
		
		
						<tr>
			<td class="col-title-settings"><strong>Dzień tygodnia</strong>Dzień, w którym odbywają się zajęcia</td>
			<td class="col-input-settings">
			

			';
			$dni_tygodnia[0][0]='pon';	$dni_tygodnia[0][1]='Poniedziałek';
			$dni_tygodnia[1][0]='wt';	$dni_tygodnia[1][1]='Wtorek';
			$dni_tygodnia[2][0]='sr';	$dni_tygodnia[2][1]='Środa';
			$dni_tygodnia[3][0]='czw';	$dni_tygodnia[3][1]='Czwartek';
			$dni_tygodnia[4][0]='pt';	$dni_tygodnia[4][1]='Piątek';
			$dni_tygodnia[5][0]='so';	$dni_tygodnia[5][1]='Sobota';
			$dni_tygodnia[6][0]='ndz';	$dni_tygodnia[6][1]='Niedziela';
			
			echo '<select style="width: 170px;" name="Dzien" class="setting-input-select {required:true,messages:{required:\'Wybierz dzień tygodnia z listy \'}}">
			<option value="">-- wybierz dzień tygodnia --</option>
			';
			foreach($dni_tygodnia as $el)
			{
			echo '	<option value="'.$el[0].'" class="sala-'.$el[0].'"';
			if($Dzien==$el[0]) echo ' selected="selected"';
			echo '>'.$el[1].'</option>';
			}
			echo '</select>

</td>
<td class="status"></td>			
		</tr>
		<tr>
			<td class="col-title-settings"><strong>Tydzień</strong>Wybierz, czy zajęcia mają odbywać się co tydzień, czy co dwa tygodnie</td>
						
			<td class="col-input-settings">
			

			';
			$tydzien[0][0]='T';	$tydzien[0][1]='co tydzień';
			$tydzien[1][0]='TN';	$tydzien[1][1]='nieparzysty';
			$tydzien[2][0]='TP';	$tydzien[2][1]='parzysty';
			
			foreach($tydzien as $el)
			{
			echo '	<label class="tydzien_form tydzien-'.$el[0].'" for="Tydzien-'.$el[0].'"><input id="Tydzien-'.$el[0].'" type="radio" name="Tydzien" value="'.$el[0].'"';
			if($Tydzien==$el[0]) echo ' checked="checked"';
			echo '/>'.$el[1].'</label>';
			}

			
			echo '
</td>
<td class="status"></td>			
		</tr>
		<tr>
			';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '<td class="col-title-settings"><strong>Godzina rozpoczęcia</strong>Wybierz godzinę rozpoczęcia zajęć (HH:MM)</td>
			<td class="col-input-settings">
			<input maxlength="2" name="GodzinaGodziny" class="setting-input-text {required:true,range:[6,22],maxlength:2, messages:{range:\'Poprawna godzina zajęć musi być z przedziału 6 - 22<br/>\',required:\'To pole jest wymagane<br/>\',number:\'Pole godzin może zawierać wyłącznie liczby<br/>\'}}" ';
echo' style="width: 30px;" type="text" value="'.date("H",strtotime($Godzina)).'"/>
<strong>:</strong>
<input maxlength="2"  name="GodzinaMinuty" class="setting-input-text {required:true,range:[0,59],maxlength:2, messages:{range:\'Wpisz wartość mniejszą od 60\',required:\'To pole jest wymagane\',number:\'Pole minut może zawierać wyłącznie liczby\'}}" ';
echo' style="width: 30px;" type="text" value="'.date("i",strtotime($Godzina)).'"/>

';


echo '

</td>
<td class="status"></td>

			
		</tr>		
<tr>
			';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '<td class="col-title-settings"><strong>Godzina zakończenia</strong>Wpisz godzinę zakończenia zajęć (HH:MM)</td>
			<td class="col-input-settings">
			<input maxlength="2" name="GodzinaKoniecGodziny" class="setting-input-text {required:true,range:[6,22],maxlength:2, messages:{range:\'Poprawna godzina zajęć musi być z przedziału 6 - 22<br/>\',required:\'To pole jest wymagane<br/>\',number:\'Pole godzin może zawierać wyłącznie liczby<br/>\'}}" ';
echo' style="width: 30px;" type="text" value="'.date("H",strtotime($GodzinaKoniec)).'"/>
<strong>:</strong>
<input maxlength="2"  name="GodzinaKoniecMinuty" class="setting-input-text {required:true,range:[0,59],maxlength:2, messages:{range:\'Wpisz wartość mniejszą od 60\',required:\'To pole jest wymagane\',number:\'Pole minut może zawierać wyłącznie liczby\'}}" ';
echo' style="width: 30px;" type="text" value="'.date("i",strtotime($GodzinaKoniec)).'"/>

';


echo '

</td>
<td class="status"></td>

			
		</tr>				
		<tr>
			';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '<td class="col-title-settings"><strong>Kod kursu</strong>Kod kursu, do którego przypisane zostaną zajęcia</td>
			<td class="col-input-settings">
			<input maxlength="16"  name="KodKursu" class="setting-input-text {required:true,minlength:2,maxlength:16, messages:{required:\'To pole jest wymagane\',number:\'Numer albumu może zawierać tylko cyfry\',minlength:\'Kod kursu musi mieć przynajmniej 2 znaki\',maxlength:\'Kod kursu może mieć najwyżej 7 znaków\'}}" ';
if(1) echo 'disabled="disabled" ';
echo' style="width: 140px;" type="text" value="'.$KodKursu.'"/>';
if(1) echo '<input type="hidden" name="KodKursu" value="'.$KodKursu.'"/>';

echo '

</td>
<td class="status"></td>

			
		</tr>
		
		
		</tbody>
		
	';
	



echo '

</table>
<input type="hidden" name="tryb_edycji" value="'.$tryb_edycji.'"/>
	<input type="submit" class="submit-button" value="zapisz"/> 
	</form>
';
}

?>