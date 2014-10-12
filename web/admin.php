<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
ob_start();
require_once('admin/admin.functions.php');
require_once('admin/configs/database.php');

try
{
  $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
  $db->query('SET NAMES UTF8');
}
catch(PDOException $e) {echo $e->getMessage();}

/* SETTINGS FROM DATABASE */
/*$sql='SELECT name,setting FROM settings';
foreach ($db->query($sql) as $row)
{
	$settings[$row['name']]=$row['setting'];	  
}*/
$admin=new Admin($db);		

if(!isset($_SESSION['logged'])) $_SESSION['logged']=0;
if(!isset($_SESSION['prob'])) $_SESSION['prob']=0;


$mozliwych_prob_logowania=20;
$login_error_message="";
if($_SESSION['prob']>$mozliwych_prob_logowania)
{
	$login_error_message="Przekroczono dopuszczalną liczbę prób. Spróbuj później.";
	$_SESSION['form_disabled']=1;
}
if(isset($_GET['wyloguj'])) if($_GET['wyloguj']==1) {$_SESSION['logged']=0; $_SESSION['prob']=0; header('Location: admin.php');}

/* logowanie */
if($_SESSION['logged']==0)
{ 
	if(isset($_POST['login']) && isset($_POST['pass']))
	{
		$zleznaki=array("\"","'","<",">","\\", "/",";",":","[","]","~","#","{","}");
		$a1=str_replace($zleznaki, '', $_POST['login']);
		$a2=str_replace($zleznaki, '', $_POST['pass']);		

		$sth=$admin->db->prepare('SELECT * FROM userzy WHERE UserLogin=:UserLogin AND PassHash=:PassHash');
		$sth->bindParam('UserLogin',$a1);
		$sth->bindParam('PassHash',md5($a2));
		$sth->execute();
		$result = $sth->fetchAll();
		$logged=false;
		foreach($result as $row)
		{
			
			$_SESSION['logged']=1;
			$_SESSION['user_login']=$a1;
			header('Location: admin.php');
			$logged=true;
		}	
		if(!$logged) {
		$login_error_message='Błędny login i/lub hasło. Pozostało prób '.($mozliwych_prob_logowania-++$_SESSION['prob']).'.';

		}
	}
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
		    <title>System obecności - autoryzacja</title>
 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="language" content="pl" />
			<link rel="stylesheet" href="admin/css/login.css" type="text/css" media="screen" />
			<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		</head>
		<body>
		';
		if($_SESSION['form_disabled'])
		{
			echo '<strong>Przekroczono dopuszczalną liczbę prób. Spróbuj ponownie później.';
		
		}
		else
		{
		echo '
			<form action="" method="POST">
			<fieldset>
			<h1>system sprawdzania obecności na uczelni wyższej</h1>
			<h2>autoryzacja</h2>
			<table>
			<tr><td><label for="login">login</label></td><td><input tabindex="1" type="text" name="login" id="login"/><label for="Prowadzacy"><input tabindex="3" id="Prowadzacy" type="checkbox" name="Prowadzacy" value="1"/> zaloguj przez Uid prowadzącego</label></td></tr>
			<tr><td><label for="pass">hasło</label></td><td><input tabindex="2" type="password" name="pass" id="pass"/></td></tr>
			<tr><td colspan="2">
			<input type="hidden" name="login_try" value="1"/>
			<input type="submit" value="zaloguj się" id="submit"/></td></tr>
			</table>
			<strong>';
			echo $login_error_message;
			echo '</strong>
			</fieldset>
		';}
		echo '
		</form>
		<p class="note">System sprawdzania obecności na uczelni wyższej v. 1.0 </p>
		</body>
		</html>';

}

//jeżeli zalogowany, wybór podstrony
if($_SESSION['logged'])
{
	require_once('admin/main.php');
}

?>