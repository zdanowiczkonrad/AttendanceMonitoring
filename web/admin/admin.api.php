<?php
//header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
ob_start();
require_once('admin.functions.php');
require_once('configs/database.php');

$admin=new Admin($db);
if($_SESSION['logged']!=1) header('Location: index.php');
{
	
	$output="";
	
	
	
	/////api GET
	

	
	//student istnieje
	if($_GET['action']=='Action_StudentIstnieje' && $_GET['NrIndeksu']>-1 && $_GET['NrIndeksu']<9999999)
	{
		$result=$admin->Action_StudentIstnieje($_GET['NrIndeksu']);
		if($result) $output='true';
		else $output='false';
	}

	//prowadzacy istnieje
	if($_GET['action']=='Action_ProwadzacyIstnieje' && $_GET['IdProwadzacy']>-1 && $_GET['IdProwadzacy']<9999999)
	{
		$result=$admin->Action_ProwadzacyIstnieje($_GET['IdProwadzacy']);
		if($result) $output='true';
		else $output='false';
	}
	
	//kurs istnieje
	if($_GET['action']=='Action_KursIstnieje')
	{
		$result=$admin->Action_KursIstnieje($_GET['KodKursu']);
		if($result) $output='true';
		else $output='false';
	}	
	//zajecia istnieje
	if($_GET['action']=='Action_ZajeciaIstnieja')
	{
		$result=$admin->Action_ZajeciaIstnieja($_GET['IdZajecia']);
		if($result) $output='true';
		else $output='false';
	}	
	//termin istnieje
	if($_GET['action']=='Action_TerminIstnieje')
	{
		$result=$admin->Action_TerminIstnieje($_GET['IdTermin']);
		if($result) $output='true';
		else $output='false';
	}	
	//sala istnieje
	if($_GET['action']=='Action_SalaIstnieje')
	{
		$result=$admin->Action_SalaIstnieje($_GET['IdSali']);
		if($result) $output='true';
		else $output='false';
	}	
	//dodaj obecnosc
	if($_GET['action']=='Action_DodajObecnosc' && $_GET['NrIndeksu']>-1 && $_GET['NrIndeksu']<9999999 && $_GET['IdTermin']>-1 && $_GET['IdTermin']<9999999)
	{
		$admin->Action_UsunObecnosc($_GET['NrIndeksu'],$_GET['IdTermin']);
		$admin->Action_DodajObecnosc($_GET['NrIndeksu'],$_GET['IdTermin'],1);
	}
	
	//dodaj spoznienie
	if($_GET['action']=='Action_DodajSpoznienie' && $_GET['NrIndeksu']>-1 && $_GET['NrIndeksu']<9999999 && $_GET['IdTermin']>-1 && $_GET['IdTermin']<9999999)
	{
		$admin->Action_UsunObecnosc($_GET['NrIndeksu'],$_GET['IdTermin']);
		$admin->Action_DodajObecnosc($_GET['NrIndeksu'],$_GET['IdTermin'],2);
	}	
	
	//usun obecnosc
	if($_GET['action']=='Action_UsunObecnosc' && $_GET['NrIndeksu']>-1 && $_GET['NrIndeksu']<9999999 && $_GET['IdTermin']>-1 && $_GET['IdTermin']<9999999)
	{
		$admin->Action_UsunObecnosc($_GET['NrIndeksu'],$_GET['IdTermin']);
	}	

	//sprawdz obecnosc
	if($_GET['action']=='Action_SprawdzObecnosc' && $_GET['NrIndeksu']>-1 && $_GET['NrIndeksu']<9999999 && $_GET['IdTermin']>-1 && $_GET['IdTermin']<9999999)
	{
		$result=$admin->Action_SprawdzObecnosc($_GET['NrIndeksu'],$_GET['IdTermin']);
		$output=$result;
	}		
	
	
	////api REQUEST do AJAX-u


	//wez date najnowszej obecnosci wpisanej do danych terminow
	if($_GET['action']=='Action_TerminyStempel')
	{
		$data=$admin->Action_TerminyStempel($_GET['IdZajec']);
		echo $data;
	}
	
	
	if($_GET['action']=='Request_WyszukajProwadzacego')
	{
		$data=$admin->Action_ProwadzacyPoNazwiskuLubImieniu($_GET['q']);
		echo json_encode($data);
	}

	if($_GET['action']=='Request_WyszukajStudenta')
	{
		$data=$admin->Action_StudentPoNazwiskuLubImieniu($_GET['q']);
		echo json_encode($data);
	}	
	
	if($_GET['action']=='Request_StudentUnikalny')
	{
		$result=$admin->Action_StudentIstnieje($_REQUEST['NrIndeksu']);
		if($result) $output='false';
		else $output='true';
	}
	
	
	
	if($_GET['action']=='Request_UidUnikalny')
	{

		$student_result=$admin->Action_PojedynczyStudentPoUid($_REQUEST['Uid']);
		$prowadzacy_result=$admin->Action_PojedynczyProwadzacyPoUid($_REQUEST['Uid']);
		
		if(!$student_result && !$prowadzacy_result) $result=false;
		else if($student_result!=false)
		{
			if(isset($_GET['NrIndeksu']))
			{
				$result=!$admin->Action_StudentIstniejeUid($_GET['NrIndeksu'],$_REQUEST['Uid']);
			}		
			else $result=true;
			
		}
		else if($prowadzacy_result!=false)
		{
			if(isset($_GET['IdProwadzacy']))
			{
				$result=!$admin->Action_ProwadzacyIstniejeUid($_GET['IdProwadzacy'],$_REQUEST['Uid']);
			}		
			else $result=true;
			
		}
		else $result=true;
		
		
		if($result) $output='false';
		else $output='true';
	}
	
	
	
		
	if($_GET['action']=='Request_KodKursuUnikalny')
	{
		$result=$admin->Action_KursIstnieje($_REQUEST['KodKursu']);
		if($result) $output='false';
		else $output='true';
	}
	
	
	
	
	
	
	
	
	////////////output
	
	echo $output;
}



?>