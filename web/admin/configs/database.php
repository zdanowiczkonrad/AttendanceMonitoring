<?php

 /* mysql */
 
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'inzynier');
	/*define('DB_PREFIX', '');*/
	
   try
   {
      $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);

	  $db->query('SET NAMES UTF8');
   }
   
   catch(PDOException $e)
   {
      //echoo('Nie można nawiązać połączenia z bazą danych. Komunikat:');
      //echoo($e->getMessage());
   }
?>