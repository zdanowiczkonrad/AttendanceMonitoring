<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="kursy_edycja";
$tryb_edycji=0;
// znaczy że edycja
if(isset($_GET['id']))
{
		$result=$admin->Action_PojedynczyKurs($_GET['id']);
		if($result)
		{
			foreach($result as $row)
			{
				$KodKursu=$row['KodKursu'];
				$NazwaKursu=$row['NazwaKursu'];
				$IdFormy=$row['IdFormy'];
				$ECTS=$row['ECTS'];
			}
			$tytul_belki="modyfikacja kursu";
			$tryb_edycji=1;
		}
}
if(!$tryb_edycji)
{
			$tytul_belki="dodaj kurs";
			$KodKursu='';
			$NazwaKursu='';
			$IdFormy=1;
			$ECTS='';
}

	$sql="SELECT * FROM formy_kursow";
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	 $formy_kursow[$iter++]=$result;
	}
	
	

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujKurs($_POST);
	}
	else
	{
		$response=$admin->Action_DodajKurs($_POST);
	}
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy kursów.</p>
					<div class="button-holder">
					<a href="?p=kursy" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się dodać/zmodyfikować danych prowadzącego. Sprawdź, czy wypełniłeś poprawnie wszystkie pola, oraz czy pole numeru ID prowadzącego jest unikalne.</p>
					<div class="button-holder">
					<a href="?p='.$pageuri.'" class="button"><span class="icon-arrow_left">powrót</span></a>
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


echo '<div id="page-title">
	<h2>
		<a href="?p=home">administracja</a> 
		&raquo; <a href="?p=kursy">kursy</a>
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
			<td class="col-title-settings"><strong>Kod kursu</strong> Unikalny kod kursu<br/>';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '</td>
			<td class="col-input-settings">
			<input maxlength="16"  name="KodKursu" class="setting-input-text {required:true,remote:\'admin/admin.api.php?action=Request_KodKursuUnikalny\', minlength:2,maxlength:16, messages:{remote:\'Ten kod kursu jest już w użyciu\', required:\'To pole jest wymagane\',number:\'Numer albumu może zawierać tylko cyfry\',minlength:\'Kod kursu musi mieć przynajmniej 2 znaki\',maxlength:\'Kod kursu może mieć najwyżej 7 znaków\'}}" ';
if($tryb_edycji) echo 'disabled="disabled" ';
echo' style="width: 140px;" type="text" value="'.$KodKursu.'"/>';
if($tryb_edycji) echo '<input type="hidden" name="KodKursu" value="'.$KodKursu.'"/>';

echo '

</td>
<td class="status"></td>

			
		</tr>
					
				<tr>
			<td class="col-title-settings"><strong>Nazwa kursu</strong> Nazwa kursu</td>
			<td class="col-input-settings">
<input maxlength="128" class="setting-input-text {required:true,minlength:2,maxlength:128, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 128 znaków\'}}" style="width: 250px;" type="text" name="NazwaKursu" value="'.$NazwaKursu.'"/>
</td>
	<td class="status"></td>		
		</tr>
						<tr>
			<td class="col-title-settings"><strong>Forma kursu</strong>Forma kursu</td>
			<td class="col-input-settings">
		<!--	<select name="IdFormy" class="setting-input-select {required:true,messages:{required:\'Wybierz formę kursu z listy \'}}">
			<option value="">---- wybierz formę kursu ----</option>
			';
			foreach($formy_kursow as $el)
			{
			echo '	<option value="'.$el[0].'" class="forma-'.$el[0].'"';
			if($IdFormy==$el[0]) echo ' selected="selected"';
			echo '>'.$el[1].'</option>';
			}
			echo '</select>
			-->
			

			';
			
			foreach($formy_kursow as $el)
			{
			echo '	<label class="formy_kursow_form forma-'.$el[0].'" for="IdFormy-'.$el[0].'"><input id="IdFormy-'.$el[0].'" type="radio" name="IdFormy" value="'.$el[0].'"';
			if($IdFormy==$el[0]) echo ' checked="checked"';
			echo '/>'.$el[1].'</label>';
			}

			
			
			
			echo '<!--
<input maxlength="64" class="setting-input-text {required:true,minlength:2,maxlength:64, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 64 znaków\'}}" style="width: 200px;" type="text" name="Nazwisko" value="'.$Nazwisko.'"/> -->
</td>
<td class="status"></td>			
		</tr>
									<tr>
			<td class="col-title-settings"><strong>ECTS</strong>Liczba punktów ECTS za kurs (jeżeli 0 - zostaw puste)</td>
			<td class="col-input-settings">
<input maxlength="2" class="setting-input-text {number: true,minlength:1,maxlength:2,range: [0,30], messages:{range:\'Liczba musi być z zakresu 0-30\',number: \'Wpisz poprawną liczbę\'}}" style="width: 60px;" type="text" name="ECTS" value="'.$ECTS.'"/> 
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