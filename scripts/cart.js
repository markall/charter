<!--//--><![CDATA[//><!--

grossprice=false;
deliverytype = 'inc';
deliveryamount = 45;
deliverytax = 17.5;
customerexempt='no';

   if (readCookie('customerexempt')>' ') {
          customerexempt=unescape(readCookie('customerexempt'));
   }


cart = new cart ();




//=========================================================================================
// OBJECTS
//=========================================================================================


function cart() {

        this.items = new Array();
        this.nettotal=0;
        this.vat=0;
        this.delivery=0;
        this.grosstotal=0;

}


function totalcart() {
        cart.nettotal=0;
        cart.vat=0;
        cart.delivery=0;
        cart.grosstotal=0;

        for (idx in cart.items)
        {
                thisitem = cart.items[idx];
                cart.vat = cart.vat + thisitem.taxamount;
//              cart.delivery = cart.delivery + thisitem.idel;
        if (grossprice) {
                    netp = thisitem.amount-thisitem.taxamount;
                        cart.nettotal = cart.nettotal + netp ;
                } else {
                        cart.nettotal = cart.nettotal + (thisitem.amount);                              
                }

        }

    if (deliverytype=='total') {
                cart.delivery = deliveryamount;
                cart.vat=cart.vat+tax(deliveryamount,deliverytax,false);
        } else {
                cart.delivery=0;
        }

        cart.grosstotal = (cart.nettotal + cart.vat + cart.delivery);   
        writecarttocookie();
        
}

function item(pid, code, description,price,qty,itax,idel,vatexempt) {

        if (description>'') {
                description = cleanstr(description);
        }
        this.pid = pid;
        this.code = code;
        this.description=description;
        this.ioptions = new Array();
        this.price=price;
        this.qty = qty;
        this.amount = eval(price*qty);
        this.itax=itax;
        this.vatexempt=vatexempt;


	if ( (customerexempt=='yes') && (this.vatexempt==1) ) {
        	this.itax=0;

	}



        this.idel=idel;
        this.taxamount = tax(this.amount,itax,grossprice);

}


function additem(cart, pid , code, description,price,qty, itax,idel,vatexempt ) {

        sitem =  new item(pid ,code, description,price,qty,itax,idel,vatexempt );
        cart.items.push( sitem );

        
//      return(cart.items[idx])

}

function removeitem(idx) {
        cart.items.splice(idx,1);
//              parent.cartwin.load('inline', showcart() , 'Shopping cart' );
        
}

function removeitembycode(icode) {

  for ( idx=cart.items.length-1;idx>=0;idx--) {
                if (cart.items[idx].code==icode) {
                        cart.items.splice(idx,1);
                }
        }
//      parent.cartwin.load('inline', showcart() , 'Shopping cart' );
}

function removeitembypid(ipid) {

  for ( idx=cart.items.length-1;idx>=0;idx--) {
                if (cart.items[idx].pid==ipid) {
                        cart.items.splice(idx,1);
                }
        }
//      parent.cartwin.load('inline', showcart() , 'Shopping cart' );
}

function clearcart() {
        for ( idx=cart.items.length-1;idx>=0;idx--) {
                        cart.items.splice(idx,1);
        }
}


function option(code,description,price,qty,itax,idel) {
        this.code = code;
        this.description  = description;
        this.price = price;
        this.itax=itax;
        this.idel=idel;
        this.taxamount = tax(price,itax,grossprice);
}


function addoption(item,code,description,price,itax,idel) {
        soption =  new option(code,description,price,qty,itax,idel);
        item.ioptions.push(soption);
//      return (item.options[idx]);

}



//======================================================================================
//  CALCULATIONS
//======================================================================================


function tax(amount,itax,grossprice ) {

        if (grossprice==true) {
                // tax included
                taxamount =  eval(amount) - ( eval(amount) / ( 1+(itax/100) ) ) ;   

        } else {
                // need to add tax
            taxamount = ((eval(amount)/100)*itax );   
        }

        taxamount = (Math.round( ((taxamount*100)) ))/100;

        return(taxamount);
}


function delivery() {



}



function discount() {



}

//======================================================================================
// CART STORAGE
//======================================================================================


function writecarttocookie() {
  var cookiecart='';

  for (idx in cart.items)
  {
    prdpid = cart.items[idx].pid;
    prdcde = cart.items[idx].code;
    prddsc = escape( cart.items[idx].description);       // product description
    prdpri = cart.items[idx].price;       // product price
    prdqty = cart.items[idx].qty;       // product quantity
    prditax = cart.items[idx].itax;
    prdidel = cart.items[idx].idel;
    prdvatexempt = cart.items[idx].vatexempt;

    if (prdcde>' ') {
       cookiecart=cookiecart+prdpid+'~'+prdcde+'~'+prddsc+'~'+prdpri+'~'+prdqty+'~'+prditax+'~'+prdidel+'~'+prdvatexempt+'~|';
    }

  }


  writeCookie('cart', escape(cookiecart),24);


}


function getcartfromcookie() {

  cart.items.length=0;

   if (readCookie('cart')>' ') {
          cookiecart=unescape(readCookie('cart'));
   } else {
          cookiecart='';   
   }

  if (cookiecart.indexOf('~|')>0) {

          cookieitems=cookiecart.split('~|');
          for ( idx in cookieitems ) {
          idetail= cookieitems[idx].split('~');
                  ipid = idetail[0];
              icode = idetail[1];               
              idescription = unescape(idetail[2]);
              iprice = idetail[3];
              iqty = idetail[4];
              itax=idetail[5];
              idel=idetail[6];
              vatexempt=idetail[7];
              if (icode>' ') {
                    additem(cart, ipid, icode,idescription,iprice, iqty, itax,idel,vatexempt );
              }
          }

        }


//  totcalculate();
  
}

function readCookie(name)
{
  var cookieValue = "";
  var search = name + "=";

  if(document.cookie.length > 0)
  { 
    offset = document.cookie.indexOf(search);
    if (offset != -1)
    { 
      offset += search.length;
      end = document.cookie.indexOf(";", offset);
      if (end == -1) end = document.cookie.length;
      cookieValue = unescape(document.cookie.substring(offset, end))
    }
  }

  return cookieValue;
}

// Example:
// writeCookie("myCookie", "my name", 24);
// Stores the string "my name" in the cookie "myCookie" which expires after 24 hours.

function writeCookie(name, value, hours)
{
  var expires = new Date();
  var expire = "";
  var path = "/";
//  var domain="<#WEBADDRESS>";

  if(hours != null)
  {
    expires.setTime( expires.getTime() + (hours * 60 * 60)  ); // set expires
//    expires = new Date((new Date()).getTime() + (hours * 36000));
     expire = "; expires=" + expires.toGMTString();
  }
  document.cookie = name + "=" +value+expire+";path="+path;
//alert(document.cookie);
}

function DelCookie(sName) { 
  document.cookie = sName + "=" + escape(sValue) + "; expires=Fri, 31 Dec 1999 23:59:59 GMT;"; 
} 



//======================================================================================
// CART CONTROL
//======================================================================================
function formatnumber(fstr,ino) {
ino=ino+'';
fstr=fstr+'';
var ndecidx= ino.lastIndexOf('.');
if (ndecidx<0 ) {
   ino=ino+'.';
   ndecidx=ino.lastIndexOf('.');
}
var sdecidx= fstr.lastIndexOf('.');
if (sdecidx<0 ) {
   sdecidx=fstr.length+1;
}

inoafterdec= ino.length-ndecidx;
fstrafterdec = fstr.length-sdecidx;
if (inoafterdec>fstrafterdec) {
  rf = inoafterdec-fstrafterdec;
  ino = ino*(100*rf);
  ino= Math.round(ino);
  ino= ino / (100*rf);
}

padv=sdecidx-ndecidx;

if (padv>0) {
   pads='';
   for (idx=0;idx<padv;idx++) {
     if (fstr.substr(idx,1)=='#') { pads=pads+' '};
         if (fstr.substr(idx,1)=='0') { pads=pads+'0'};
   }
   ino=pads+ino;
   lfstr=fstr.length;
   lino=ino.length;
   if (lfstr>=lino) {
      pads='';
      for (idx=lino; idx<=lfstr;idx++) {
         if (fstr.substr(idx,1)=='#') { pads=pads+' '};
             if (fstr.substr(idx,1)=='0') { pads=pads+'0'};  
          }
          ino=ino+pads;
   } else {
   
   }
} else {
   
}

return(ino);
}
function stdf(ino) {
    ono= formatnumber('######.00',ino);
    return ( ono )
}

function refresh(ctype,template ) {
        if (ctype=='win') {
                cartwin.load('inline', showcart() , 'Shopping cart' );
        } else {
                totalcart();
                document.location = 'content.php?template=templates/shop/cart.html';
        }
}

function showcart(ctype,template) {
        cartashtml = "";
    cartashtml = cartashtml+"<table id='scart'>";
    cartashtml = cartashtml +"<tr><th>No.</th><th>Code</th><th>Description</th><th>Qty</th><th>Price</th><th>Amount</th></tr>";
        itemcolour1 = '#E4FEB4';
        itemcolour2 = '#FED3FB';
        itemcolour = itemcolour2;
        oldpid = '';
        for (x in cart.items)
        {
            idx = eval(x)+1;
        if (cart.items[x].pid!=oldpid) {
                        if (itemcolour==itemcolour1) {
                                itemcolour=itemcolour2;
                        } else {
                                itemcolour=itemcolour1;
                        }
                        oldpid=cart.items[x].pid;
                }

                cartashtml = cartashtml +"<tr style='background-color:"+itemcolour+";' >";
                cartashtml = cartashtml +"<td style='text-align:left;' >"+idx+"</td>";
                cartashtml = cartashtml +"<td style='text-align:left;' >"+cart.items[x].code +"</td>";
                cartashtml = cartashtml +"<td style='text-align:left;'>"+cart.items[x].description + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+cart.items[x].qty + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+stdf(cart.items[x].price) + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+stdf(cart.items[x].amount) + "</td>";
//              cartashtml = cartashtml +"<td style='text-align:right;'><a href='javascript:removeitem("+x+");refresh();' >rem</a>" + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'><a href='javascript:removeitembypid(\""+cart.items[x].pid+"\");";
                cartashtml = cartashtml +"refresh(\""+ctype+"\",\""+template+"\");' style='background-color:red;color:white;' title='Remove' > remove </a>" + "</td>";
                cartashtml = cartashtml +"</tr>";               
        }

        totalcart();
        
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td><td></td>";
        cartashtml = cartashtml +"<th>Net total: </th><td style='text-align:right;' >"+stdf(cart.nettotal)+"</td></tr>";

        if (delivery=='total') {
                cartashtml = cartashtml +"<tr><td></td><td></td><td></td><td></td>";
                cartashtml = cartashtml +"<th>Delivery: </th><td style='text-align:right;'>"+stdf(cart.delivery)+"</td></tr>";  
        }
        
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td><td></td>";
        cartashtml = cartashtml +"<th>VAT: </th><td style='text-align:right;' >"+stdf(cart.vat)+"</td></tr>";
        
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td><td></td>";
        cartashtml = cartashtml +"<th>Total: </th><td style='text-align:right;'>"+stdf(cart.grosstotal)+"</td></tr>";

//                              "Delivery: "+cart.delivery+" <br/>"+
                                                                
        cartashtml = cartashtml +"</table>";

        
        if (ctype=='win') {
                cartashtml = cartashtml +"<br/><input type=\"button\" value=\"Checkout\" onclick=\"checkout();\" /><br/>";
                cartashtml = cartashtml +"<br/><input type=\"button\" value=\"Continue Shopping\" onclick=\"cartwin.hide();\" /><br/>";         
        } else {
                cartashtml = cartashtml +"<p style='text-align:center;'><input type=\"button\" value=\"Continue Shopping\" onclick=\"javascript:history.go(-1)\" />&nbsp;";                     
                cartashtml = cartashtml +"<input type=\"button\" value=\"Checkout\" onclick=\"checkout();\" /></p>";   

        }
//      document.getElementById("cartdisplay").innerHTML = cartashtml;
        
        return cartashtml;
}

function onpageload() {

        getcartfromcookie();


//      additem(cart, '0001', 'My widget' ,100,1, 17.5,0);
        
//      for (x in cart.items)
//      {
//              document.write(cart.items[x].code +'  '+ cart.items[x].description + "  <br />");
//      }
        
        
//      writecarttocookie();
        
}

function checkout() {

        cartdesc="";
    cartashtml = "<table id='scart'>";
    cartashtml = cartashtml +"<tr><th>Code</th><th>Description</th><th>Qty</th><th>Price</th><th>Amount</th></tr>";
                
        for (x in cart.items)
        {
            idx = eval(x)+1;
                cartdesc = cartdesc +"<item code=\""+cart.items[x].code +"\" description=\""+cart.items[x].description + "\" qty=\""+cart.items[x].qty + "\" ";
                cartdesc = cartdesc +"price=\""+stdf(cart.items[x].price) + "\" ";
                cartdesc = cartdesc +"amount=\""+stdf(cart.items[x].amount) + "\" ";
                cartdesc = cartdesc +"</item>";
                
                cartashtml = cartashtml +"<tr>";
                cartashtml = cartashtml +"<td style='text-align:left;' >"+cart.items[x].code +"</td>";
                cartashtml = cartashtml +"<td style='text-align:left;'>"+cart.items[x].description + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+cart.items[x].qty + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+stdf(cart.items[x].price) + "</td>";
                cartashtml = cartashtml +"<td style='text-align:right;'>"+stdf(cart.items[x].amount) + "</td>";
                cartashtml = cartashtml +"</tr>";               
        }
        
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td>";
        cartashtml = cartashtml +"<th>Net total: </th><td style='text-align:right;'>"+stdf(cart.nettotal)+"</td></tr>";
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td>";

        if (delivery=='total') {        
                cartashtml = cartashtml +"<th>Delivery: </th><td style='text-align:right;'>"+stdf(cart.delivery)+"</td></tr>";
                cartashtml = cartashtml +"<tr><td></td><td></td><td></td>";
        }
        cartashtml = cartashtml +"<th>VAT: </th><td style='text-align:right;'>"+stdf(cart.vat)+"</td></tr>";
        cartashtml = cartashtml +"<tr><td></td><td></td><td></td>";
        cartashtml = cartashtml +"<th>Total: </th><td style='text-align:right;'>"+stdf(cart.grosstotal)+"</td></tr>";           

        cartashtml = cartashtml +"</table>";
        cartashtml = cartashtml;

    document.getElementById("idescription").value=cartashtml;
    document.getElementById("descriptionxml").value=cartdesc;   

    document.getElementById("cost").value=stdf(cart.grosstotal);
        document.getElementById("grossamount").value=stdf(cart.grosstotal);

    document.getElementById("netamount").value=stdf(cart.nettotal);     
    document.getElementById("vatamount").value=stdf(cart.vat);  
    document.getElementById("deliveryamount").value=stdf(cart.delivery);
        
    document.getElementById("order").submit();
        
}

function cleanstr(istr) {
    re = new RegExp(" & " , "g" );
    istr = istr.replace(re,' &amp; ');
    re = new RegExp("'" , "g" );
        istr = istr.replace(re,"\'");
        
        return istr
}


function cartintemplate(template) {
        tstr = ajaxget(template,0);
        tstr = tstr.replace( '<%cart%>' , showcart('scr',template ) );
        return tstr;
        
}


//--><!]]>
