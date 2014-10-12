package com.example.obecnosci_klient_1_0;


import android.os.Bundle;
import android.preference.PreferenceManager;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

//klient

import java.io.DataInputStream;
import java.io.PrintStream;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;



public class MainActivity extends Activity {

	
	//wstepna definicja obiektow gui
	TextView responseContentTextView;
	EditText requestUidEditText,requestDateEditText;
	CheckBox simulateUid,simulateDate;
	
	//ustawienia
	SharedPreferences sharedPrefs;
	
	 // The client socket
	  private static Socket clientSocket = null;
	  // The output stream
	  private static PrintStream os = null;
	  // The input stream
	  private static DataInputStream is = null;
	
	  private static BufferedReader inputLine = null;
	  private static boolean closed = false;
	  int portNumber;
	  
	  String host;
	  
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        sharedPrefs = PreferenceManager.getDefaultSharedPreferences(MainActivity.this);
        //odnalezienie po ID
        requestUidEditText = (EditText)findViewById(R.id.requestUid);

        responseContentTextView = (TextView)findViewById(R.id.responseContent);

        
        
        
        //inicjalizacja klienta
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.activity_main, menu);
        return true;
        
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
      switch (item.getItemId()) {
      case R.id.menu_settings:
        //Toast.makeText(this, "Ustawienia", Toast.LENGTH_SHORT).show();
        startActivity(new Intent(this, Preferences.class));
        break;
      case R.id.menu_minimalize:
    	  Intent intent = new Intent(Intent.ACTION_MAIN);
    	    intent.addCategory(Intent.CATEGORY_HOME);
    	    startActivity(intent);
    	    break;
      default:
        break;
      }

      return true;
    }
    

    
    public void pseudoConsoleWrite(String tekst)
    {
    	responseContentTextView.setText(tekst+"\n"+responseContentTextView.getText());
    }
    
    
    public boolean buttonRequestClick(View view) {
    	//wstep
        //Toast.makeText(this, "Odczytano zbliżenie", Toast.LENGTH_SHORT).show();
        host=sharedPrefs.getString("server_ip", "19.168.1.100");
        portNumber=Integer.parseInt(sharedPrefs.getString("tcp_port", "2323"));
        pseudoConsoleWrite("Nawiazywanie polaczenia z "+host+":"+portNumber+"...");
        
        //inicjalizacja polaczenia
        try {
            clientSocket = new Socket(host, portNumber);
            os = new PrintStream(clientSocket.getOutputStream());
            is = new DataInputStream(clientSocket.getInputStream());
            pseudoConsoleWrite("Nawiązano poprawne połązenie.");
          } catch (UnknownHostException e) {
        	  pseudoConsoleWrite("Nie odnaleziono hosta " + host);
        	  return false;
          } catch (IOException e) {
        	  pseudoConsoleWrite("Błąd połączenia I/O z hostem "
                + host);
        	  return false;
          }
        
        //wyslanie komunikatu
        if (clientSocket != null && os != null && is != null) {
        	String command="uid "+requestUidEditText.getText()+" sala "+sharedPrefs.getString("id_sali", "-1");
        	
        	if(requestUidEditText.getText().toString().length() == 0)
        	{
        		Toast.makeText(this,"Wpisz poprawną wartość UID", Toast.LENGTH_SHORT).show();
        	
        	}
        	else os.println(command);
        	
        	
            String responseLine;
            try {
				responseLine = is.readLine();
				Toast.makeText(this,responseLine, Toast.LENGTH_SHORT).show();
	            is.close();
	            os.close();
	            clientSocket.close();
			} catch (IOException e) {
				Toast.makeText(this,"Błąd! Nie otrzymano odpowiedzi z serwera", Toast.LENGTH_SHORT).show();
				 pseudoConsoleWrite("! Błąd otrzymywania komunikatu.");
				 return false;
			}
           
            return true;
          }
		return false;
    

    
    	 //Toast.makeText(this, sharedPrefs.getString("server_ip", "19.168.1.100"), Toast.LENGTH_SHORT).show();
        
        
        
        
    	//dopisz tekst do symulatora konsoli
    	
    	//

    }
    
    // odblokowanie/zablokowanie inputow
     	

}
