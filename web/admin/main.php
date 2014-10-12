<?php
@session_start();
ob_start();

if($_SESSION['logged']!=1) header('Location: index.php');


/* page selector */

switch(@$_GET['p'])
{
	case 'sale_edycja': 	$page = 'sale.edycja'; 	$pagetitle="Dodaj/modyfikuj salę"; 			$pid=41; 	break;
	case 'sale': 	$page = 'sale'; 	$pagetitle="Sale"; 			$pid=40; 	break;
	case 'zapisy_dodaj': 	$page = 'zapisy.dodaj'; 	$pagetitle="Dodaj studenta do terminu"; 			$pid=39; 	break;
	case 'zapisy': 	$page = 'zapisy'; 	$pagetitle="Zapisy"; 			$pid=38; 	break;
	case 'terminy_obecnosci': $page = 'terminy_obecnosci'; $pagetitle="Tabela obecności z całego kursu"; $pid=37;	break;
	case 'lista_obecnosci': $page = 'lista_obecnosci'; $pagetitle="Lista obecności";$pid=36;	break;
	case 'terminy_edycja': $page = 'terminy.edycja'; $pagetitle="Dodaj/modyfikuj terminy";$pid=35;	break;
	case 'terminy': 	$page = 'terminy'; 	$pagetitle="Terminy"; 			$pid=34; 	break;
	case 'zajecia_edycja': $page = 'zajecia.edycja'; $pagetitle="Dodaj/modyfikuj zajęcia";$pid=33;	break;
	case 'zajecia': 	$page = 'zajecia'; 	$pagetitle="Zajęcia"; 			$pid=32; 	break;
	case 'kursy_edycja': $page = 'kursy.edycja'; $pagetitle="Dodaj/modyfikuj kursy";$pid=31;	break;
	case 'kursy': 	$page = 'kursy'; 	$pagetitle="Kursy"; 			$pid=30; 	break;
	case 'prowadzacy_edycja': $page = 'prowadzacy.edycja'; $pagetitle="Dodaj/modyfikuj dane prowadzącego";$pid=21;	break;
	case 'prowadzacy': 	$page = 'prowadzacy'; 	$pagetitle="Prowadzący"; 			$pid=20; 	break;
	case 'studenci_edycja': $page = 'studenci.edycja'; $pagetitle="Dodaj/modyfikuj dane studenta";$pid=11;	break;
	case 'studenci': 	$page = 'studenci'; 	$pagetitle="Studenci"; 			$pid=10; 	break;
	default:			$page = 'home';	 		$pagetitle="Strona główna";		$pid=0;	 	break;	
}

/* sprawdza czy aktywna strona */
function czy_akt($l,$p,$pid)
{
	$eff=' class="active"';
	if($pid>=$l && $pid<=$p) return $eff;
}

function safesearch($string)
{
$cleansedstring = ereg_replace("[^A-Za-z0-9]", "", $string);
return $cleansedstring;
}
/* JEZYKI */
if(!isset($_SESSSION['lang'])) $_SESSION['lang']=$settings['language'];
if(isset($_GET['lang'])) $_SESSION['lang']=$_GET['lang'];
$lang=$_SESSION['lang'];


/* NA STRONIE */
if(isset($_POST['sortowanie']))
{
	$_SESSION['sort']=$_POST['sortowanie'];
	$_SESSION['nastronie']=$_POST['nastronie'];
}

if(!isset($_SESSION['nastronie'])) $_SESSION['nastronie']=10;
$nastronie=$_SESSION['nastronie'];

/* SORTOWANIE */
if(!isset($_GET['strona'])) $strona=1;
else $strona=$_GET['strona'];

/* BRZEGI ZAPYTANIA */
$limit_begin=($strona-1)*$nastronie;
$limit_end=$nastronie;

/* TYP SORTOWANIA */
if(isset($_GET['sort'])) $_SESSION['sort']=$_GET['sort'];
if(!isset($_SESSION['sort'])) $_SESSION['sort']=1;
$sort=$_SESSION['sort'];


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="language" content="pl" />
<meta http-equiv="Content-Language" content="pl" />
<title>'.$pagetitle.' - System kontroli obecności na uczelni :: Konrad Zdanowicz</title>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="icon" href="favicon.png" type="image/png" />

<link rel="stylesheet" type="text/css" href="admin/css/main.css" />
<link rel="stylesheet" type="text/css" href="admin/css/form.css" />
	<link rel="stylesheet" href="scripts/themes/base/jquery.ui.all.css">


<script language="javascript" type="text/javascript" src="scripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="scripts/jquery.metadata.js"></script>






		<script src="scripts/ui/i18n/jquery.ui.datepicker-pl.js"></script>
		<script src="scripts/jquery-ui-1.8.21.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="scripts/jquery.validate.js"></script>
<script language="javascript" type="text/javascript" src="scripts/script.admin.js"></script>



</head>
<body>

<div id="container" style="visibilty: hidden;"><div id="top"><div id="header">
				<h1>'.$pagetitle.' - system kontroli obecności na uczelni wyższej</h1>
				<p><a href="?p=info">zalogowano jako '.$_SESSION['user_login'].'</a></p>
				<div id="quick-menu">
				<a href="#" id="viewswitcher">widok</a>
					<!--<a href="?p=settings" class="icon-cog">ustawienia</a>-->
					<a href="?wyloguj=1" class="icon-key">wyloguj</a>
					
				</div>
			</div>
			
			<div id="main-menu">
				<ul>
					<li'.czy_akt(0,0,$pid).'><a class="icon-house dynamic-load" href="?p=home" rel="#content">start</a></li>
					
					<li'.czy_akt(10,19,$pid).'><a href="?p=studenci" class="icon-group dynamic-load" rel="#content">studenci</a></li>
					<li'.czy_akt(20,29,$pid).'><a href="?p=prowadzacy" class="icon-user_gray dynamic-load" rel="#content">prowadzący</a></li>
					<li'.czy_akt(30,39,$pid).'><a href="?p=kursy" class="icon-calendar dynamic-load" rel="#content">kursy i zajęcia</a></li>
					<li'.czy_akt(40,49,$pid).'><a href="?p=sale" class="icon-door dynamic-load" rel="#content">sale</a></li>







					
					<div style="display: none;">
					<li'.czy_akt(7,9,$pid).'><a href="?p=pages" class="icon-page_white_copy dynamic-load" rel="#content">strony</a>
						 <!--<ul>
							<li'.czy_akt(8,8,$pid).'><a href="?p=add_page" class="icon-add dynamic-load">dodaj</a>
							<li'.czy_akt(9,9,$pid).'><a href="?p=pages" class="icon-page_edit dynamic-load">edytuj</a>
						</ul> -->
					</li>
					<li'.czy_akt(4,6,$pid).'><a href="?p=menus" class="icon-sitemap">menu</a>
					</li>
					<li'.czy_akt(1,3,$pid).'><a href="?p=lang" class="icon-world">języki</a>
						<!--<ul>
							<li'.czy_akt(2,2,$pid).'><a href="?p=add_lang" class="icon-add">dodaj</a>
							<li'.czy_akt(3,3,$pid).'><a href="?p=edit_lang" class="icon-page_edit">edytuj</a>
						</ul>-->
					</li>
					<li'.czy_akt(10,12,$pid).'><a href="?p=modules" class="icon-package">moduły</a>
						<!--<ul>
							<li'.czy_akt(11,11,$pid).'><a href="?p=add_module" class="icon-add">dodaj</a>
							<li'.czy_akt(12,12,$pid).'><a href="?p=edit_module" class="icon-page_edit">edytuj</a>
						</ul>-->
					</li>

					</div>
				</ul>
				<div class="clear"></div>
			</div>
		</div>

		<div id="content">';
		
		require_once($page.'.php');
		
		echo '</div>
		</div>
		</body></html>';
?>