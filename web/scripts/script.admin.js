//next focus
var data_najnowsza_tutaj;
var continue_checking=true;
var timeout_petla=1000;


$.fn.focusNextInputField = function() {
    return this.each(function() {
        var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
        var index = fields.index( this );
        if ( index > -1 && ( index + 1 ) < fields.length ) {
            fields.eq( index + 1 ).focus();
        }
        return false;
    });
};

//cookies
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
		{
			return unescape(y);
		}
	}
}

function available_date(date) {
  dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
  if ($.inArray(dmy, availableDates) != -1) {
    return [true, "","dostępny termin"];
  } else {
    return [false,"highlight","zajęty termin"];
  }
}


 $(function(){
 
 
 

 

 
   	//menu glowne animacja
	$("#main-menu>ul>li").mouseenter(function(){
		$("ul",this).stop(true,true).fadeIn(200);
	});
	$("#main-menu>ul>li").mouseleave(function(){
		$("ul",this).stop(true,true).fadeOut(200);
	});
		
	//zmiana widoku strony
	var cookie_siteview=getCookie("kos_siteview");
	if (cookie_siteview!=null && cookie_siteview!="")
	{
		//alert(cookie_siteview);
	
	}
	else setCookie("kos_siteview",2,365);
	var switcher_views=new Array(
		"layout-narrow",
		"layout-wide",
		"layout-fixed",
		"layout-narrow"
	);
	var switcher_icons=new Array(
		"icon-magnifier_zoom_in",
		"icon-monitor",
		"icon-magnifier_zoom_out",
		"icon-magnifier_zoom_in"
	);
    var switcher_alt=new Array(
	   "Dopasuj do wysokiej rozdzielczości",
	   "Dopasuj do szerokości okna",
	   "Dopasuj do niskiej rozdzielczości",
	   "Dopasuj do wysokiej rozdzielczości"
	);
	$("#viewswitcher").addClass(switcher_icons[cookie_siteview]).attr("title",switcher_alt[cookie_siteview]);
	$("#container").addClass(switcher_views[cookie_siteview]);
	
	$("#viewswitcher").click(function(){
		cookie_siteview=getCookie("kos_siteview");
		$("#container").removeClass(switcher_views[cookie_siteview]).addClass(switcher_views[(cookie_siteview+1)%3]);
		$(this).removeClass(switcher_icons[cookie_siteview]).addClass(switcher_icons[(cookie_siteview+1)%3])
			   .attr("title",switcher_alt[(cookie_siteview+1)%3]);
		setCookie("kos_siteview",(cookie_siteview+1)%3,365);
	});
	
	//mass action checkbox
	$("#mass-action").click(function(){
	if($("input[name='mass-action-item[]']:checked").length<1)
	{
		alert("Zaznacz elementy do usunięcia.");
		return false;
	}
	else
	{
		var ile=$("input[name='mass-action-item[]']:checked").length;
		var answer = confirm("Zaznaczono do usunięcia elementów "+ile+". Operacji usuwania nie można cofnąć. Czy na pewno chcesz kontynuować?");
		if (answer){
			$("#mass-action-form").submit();
		}
		else{
		return false;
		}
	
		$("#mass-action-form").submit();
	}			
	});
	
	//animacja panelu wyszkukiwania
	$("#search-panel-show").click(function(){
		if($("#search-panel").css("display")=="none")
		{
			$("#search-panel").css("opacity",0);
			$("#search-panel").animate({
			    opacity: 1,
			    height: 'toggle'
			}, 400);
		}
		else 
		{
			$("#search-panel").animate({
			    opacity: 0,
			    height: 'toggle'
			}, 400);
		}
		
	});
	
	//DYNAMICZNA OBECNOSC!!!
var sprawdzObecnosc= function($NrIndeksu, $IdTermin,$elem)
 {
				  continue_checking=false;
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1; //January is 0!

					var yyyy = today.getFullYear();
					var hh=today.getHours();
					var MM=today.getMinutes();
					var ss=today.getSeconds();
					
					if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm}
					if(hh<10){hh='0'+hh} if(MM<10){MM='0'+MM} if(ss<10){ss='0'+ss}
					today = yyyy+"-"+mm+"-"+dd+" "+hh+":"+MM+":"+ss;

					data_najnowsza_tutaj=today;
					
					
 					  $elem.fadeOut(200,function(){
						$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_SprawdzObecnosc", NrIndeksu:$NrIndeksu,IdTermin:$IdTermin }
}).done(function( msg ) {
					  var result=msg;
						continue_checking=true;
					  if(result==0)
					  {				
						$elem.html('<span class="ikonka icon-cross" title="nieobecny">-</span>');
					  }
					  else if(result==1)
					  {				
						$elem.html('<span class="ikonka icon-tick" title="obecny">+</span>');
					  }
					  else if(result==2)
					  {				
						$elem.html('<span class="ikonka icon-hourglass" title="spóźnienie">!</span>');
					  }
					  else if(result==3)
					  {				
						$elem.html('<span class="ikonka icon-help" title="brak informacji">?</span>');
					  }
					  $elem.fadeIn(200);  
					  });
				});
 }
		$(".dynamic-obecnosc").click(function(){
		

		var elem=$(this);
		var akcja=-1;
		var obecnosc_data=$(this).metadata();
		
				$( "#dialog-obecnosc" ).dialog({
			resizable: false,
			height:170,
			width: 380,
			modal: true,
			buttons: {
				"Obecność": function() {
				$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_DodajObecnosc", NrIndeksu:obecnosc_data.NrIndeksu,IdTermin:obecnosc_data.IdTermin }
}).done(function( msg ) {
					  sprawdzObecnosc(obecnosc_data.NrIndeksu,obecnosc_data.IdTermin,elem);
				});
					$( this ).dialog( "close" );
				},
				"Spóźnienie": function() {
				$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_DodajSpoznienie", NrIndeksu:obecnosc_data.NrIndeksu,IdTermin:obecnosc_data.IdTermin }
}).done(function( msg ) {
					  sprawdzObecnosc(obecnosc_data.NrIndeksu,obecnosc_data.IdTermin,elem);
				});
					$( this ).dialog( "close" );
				},
				"Nieobecność": function() {
				$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_UsunObecnosc", NrIndeksu:obecnosc_data.NrIndeksu,IdTermin:obecnosc_data.IdTermin }
}).done(function( msg ) {
					  sprawdzObecnosc(obecnosc_data.NrIndeksu,obecnosc_data.IdTermin,elem);

				});
					$( this ).dialog( "close" );
				}
			},
			open: function() {
			 $('.ui-dialog-buttonpane').find('button:contains("Obecność")').addClass('button-dialog-obecnosc').removeClass('ui-button-text-only')
    .addClass('ui-button-text-icon')
    .prepend('<span class="button-icon icon-tick" style="float: left;"></span>');
			 $('.ui-dialog-buttonpane').find('button:contains("Spóźnienie")').addClass('button-dialog-spoznienie').removeClass('ui-button-text-only')
    .addClass('ui-button-text-icon')
    .prepend('<span class="button-icon icon-hourglass" style="float: left;"></span>');
			 $('.ui-dialog-buttonpane').find('button:contains("Nieobecność")').addClass('button-dialog-nieobecnosc').removeClass('ui-button-text-only')
    .addClass('ui-button-text-icon')
    .prepend('<span class="button-icon icon-cross" style="float: left;"></span>');
			
			}
		});
		
		

		
		
		
		
		return false;
	
	});
	
	
		
		
	var datepicker_extender;
	//date picker
	//if($( ".input-datepicker" ).metadata().strictdates=="true")
	//{
		datepicker_extender = { beforeShowDay: available_date };
	//}
	
	
	$( ".input-datepicker" ).datepicker(
		$.extend(
		{
			"option": $.datepicker.regional[ "pl" ],
			dateFormat:"yy-mm-dd",
			showOtherMonths: true,
			selectOtherMonths: true,
			showWeek: true,
			showOn: "both",
			showAnim: "slideDown",
			buttonImage: "files/ikony/calendar_edit.png",
			buttonImageOnly: true
		}
		,datepicker_extender)
	);
	
	
	
	
	
		//autosuggest
$("#ProwadzacyAutoSuggest").autocomplete({
			source: "admin/prowadzacySearch.php",
			minLength: 1,
			select: function( event, ui ) {
			$("#ProwadzacyValue").val(ui.item.id);
			$("#ProwadzacyValueDisabled").val(ui.item.id);
			return true;
			}
		});
 
 $("#StudenciAutoSuggest").autocomplete({
			source: "admin/studenciSearch.php",
			minLength: 1,
			select: function( event, ui ) {
			$("#StudenciValue").val(ui.item.id);
			$("#StudenciValueDisabled").val(ui.item.id);
			return true;
			}
		});

		$("#StudenciValueDisabled").focus(function(){
			$(this).focusNextInputField();
		});
	

	//legend toggle fieldset
	$("fieldset legend").click(function(){
	    
		var item=$(this).parent();
		if(item.hasClass("expanded"))
		{
		    item.css("overflow", "hidden")
				.animate({"height": "10"},200)
				.removeClass("expanded")
				.addClass("hidden")
			    .children("table").fadeOut(200);
			$(this).append(' <span class="mini">(kliknij, aby rozwinąć)</span>');
		}
		else if(item.hasClass("hidden"))
		{
			item.css("height","auto")
			    .css("overflow", "auto")
			    .children("table").show();
			var wys=item.height();
			item.css("height","10px")
				.animate({"height": wys},200)
				.removeClass("hidden")
				.addClass("expanded");
				
			$(this).children("span").remove();
		}
	});
	//autohide
	$("fieldset.autohide>legend").click();


	//ujednolicenie inputów
	var oldtitle=document.title;
	
	//selektor
	$(".selektor").change(function(){
		document.location.href=$(this).attr("rel")+$(this).val();
	});

	
    //dialog ze strony glownej
	
// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		//dialog - szybka edycja studentow
		$( "#dialog-ims-szybka-studenci" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do edycji": function() {
				var hak="#dialog-ims-szybka-studenci";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_StudentIstnieje", NrIndeksu:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
					    $(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
						var tmp=new String("?p=studenci_edycja&id="+id_value);
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Student o zadanym numerze albumu nie istnieje. Nie można otworzyć okna szybkiej edycji!");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szybka-studenci" ).click(function() {
				$( "#dialog-ims-szybka-studenci" ).dialog( "open" );
			});
		
		//dialog - szukaj studentow
				
		$( "#dialog-ims-szukaj-studenci" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Szukaj": function() {
				
				var hak="#dialog-ims-szukaj-studenci";
				var id_value=$(hak+"-id").val();
				
					  if(id_value!="")
					  {				
					  
					    $(hak+" p.komunikat").text("Proszę czekać...");
						var tmp=new String("?p=studenci&searchword="+id_value+"&areas%5B%5D=NrIndeksu&areas%5B%5D=Nazwisko&areas%5B%5D=Uid");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Wpisz wyszukiwaną frazę.");
						$(hak+"-id").css("border-color","red");
					  }
					  
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szukaj-studenci" ).click(function() {
				$( "#dialog-ims-szukaj-studenci" ).dialog( "open" );
			});
			
		//dialog - szybka edycja prowadzacych
		$( "#dialog-ims-szybka-prowadzacy").dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do edycji": function() {
				var hak="#dialog-ims-szybka-prowadzacy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ProwadzacyIstnieje", IdProwadzacy:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=prowadzacy_edycja&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Prowadzący o zadanym numerze indeksu nie istnieje. Nie można otworzyć okna szybkiej edycji.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szybka-prowadzacy" ).click(function() {
				$( "#dialog-ims-szybka-prowadzacy" ).dialog( "open" );
			});
	
		//dialog - szukaj prowadzacych
		
			$( "#dialog-ims-szukaj-prowadzacy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Szukaj": function() {
				var hak="#dialog-ims-szukaj-prowadzacy";
				var id_value=$(hak+"-id").val();
				
					  if(id_value!="")
					  {				
					    $(hak+" p.komunikat").text("Proszę czekać...");
						var tmp=new String("?p=prowadzacy&searchword="+id_value+"&areas%5B%5D=IdProwadzacy&areas%5B%5D=Nazwisko&areas%5B%5D=Uid");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Wpisz wyszukiwaną frazę.");
						$(hak+"-id").css("border-color","red");
					  }
					  
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szukaj-prowadzacy" ).click(function() {
				$( "#dialog-ims-szukaj-prowadzacy" ).dialog( "open" );
			});
			
		//dialog - szybka edycja kursów
		
			$( "#dialog-ims-szybka-kursy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do edycji": function() {
				var hak="#dialog-ims-szybka-kursy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_KursIstnieje", KodKursu:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=kursy_edycja&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Kurs o podanym kodzie nie istnieje. Nie można otworzyć okna szybkiej edycji.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szybka-kursy" ).click(function() {
				$( "#dialog-ims-szybka-kursy" ).dialog( "open" );
			});
			
		//dialog - dodaj grupę do kursu
		
			$( "#dialog-ims-dodaj-kursy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Dodaj grupę": function() {
				var hak="#dialog-ims-dodaj-kursy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_KursIstnieje", KodKursu:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=zajecia_edycja&KodKursu="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Kurs o podanym kodzie nie istnieje. Nie można utworzyć grupy.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-dodaj-kursy" ).click(function() {
				$( "#dialog-ims-dodaj-kursy" ).dialog( "open" );
			});
			
			
			
			
			$('.okno-dialogowe-glowne form').submit( function(e) {
				e.preventDefault();
				alert("Aby wysłać formularz, użyj domyślnego przycisku w formularzu.");
			});
			
					//dialog - szybka edycja grup
		
			$( "#dialog-ims-szybka-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do edycji": function() {
				var hak="#dialog-ims-szybka-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ZajeciaIstnieja", IdZajecia:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=zajecia_edycja&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Zajęcia o podanym ID nie istnieją. Nie można otworzyć okna szybkiej edycji.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szybka-grupy" ).click(function() {
				$( "#dialog-ims-szybka-grupy" ).dialog( "open" );
			});
			
		//dialog - zapisy do grup
		
			$( "#dialog-ims-zapisy-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do zapisów": function() {
				var hak="#dialog-ims-zapisy-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ZajeciaIstnieja", IdZajecia:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=zapisy_dodaj&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Zajęcia o podanym ID nie istnieją. Nie można otworzyć okna zapisów.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-zapisy-grupy" ).click(function() {
				$( "#dialog-ims-zapisy-grupy" ).dialog( "open" );
			});
			
		
//dialog - lista terminów grupy
		
			$( "#dialog-ims-terminy-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do listy terminów": function() {
				var hak="#dialog-ims-terminy-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ZajeciaIstnieja", IdZajecia:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=terminy&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Zajęcia o podanym ID nie istnieją. Nie można otworzyć okna terminów.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-terminy-grupy" ).click(function() {
				$( "#dialog-ims-terminy-grupy" ).dialog( "open" );
			});		
			

//dialog - tabela obecnosci
		
			$( "#dialog-ims-obecnosci-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do dziennika obecności": function() {
				var hak="#dialog-ims-obecnosci-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ZajeciaIstnieja", IdZajecia:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=terminy_obecnosci&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Zajęcia o podanym ID nie istnieją. Nie można otworzyć tabeli obecności.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-obecnosci-grupy" ).click(function() {
				$( "#dialog-ims-obecnosci-grupy" ).dialog( "open" );
			});	

			//dialog - dodaj termin do grupy
		
			$( "#dialog-ims-dodaj-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Dodaj termin": function() {
				var hak="#dialog-ims-dodaj-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_ZajeciaIstnieja", IdZajecia:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=terminy_edycja&IdZajecia="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Zajęcia o podanym ID nie istnieją. Nie można utworzyć zajęć.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-dodaj-grupy" ).click(function() {
				$( "#dialog-ims-dodaj-grupy" ).dialog( "open" );
			});	

			
						//dialog - przegladaj grupy
		
			$( "#dialog-ims-przegladaj-grupy" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przeglądaj grupy kursu": function() {
				var hak="#dialog-ims-przegladaj-grupy";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_KursIstnieje", KodKursu:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=zajecia&KodKursu="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Nie istnieje kurs o podanym kodzie. Nie można otworzyć widoku grup.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-przegladaj-grupy" ).click(function() {
				$( "#dialog-ims-przegladaj-grupy" ).dialog( "open" );
			});	


	//dialog - szybka sale
		
			$( "#dialog-ims-szybka-sale" ).dialog({
			resizable: false,
			height:300,
			width: 300,
			modal: true,
			autoOpen: false,
			buttons: {
				"Przejdź do edycji sali": function() {
				var hak="#dialog-ims-szybka-sale";
				var id_value=$(hak+"-id").val();
				
					$.ajax({
					  type: "GET",
					  url: "admin/admin.api.php",
					  data: { action: "Action_SalaIstnieje", IdSali:id_value}
}).done(function( msg ) {
					  var result=msg;
					  if(result=="true")
					  {				
						var tmp=new String("?p=sale_edycja&id="+id_value);
						$(hak+" p.komunikat").text("Znaleziono. Proszę czekać...");
				        window.location.href = tmp;
						$( this ).dialog( "close" );
					  }
					  else
					  {				
						$(hak+" p.komunikat").text("Nie istnieje sala po podanym ID. Nie można otworzyć okna edycji.");
						$(hak+"-id").css("border-color","red");
					  }
					  });
				},
				"Anuluj": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#ims-szybka-sale" ).click(function() {
				$( "#dialog-ims-szybka-sale" ).dialog( "open" );
			});	
	
			$('.window_alert a').click(function(){
			continue_checking=false;
			$('.window_alert').fadeOut();
			return false;
		});	
		
		$('.window_alert button').click(function(){
		location.reload(false);
		});
			
});