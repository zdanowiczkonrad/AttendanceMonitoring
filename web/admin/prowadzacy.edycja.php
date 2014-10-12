<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="prowadzacy_edycja";
$tryb_edycji=0;
// znaczy że edycja
if(isset($_GET['id']))
{
		$result=$admin->Action_PojedynczyProwadzacy($_GET['id']);
		if($result)
		{
			foreach($result as $row)
			{
				$IdProwadzacy=$row['IdProwadzacy'];
				$Imie=$row['Imie'];
				$Nazwisko=$row['Nazwisko'];
				$Tytul=$row['Tytul'];
				$Uid=$row['Uid'];
			}
			$tytul_belki="Modyfikacja danych prowadzącego";
			$tryb_edycji=1;
		}
}
if(!$tryb_edycji)
{
			$tytul_belki="dodaj prowadzącego";
			$IdProwadzacy='(przydzielany automatycznie)';
			$Tytul='mgr inż.';
			$Imie='';
			$Nazwisko='';
			$Uid='';
}


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujProwadzacego($_POST);
	}
	else
	{
		$response=$admin->Action_DodajProwadzacego($_POST);
	}
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy prowadzących.</p>
					<div class="button-holder">
					<a href="?p=prowadzacy" class="button"><span class="icon-arrow_left">powrót</span></a>
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
			&raquo; <a href="?p=prowadzacy">prowadzący</a>
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
	<li><a href="?p=prowadzacy" class="tabbutton icon-application_view_list">przeglądanie listy prowadzących</a></li> 
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
				<td class="col-title-settings"><strong>numer ID prowadzącego</strong> Wartość jest przydzielana automatycznie<br/>';
	//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
	echo '</td>
				<td class="col-input-settings">
	<input class="setting-input-text" style="width: 158px;" disabled="disabled" name="IdProwadzacy" value="'.$IdProwadzacy.'"/>';
	if($tryb_edycji) echo '<input type="hidden" name="IdProwadzacy" value="'.$IdProwadzacy.'"/>';
	echo '

	</td>
	<td class="status"></td>

				
			</tr>
						<tr>
				<td class="col-title-settings"><strong>Tytuł</strong>Tytuł naukowy prowadzącego</td>
				<td class="col-input-settings">
	<input maxlength="16" class="setting-input-text {required:true,minlength:2,maxlength:16, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 16 znaków\'}}" style="width: 200px;" type="text" name="Tytul" value="'.$Tytul.'"/> 
	</td>
	<td class="status"></td>			
			</tr>
					<tr>
				<td class="col-title-settings"><strong>Imię</strong> Imię prowadzącego</td>
				<td class="col-input-settings">
	<input maxlength="64" class="setting-input-text {required:true,minlength:2,maxlength:64, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 64 znaków\'}}" style="width: 200px;" type="text" name="Imie" value="'.$Imie.'"/>
	</td>
		<td class="status"></td>		
			</tr>
							<tr>
				<td class="col-title-settings"><strong>Nazwisko</strong>Nazwisko prowadzącego</td>
				<td class="col-input-settings">
	<input maxlength="64" class="setting-input-text {required:true,minlength:2,maxlength:64, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 64 znaków\'}}" style="width: 200px;" type="text" name="Nazwisko" value="'.$Nazwisko.'"/> 
	</td>
	<td class="status"></td>			
			</tr>
				<tr>
				<td class="col-title-settings"><strong>UID karty</strong>Unikalny numer indentyfikacyjny karty</td>
				<td class="col-input-settings">
	<input maxlength="64" class="setting-input-text {remote:\'admin/admin.api.php?action=Request_UidUnikalny';
	if($tryb_edycji) echo "&IdProwadzacy=$IdProwadzacy";

	echo'\',required:true,number: true,minlength:2,maxlength:32, messages:{remote: \'Istnieje w bazie użytkownik o tym numerze ID karty\', required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 32 znaki\',number:\'To pole może zawierać tylko liczby!\'}}" style="width: 200px;" type="text" name="Uid" value="'.$Uid.'"/> 
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