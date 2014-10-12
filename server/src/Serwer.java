import java.io.DataInputStream;
import java.io.PrintStream;
import java.io.IOException;
import java.net.Socket;
import java.net.ServerSocket;

//mysql
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.ResultSet;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.StringTokenizer;
/*
 * A chat server that delivers public and private messages.
 */
public class Serwer {

  // The server socket. 
  private static ServerSocket serverSocket = null;
  
  // The client socket.
  private static Socket clientSocket = null;

  // This chat server can accept up to maxClientsCount clients' connections.
  private static final int maxClientsCount = 200;
  
  // lista watkow z polaczeniami klientow
  private static final clientThread[] threads = new clientThread[maxClientsCount];

  public static void main(String args[]) {
	  
    // The default port number.
    int portNumber = 2048;
    if (args.length < 1)
    {

    }
    else
    {
	//wykonuje sie, jezeli argumenty wywolania istnieja
      portNumber = Integer.valueOf(args[0]).intValue();
    }
    
    System.out.println("[i] Serwer aktywny, numer portu: " + portNumber);

    /*
     * Open a server socket on the portNumber (default 5512). Note that we can
     * not choose a port less than 1023 if we are not privileged users (root).
     */
    try {
      serverSocket = new ServerSocket(portNumber);
    } catch (IOException e) {
      //System.out.println(e);
    	System.out.println("[!] Wybrany port jest już otwarty i nasłuchiwany przez inną aplikację.");
    }

    /*
     * Create a client socket for each connection and pass it to a new client
     * thread.
     */
    while (true) {
      try {
	  //przyjmuje polaczenie
        clientSocket = serverSocket.accept();
        int i = 0;
		//szuka wolnego watku
        for (i = 0; i < maxClientsCount; i++) {
          if (threads[i] == null) {
		  //znalazl nieprzypisany watek. utworz watek
            (threads[i] = new clientThread(clientSocket)).start();
            break;
          }
        }
        if (i == maxClientsCount) {
          PrintStream os = new PrintStream(clientSocket.getOutputStream());
          os.println("[i] Serwer jest zajęty. Spróbuj później.");
          os.close();
          clientSocket.close();
        }
      } catch (IOException e) {
        //System.out.println(e);
    	  
      }
    }
  }
}











/* klient */

class clientThread extends Thread {
	
  private static Connection conn;
  private static Statement  stmt;
  private static ResultSet result = null;
private static final String CONNECTION_URL = "jdbc:mysql://localhost/inzynier?user=root&;password=";
  private DataInputStream is = null;
  private PrintStream os = null;
  private Socket clientSocket = null;

  public clientThread(Socket clientSocket) {
    this.clientSocket = clientSocket;

  }

  public void run() {
	//mysql conn
	     try
	        {
	    	 Class.forName( "com.mysql.jdbc.Driver" ).newInstance();
	    	  conn = DriverManager.getConnection( CONNECTION_URL );
	    	  stmt = conn.createStatement();
	        }
	        catch( Exception e )
	        {
	            System.out.println("[!] Brak połączenia z bazą danych MySQL" );
	            //e.printStackTrace();
	        }

    try {
      is = new DataInputStream(clientSocket.getInputStream());
      os = new PrintStream(clientSocket.getOutputStream());
      String input="uid -1 sala -1";
      String output;
      
        //wez dane
      
        try {
			input = is.readLine().trim();
		} catch (Exception e2) {
			e2.printStackTrace();
		}
  		//symulowane zapytanie
  		//input="uid 14 sala 7";
       
		String serverResponse="Brak odpowiedzi...";
		int serverResponseCode=0;

  		// DANE WEJSCIOWE
  		// okreslenie daty i waznych parametrow
  		
  		// dozwolone spoznienie obecnosci i otwarcia sali (minuty)
  		int spoznienie_student=16;
  		int spoznienie_prowadzacego=30;
  		
  		

  		
  		//obecna data i czas
  		DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
  		Date date = new Date();
  		String data_teraz="2012-09-20 11:11:12";
  		data_teraz=dateFormat.format(date);
  	
  		//obecny czas
  		dateFormat=new SimpleDateFormat("HH:mm:ss");
  		String czas_teraz=dateFormat.format(date);
  		
  		//obecna data
  		dateFormat=new SimpleDateFormat("yyyy-MM-dd");
  		String samadata_teraz=dateFormat.format(date);

  		//dzien tygodnia
  		Calendar cal = Calendar.getInstance();
  		cal.setTime(date);
  		int dow = cal.get(Calendar.DAY_OF_WEEK);
  		
  		String dzien_teraz=null;
  		switch(dow)
  		{
  			case 2: dzien_teraz="pon";	break;
  			case 3: dzien_teraz="wt";	break;
  			case 4: dzien_teraz="sr";	break;
  			case 5: dzien_teraz="czw";	break;
  			case 6: dzien_teraz="pt";	break;
  			case 7: dzien_teraz="so";	break;
  			case 1: dzien_teraz="ndz";	break;
  		}
  	
  		
  		//parzystosc tygodnia
  		dateFormat = new SimpleDateFormat("w");
  		int numer_tygodnia=Integer.parseInt(dateFormat.format(date));
  		
  		String tydzien=null;
  		switch(numer_tygodnia%2)
  		{
  			case 0: tydzien="TP"; break;
  			case 1: tydzien="TN"; break;
  		}
  	
  		
  		//dane pomocnicze
  		String temp=null;
  		String uid=null;
  		String sala=null;
  		String symulacyjnadata=null;
  		//rozebranie tresci zapytania
  		 StringTokenizer st = new StringTokenizer(input);
  		 
          while (st.hasMoreTokens()) {
          	temp=st.nextToken();
              if(temp.equals("uid"))
              {
            	
              	try {
					uid=st.nextToken();
				} catch (Exception e) {
					uid="-1";
					e.printStackTrace();
				} 
            	  
     
              }
              else if(temp.equals("sala"))
              {
              	try {
					sala=st.nextToken();
				} catch (Exception e) {
					sala="-1";
					e.printStackTrace();
				}
              }
              else if(temp.equals("data"))
              {
            	try {
					symulacyjnadata=st.nextToken();
				} catch (Exception e) {
					symulacyjnadata="-1";
					e.printStackTrace();
				}
              }
              //else System.out.println(temp);
          }

  	   
  	        if(sala.equals("-1") || uid.equals("-1"))
  	        {
  	        	serverResponse="Niepelne lub bledne zapytanie.";
  	        }
  	        else
  	        {
  	        	//wlasciwe przetworzenie zapytania
  	        	
  	        	//czy istnieje w bazie prowadzacy o zadanym numerze uid
  	        	
              	try {
              		
              		String UidProwadzacego=null;
              		String UidStudenta=null;
              		String IdZajecia=null;
              		String Tydzien=null;
              		String Godzina=null;
              		String IdProwadzacy=null;
              		String DataOtwarcia=null;
              		String IdTermin=null;
              		String query= "SELECT IdZajecia,Tydzien,Godzina,IdProwadzacy FROM zajecia WHERE IdSali='"+sala+"' AND Dzien='"+dzien_teraz+"' AND Godzina<'"+czas_teraz+"' AND GodzinaKoniec>'"+czas_teraz+"'";
  					//System.out.println(query);
  					
  					//zidenfytikuj zajecia w tej sali
  					result = stmt.executeQuery(query);
  					int found=0;
  					while( result.next())
  					{
  						found++;
  						IdZajecia=result.getString("IdZajecia");
  						Tydzien=result.getString("Tydzien");
  						Godzina=result.getString("Godzina");
  						IdProwadzacy=result.getString("IdProwadzacy");
  						
  					}
  					
  					
  					
  					//zobacz czy w tym dniu utworzono zajecia
  					query="SELECT IdTermin,Data FROM Terminy WHERE IdZajecia='"+IdZajecia+"' AND DATE_FORMAT(Data, '%Y-%m-%d') = '"+samadata_teraz+"'";
  					result = stmt.executeQuery(query);
  					found=0;
  					while( result.next())
  					{
  						found++;
  						DataOtwarcia=result.getString("Data");
  						IdTermin=result.getString("IdTermin");
  						///////////////////// JAVA dodaje .0 na koncu daty!!!
  						DataOtwarcia=DataOtwarcia.substring(0, DataOtwarcia.length() - 2);
  					}
  					
  					
  					
  					//wybierz z bazy IdProwadzacego ktory ma taki UID
  					result = stmt.executeQuery( "SELECT IdProwadzacy FROM prowadzacy WHERE Uid="+uid+";");
  					while( result.next())
  					{
  						UidProwadzacego=result.getString("IdProwadzacy");
  						
  					}
  					
  					//wybierz z bazy IdStudenta ktory ma taki UID
  					result = stmt.executeQuery( "SELECT NrIndeksu FROM studenci WHERE Uid="+uid+";");
  					while( result.next())
  					{
  						UidStudenta=result.getString("NrIndeksu");
  					}
  				
  						
  					
  					if(IdZajecia==null)
  					{
  						serverResponse="Brak zajec w tej sali o tej porze.";
  						serverResponseCode=-1;
  					}
  					else if(UidProwadzacego!=null)
  					{
  						
  						if(Integer.parseInt(IdProwadzacy)==Integer.parseInt(UidProwadzacego))
  						{
  							
  							if(Tydzien.equals("T") || Tydzien.equals(tydzien))
  							{
  								if(IdTermin==null)
  								{
  									Calendar najpozniej_tolerowane=Calendar.getInstance();
  									Calendar teraz=Calendar.getInstance();
  									
  									dateFormat=new SimpleDateFormat("HH:mm:ss");
  									  try {
  										date = (Date)dateFormat.parse(Godzina);
  									} catch (ParseException e1) {
  										// TODO Auto-generated catch block
  										e1.printStackTrace();
  									}  
  									  najpozniej_tolerowane.setTime(date);
  									  najpozniej_tolerowane.add(Calendar.MINUTE, spoznienie_prowadzacego);
  									  //teraz.getTime();
  									  
  									  dateFormat=new SimpleDateFormat("HH:mm:ss");
  									
  									  //System.out.println(dateFormat.format(najpozniej_tolerowane.getTime()));
  									 // System.out.println(dateFormat.format(teraz.getTime()));
  									  //System.out.println(dateFormat.format(najpozniej_tolerowane.getTime()));
  									  boolean check=najpozniej_tolerowane.before(teraz);
  									if(check)
  									{
  										
  										stmt.executeUpdate("INSERT INTO terminy(Data,IdZajecia) VALUES('"+data_teraz+"','"+IdZajecia+"')");
  										
  										serverResponse="Sala zostala otwarta.";
  										serverResponseCode=1;
  									}
  									else
  									{
  										serverResponse="Spoznienie prowadzacego jest za duze, aby otworzyc sale (maksymalny tolerowany czas spoznienia: "+spoznienie_prowadzacego+" minut).";
  										serverResponseCode=-1;
  										
  									}
  								}
  								else
  								{
  									serverResponse="Sala jest juz otwarta.";
  									serverResponseCode=0;
  								}
  							}
  							else
  							{
  								serverResponse="Brak zajec w tej sali o tej porze (nieodpowiedni tydzien).";
  								serverResponseCode=-1;
  							}
  						
  						}
  						else
  						{
  							
  							serverResponse="Prowadzacy nie rowadzi tych zajec.";
  							serverResponseCode=-1;							
  						}
  				
  						//masz juz IdZajec
  						//teraz zobacz czy istnieje termin
  						//jesli nie,utworz
  						
  					}
  					else if(UidStudenta!=null)
  					{
  						if(IdTermin==null)
  						{
  							serverResponse="Zajecia powinny sie odbywac, ale sala jeszcze nie jest otwarta przez prowadzacego.";
  							serverResponseCode=-1;
  						}
  						else
  						{
  							//sprawdz czy student jest zapisany
  							query="SELECT NrIndeksu FROM zajecia_studentow WHERE IdZajecia='"+IdZajecia+"' AND NrIndeksu='"+UidStudenta+"'";
  							result = stmt.executeQuery(query);
  							found=0;
  							while( result.next())
  							{
  								found++;
  							}
  							if(found>0)
  							{
  								//sprawdz czy nie wpisano obecnosci
  								query="SELECT Typ FROM obecnosci WHERE IdTermin='"+IdTermin+"' AND NrIndeksu='"+UidStudenta+"'";
  								result = stmt.executeQuery(query);
  								found=0;
  								String typ_obecnosci=null;
  								while( result.next())
  								{
  									found++;
  									typ_obecnosci=result.getString("Typ");
  								}
  								if(found>0)
  								{
  									serverResponse="Studentowi juz zostala wpisana obecnosc ("+typ_obecnosci+")";
  									serverResponseCode=0;
  								}
  								else
  								{
  								
  									Calendar najpozniej_tolerowane=Calendar.getInstance();
  									Calendar teraz=Calendar.getInstance();
  									
  									dateFormat=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
  									  try {
  										date = (Date)dateFormat.parse(DataOtwarcia);
  									} catch (ParseException e1) {
  										// TODO Auto-generated catch block
  										e1.printStackTrace();
  									}  
  									  najpozniej_tolerowane.setTime(date);
  									  najpozniej_tolerowane.add(Calendar.MINUTE, spoznienie_student);
  									  teraz.getTime();
  									
  									if(teraz.before(najpozniej_tolerowane)==true)
  									{
  																	
  										// wpisz obecnosc
  										stmt.executeUpdate("INSERT INTO obecnosci VALUES('"+IdTermin+"','"+UidStudenta+"','1','"+dateFormat.format(teraz.getTime())+"')");
  										serverResponse="Studentowi zostala wpisana obecnosc.";
  										serverResponseCode=1;
  									}
  									else
  									{
  										stmt.executeUpdate("INSERT INTO obecnosci VALUES('"+IdTermin+"','"+UidStudenta+"','2','"+dateFormat.format(teraz.getTime())+"')");
  										serverResponse="Studentowi zostalo wpisane spoznienie.";
  										serverResponseCode=1;
  									}
  								}
  							}
  							else
  							{
  								serverResponse="Student nie jest zapisany na te zajecia.";
  								serverResponseCode=-1;
  							}
  						}
  					}
  					else
  					{
  						serverResponse="Brak zadanego UID w bazie.";
  						serverResponseCode=-2;
  					}
  				/////studenten
  						//zobacz czy student jest zapisany
  						//zobacz czy zajecia sa otwarte
  						//mozesz wpisac obecnosc
  						
  				/////prowadzacy
  						//sprawdz czy istnieja zajecia na ten czas w tej sali
  						//sprawdz czy wlasciwy tydzien
  						//sprawdz czy prowadzacy prowadzi te zajecia
  					
  						//masz juz IdZajec
  						//teraz zobacz czy istnieje termin
  						//jesli nie,utworz
  						
  					
  					
  					
  					
  					
  					
  				} catch (SQLException e) {
  					// TODO Auto-generated catch block
  					e.printStackTrace();
  				}
  	        	
  	        }
  	        //System.out.println(serverResponse);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        //...
        //output=input;
          os.println(serverResponse+" [ "+serverResponseCode+" ]");
        }
      
     catch (IOException e) {
    }
  }
}