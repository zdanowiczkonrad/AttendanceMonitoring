<?php
if($_SESSION['logged']!=1) header('Location: index.php');

echo '
<div id="page-title">
	<h2>
		<a href="?p=home">administracja</a> 
		&raquo; <a href="?p=pages">strona główna</a> </h2>
		
		
</div>
<!-- dialogs -->


	<div id="dialog-ims-szybka-studenci" title="Edytuj studenta" class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny numer albumu studenta, aby otworzyć okno edycji.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szybka-studenci-id"><strong>Numer albumu</strong>
		<input type="text" name="dialog-ims-szybka-studenci-id" id="dialog-ims-szybka-studenci-id"/></label>
	</fieldset>
	</form>
</div>



	<div id="dialog-ims-szukaj-studenci" title="Wyszukaj studenta..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz wyszukiwaną frazę w polu poniżej.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szukaj-studenci-id"><strong>Wyszukiwana fraza</strong>
		<input type="text" name="dialog-ims-szukaj-studenci-id" id="dialog-ims-szukaj-studenci-id"/></label>
	</fieldset>
	</form>
</div>



	<div id="dialog-ims-szybka-prowadzacy" title="Edytuj prowadzącego" class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny numer id prowadzącego, aby otworzyć okno edycji.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szybka-prowadzacy-id"><strong>ID prowadzącego</strong>
		<input type="text" name="dialog-ims-szybka-prowadzacy-id" id="dialog-ims-szybka-prowadzacy-id"/></label>
	</fieldset>
	</form>
</div>


	<div id="dialog-ims-szukaj-prowadzacy" title="Wyszukaj prowadzącego..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz wyszukiwaną frazę w polu poniżej.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szukaj-prowadzacy-id"><strong>Wyszukiwana fraza</strong>
		<input type="text" name="dialog-ims-szukaj-prowadzacy-id" id="dialog-ims-szukaj-prowadzacy-id"/></label>
	</fieldset>
	</form>
</div>

	<div id="dialog-ims-szybka-kursy" title="Edytuj kurs" class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny kod kursu aby otworzyć okno edycji.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szybka-kursy-id"><strong>Kod kursu</strong>
		<input type="text" name="dialog-ims-szybka-kursy-id" id="dialog-ims-szybka-kursy-id"/></label>
	</fieldset>
	</form>
</div>


<div id="dialog-ims-dodaj-kursy" title="Dodaj grupę do kursu" class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz kod kursu, do którego chcesz dodać grupę zajęciową</p>
	<form>
	<fieldset>
		<label for="dialog-ims-dodaj-kursy-id"><strong>Kod kursu</strong>
		<input type="text" name="dialog-ims-dodaj-kursy-id" id="dialog-ims-dodaj-kursy-id"/></label>
	</fieldset>
	</form>
</div>
		
			

<div id="dialog-ims-szybka-grupy" title="Edytuj grupę zajęciową..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID grupy, aby otworzyć edycję.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szybka-grupy-id"><strong>ID grupy</strong>
		<input type="text" name="dialog-ims-szybka-grupy-id" id="dialog-ims-szybka-grupy-id"/></label>
	</fieldset>
	</form>
</div>






<div id="dialog-ims-zapisy-grupy" title="Zapisy do grupy zajęciowej..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID grupy, aby otworzyć okno zapisów.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-zapisy-grupy-id"><strong>ID grupy</strong>
		<input type="text" name="dialog-ims-zapisy-grupy-id" id="dialog-ims-zapisy-grupy-id"/></label>
	</fieldset>
	</form>
</div>


<div id="dialog-ims-terminy-grupy" title="Lista terminów grupy zajęciowej..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID grupy, aby otworzyć listę zajęć.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-terminy-grupy-id"><strong>ID grupy</strong>
		<input type="text" name="dialog-ims-terminy-grupy-id" id="dialog-ims-terminy-grupy-id"/></label>
	</fieldset>
	</form>
</div>



<div id="dialog-ims-obecnosci-grupy" title="Dziennik obecności grupy..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID grupy, aby otworzyć tabelę obecności z zajęć.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-obecnosci-grupy-id"><strong>ID grupy</strong>
		<input type="text" name="dialog-ims-obecnosci-grupy-id" id="dialog-ims-obecnosci-grupy-id"/></label>
	</fieldset>
	</form>
</div>


<div id="dialog-ims-dodaj-grupy" title="Dodaj termin do grupy..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID grupy, aby utworzyć nowy termin w grupie.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-dodaj-grupy-id"><strong>ID grupy</strong>
		<input type="text" name="dialog-ims-dodaj-grupy-id" id="dialog-ims-dodaj-grupy-id"/></label>
	</fieldset>
	</form>
</div>



<div id="dialog-ims-przegladaj-grupy" title="Przeglądanie grup zajęciowych" class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny kod kursu, aby otworzyć widok grup zajęciowych.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-przegladaj-grupy-id"><strong>Kod kursu</strong>
		<input type="text" name="dialog-ims-przegladaj-grupy-id" id="dialog-ims-przegladaj-grupy-id"/></label>
	</fieldset>
	</form>
</div>

	<div id="dialog-ims-szybka-sale" title="Edytuj salę..." class="okno-dialogowe-glowne">
	<p class="komunikat">Wpisz poprawny ID sali, aby włączyć okno edycji.</p>
	<form>
	<fieldset>
		<label for="dialog-ims-szybka-sale-id"><strong>ID sali</strong>
		<input type="text" name="dialog-ims-szybka-sale-id" id="dialog-ims-szybka-sale-id"/></label>
	</fieldset>
	</form>
</div>




<div class="intro-menu-container">

<h2>Adminstracja</h2>
<p>Najedź kursorem na ikonę, aby rozpocząć zarządzanie stroną.</p>
<ul class="intro-menu">
	<li class="im-studenci"><a href="#"><span>studenci</span></a>
		<ul>
			<li class="ims-szybkaedycja"><a href="#" class="icon-pencil" id="ims-szybka-studenci">szybka edycja...<span>Okno szybkiej edycji studenta</span></a></li>
			<li class="ims-dodaj"><a href="?p=studenci_edycja" class="icon-add">dodaj</a></li>
			<li class="ims-przegladaj"><a href="?p=studenci" class="icon-application_view_list">przeglądaj</a></li>
			<li class="ims-wyszukaj"><a href="#" class="icon-magnifier" id="ims-szukaj-studenci">wyszukaj</a></li>
		</ul>
	</li>
	<li class="im-prowadzacy"><a href="#"><span>prowadzący</span></a>
		<ul>
			<li class="ims-szybkaedycja"><a href="#" class="icon-pencil" id="ims-szybka-prowadzacy">szybka edycja...</a></li>
			<li class="ims-dodaj"><a href="?p=prowadzacy_edycja" class="icon-add">dodaj</a></li>
			<li class="ims-przegladaj"><a href="?p=prowadzacy" class="icon-application_view_list">przeglądaj</a></li>
			<li class="ims-wyszukaj"><a href="#" class="icon-magnifier" id="ims-szukaj-prowadzacy">wyszukaj</a></li>
		</ul>
	</li>
	<li class="im-kursy"><a href="#"><span>kursy</span></a>
		<ul>
			<li class="ims-szybkaedycja"><a href="#" class="icon-pencil" id="ims-szybka-kursy">szybka edycja...</a></li>
			<li class="ims-dodaj"><a href="?p=kursy_edycja" class="icon-calendar_add">dodaj kurs</a></li>
			<li class="ims-dodaj"><a href="#"  class="icon-date_add" id="ims-dodaj-kursy">dodaj grupę do kursu</a></li>
			<li class="ims-przegladaj"><a href="?p=kursy" class="icon-application_view_list">przeglądaj</a></li>
			<li class="ims-wyszukaj"><a href="#" class="icon-magnifier" id="ims-szukaj-kursy">wyszukaj</a></li>

		</ul>
	</li>
	<li class="im-grupy"><a href="#"><span>grupy zajęciowe</span></a>
		<ul>
			<li class="ims-szybkaedycja"><a href="#" class="icon-pencil" id="ims-szybka-grupy">szybka edycja...</a></li>
			<li class="ims-przegladaj"><a href="#" class="icon-application_view_list" id="ims-przegladaj-grupy">przeglądaj grupy</a></li>
			<li class="ims-zapisy"><a href="#" class="icon-user" id="ims-zapisy-grupy">zapisy do grupy</a></li>
			<li class="ims-lista"><a href="#" class="icon-date" id="ims-terminy-grupy">lista terminów grupy</a></li>
			<li class="ims-tabela"><a href="#" class="icon-book" id="ims-obecnosci-grupy">obecności z zajęć</a></li>
			<li class="ims-dodaj"><a href="#" class="icon-date_add" id="ims-dodaj-grupy">dodaj termin</a></li>
		</ul>
	</li>
		<li class="im-sale"><a href="#"><span>sale</span></a>
		<ul>
			<li class="ims-szybkaedycja"><a href="#" class="icon-pencil" id="ims-szybka-sale">szybka edycja...</a></li>
			<li class="ims-dodaj"><a href="?p=sale_edycja" class="icon-add">dodaj</a></li>
			<li class="ims-przegladaj"><a href="?p=sale" class="icon-application_view_list">przeglądaj</a></li>
		</ul>
	</li>
	<li class="clear"></li>
</ul>
<div class="clear"></div>
</div>
';


?>