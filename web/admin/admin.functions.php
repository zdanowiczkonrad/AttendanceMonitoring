<?php

class Admin
{
	public $db;
	
	public function Admin($baza)
	{
		$this->db=$baza;
	}

	
	private function Action_PobierzKursyProwadzacego()
	{
		$sth=$this->db->prepare("SELECT * FROM kursy JOIN (zajecia JOIN zajecia_prowadzacych ON zajecia_prowadzacych.IdZajecia = zajecia.IdZajecia) ON kursy.KodKursu = zajecia.KodKursu WHERE zajecia_prowadzacych.IdProwadzacy=:IdProwadzacy GROUP BY kursy.KodKursu");
		$sth->bindParam('IdProwadzacy',$d['IdProwadzacy']);
		$sth->execute();
		$result = $sth->fetchAll();
		
		
		return $result;
		/*
		$iter=0;
		foreach($result as $row)
		{
			$this->_buildResponseAddContent("<kurs>");
			$this->_buildResponseAddElement("KodKursu",$row['KodKursu']);
			$this->_buildResponseAddElement("NazwaKursu",$row['NazwaKursu']);
			$this->_buildResponseAddElement("IdFormy",$row['IdFormy']);
			$this->_buildResponseAddElement("ECTS",$row['ECTS']);
			$this->_buildResponseAddContent("</kurs>");
			$iter++;
		}		
		$this->_buildResponseAddElement("ZnalezionoKursow",$iter);
		$this->_buildResponseAddContent("</kursy>");
		*/
	}	
	
	
	
	
	
	
	
	
	///////////////////////////////
	// TERMINY
	///////////////////////////////
	
	public function Action_TerminIstnieje($id)
	{
	$sth=$this->db->prepare('SELECT * FROM terminy WHERE IdTermin=:IdTermin');
		$sth->bindParam('IdTermin',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}

	
	public function Action_DodajTermin($d)
	{
		if($this->Action_TerminIstnieje($d['IdTermin'])) return false;
		$sth=$this->db->prepare('INSERT INTO terminy (Data,IdZajecia) VALUES (:Data,:IdZajecia)');
		$sth->bindParam('Data',$d['Data']);
		$sth->bindParam('IdZajecia',$d['IdZajecia']);
		$sth->execute();
		return true;

	}
	
	public function Action_UsunTermin($id)
	{
		$sth=$this->db->prepare('DELETE FROM obecnosci WHERE IdTermin=:IdTermin');
		$sth->bindParam('IdTermin',$id);
		$sth->execute();
		$sth=$this->db->prepare('DELETE FROM terminy WHERE IdTermin=:IdTermin');
		$sth->bindParam('IdTermin',$id);
		$sth->execute();
		return !$this->Action_TerminIstnieje($id);
	}
	
	public function Action_AktualizujTermin($d)
	{
	
		$sth=$this->db->prepare('UPDATE terminy SET
			Data=:Data
			WHERE
			IdTermin=:IdTermin
			');
		$sth->bindParam('Data',$d['Data']);
		$sth->bindParam('IdTermin',$d['IdTermin']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}

	public function Action_PojedynczyTermin($id)
	{
		$sth=$this->db->prepare('SELECT
	terminy.IdTermin as IdTermin, 
	terminy.Data as Data 
	FROM terminy 
	WHERE terminy.IdTermin=:IdTermin 
	GROUP BY terminy.IdTermin');
		$sth->bindParam('IdTermin',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	///////////////////////////////
	// ZAJECIA
	///////////////////////////////
	
	
	
	
	
	
	
	
	
	
	
	public function Action_ZajeciaIstnieja($id)
	{
	$sth=$this->db->prepare('SELECT * FROM zajecia WHERE IdZajecia=:IdZajecia');
		$sth->bindParam('IdZajecia',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}

	
	public function Action_DodajZajecia($d)
	{
		if($this->Action_ZajeciaIstnieja($d['IdZajecia'])) return false;
		$sth=$this->db->prepare('INSERT INTO zajecia (IdSali,Dzien,Godzina,GodzinaKoniec,Tydzien,KodKursu,IdProwadzacy) VALUES (:IdSali,:Dzien,:Godzina,:GodzinaKoniec,:Tydzien,:KodKursu,:IdProwadzacy)');
		$sth->bindParam('IdSali',$d['IdSali']);
		$sth->bindParam('Dzien',$d['Dzien']);
		$sth->bindParam('Godzina',$d['Godzina']);
		$sth->bindParam('GodzinaKoniec',$d['GodzinaKoniec']);
		$sth->bindParam('Tydzien',$d['Tydzien']);
		$sth->bindParam('KodKursu',$d['KodKursu']);
		$sth->bindParam('IdProwadzacy',$d['IdProwadzacy']);
		$sth->execute();
		return true;

	}
	
	public function Action_UsunZajecia($id)
	{
	echo "doo";
		$sth=$this->db->prepare('SELECT IdTermin FROM terminy WHERE IdZajecia=:IdZajecia');
		$sth->bindParam('IdZajecia',$id);
		$sth->execute();
		$result = $sth->fetchAll();

		foreach($result as $row)
		{	
			$this->Action_UsunTermin($row['IdTermin']);
		}		

		$sth=$this->db->prepare('DELETE FROM zajecia_studentow WHERE IdZajecia=:IdZajecia');
		$sth->bindParam('IdZajecia',$id);
		$sth->execute();
	
		$sth=$this->db->prepare('DELETE FROM zajecia WHERE IdZajecia=:IdZajecia');
		$sth->bindParam('IdZajecia',$id);
		$sth->execute();
		return !$this->Action_ZajeciaIstnieja($id);
	}
	
	public function Action_AktualizujZajecia($d)
	{
	
		$sth=$this->db->prepare('UPDATE zajecia SET
			IdSali=:IdSali,
			Dzien=:Dzien,
			Godzina=:Godzina,
			GodzinaKoniec=:GodzinaKoniec,
			Tydzien=:Tydzien,
			KodKursu=:KodKursu,
			IdProwadzacy=:IdProwadzacy
			WHERE
			IdZajecia=:IdZajecia
			');
		$sth->bindParam('IdZajecia',$d['IdZajecia']);
		$sth->bindParam('IdSali',$d['IdSali']);
		$sth->bindParam('Dzien',$d['Dzien']);
		$sth->bindParam('Godzina',$d['Godzina']);
		$sth->bindParam('GodzinaKoniec',$d['GodzinaKoniec']);
		$sth->bindParam('Tydzien',$d['Tydzien']);
		$sth->bindParam('KodKursu',$d['KodKursu']);
		$sth->bindParam('IdProwadzacy',$d['IdProwadzacy']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}

	public function Action_PojedynczeZajecia($id)
	{
		$sth=$this->db->prepare('SELECT
	zajecia.IdZajecia as IdZajecia,
	zajecia.Dzien as Dzien,
	zajecia.Godzina as Godzina,
	zajecia.GodzinaKoniec as GodzinaKoniec,
	zajecia.Tydzien as Tydzien,
	zajecia.KodKursu as KodKursu,
	zajecia.IdSali as IdSali,
	zajecia.IdProwadzacy as IdProwadzacy,
	COUNT(terminy.IdTermin) AS LiczbaTerminow,
	(SELECT COUNT(*) FROM zajecia_studentow WHERE zajecia_studentow.IdZajecia=zajecia.IdZajecia) AS ZapisanychStudentow,
	sale.Sala,
	sale.Budynek,
	prowadzacy.Imie as Imie,
	prowadzacy.Nazwisko as Nazwisko,
	prowadzacy.Tytul as Tytul,
	kursy.NazwaKursu as NazwaKursu,
	kursy.IdFormy as IdFormy
	FROM zajecia
	LEFT JOIN kursy ON zajecia.KodKursu=kursy.KodKursu 
	LEFT JOIN terminy ON terminy.IdZajecia=zajecia.IdZajecia
	LEFT JOIN sale ON zajecia.IdSali=sale.IdSali
	LEFT JOIN prowadzacy ON prowadzacy.IdProwadzacy=zajecia.IdProwadzacy 
	WHERE zajecia.IdZajecia=:IdZajecia
	GROUP BY zajecia.IdZajecia');
		$sth->bindParam('IdZajecia',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}	
	
	
	public function Action_UsunObecnosc($NrIndeksu,$IdTermin)
	{
		$sth=$this->db->prepare('DELETE FROM obecnosci WHERE IdTermin=:IdTermin AND NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->bindParam('IdTermin',$IdTermin);
		$sth->execute();
	}
	
	public function Action_DodajObecnosc($NrIndeksu,$IdTermin,$Typ)
	{
		$sth=$this->db->prepare('INSERT INTO obecnosci (IdTermin,NrIndeksu,Typ,DataObecnosci) VALUES (:IdTermin,:NrIndeksu,:Typ,:Data)');
		$sth->bindParam('Data',date('Y-m-d H:i:s'));
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->bindParam('IdTermin',$IdTermin);
		$sth->bindParam('Typ',$Typ);
		$sth->execute();
	
	}	
	
	public function Action_SprawdzObecnosc($NrIndeksu,$IdTermin)
	{
		$sth=$this->db->prepare('SELECT Typ FROM obecnosci WHERE IdTermin=:IdTermin AND NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->bindParam('IdTermin',$IdTermin);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return $row['Typ'];
		}		
		return "0";
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	///////////////////////////////
	//  KURSY
	///////////////////////////////
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function Action_KursIstnieje($id)
	{
	$sth=$this->db->prepare('SELECT * FROM kursy WHERE KodKursu=:KodKursu');
		$sth->bindParam('KodKursu',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}

	
	public function Action_DodajKurs($d)
	{
		if($this->Action_KursIstnieje($d['KodKursu'])) return false;
		$sth=$this->db->prepare('INSERT INTO kursy (KodKursu,NazwaKursu,IdFormy,ECTS) VALUES (:KodKursu,:NazwaKursu,:IdFormy,:ECTS)');
		$sth->bindParam('KodKursu',$d['KodKursu']);
		$sth->bindParam('NazwaKursu',$d['NazwaKursu']);
		$sth->bindParam('IdFormy',$d['IdFormy']);
		$sth->bindParam('ECTS',$d['ECTS']);
		$sth->execute();
		return $this->Action_KursIstnieje($d['KodKursu']);

	}
	
	public function Action_UsunKurs($id)
	{
		$sth=$this->db->prepare('SELECT IdZajecia FROM zajecia WHERE KodKursu=:KodKursu');
		$sth->bindParam('KodKursu',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{	
			$this->Action_UsunZajecia($row['IdZajecia']);
		}	
	
		$sth=$this->db->prepare('DELETE FROM kursy WHERE KodKursu=:KodKursu');
		$sth->bindParam('KodKursu',$id);
		$sth->execute();
		return !$this->Action_KursIstnieje($id);
	}
	
	public function Action_AktualizujKurs($d)
	{
	
		$sth=$this->db->prepare('UPDATE kursy SET
			NazwaKursu=:NazwaKursu,
			IdFormy=:IdFormy,
			ECTS=:ECTS
			WHERE KodKursu=:KodKursu');
		$sth->bindParam('KodKursu',$d['KodKursu']);
		$sth->bindParam('NazwaKursu',$d['NazwaKursu']);
		$sth->bindParam('IdFormy',$d['IdFormy']);
		$sth->bindParam('ECTS',$d['ECTS']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}

	public function Action_PojedynczyKurs($id)
	{
		$sth=$this->db->prepare('SELECT * FROM kursy WHERE KodKursu=:KodKursu');
		$sth->bindParam('KodKursu',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//// STUDENCI - PODSTAWOWE FUNKCJE
	////
	////
	
	public function Action_StudentIstnieje($id)
	{
	$sth=$this->db->prepare('SELECT * FROM studenci WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}
	
	public function Action_StudentIstniejeUid($id,$uid)
	{
	$sth=$this->db->prepare('SELECT * FROM studenci WHERE NrIndeksu=:NrIndeksu AND Uid=:Uid');
		$sth->bindParam('NrIndeksu',$id);
		$sth->bindParam('Uid',$uid);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}	
	
	public function Action_DodajStudenta($d)
	{
		if($this->Action_StudentIstnieje($d['NrIndeksu'])) return false;
		$sth=$this->db->prepare('INSERT INTO studenci (NrIndeksu,Imie,Nazwisko,Uid) VALUES (:NrIndeksu,:Imie,:Nazwisko,:Uid)');
		$sth->bindParam('NrIndeksu',$d['NrIndeksu']);
		$sth->bindParam('Imie',$d['Imie']);
		$sth->bindParam('Nazwisko',$d['Nazwisko']);
		$sth->bindParam('Uid',$d['Uid']);
		$sth->execute();
		return $this->Action_StudentIstnieje($d['NrIndeksu']);

	}
	
	public function Action_UsunStudenta($id)
	{
		$sth=$this->db->prepare('DELETE FROM obecnosci WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$id);
		$sth->execute();	
		$sth=$this->db->prepare('DELETE FROM zajecia_studentow WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$id);
		$sth->execute();		
		$sth=$this->db->prepare('DELETE FROM studenci WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$id);
		$sth->execute();
		return !$this->Action_StudentIstnieje($id);
	}
	
	public function Action_AktualizujStudenta($d)
	{
	
		$sth=$this->db->prepare('UPDATE studenci SET
			Imie=:Imie,
			Nazwisko=:Nazwisko,
			Uid=:Uid
			WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$d['NrIndeksu']);
		$sth->bindParam('Imie',$d['Imie']);
		$sth->bindParam('Nazwisko',$d['Nazwisko']);
		$sth->bindParam('Uid',$d['Uid']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}

	public function Action_PojedynczyStudent($id)
	{
		$sth=$this->db->prepare('SELECT * FROM studenci WHERE NrIndeksu=:NrIndeksu');
		$sth->bindParam('NrIndeksu',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}
	
	public function Action_PojedynczyStudentPoUid($id)
	{
		$sth=$this->db->prepare('SELECT * FROM studenci WHERE Uid=:Uid');
		$sth->bindParam('Uid',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}	
	
	public function Action_StudentPoNazwiskuLubImieniu($q)
	{
		$sth=$this->db->prepare('SELECT NrIndeksu,Imie,Nazwisko FROM Studenci WHERE Imie LIKE :Query OR Nazwisko LIKE :Query OR NrIndeksu LIKE :Query');
		$search_query='%'.$q.'%';
		$sth->bindValue('Query','%'.$search_query.'%');
		$sth->execute();	
		$result = $sth->fetchAll();
		$data = array();
		$iter=0;
		foreach($result as $row)
		{
			$json = array();
			$json['id'] = $row['NrIndeksu'];
			$json['value'] = '('.$row['NrIndeksu'].') '.$row['Imie'].' '.$row['Nazwisko'];
			$data[] = $json;
			$iter++;

		}		
		if($iter>0) return $data;
		return;
		
	}
	
	
	
	//// PROWADZACY - PODSTAWOWE FUNKCJE
	////
	////
	
	
	public function Action_ProwadzacyIstnieje($id)
	{
	$sth=$this->db->prepare('SELECT * FROM prowadzacy WHERE IdProwadzacy=:IdProwadzacy');
		$sth->bindParam('IdProwadzacy',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}
	
	public function Action_ProwadzacyIstniejeUid($id,$uid)
	{
	$sth=$this->db->prepare('SELECT * FROM prowadzacy WHERE IdProwadzacy=:IdProwadzacy AND Uid=:Uid');
		$sth->bindParam('IdProwadzacy',$id);
		$sth->bindParam('Uid',$uid);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}
	
	public function Action_DodajProwadzacego($d)
	{
		$sth=$this->db->prepare('INSERT INTO prowadzacy (Imie,Nazwisko,Tytul,Uid) VALUES (:Imie,:Nazwisko,:Tytul,:Uid)');
		$sth->bindParam('Imie',$d['Imie']);
		$sth->bindParam('Nazwisko',$d['Nazwisko']);
		$sth->bindParam('Tytul',$d['Tytul']);
		$sth->bindParam('Uid',$d['Uid']);
		$sth->execute();
		return true;

	}
	
	public function Action_UsunProwadzacego($id)
	{
		$sth=$this->db->prepare('DELETE FROM prowadzacy WHERE IdProwadzacy=:IdProwadzacy');
		$sth->bindParam('IdProwadzacy',$id);
		$sth->execute();
		return !$this->Action_ProwadzacyIstnieje($id);
	}
	
	public function Action_AktualizujProwadzacego($d)
	{
	
		$sth=$this->db->prepare('UPDATE prowadzacy SET
			Imie=:Imie,
			Nazwisko=:Nazwisko,
			Tytul=:Tytul,
			Uid=:Uid
			WHERE IdProwadzacy=:IdProwadzacy');
		$sth->bindParam('IdProwadzacy',$d['IdProwadzacy']);
		$sth->bindParam('Imie',$d['Imie']);
		$sth->bindParam('Nazwisko',$d['Nazwisko']);
		$sth->bindParam('Tytul',$d['Tytul']);
		$sth->bindParam('Uid',$d['Uid']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}

	public function Action_PojedynczyProwadzacy($id)
	{
		$sth=$this->db->prepare('SELECT * FROM prowadzacy WHERE IdProwadzacy=:IdProwadzacy');
		$sth->bindParam('IdProwadzacy',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}	
	
	public function Action_PojedynczyProwadzacyPoUid($id)
	{
		$sth=$this->db->prepare('SELECT * FROM prowadzacy WHERE Uid=:Uid');
		$sth->bindParam('Uid',$id);
		$sth->execute();
		
		$result = $sth->fetchAll();
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}		
	
	public function Action_ProwadzacyPoNazwiskuLubImieniu($q)
	{
		$sth=$this->db->prepare('SELECT IdProwadzacy,Tytul,Imie,Nazwisko FROM prowadzacy WHERE Imie LIKE :Query OR Nazwisko LIKE :Query');
		$search_query='%'.$q.'%';
		$sth->bindValue('Query','%'.$search_query.'%');
		$sth->execute();	
		$result = $sth->fetchAll();
		$data = array();
		$iter=0;
		foreach($result as $row)
		{
			$json = array();
			$json['id'] = $row['IdProwadzacy'];
			$json['value'] = $row['Tytul'].' '.$row['Imie'].' '.$row['Nazwisko'];
			$data[] = $json;
			$iter++;

		}		
		if($iter>0) return $data;
		return;
		
	}


	
	
	///////////////////////////////
	// ZAPISY STUDENTA
	///////////////////////////////
	

	public function Action_StudentZapisany($NrIndeksu,$IdZajecia)
	{
		$sth=$this->db->prepare('SELECT * FROM zajecia_studentow WHERE IdZajecia=:IdZajecia AND NrIndeksu=:NrIndeksu');
		$sth->bindParam('IdZajecia',$IdZajecia);
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}
	
	public function Action_ZapiszStudenta($NrIndeksu,$IdZajecia)
	{
		if($this->Action_StudentZapisany($NrIndeksu,$IdZajecia)) return true;
		$sth=$this->db->prepare('INSERT INTO zajecia_studentow (IdZajecia,NrIndeksu) VALUES (:IdZajecia,:NrIndeksu)');		
		$sth->bindParam('IdZajecia',$IdZajecia);
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->execute();
		return true;
	}	

	public function Action_WypiszStudenta($NrIndeksu,$IdZajecia)
	{
		$sth=$this->db->prepare('DELETE FROM zajecia_studentow WHERE IdZajecia=:IdZajecia AND NrIndeksu=:NrIndeksu');
		$sth->bindParam('IdZajecia',$IdZajecia);
		$sth->bindParam('NrIndeksu',$NrIndeksu);
		$sth->execute();
	
		return !$this->Action_StudentZapisany($NrIndeksu,$IdZajecia);
	}	
	
	
	
	
	
	
	
	///////////////////////////////
	// SALE
	///////////////////////////////	
	
	public function Action_PojedynczaSala($id)
	{
		$sth=$this->db->prepare('SELECT * FROM Sale WHERE IdSali=:IdSali');
		$sth->bindParam('IdSali',$id);
		$sth->execute();
		$result = $sth->fetchAll();		
		$iter=0;
		foreach($result as $row)
		{
			$iter++;
		}		
		if($iter) return $result;
		return false;
	}	
	
	public function Action_SalaIstnieje($id)
	{
		$sth=$this->db->prepare('SELECT * FROM Sale WHERE IdSali=:IdSali');
		$sth->bindParam('IdSali',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return true;
		}		
		return false;
	}	
	
	
	
	public function Action_DodajSale($d)
	{

		$sth=$this->db->prepare('INSERT INTO sale (Sala,Budynek) VALUES (:Sala,:Budynek)');
		$sth->bindParam('Sala',$d['Sala']);
		$sth->bindParam('Budynek',$d['Budynek']);
		$sth->execute();
		return true;

	}
	
	public function Action_UsunSale($id)
	{
		$sth=$this->db->prepare('SELECT COUNT(*) as liczba FROM zajecia WHERE IdSali=:IdSali');
		$sth->bindParam('IdSali',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			if($row['liczba']>0) return false;
		}	
		$sth=$this->db->prepare('DELETE FROM sale WHERE IdSali=:IdSali');
		$sth->bindParam('IdSali',$id);
		$sth->execute();
		return !$this->Action_SalaIstnieje($id);
	}
	
	public function Action_AktualizujSale($d)
	{
	
		$sth=$this->db->prepare('UPDATE sale SET
			Sala=:Sala,
			Budynek=:Budynek
			WHERE IdSali=:IdSali');
		$sth->bindParam('Sala',$d['Sala']);
		$sth->bindParam('Budynek',$d['Budynek']);
		$sth->bindParam('IdSali',$d['IdSali']);
		try
		{
			$sth->execute();	
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}	
	
	public function Action_TerminyStempel($d)
	{
		if(is_array($d))
		{
			$list_in="-1";
			for($i=0;$i<sizeof($d);$i++)
			{
				$list_in.=",".$d[$i];
			}
		}
		else $list_in=$d;

		$sql="SELECT DataObecnosci
			FROM `obecnosci` 
			WHERE IdTermin IN (".$list_in.") 
			ORDER BY DataObecnosci DESC 
			LIMIT 0 , 1";
			
		$sth=$this->db->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $row)
		{
			return $row['DataObecnosci'];
		}		
		return false;
	}
}
?>