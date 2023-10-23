// JavaScript Document

function ajaxget(url,action, tid ) 
{
var xmlHttp; 
var isie;

 isie=true;

try {
	// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	    isie=false;
    } 
    catch (e) {
	// Internet Explorer
	try {
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	      } catch (e)  {
		try {
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	      	} 
	            catch (e) {
			alert("Your browser does not support AJAX!");
			return false;
	           		}
		  }
    }




xmlHttp.onreadystatechange=function() {

	  if(xmlHttp.readyState==4) { 
		// This is where the functional code goes
		switch (action) {
		case 0:

			return ( xmlHttp.responseText );
			break;
		case 1:
			return ( xmlHttp.responseText );
			break;
		}



        responsetext =  xmlHttp.responseText ;
	
	if (tid>' ') { document.getElementById(tid).innerHTML = responsetext; }

		// return the text
	  } 
} 

if (action==0 || isie ) {
	// Asynchronous get does not seem to work with Firefox so do it this way!
xmlHttp.abort();
	xmlHttp.open("GET",url,false); 
} else {
xmlHttp.abort();
	xmlHttp.open("GET",url,true); 
}

xmlHttp.send(null);


return ( xmlHttp.responseText  );


} 

