<?php
if($_SESSION['logged']!=1) header('Location: index.php');
$pageuri="terminy_edycja";
$tryb_edycji=0;
// znaczy że edycja
$result=$admin->Action_PojedynczeZajecia($_GET['IdZajecia']);

if($result)
{
	$IdZajecia=$_GET['IdZajecia'];
	foreach($result as $row)
	{
		$IdSali=$row['IdSali'];
		$DzienShort=$row['Dzien'];
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
				$Dzien=$row['Dzien'];
		$Godzina=date("H:i",strtotime($row['Godzina']));
		$GodzinaKoniec=date("H:i",strtotime($row['GodzinaKoniec']));
		$Tydzien=$row['Tydzien'];
		if($Tydzien=="T") $TydzienLadnie="";
		else $TydzienLadnie=$Tydzien;
		$KodKursu=$row['KodKursu'];
		$IdProwadzacy=$row['IdProwadzacy'];
		$Prowadzacy=$row['Tytul'].' '.$row['Imie'].' '.$row['Nazwisko'];
		$Sala=$row['Sala'];
		$Budynek=$row['Budynek'];
		$ZapisanychStudentow=$row['ZapisanychStudentow'];
		$GodzinyFormat=date("H",strtotime($row['Godzina']));
		$MinutyFormat=date("i",strtotime($row['Godzina']));
		$SekundyFormat=date("s",strtotime($row['Godzina']));
		
	}
	//pobranie terminów z danych zajęć
	//w postaci mapy dat
		$i=0;
		$sql="SELECT Data FROM terminy WHERE IdZajecia=$IdZajecia";
		foreach($db->query($sql) as $row)
		{
			$daty_wykorzystane[date("j-n-Y",strtotime($row[0]))]=1;
		}
}




if(isset($_GET['id']))
{

		$result=$admin->Action_PojedynczyTermin($_GET['id']);
		
		if($result)
		{
			foreach($result as $row)
			{
				$IdTermin=$row['IdTermin'];
				$Data=$row['Data'];
				$DataFormat=date("Y-m-d",strtotime($Data));
				$IdZajecia=$row['IdZajecia'];
				$GodzinyFormat=date("H",strtotime($Data));
				$MinutyFormat=date("i",strtotime($Data));
				$SekundyFormat=date("s",strtotime($Data));
			}
			$tytul_belki="modyfikacja terminu";
			$tryb_edycji=1;
		}
		
}
if(!$tryb_edycji)
{
				$tytul_belki="dodaj termin";
				$IdZajecia="(przydzielany automatycznie)";
				$Godziny=$Minuty=$Sekundy="";
				$DataFormat="";

}


	$sql="SELECT * FROM sale";
	$iter=0;
	foreach($db->query($sql) as $result)
	{
	 $sale[$iter++]=$result;
	}
	
	

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$_POST['Data']=$_POST['Dzien']." ".$_POST['Godziny'].":".$_POST['Minuty'].":".$_POST['Sekundy'];
	//dokonano aktualizacji/dodania uzytkownika
	// operacja jednak mogla przyjsc albo z widoku listy terminow, albo z widoku tabeli
	// domyslnie powraca do listy, ale teraz program sprawdzi, czy polecenie przyszlo
	// z tabeli. jezeli tak, przekieruj tam a nie do widoku domyslnego terminow.
	$stronapowrotna="";
	if($_POST['stronapowrotna']=="terminy_obecnosci") $stronapowrotna="_obecnosci";

	
	if($_POST['tryb_edycji'])
	{
		$response=$admin->Action_AktualizujTermin($_POST);
	}
	else
	{
		
		$response=$admin->Action_DodajTermin($_POST);
	}
	if($response==true)
	
	echo '
	<div class="dialog-box">
				<strong class="question">Akcja przebiegła poprawnie.</strong>
				<p>Naciśnij powrót, aby wrócić do listy terminów.</p>
					<div class="button-holder">
					<a href="?p=terminy'.$stronapowrotna.'&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
					<div class="clear"></div>
					</div>
				</div>';
		else echo'
		<div class="dialog-box">
				<strong class="question">Akcja przebiegła niepoprawnie.</strong>
				<p>Nie udało się dodać/zmodyfikować terminu. Sprawdź, czy wypełniłeś poprawnie wszystkie pola.</p>
					<div class="button-holder">
						<a href="?p=terminy'.$stronapowrotna.'&id='.$_POST['IdZajecia'].'" class="button"><span class="icon-arrow_left">powrót</span></a>
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

		<h2><a href="?p=home">administracja</a> &raquo; <a href="?p=kursy">kursy</a> 
&raquo; <a href="?p=zajecia&KodKursu='.$KodKursu.'">'.$informacja_o_kursie['NazwaKursu'].' ('.$informacja_o_kursie['NazwaFormy'].')</a>
&raquo; <a href="?p=terminy&id='.$_GET['IdZajecia'].'">'."$Dzien $TydzienLadnie $Godzina-$GodzinaKoniec, sala $Sala, $Budynek".'</a>
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
<li><a href="?p=terminy&id='.$_GET['IdZajecia'].'" class="tabbutton icon-date">przeglądanie listy terminów</a></li>
<li class="current"><a href="#" class="tabbutton icon-add">'.$tytul_belki.'</a></li>
</ul>

			<div class="clear"></div>
			
			</div>
';	

echo '

		
	<form action="admin.php?p='.$pageuri.'" method="post" id="form_glowny">
	<input type="hidden" name="IdZajecia" value="'.$_GET['IdZajecia'].'"/>
	<input type="hidden" name="stronapowrotna" value="'.$_GET['frompage'].'"/>
	<table class="pages_table">';

	echo '
	<thead>
		<tr>
			<td colspan="3"><strong>'.$tytul_belki.'</strong></td>
		</tr>
	</thead>
	
	<tbody>
	<tr>
			<td class="col-title-settings"><strong>Numer ID terminu</strong> Wartość jest przydzielana automatycznie<br/>';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '</td>
			<td class="col-input-settings">
<input class="setting-input-text" style="width: 40px;" disabled="disabled" name="IdTermin" value="'.$IdTermin.'"/>';
if($tryb_edycji) echo '<input type="hidden" name="IdTermin" value="'.$IdTermin.'"/>';
echo '

</td>
<td class="status"></td>

			
		</tr>
		
			<tr>
			<td class="col-title-settings"><strong>Data zajęć</strong>Dzień, w którym odbyły się zajęcia (YYYY-MM-DD)</td>
						
			<td class="col-input-settings">
			<script type="text/javascript" language="javascript"> 
			//<![CDATA[ 
			
			var availableDates = [
			';
			////////wygenerowanie dostepnych dni w kalendarzu
			switch($DzienShort)
			{
				case "pon": $gen_dzien=1; break;
				case "wt": $gen_dzien=2;break;
				case "sr": $gen_dzien=3;break;
				case "czw": $gen_dzien=4;break;
				case "pt": $gen_dzien=5;break;
				case "so": $gen_dzien=6;break;
				case "ndz": $gen_dzien=0;break;
			}
			//0,1,2. -1-co tydzien, 1-nieparzysty, 0-parzysty
			if($Tydzien=="T") $gen_tydzien=-1;
			else if($Tydzien=="TN") $gen_tydzien=1;
			else if($Tydzien=="TP") $gen_tydzien=0;
			

			//poczatek semestru/roku szkolnego
			$data_pocz="2012-10-01";

			//koniec semestru/roku szkolnego
			$data_kon="2013-09-01";

			//okresl date pierwszego wystapienia szukanego dnia
			$datapoczatku=strtotime($data_pocz);
			for($i=0; $i < 14 ;$i++)
			{
				if($gen_dzien==date("w",$datapoczatku) && (date("W",$datapoczatku)%2==$gen_tydzien || $gen_tydzien==-1))
				{
					$i=14;
				}
				else $datapoczatku=strtotime("+1 day",$datapoczatku);
			}

			//lec wszystkie daty
			//na podstawie parzystosci dostosuj $plusdzien
			if($gen_tydzien>-1) $zwiekszaj=14;
			else $zwiekszaj=7;
			for($i=$datapoczatku;$i<=strtotime($data_kon);$i=strtotime("+".$zwiekszaj." days", $i))
			{
				
			if($daty_wykorzystane[date("j-n-Y",$i)]!=1) echo date("\"j-n-Y\",\n",$i);
			}

			
			echo '
			""
			];

			//]]> 
			</script>
			


<input maxlength="128" class="input-datepicker setting-input-text {strictdates:true,required:true,date:true, maxlength:10,minlength:10, messages:{date: \'Wpisz poprawną datę\',required:\'To pole jest wymagane\',minlength:\'Niepoprawna data\',maxlength:\'Niepoprawna data\'}}" style="width: 170px;" type="text" name="Dzien" value="'.$DataFormat.'"/>

</td>
<td class="status"></td>			
		</tr>
		

		<tr>
			';
//if($tryb_edycji) echo '(modyfikacja numeru albumu nie jest możliwa)';
echo '<td class="col-title-settings"><strong>Godzina rozpoczęcia</strong>Godzina rozpoczęcia zajęć (HH:MM:SS)</td>
			<td class="col-input-settings">
			<input maxlength="2" name="Godziny" class="setting-input-text {required:true,range:[6,22],maxlength:2, messages:{range:\'Poprawna godzina zajęć musi być z przedziału 6 - 22<br/>\',required:\'To pole jest wymagane<br/>\',number:\'Pole godzin może zawierać wyłącznie liczby<br/>\'}}" ';
echo' style="width: 30px;" type="text" value="'.$GodzinyFormat.'"/>
<strong>:</strong>
<input maxlength="2"  name="Minuty" class="setting-input-text {required:true,range:[0,59],maxlength:2, messages:{range:\'Wpisz wartość mniejszą od 60\',required:\'To pole jest wymagane\',number:\'Pole minut może zawierać wyłącznie liczby\'}}" ';
echo' style="width: 30px;" type="text" value="'.$MinutyFormat.'"/>
<strong>:</strong>
<input maxlength="2"  name="Sekundy" class="setting-input-text {required:true,range:[0,59],maxlength:2, messages:{range:\'Wpisz wartość mniejszą od 60\',required:\'To pole jest wymagane\',number:\'Pole sekund może zawierać wyłącznie liczby\'}}" ';
echo' style="width: 30px;" type="text" value="'.$SekundyFormat.'"/>
';


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