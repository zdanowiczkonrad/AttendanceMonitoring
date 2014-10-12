import java.awt.Color;
import java.awt.TrayIcon;
import java.util.List;

import javax.smartcardio.*;
import javax.swing.JLabel;



public class CzytnikWatek implements Runnable {

	public int numerczytnika=1;
	public List<CardTerminal> terminals =null;
	public int liczbaczytnikow=0;
	public CardTerminal terminal;
	public boolean stankarty=false;
	private Card card;
	public long card_uid_decimal;
	public boolean aktywneczytanie=false;
	public MainWindow gui;
	public CzytnikWatek(MainWindow mainWindow) {
		gui=mainWindow;
		gui.lblUid.setText("<brak karty>");
	}

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub

	}
	
	public boolean initializeReader()
	{
		stankarty=false;
		//System.out.println("Inicjalizacja klienta");
		TerminalFactory factory = TerminalFactory.getDefault();
		
		
		try {
			terminals = factory.terminals().list();
			//System.out.println("Odnaleziono czytnik(i): " + terminals);
			liczbaczytnikow=terminals.size();
			if(liczbaczytnikow>1)
			{
				//System.out.println("Istnieje wiêcej ni¿ jeden pod³¹czony czytnik. Wybieram czytnik contactless (indeks=1)");
			}
			
			terminal = terminals.get(numerczytnika);
			return true;
		}
		catch (CardException e) 
		{
			return false;
		}

	}
	
	public String[] getTerminalList()
	{
		String[] temp=new String[liczbaczytnikow];
		for(int i=0;i<liczbaczytnikow;i++)
		{
			temp[i]=terminals.get(i).toString();
			//System.out.println(temp[i]);
		}
		return temp;
	}
	
	public void changeState()
	{
		if(stankarty)
		{
			gui.lblUid.setText(Long.toString(card_uid_decimal));
			//gui.underlblUid.setBackground(new Color(51, 230, 103));
			gui.getServerResponse(Long.toString(card_uid_decimal),"442");
			gui.kartaTray.setLabel(Long.toString(card_uid_decimal));
			
		}
		else
		{
			gui.lblUid.setText("<brak karty>");
			gui.kartaTray.setLabel("<brak karty>");
			gui.underlblUid.setBackground(new Color(204,204,204));
			gui.lblUid.setForeground(new Color(10,10,10));
		}
	}
	
	public void run()
	{
		while(aktywneczytanie)
		{
			try {
			    Thread.sleep(1);
			} catch(InterruptedException ex) {
			    Thread.currentThread().interrupt();
			}
			card = null;    
			try {
				card = terminal.connect("T="+Integer.toString(numerczytnika));
				if(stankarty==false)
				{
					//System.out.println("Zbli¿ono kartê " + card);
					
					CardChannel channel = card.getBasicChannel();
					
			        
			        String s="";
			        String s1=""; 
			        String uid_temp="";
			        int n,x;
			       
			        //////////UID///////////   
		            s1=""; 
		            CommandAPDU c2= new CommandAPDU(0xff,0xCA,0x00,0x00,null,0x00,0x00,0x1);
		            ResponseAPDU r1 = null;
					try {
						r1 = channel.transmit(c2);
					} catch (CardException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}  
		            byte uid[]=r1.getBytes();
		         

		            for (n = 0; n < uid.length-2; n++) 
		            {
		                x = (int) (0x000000FF & uid[n]);  // byte to int conversion
		                s = Integer.toHexString(x).toUpperCase();
		                if (s.length() == 1) s = "0" + s;
		                s1=s1+s+"";
		            }
		             uid_temp=s1;
		             card_uid_decimal = 0;
		             card_uid_decimal=Long.parseLong(uid_temp,16);
		             
		             //dec
		             //System.out.println("UID (dec) = "+Long.toString(card_uid_decimal));
		             //hex
		             //System.out.println("UID (hex) = "+uid_temp);
		             stankarty=true;
		             changeState();
		             
			        try {
						card.disconnect(false);
					} catch (CardException e) {
						// TODO Auto-generated catch block
						//e.printStackTrace();
					}	
				}

			} catch (CardException e) {
				if(stankarty==true)
				{
					
					//System.out.println("Kartê usuniêto z czytnika.");
					stankarty=false;
					changeState();
				}
			}
		}
		//System.out.println("wy³¹czam watek...");
	}

}
