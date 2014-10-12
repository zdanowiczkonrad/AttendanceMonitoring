import java.awt.AWTException;
import java.awt.CheckboxMenuItem;
import java.awt.Desktop;
import java.awt.EventQueue;
import java.awt.Image;
import java.awt.Menu;
import java.awt.MenuItem;
import java.awt.PopupMenu;
import java.awt.SystemTray;
import java.awt.TrayIcon;
import java.awt.Toolkit;
import javax.swing.JFrame;

import javax.swing.JLabel;
import javax.swing.JPanel;
import java.awt.SystemColor;


import javax.swing.JButton;
import java.awt.Font;

import javax.swing.DefaultListModel;
import javax.swing.JOptionPane;
import javax.swing.JScrollPane;
import javax.swing.JTabbedPane;
import javax.swing.JTextField;
import javax.swing.UnsupportedLookAndFeelException;

import javax.swing.JList;
import javax.swing.UIManager;
import java.awt.Color;
import javax.swing.border.LineBorder;
import javax.swing.event.ListSelectionListener;

import java.awt.BorderLayout;
import javax.swing.SwingConstants;
import javax.swing.JTextPane;
import java.awt.Window.Type;
import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.IOException;
import java.io.PrintStream;
import java.net.MalformedURLException;
import java.net.Socket;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.net.UnknownHostException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.regex.Pattern;

import java.awt.event.*;  


import javax.swing.event.*;
import javax.swing.ImageIcon;
import java.awt.Dialog.ModalExclusionType;
import javax.swing.border.EtchedBorder;


public class MainWindow implements ActionListener {

	/*
	 * GUI
	 */
	
	private JFrame frmKlientObecnoci;
	private JTextField textFieldIP;
	private JTextField textFieldPort;
	private JTextField textFieldIdSali;
	public DefaultListModel listaCzytnikow;
	public CzytnikWatek Czytnik;
	private JButton btnOdwieListPodczonych;
	private JList list;
	private JButton btnWybierzZaznaczony;
	private JLabel lblCzytnikWybrany;
	private JLabel lblPoczono;
	private JButton btnStop;
	public boolean czytanieUruchomione=false;
	public Thread czytanieKartWatek;
	public JLabel lblUid;
	public JPanel underlblUid;
	
	public boolean validConnection=false;
	public String validHost=null;
	public String validPort=null;
	
	/*
	 * KLIENT 
	 */
	
	 // The client socket
	  public static Socket clientSocket = null;
	  // The output stream
	  public static PrintStream os = null;
	  // The input stream
	  public static DataInputStream is = null;
	
	  public static BufferedReader inputLine = null;
	  public static boolean closed = false;
	  int portNumber;
	  
	  String host;
	public JTextPane txtpnOdpowiedZSerwera;
	public JTextPane textPaneLog;
	private JLabel lblUstawieniaZapisanoPomylnie;
	public JButton btnPolaczZSerwerem;
	public JButton btnWyczy;
	public MenuItem kartaTray;
	public MenuItem quickStartMenu;
	public TrayIcon trayIcon;
	private JPanel panelGlowny;
	private JPanel panelUstawienia;
	private JPanel panelCzytnik;
	private JTabbedPane tabbedPane;
	private JLabel lblNieaktywny;
	private JButton btnAdministracja;
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		  /* Use an appropriate Look and Feel */
        try {
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
            //UIManager.setLookAndFeel("javax.swing.plaf.metal.MetalLookAndFeel");
        
        } catch (IllegalAccessException ex) {
            ex.printStackTrace();
        } catch (InstantiationException ex) {
            ex.printStackTrace();
        } catch (ClassNotFoundException ex) {
            ex.printStackTrace();
        } catch (UnsupportedLookAndFeelException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		EventQueue.invokeLater(new Runnable() {
			

			public void run() {
				try {
					MainWindow window = new MainWindow();
					window.frmKlientObecnoci.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the application.
	 */
	public MainWindow() {
		//frmKlientObecnoci.setIconImage(Toolkit.getDefaultToolkit().getImage("images\\app.png")); 
		initialize();
		this.frmKlientObecnoci.setDefaultCloseOperation(JFrame.HIDE_ON_CLOSE);
		btnOdwieListPodczonych.setActionCommand("odswiezListeCzytnikow");
		btnOdwieListPodczonych.addActionListener(this);
		
		list.addListSelectionListener(new SharedListSelectionHandler());
		
		btnWybierzZaznaczony.setActionCommand("wybierzZaznaczonyCzytnik");
		btnWybierzZaznaczony.addActionListener(this);
		
		btnStop.setActionCommand("startStopWatku");
		btnStop.addActionListener(this);
		btnStop.setEnabled(false);
		
		Czytnik=new CzytnikWatek(this);
		
		
		btnPolaczZSerwerem.setActionCommand("polaczenieZSerwerem");
		
		JLabel label = new JLabel("Stan po\u0142\u0105czenia");
		label.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		label.setBounds(10, 149, 93, 14);
		panelUstawienia.add(label);
		btnPolaczZSerwerem.addActionListener(this);
		
		aktualizujListeCzytnikow();
		
		czytanieKartWatek = new Thread(Czytnik);
		textPaneLog.setText("<small style=\"font-family: 'Segoe UI',sans-serif;\"><b>uruchomienie</b><br/>1. Pod\u0142\u0105cz czytnik i od\u015Bwie\u017C list\u0119 czytnik\u00F3w<br/>2. Wybierz z listy czytnik (CL)<br/>3. Wpisz IP serwera, numer portu i sali<br/>4. Naci\u015Bnij przycisk \"po\u0142\u0105cz\", aby uzyska\u0107 poprawne po\u0142\u0105czenie<br/>5. Naci\u015Bnij przycisk \"start\", aby uruchomi\u0107 uslug\u0119</small>");
		btnWyczy.setActionCommand("wyczysc");
		
		JLabel lblKlientNieJest = new JLabel("Dziennik zdarze\u0144");
		lblKlientNieJest.setForeground(new Color(102, 102, 102));
		lblKlientNieJest.setFont(new Font("Segoe UI", Font.PLAIN, 15));
		lblKlientNieJest.setBounds(8, 4, 218, 26);
		panelGlowny.add(lblKlientNieJest);
		
		JLabel lblPracaInynierska = new JLabel("Praca in\u017Cynierska - Konrad Zdanowicz");
		lblPracaInynierska.setHorizontalAlignment(SwingConstants.RIGHT);
		lblPracaInynierska.setFont(new Font("Segoe UI", Font.ITALIC, 11));
		lblPracaInynierska.setBounds(8, 386, 445, 20);
		frmKlientObecnoci.getContentPane().add(lblPracaInynierska);
		
		lblNieaktywny = new JLabel("nieaktywny");
		lblNieaktywny.setForeground(SystemColor.controlDkShadow);
		lblNieaktywny.setFont(new Font("Segoe UI", Font.BOLD, 15));
		lblNieaktywny.setBounds(189, 9, 116, 31);
		lblNieaktywny.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\status-busy.png")));
		frmKlientObecnoci.getContentPane().add(lblNieaktywny);
		
		btnAdministracja = new JButton("Administracja");
		btnAdministracja.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\key.png")));
		btnAdministracja.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		btnAdministracja.setBounds(8, 381, 171, 23);
		btnAdministracja.setActionCommand("administracja");
		btnAdministracja.addActionListener(this);
		
		frmKlientObecnoci.getContentPane().add(btnAdministracja);
		btnWyczy.addActionListener(this);
		
		
		
	}
	
	/*
	 * otworz strone
	 */
	
	public static void openWebpage(URI uri) {
	    Desktop desktop = Desktop.isDesktopSupported() ? Desktop.getDesktop() : null;
	    if (desktop != null && desktop.isSupported(Desktop.Action.BROWSE)) {
	        try {
	            desktop.browse(uri);
	        } catch (Exception e) {
	            e.printStackTrace();
	        }
	    }
	}

	public static void openWebpage(URL url) {
	    try {
	        openWebpage(url.toURI());
	    } catch (URISyntaxException e) {
	        e.printStackTrace();
	    }
	}
	
	/*
	 * aktualizacja listy czytnikow
	 */
	public void aktualizujListeCzytnikow()
	{
		//funkcja do resetowania watku czytania
		boolean jest=Czytnik.initializeReader();
		listaCzytnikow.clear();
		if(jest)
		{
			for(int i=0;i<Czytnik.liczbaczytnikow;i++)
			{
				listaCzytnikow.addElement(Czytnik.terminals.get(i));
			}
			//guiLista=new JList();
		}
		else
		{
			//System.out.println("Nie odnaleziono czytników!");
		}
	}

	
	/* 
	 * zmiana stanu listy czytnikow
	 */
	
	class SharedListSelectionHandler implements ListSelectionListener {
	    public void valueChanged(ListSelectionEvent e) {
	
	    	
	    	   if (e.getValueIsAdjusting() == false) {
	    		   	//System.out.println(list.getSelectedIndex());
	    	        if (list.getSelectedIndex() == -1) {
	    	        //No selection, disable fire button.
	    	        	btnWybierzZaznaczony.setEnabled(false);
	    	        	btnStop.setEnabled(false);

	    	        } else {
	    	        //Selection, enable the fire button.
	    	        	btnWybierzZaznaczony.setEnabled(true);
	    	        	btnStop.setEnabled(true&&validConnection);
	    	        }
	    	    }
	    }
	}
	
	
	/* eventy */
	public void actionPerformed(ActionEvent e) {
		
		
        if ("odswiezListeCzytnikow".equals(e.getActionCommand())) {
            btnOdwieListPodczonych.setEnabled(false);
        	aktualizujListeCzytnikow();
        	btnOdwieListPodczonych.setEnabled(true);
        } 
        else if("administracja".equals(e.getActionCommand()))
        {
        try {
			openWebpage(new URL("http://"+textFieldIP.getText()+"/inzynier/admin/"));
		} catch (MalformedURLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
        
        }
        else if ("polaczenieZSerwerem".equals(e.getActionCommand())) {
        	
        	
            btnPolaczZSerwerem.setText("sprawdzanie po³¹czenia...");
           
        	try {
				Thread.sleep(1);
			} catch (InterruptedException e1) {
			
				//e1.printStackTrace();
			}
        	if("".equals(textFieldIP.getText()) | "".equals(textFieldPort.getText()))
        	{
        		lblUstawieniaZapisanoPomylnie.setText("Wpisz poprawny adres IP i numer portu!");
        	}
        	
        	lblUstawieniaZapisanoPomylnie.setForeground(new Color(150,150,150));
        	validConnection=false;
                try {
					
					
					
					
						  clientSocket = new Socket(textFieldIP.getText(), Integer.parseInt(textFieldPort.getText()));
				            os = new PrintStream(clientSocket.getOutputStream());
				            is = new DataInputStream(clientSocket.getInputStream());
				 
				                 	
				         
				        
				        //wyslanie komunikatu
				        if (clientSocket != null && os != null && is != null) {
				        	String command="uid 0 sala 0";
				        	 os.println(command);

				            String responseLine;
				            try {
								responseLine = is.readLine();
					            is.close();
					            os.close();
					            clientSocket.close();
							} catch (IOException e4) {
							}
				          }

					
					
					
					lblUstawieniaZapisanoPomylnie.setText("Po³¹czenie z hostem "+textFieldIP.getText()+":"+textFieldPort.getText()+" poprawne.");
					lblUstawieniaZapisanoPomylnie.setForeground(new Color(15,177,23));
					validConnection=true;
					validHost=textFieldIP.getText();
					validPort=textFieldPort.getText();
					btnStop.setEnabled(true);
				}
                catch (NumberFormatException e2) {
    				// TODO Auto-generated catch block
    				//e2.printStackTrace();
                	lblUstawieniaZapisanoPomylnie.setText("Niepoprawne dane wejœciowe. SprawdŸ poprawnoœæ wpisanych wartoœci.");
    			} catch (UnknownHostException e1) {
					//e1.printStackTrace();
					lblUstawieniaZapisanoPomylnie.setText("Nie odnaleziono hosta "+textFieldIP.getText()+"!");
				} catch (IOException e1) {
					// TODO Auto-generated catch block
					//e1.printStackTrace();
					lblUstawieniaZapisanoPomylnie.setText("Nie mo¿na po³¹czyæ z hostem "+textFieldIP.getText()+":"+textFieldPort.getText()+"!");
				}
                
                
                btnPolaczZSerwerem.setText("po³¹cz");
                
        } 
        else if ("wyczysc".equals(e.getActionCommand())) {
        	textPaneLog.setText("");
        	
        }         
        else if ("wybierzZaznaczonyCzytnik".equals(e.getActionCommand())) {
        	int sel=(int)list.getSelectedIndex();
        	Czytnik.numerczytnika=sel;
        	lblCzytnikWybrany.setText(Czytnik.terminals.get(sel).toString());
        	Czytnik.initializeReader();
        	
        } 
        else if ("startStopWatku".equals(e.getActionCommand())) {
        	if(!czytanieUruchomione)
        	{
        		Czytnik.aktywneczytanie=true;
        		czytanieKartWatek= new Thread(Czytnik);
        		czytanieKartWatek.start();
        		czytanieUruchomione=true;
        		btnStop.setText("Stop");
        		lblPoczono.setText("Czytanie kart jest obecnie w³¹czone");	
        		trayIcon.setToolTip("Klient obecnoœci [aktywny]");
        		quickStartMenu.setLabel("Czytanie kart aktywne");
        		tabbedPane.setSelectedIndex(0);
        		tabbedPane.setEnabledAt(1, false);
        		tabbedPane.setEnabledAt(2, false);
        		lblNieaktywny.setText("aktywny");
        		lblNieaktywny.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\status.png")));
        	}
        	else
        	{
        		czytanieKartWatek.interrupt();
        		Czytnik.aktywneczytanie=false;
        		czytanieUruchomione=false;
        		btnStop.setText("Start");
        		trayIcon.setToolTip("Klient obecnoœci [nieaktywny]");
        		lblPoczono.setText("Czytanie kart jest obecnie wy³¹czone");
        		quickStartMenu.setLabel("Czytanie kart nieaktywne");
        		tabbedPane.setEnabledAt(1, true);
        		tabbedPane.setEnabledAt(2, true);
        		lblNieaktywny.setText("zatrzymany");
        		lblNieaktywny.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\status-busy.png")));
        	}
        	
        } 
    }
	
	/* 
	 * obsluga logu
	 */
	
	public void pseudoconsoleWrite(String in)
	{
	       Date dNow = new Date( );
	       SimpleDateFormat ft = 
	       new SimpleDateFormat ("yyyy-MM-dd HH:mm:ss");

		//System.out.println(in);
		textPaneLog.setText("<small style='font-family: \"Segoe UI\",sans-serif;'><i>"+ft.format(dNow)+"</i> "+in+"\n</small>"+textPaneLog.getText());
	}
	/* odczytanie UID i akcja */
	
	public boolean getServerResponse(String uid,String sala)
	{
		String response=null;

		sala=textFieldIdSali.getText();
		if("".equals(sala) | Integer.parseInt(sala)<0)
		{
			pseudoconsoleWrite("Wpisz poprawny identyfikator sali!");
			return false;
		}
		
        host=validHost;
        portNumber=Integer.parseInt(validPort);
        //pseudoconsoleWrite("Nawiazywanie polaczenia z "+host+":"+portNumber+"...");
        
        //inicjalizacja polaczenia
        try {
            clientSocket = new Socket(host, portNumber);
            os = new PrintStream(clientSocket.getOutputStream());
            is = new DataInputStream(clientSocket.getInputStream());
           // pseudoconsoleWrite("Nawi¹zano poprawne po³¹zenie.");
          } catch (UnknownHostException e) {
        	 // pseudoconsoleWrite("Nie odnaleziono hosta " + host);
        	  return false;
          } catch (IOException e) {
        	 // pseudoconsoleWrite("B³¹d po³¹czenia I/O z hostem " + host);
        	  return false;
          }
        
        //wyslanie komunikatu
        if (clientSocket != null && os != null && is != null) {
        	String command="uid "+uid+" sala "+sala;
        	
        	if(uid.length()==0)
        	{
        		// pseudoconsoleWrite("Wpisz poprawn¹ wartoœæ UID");
        	
        	}
        	else os.println(command);
        	
        	
            String responseLine;
            try {
				responseLine = is.readLine();
				pseudoconsoleWrite(uid+": "+responseLine);
				
				
				int responseFlag=Integer.parseInt(responseLine.substring(
						responseLine.indexOf('[', 0)+2,
						responseLine.indexOf(']')-1)
	                  );
				
				responseLine=responseLine.substring(0,responseLine.indexOf('['));
				if(responseFlag<0)
				{
					 trayIcon.displayMessage(responseLine,
			                    "zbli¿ono kartê o UID "+uid, TrayIcon.MessageType.ERROR);
					 lblUid.setForeground(new Color(102,0,0));
					 underlblUid.setBackground(new Color(218, 107, 103));
				}
				else if(responseFlag==0)
				{
					trayIcon.displayMessage(responseLine,
							"zbli¿ono kartê o UID "+uid, TrayIcon.MessageType.WARNING);
					underlblUid.setBackground(new Color(204,204,204));
					lblUid.setForeground(new Color(10,10,10));
				}

				else if(responseFlag>0)
				{
					trayIcon.displayMessage(responseLine,
							"zbli¿ono kartê o UID "+uid, TrayIcon.MessageType.INFO);
					lblUid.setForeground(new Color(16,64,2));
					underlblUid.setBackground(new Color(51, 230, 103));
				}

				txtpnOdpowiedZSerwera.setText(responseLine);
	            is.close();
	            os.close();
	            clientSocket.close();
			} catch (IOException e) {
				
				 pseudoconsoleWrite("! B³¹d otrzymywania komunikatu.");
				 return false;
			}
           
            return true;
          }
		return false;
    

		//return response;
	
	}
	
	
	
	
	
	
	
	
	
	
	
	private void initialize() {
		frmKlientObecnoci = new JFrame();
		frmKlientObecnoci.setIconImage(Toolkit.getDefaultToolkit().getImage("images\\app.png"));
		frmKlientObecnoci.setModalExclusionType(ModalExclusionType.APPLICATION_EXCLUDE);
		frmKlientObecnoci.setResizable(false);
		frmKlientObecnoci.getContentPane().setFont(new Font("Segoe UI", Font.PLAIN, 14));
		frmKlientObecnoci.setTitle("Klient obecno\u015Bci");
		frmKlientObecnoci.setBounds(100, 100, 469, 443);
		frmKlientObecnoci.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frmKlientObecnoci.getContentPane().setLayout(null);
		
		JLabel lblNewLabel = new JLabel("Klient obecno\u015Bci");
		lblNewLabel.setForeground(new Color(105, 105, 105));
		lblNewLabel.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\app.png")));
		lblNewLabel.setFont(new Font("Segoe UI", Font.PLAIN, 17));
		lblNewLabel.setBounds(8, 9, 171, 31);
		frmKlientObecnoci.getContentPane().add(lblNewLabel);
		
		tabbedPane = new JTabbedPane(JTabbedPane.TOP);
		tabbedPane.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		tabbedPane.setBounds(8, 113, 448, 262);
		frmKlientObecnoci.getContentPane().add(tabbedPane);
		
		panelGlowny = new JPanel();
		panelGlowny.setBackground(SystemColor.control);
		tabbedPane.addTab("Historia", new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\application-monitor.png")), panelGlowny,null);
		panelGlowny.setLayout(null);
		
		textPaneLog = new JTextPane();
		textPaneLog.setFont(new Font("Segoe UI", Font.PLAIN, 10));
		textPaneLog.setEditable(false);
		textPaneLog.setContentType("text/html");
		JScrollPane jsp = new JScrollPane(textPaneLog);
		jsp.setLocation(2, 39);
		jsp.setSize(439, 191);
		textPaneLog.setBackground(SystemColor.textHighlightText);
		textPaneLog.setBounds(0, 40, 443, 195);
		panelGlowny.add(jsp);
		
		btnWyczy = new JButton("Wyczy\u015B\u0107 dziennik");
		btnWyczy.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		btnWyczy.setIcon(null);
		btnWyczy.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent arg0) {
			}
		});
		btnWyczy.setBounds(312, 6, 121, 26);
		panelGlowny.add(btnWyczy);
		
		panelUstawienia = new JPanel();
		panelUstawienia.setBackground(SystemColor.control);
		tabbedPane.addTab("Ustawienia po³¹czenia", new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\plug.png")), panelUstawienia, null);
		panelUstawienia.setLayout(null);
		
		textFieldIP = new JTextField();
		textFieldIP.setText("192.168.137.1");
		textFieldIP.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		textFieldIP.setBounds(137, 11, 163, 24);
		panelUstawienia.add(textFieldIP);
		textFieldIP.setColumns(10);
		
		JLabel lblAdresIpSerwera = new JLabel("adres IP serwera");
		lblAdresIpSerwera.setLabelFor(textFieldIP);
		lblAdresIpSerwera.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		lblAdresIpSerwera.setBounds(10, 17, 107, 14);
		panelUstawienia.add(lblAdresIpSerwera);
		
		JLabel lblNumerPortu = new JLabel("numer portu");
		lblNumerPortu.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		lblNumerPortu.setBounds(10, 48, 82, 14);
		panelUstawienia.add(lblNumerPortu);
		
		textFieldPort = new JTextField();
		textFieldPort.setText("2323");
		textFieldPort.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		textFieldPort.setColumns(10);
		textFieldPort.setBounds(137, 40, 163, 24);
		panelUstawienia.add(textFieldPort);
		
		JLabel lblIdSali = new JLabel("ID sali");
		lblIdSali.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		lblIdSali.setBounds(10, 76, 82, 14);
		panelUstawienia.add(lblIdSali);
		
		textFieldIdSali = new JTextField();
		textFieldIdSali.setText("1");
		textFieldIdSali.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		textFieldIdSali.setColumns(10);
		textFieldIdSali.setBounds(137, 70, 163, 24);
		panelUstawienia.add(textFieldIdSali);
		
		btnPolaczZSerwerem = new JButton("po\u0142\u0105cz");
		btnPolaczZSerwerem.setFont(new Font("Segoe UI", Font.BOLD, 11));
		btnPolaczZSerwerem.setBounds(10, 104, 190, 24);
		panelUstawienia.add(btnPolaczZSerwerem);
		
		lblUstawieniaZapisanoPomylnie = new JLabel("<brak po\u0142\u0105czenia>");
		lblUstawieniaZapisanoPomylnie.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		lblUstawieniaZapisanoPomylnie.setForeground(SystemColor.textInactiveText);
		lblUstawieniaZapisanoPomylnie.setBounds(104, 139, 312, 32);
		panelUstawienia.add(lblUstawieniaZapisanoPomylnie);
		
		panelCzytnik = new JPanel();
		tabbedPane.addTab("Ustawienia czytnika", new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\drive.png")), panelCzytnik, null);
		panelCzytnik.setLayout(null);
		
		btnOdwieListPodczonych = new JButton("od\u015Bwie\u017C list\u0119");
		btnOdwieListPodczonych.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		
		btnOdwieListPodczonych.setBounds(278, 120, 153, 23);
		panelCzytnik.add(btnOdwieListPodczonych);
		
		btnWybierzZaznaczony = new JButton("Wybierz zaznaczony");
		btnWybierzZaznaczony.setFont(new Font("Segoe UI", Font.BOLD, 12));
		btnWybierzZaznaczony.setEnabled(false);
		btnWybierzZaznaczony.setBounds(10, 120, 240, 23);
		panelCzytnik.add(btnWybierzZaznaczony);
		
		
		//String[] data={"1","2"};
		listaCzytnikow=new DefaultListModel();
		
		listaCzytnikow.addElement("<brak czytników wykrytych>");
		list = new JList(listaCzytnikow);
		list.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		list.setSelectionMode(0);
		JScrollPane scrollPane = new JScrollPane(list);
		scrollPane.setSize(420, 100);
		scrollPane.setLocation(10, 15);
		
		
		list.setBounds(10, 14, 178, 54);
		
		panelCzytnik.add(scrollPane);
		 

		 
		 
		 
		JLabel lblWybranyCzytnik = new JLabel("Wybrany czytnik");
		lblWybranyCzytnik.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		lblWybranyCzytnik.setBounds(10, 167, 93, 14);
		panelCzytnik.add(lblWybranyCzytnik);
		
		lblCzytnikWybrany = new JLabel("<brak informacji>");
		lblCzytnikWybrany.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		lblCzytnikWybrany.setForeground(SystemColor.textInactiveText);
		lblCzytnikWybrany.setBounds(119, 166, 312, 14);
		panelCzytnik.add(lblCzytnikWybrany);
		
		JLabel lblStanPoczenia = new JLabel("Stan po\u0142\u0105czenia");
		lblStanPoczenia.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		lblStanPoczenia.setBounds(10, 192, 93, 14);
		panelCzytnik.add(lblStanPoczenia);
		
		lblPoczono = new JLabel("Czytanie kart jest obecnie zatrzymane");
		lblPoczono.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		lblPoczono.setForeground(SystemColor.textInactiveText);
		lblPoczono.setBounds(119, 192, 312, 14);
		panelCzytnik.add(lblPoczono);
		
		btnStop = new JButton("Start");
		btnStop.setIcon(new ImageIcon(Toolkit.getDefaultToolkit().getImage("images\\control-power.png")));
		btnStop.setForeground(SystemColor.activeCaptionText);
		btnStop.setFont(new Font("Segoe UI", Font.BOLD, 15));
		btnStop.setBounds(340, 10, 116, 29);
		frmKlientObecnoci.getContentPane().add(btnStop);
		
		JPanel panel_3 = new JPanel();
		panel_3.setBorder(UIManager.getBorder("TextField.border"));
		panel_3.setBounds(10, 53, 446, 55);
		frmKlientObecnoci.getContentPane().add(panel_3);
		panel_3.setLayout(null);
		
		underlblUid = new JPanel();
		underlblUid.setBackground(new Color(204, 204, 204));
		underlblUid.setBorder(new LineBorder(new Color(153, 153, 153)));
		underlblUid.setBounds(6, 6, 170, 43);
		panel_3.add(underlblUid);
		underlblUid.setLayout(new BorderLayout(0, 0));
		
		lblUid = new JLabel("Zbli\u017C kart\u0119");
		lblUid.setBackground(new Color(204, 204, 204));
		lblUid.setHorizontalAlignment(SwingConstants.CENTER);
		lblUid.setFont(new Font("Segoe UI Semibold", Font.PLAIN, 13));
		underlblUid.add(lblUid);
		
		txtpnOdpowiedZSerwera = new JTextPane();
		txtpnOdpowiedZSerwera.setForeground(SystemColor.inactiveCaptionText);
		txtpnOdpowiedZSerwera.setEditable(false);
		txtpnOdpowiedZSerwera.setFont(new Font("Segoe UI", Font.PLAIN, 11));
		txtpnOdpowiedZSerwera.setBackground(UIManager.getColor("Panel.background"));
		txtpnOdpowiedZSerwera.setText("<zbli\u017C kart\u0119, aby otrzyma\u0107 odpowied\u017A>");
		txtpnOdpowiedZSerwera.setBounds(179, 2, 257, 47);
		panel_3.add(txtpnOdpowiedZSerwera);
		
		//tray icon
		
		
		   //Check the SystemTray is supported
        if (!SystemTray.isSupported()) {
            System.out.println("SystemTray is not supported");
            return;
        }
        
        
        final PopupMenu popup = new PopupMenu();
        trayIcon = new TrayIcon(Toolkit.getDefaultToolkit().createImage("images/tray.png"));
        final SystemTray tray = SystemTray.getSystemTray();
       
        kartaTray = new MenuItem("<brak karty>");
        kartaTray.setEnabled(false);

        MenuItem quickSettingsMenu = new MenuItem("Ustawienia czytnika...");
        quickStartMenu = new MenuItem("Czytanie kart nieaktywne");
        quickStartMenu.setEnabled(false);
        MenuItem exitItem = new MenuItem("Zamknij");
       
        //Add components to pop-up menu
        popup.add(quickStartMenu);

        popup.add(kartaTray);
        popup.addSeparator();
        
    
        popup.add(quickSettingsMenu);
        popup.addSeparator();
       /* quickSettingsMenu.add(errorItem);
        quickSettingsMenu.add(warningItem);
        quickSettingsMenu.add(infoItem);
        quickSettingsMenu.add(noneItem);*/
        popup.add(exitItem);
        trayIcon.setToolTip("Klient obecnoœci [nieaktywny]");
        
      
        trayIcon.setPopupMenu(popup);
       
        try {
            tray.add(trayIcon);
        } catch (AWTException e) {
            System.out.println("TrayIcon could not be added.");
        }
        trayIcon.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                //JOptionPane.showMessageDialog(null,"Klient jest uruchomiony.");
            	
            	frmKlientObecnoci.setVisible(true);
            }
        });
         
        kartaTray.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                JOptionPane.showMessageDialog(null,
                        "This dialog box is run from the About menu item");
            }
        });
         
        quickSettingsMenu.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
            	frmKlientObecnoci.setVisible(true);
            }
        });
         
      
         

         
        exitItem.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {

            	Object[] options = {"Tak",
                "Nie"};
			int n = JOptionPane.showOptionDialog(frmKlientObecnoci,
			"Czy na pewno chcesz wyjœæ z programu?",
			"Klient obecnoœci",
			JOptionPane.YES_NO_OPTION,
			JOptionPane.QUESTION_MESSAGE,
			null,     //do not use a custom Icon
			options,  //the titles of buttons
			options[1]); //default button title   	
            	if(n==0)
            	{
            		tray.remove(trayIcon);
                    System.exit(0);
            	}
            }
        });
    }
     

		
		

//Obtain the image URL
protected static Image createImage(String path, String description) {
    URL imageURL = MainWindow.class.getResource(path);
     
    if (imageURL == null) {
        System.err.println("Resource not found: " + path);
        return null;
    } else {
        return (new ImageIcon(imageURL, description)).getImage();
    }
}
}
