<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="studenci_edycja";
$tryb_edycji=0;
// znaczy że edycja
if(isset($_GET['id']))
{
		$result=$admin->Action_PojedynczyStudent($_GET['id']);
		if($result)
		{
			foreach($result as $row)
			{
				$NrIndeksu=$row['NrIndeksu'];
				$Imie=$row['Imie'];
				$Nazwisko=$row['Nazwisko'];
				$Uid=$row['Uid'];
			}
			$tytul_belki="modyfikacja danych studenta";
			$tryb_edycji=1;
		}
}
if(!$tryb_edycji)
{
			$tytul_belki="dodaj studenta";
			$NrIndeksu='';
			$Imie='';
			$Nazwisko='';
			$Uid='';
}


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujStudenta($_POST);
	}
	else
	{
		$response=$admin->Action_DodajStudenta($_POST);
	}
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy studentów.</p>
					<div class="button-holder">
					<a href="?p=studenci" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się dodać/zmodyfikować danych studenta. Sprawdź, czy wypełniłeś poprawnie wszystkie pola, oraz czy pole numeru indeksu jest unikalne.</p>
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
		&raquo; <a href="?p=studenci">studenci</a>
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
<li><a href="?p=studenci" class="tabbutton icon-application_view_list">przeglądanie listy studentów</a></li> 
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
			<td class="col-title-settings"><strong>numer indeksu</strong> Numer indeksu studenta.<br/>';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '</td>
			<td class="col-input-settings">
<input maxlength="7"  name="NrIndeksu" class="setting-input-text {required:true,number:true,remote:\'admin/admin.api.php?action=Request_StudentUnikalny\', minlength:2,maxlength:7, messages:{remote:\'Ten numer albumu jest już w użyciu\', required:\'To pole jest wymagane\',number:\'Numer albumu może zawierać tylko cyfry\',minlength:\'Numer albumu musi mieć przynajmniej 2 znaki\',maxlength:\'Numer albumu może mieć najwyżej 7 znaków\'}}" ';
if($tryb_edycji) echo 'disabled="disabled" ';
echo' style="width: 100px;" type="text" value="'.$NrIndeksu.'"/>';
if($tryb_edycji) echo '<input type="hidden" name="NrIndeksu" value="'.$NrIndeksu.'"/>';
echo '

</td>
<td class="status"></td>

			
		</tr>
				<tr>
			<td class="col-title-settings"><strong>Imię</strong> Imię studenta.</td>
			<td class="col-input-settings">
<input maxlength="64" class="setting-input-text {required:true,minlength:2,maxlength:64, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 64 znaków\'}}" style="width: 200px;" type="text" name="Imie" value="'.$Imie.'"/>
</td>
	<td class="status"></td>		
		</tr>
						<tr>
			<td class="col-title-settings"><strong>Nazwisko</strong>Nazwisko studenta</td>
			<td class="col-input-settings">
<input maxlength="64" class="setting-input-text {required:true,minlength:2,maxlength:64, messages:{required:\'To pole jest wymagane\',minlength:\'Wpisz przynajmniej 2 znaki\',maxlength:\'Pole może mieć najwyżej 64 znaków\'}}" style="width: 200px;" type="text" name="Nazwisko" value="'.$Nazwisko.'"/> 
</td>
<td class="status"></td>			
		</tr>
			<tr>
			<td class="col-title-settings"><strong>UID karty</strong>Unikalny numer indentyfikacyjny karty</td>
			<td class="col-input-settings">
<input maxlength="64" class="setting-input-text {remote:\'admin/admin.api.php?action=Request_UidUnikalny';
if($tryb_edycji) echo "&NrIndeksu=$NrIndeksu";
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