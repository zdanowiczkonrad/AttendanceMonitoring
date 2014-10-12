<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="sale_edycja";
$tryb_edycji=0;
// znaczy że edycja
if(isset($_GET['id']))
{
		$result=$admin->Action_PojedynczaSala($_GET['id']);
		if($result)
		{
			foreach($result as $row)
			{
				$IdSali=$row['IdSali'];
				$Sala=$row['Sala'];
				$Budynek=$row['Budynek'];
			}
			$tytul_belki="modyfikacja sali";
			$tryb_edycji=1;
		}
}
if(!$tryb_edycji)
{
			$tytul_belki="dodaj salę";
			$NrIndeksu='';
			$Imie='';
			$Nazwisko='';
			$Uid='';
}


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujSale($_POST);
	}
	else
	{
		$response=$admin->Action_DodajSale($_POST);
	}
	if($response==true)
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy sal.</p>
					<div class="button-holder">
					<a href="?p=sale" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się dodać/zmodyfikować danych sali. Sprawdź, czy wypełniłeś poprawnie wszystkie pola.</p>
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
		&raquo; <a href="?p=sale">sale</a>
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
<li><a href="?p=sale" class="tabbutton icon-application_view_list">przeglądanie sal</a></li> 
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
				<td class="col-title-settings"><strong>numer ID sali</strong> Wartość jest przydzielana automatycznie<br/>';
	//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
	echo '</td>
				<td class="col-input-settings">
	<input class="setting-input-text" style="width: 158px;" disabled="disabled" name="IdSali" value="'.$IdSali.'"/>';
	if($tryb_edycji) echo '<input type="hidden" name="IdSali" value="'.$IdSali.'"/>';
	echo '

	</td>
	<td class="status"></td>

				
			</tr>
				<tr>
			<td class="col-title-settings"><strong>Numer sali</strong> Numer sali</td>
			<td class="col-input-settings">
<input maxlength="8" class="setting-input-text {required:true,maxlength:8, messages:{required:\'To pole jest wymagane\',maxlength:\'Pole może mieć najwyżej 8 znaków\'}}" style="width: 200px;" type="text" name="Sala" value="'.$Sala.'"/>
</td>
	<td class="status"></td>		
		</tr>
										<tr>
			<td class="col-title-settings"><strong>Budynek</strong> Budynek, w którym znajduje się sala</td>
			<td class="col-input-settings">

<input maxlength="4" class="setting-input-text {required:true,maxlength:4, messages:{required:\'To pole jest wymagane\',maxlength:\'Pole może mieć najwyżej 4 znaki\'}}" style="width: 200px;" type="text" name="Budynek" value="'.$Budynek.'"/>
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