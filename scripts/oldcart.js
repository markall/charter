<!-- Created By Mark Alliston  -->
<!--
// *********************************************************************************************
// This code is the copyright  of Mark Alliston 1999
// Do not copy or modify  any of this code unless you have purchased 
// the software.
// *********************************************************************************************

function handleError() {
        alert("YOU HAVE ENCOUNTERED A JAVASCRIPT ERROR AND THIS PAGE WILL NOT WORK");
        return true;
}

window.onerror = handleError;

var customername = ' ';
var customercompany=' ';
var customeraddr1 = ' ';
var customeraddr2 = ' ';
var customertown = ' ';
var customercounty = ' ';
var customercountry = ' ';
var customerpostcode = ' ';
var customertelephone = ' ';
var customerfax = ' ';
var customeremail = ' ';
var customerurl= ' ';
var customerdeldest =' ';
var customerdelcid=' ';
var customercard=' ';
var customerref=' ';
var customernotes=' ';
var salamt=0;
var taxamt=0;
var dela=0;
var data=0;



function initcustomer() {

CartInitialise()
self.document.order.cname.value=customername;
self.document.order.email.value=customeremail;
self.document.order.telephone.value= customertelephone;
self.document.order.addr1.value=  customeraddr1;
self.document.order.addr2.value=  customeraddr2;
self.document.order.town.value=  customertown;
self.document.order.county.value=  customercounty;
self.document.order.country.value=  customercountry;
self.document.order.postcode.value=  customerpostcode;
self.document.order.notes.value= customernotes;
//self.document.order.currency.value= customercurrency;
//  customercurrencyrate= self.document.order.currency.options[self.document.order.currency.selectedIndex].value;

var idx=0;


}

function setdetailline() {                              
     orddet = ' ';
     for ( var i = 1; i <= presentdetailline-1 ; i++) {
        if (parent.orderlines[i].prdcde > ' ') {

tmpstr=self.orderlines[i].prddsc;
if (self.orderlines[i].prddsc.indexOf('|')>0) {
  tmpstr=self.orderlines[i].prddsc.substring(0,self.orderlines[i].prddsc.indexOf('|'))
}
        orddet = orddet+'<ITEM code="'+orderlines[i].prdcde+'" description="'+tmpstr +
                        '" quantity="'+orderlines[i].prdqty+'" cost="'+orderlines[i].prdtot+
                        '" delivery="'+(orderlines[i].prdidl*orderlines[i].prdqty )+'" ></ITEM>';


      orddet = orddet.replace(/<BR>/,"  ") ;
      orddet = orddet.replace(/<P>/,"  ") ;
//      orddet = orddet.replace(/£/,"  ") ;


//      orddet = orddet+'Item '+eval(i)+': '+orderlines[i].prdcde+'  ' +
//                                                                orderlines[i].prddsc +'   '+
//                              'Qty '+orderlines[i].prdqty+' '+
//                                                               'Cost '+orderlines[i].prdtot+' '+ 
//                              'Delivery '+(orderlines[i].prdidl*orderlines[i].prdqty )+'\n';
            }      // if
     } 

   self.document.order.orderinfo.value=orddet;
   self.document.order.amount.value=self.document.order.tot.value;
   if (self.document.order.amount.value>1) {
       document.getElementById("order").submit() ;
 } else {
      alert('Your shopping cart is empty, please continue shopping ');
  }
}


function cleardetail() {
     salamt=0;
     dela=0;
     taxamt=0;

   self.document.order.sales.value=0;
   self.document.order.tax.value=0;
   self.document.order.del.value=0;
   self.document.order.tot.value=0;

    for ( var i = 1; i <=  presentdetailline-1 ; i++) {
         orderlines[i].prdcde = ' ';
     }  // clear  all items
     writecarttocookie()

}

function chemail(){
     customeremail=self.document.order.email.value;
}
function chsales(){
  self.document.order.sales.value= salamt;
}
function chtax(){
  self.document.order.tax.value= taxamt;
}
function chdel(){
  self.document.order.del.value= dela;
}
function chtot() {
   totcalculate();
}

function chdeldest(){

customerdeldest=self.document.order.deldest.options[self.document.order.deldest.selectedIndex].value;

parent.totcalculate();

}

var dsppr = ""
var count = 0
var proc = self


//***************************************************************************************


function CartInitialise() {
forceRewrite();
var customernotes='';
orderlines = new linearray ( 5, '');
self.rate = 1;
orddet = '';
novat = false;
GrossPrice =true;
delamt=<#DELIVERYAMOUNT>;
disamt=0;
self.customerdeldest='<#DEFAULTDESTINATION>';
<#DELIVERYDEFINEOPTIONS>
<#DISCOUNTDEFINEOPTIONS>

getcartfromcookie();
// getcustfromcookie();

}


function getcustfromcookie() {

customername = readCookie('customername');
customercompany=readCookie('customercompany');
customeraddr1 = readCookie('customeraddr1');
customeraddr2 = readCookie('customeraddr2');
customertown = readCookie('customertown');
customercounty = readCookie('customercounty');
customercountry = readCookie('customercountry');
customerpostcode = readCookie('customerpostcode');
customertelephone = readCookie('customertelephone');
customerfax = readCookie('customerfax');
customeremail = readCookie('customeremail');
customerurl= readCookie('customerurl');
customerdeldest =readCookie('customerdeldest');
customerdelcid=readCookie('customerdelcid');
customercard=readCookie('customercard');
customerref=readCookie('customerref');
salamt=readCookie('salamt');
taxamt=readCookie('taxamt');
dela=readCookie('dela');
data=readCookie('data');


}
  
function getcartfromcookie() {

  orderlines.length=0;

   if (readCookie('cart')>' ') {
          var cart=readCookie('cart');
   } else {
          var cart='';   
   }

  presentdetailline=1;
  items=cart.split('~|');

  for ( idx=0;idx<items.length-1;idx++) {
    idetail= items[idx].split('~');

    val = idetail[0];
    dsc = idetail[1];
    pri = idetail[2];
    qty = idetail[3];
    taxa = idetail[4];
    itx=idetail[5];
    idl=idetail[6];

    if (val>' ') {
      orderlines[presentdetailline] = new detailline(val  , dsc , pri ,qty , taxa, itx ,idl );
      presentdetailline++;
    }
        
  }

  totcalculate();
  
}



function writecusttocookie() {

writeCookie('customername',customername,0);
writeCookie('customercompany',customercompany,0);
writeCookie( 'customeraddr1',customeraddr1,0);
writeCookie( 'customeraddr2' ,customeraddr2,0);
writeCookie( 'customertown' ,customertown,0);
writeCookie( 'customercounty' ,customercounty,0);
writeCookie( 'customercountry' ,customercountry,0);
writeCookie( 'customerpostcode' ,customerpostcode,0);
writeCookie( 'customertelephone' ,customertelephone,0);
writeCookie( 'customerfax' ,customerfax,0);
writeCookie( 'customeremail' ,customeremail,0);
writeCookie( 'customerurl',customerurl,0);
writeCookie( 'customerdeldest' ,customerdeldest,0);
writeCookie( 'customerdelcid',customerdelcid,0);
writeCookie( 'customercard',customercard,0);
writeCookie( 'customerref',customerref,0);
writeCookie( 'salamt',salamt,0);
writeCookie( 'taxamt',taxamt,0);
writeCookie( 'dela',dela,0);
writeCookie( 'data',data,0);


}

function writecarttocookie() {
  var cart='';
  for (idx=1;idx<presentdetailline;idx++) {
    prdcde = self.orderlines[idx].prdcde;
    prddsc = self.orderlines[idx].prddsc;       // product description
    prdpri = self.orderlines[idx].prdpri;       // product price
    prdqty = self.orderlines[idx].prdqty;       // product quantity
    prdtax = self.orderlines[idx].prdtax;
    prditx = self.orderlines[idx].prditx;
    prdidl = self.orderlines[idx].prdidl;
    if (prdcde>' ') {
       cart=cart+prdcde+'~'+prddsc+'~'+prdpri+'~'+prdqty+'~'+prdtax+'~'+prditx+'~'+prdidl+'~|';
    }
  }

  writeCookie('cart', cart,24);
  writecusttocookie();
}


function linearray ( n , init ) {
this.size = n;
for (i=1 ; i<=n ; i++)
{
this[i] = init;  // initialize left hand side;
}
return this
}


function calcdelivery(tot ) {
   var theresult = 0;
  novat=false;
   if (self.deldescription.length>1) {
       for (i=0 ; i<=self.deldescription.length ; i++)
      {
         if (self.customerdeldest==delarea[i]) {
             if (delcutin[i]<tot) {
                 theresult = eval(delamount[i]);
                 if (deltaxrte[i] == 0) {
                      novat=true;
                 }
             }  // if this cut in point
           } // if same area
       }
   }
   return( theresult);

}

function calcdiscount() {
     if (self.disdescription.length>1) {
        for (i=0 ; i<=self.disdescription.length ; i++)
       {
             if (discutin[i]<self.salamt) {
                   tmpdis = disamt[i];
             }
        }
     }
}

function detailline ( prdcde , prddsc , prdpri , prdqty, prdtax, prditx,prdidl  )  {

this.length = 7;
this.prdcde = prdcde;   // product code
this.prddsc = prddsc;   // product description
this.prdqty = prdqty;   // product quantity
this.prdpri = prdpri;   // product price
this.prdtot = prdpri * prdqty 
this.prdtax = prdtax;
this.prditx = prditx;
this.prdidl = prdidl;


var tmptot=Math.round(prdqty  *prdpri*100)/100;
var tmpdel= 0;

self.salamt = Math.round((self.salamt+tmptot)*100)/100;

if (this.prdidl!=0) {
  tmpdel = Math.round(prdidl*prdqty*delamt*100)/100;
} else {
   tmpdel = Math.round( (prdqty * delamt * calcdelivery(this.prdpri)*100)/100 );
}   

if (delamt==99999) {
   self.dela= eval(calcdelivery(self.salamt) );
} else { 
 self.dela= eval(self.dela+tmpdel );
}

// VAT where price is Gross 
if (GrossPrice==true) {
    self.taxamt = self.taxamt+( ( (eval(tmptot)+eval(tmpdel))-((eval(tmptot)+eval(tmpdel))/( 1+(prditx

/100)) )));   
} else {
// VAT where price is NETT
    self.taxamt = self.taxamt+ ((eval(tmptot)/100)*prditx );   
}

self.taxamt = Math.round(self.taxamt*100)/100;
if (novat==true) {
 self.taxamt = 0;
}

//if (self.main.location.href.indexOf('ordfrm')>0) {
   self.document.order.sales.value=Math.round(self.salamt*100)/100;
   self.document.order.del.value=Math.round(self.dela*100)/100;
   self.document.order.tax.value=Math.round(self.taxamt*100)/100;

// VAT where price is Gross 
if (GrossPrice==true) {
   self.document.order.tot.value=Math.round((self.salamt+self.dela )*100)/100;
} else {
// VAT where price is NETT
   self.document.order.tot.value=Math.round((self.salamt+self.dela+self.taxamt)*100)/100;
}


}

function forceRewrite()
{

}


function setvalue(val,qty,dsc,pri,tax,del)  {
 pri=pri+'';
 if (pri.indexOf('|')>-1) {
     itx=pri.indexOf('|');
     tmpstr=pri.substr(itx+1, pri.length );
     itx=tmpstr.indexOf('|');
     pri=tmpstr.substr(0,itx);
     tmpstr=tmpstr.substr(itx+1, tmpstr.length );
     itx=tmpstr.indexOf('|');
     tax=tmpstr.substr(0,itx);
 }

var maxno=noofdetaillines;
var currentline = presentdetailline;
var itx=0;

if (qty==0)  {
 remline(val);
}
else {

      qty = Math.round(qty*100)/100;
       pri = Math.round(pri * rate*100)/100;
       tot = Math.round(pri * rate * qty * 100)/100;
       itx = Math.round(tax * rate *100)/100;
       taxa = Math.round(tax * rate * qty * 100)/100;
       idl = Math.round(del * rate * 100  )/100;
       if (currentline > maxno ) {
            currentline = 1;
      }

       orderlines[presentdetailline] = new detailline(val  , dsc , pri ,qty , taxa, itx ,idl );
       presentdetailline ++;
}
writecarttocookie();

return;

}


function remline(prdcde) {
 for ( var i = 1; i <= presentdetailline-1 ; i++) {
           if (self.orderlines[i].prdcde   == prdcde  ) {
                self.orderlines[i].prdcde = ' ';
         }     
     }
    totcalculate();
    writecarttocookie();
}

function totcalculate() {
var gtot=0;
var gtax=0;
var gdel=0;

    for ( var i = 1; i <= presentdetailline-1 ; i++) {
          if (self.orderlines[i].prdcde > ' ') {
               pri = Math.round(self.orderlines[i].prdpri * 100)/100;
               tot = Math.round(self.orderlines[i].prdqty  *pri*100)/100;


               if (self.orderlines[i].prdidl!=0) {
                   tdel = Math.round(self.orderlines[i].prdidl*
                               self.orderlines[i].prdqty*delamt*100)/100;
                } else {
                                tmpdel = eval( calcdelivery(pri) );
                                 tdel = Math.round(tmpdel *  self.orderlines[i].prdqty*delamt*100)/100;
                }   // if the detail has a delivery amount


              // VAT off GROSS price 
              if (GrossPrice==true) {
                       ttax = (  (eval(tot)+eval(tdel))-( (eval(tot)+eval(tdel))/(1+eval(self.orderlines[i].prditx/100)) ) ); 
               } else {
               // VAT on NETT price
                        ttax = ( eval(tot) /100)*eval(self.orderlines[i].prditx);       
               } // if price includes VAT

                ttax = Math.round( ttax*100)/100;
                gtot = Math.round((eval(gtot) + eval(tot) )*100)/100;
                gtax = Math.round((eval(gtax) + eval(ttax))*100)/100;
                gdel = Math.round((eval(gdel)+  eval(tdel))*100)/100;
            } // if valid order line
     }  // end for items;

    if (delamt==99999) {
            gdel = eval( calcdelivery( gtot ) )
     }

    self.salamt=gtot;
    self.dela=gdel;
    self.taxamt=gtax;
if (novat==true) {
 self.taxamt = 0;
}

       self.document.order.sales.value=stdf(self.salamt);
       self.document.order.del.value=stdf(self.dela);
       self.document.order.tax.value=stdf(self.taxamt);

       // VAT off GROSS price 
        if (GrossPrice==true) {
            self.document.order.tot.value=stdf(Math.round((self.salamt+self.dela )*100)/100);
        } else {
            // VAT on NETT price
            self.document.order.tot.value=stdf(Math.round((self.salamt+self.dela +self.taxamt )*100)/100);
        }
//    }

   
}


function showlist() {


astr='<html><head>';

astr=astr+'<script language="Javascript">';
astr=astr+'function setprice(){ };';
astr=astr+'function settotal() {';
astr=astr+'  grtotal = '+(eval(self.salamt)+eval(self.dela)+eval(self.taxamt))+';';
astr=astr+'}';
astr=astr+'function currencychange() {';
astr = astr +' Convert.Total.value = Math.round(self.document.Convert.currency.options[self.document.Convert.currency.selectedIndex].value*grtotal*100)/100;';
astr=astr+'}';
astr=astr+'var grtotal=0;';
astr=astr+'</script>'; 

astr=astr+'<script language="JavaScript" src="maincode.js"> </script>';
astr=astr+'<link href="styles/style-sheet.css" rel="stylesheet" type="text/css" />';
astr=astr+'</head>';
astr=astr+'<body  onload="initcustomer();settotal();" >'; 
astr=astr+'<div id="cart" style="width:1024px;">';
astr=astr+'<div id="header">';
astr=astr+'<img src="logo.gif" alt="<#COMPANYNAME>" id="logo" />';
astr=astr+'  <div id="contact">';
astr=astr+'             t: <#COMPANYTELEPHONE><br />';
astr=astr+'             f: <#FAX><br />';
astr=astr+'             e:<a href="mailto:<#CONTACTEMAIL>"><#CONTACTEMAIL></a><br />';
astr=astr+'             w:<a href="<#WEBADDRESS>"><#WEBADDRESS></a>';
astr=astr+'  </div> <!-- contact -->';
astr=astr+'</div> <!-- header -->';
astr=astr+'<div id="address">';
astr=astr+'<table><tr><td>To : <br/><#COMPANYNAME><br/><#COMPANYADDR><br/>Tel: <#COMPANYTELEPHONE><br/>Fax: <#FAX>';
astr=astr+'</td><td>From : <br/>'+self.customername+'<br/>'+self.customercompany+'<br/>'+self.customeraddr1+'<br/>'+self.customeraddr2+'<br/>'+self.customertown+'<br/>'+self.customercounty+'<br/>'+self.customercountry+'<br/>'+self.customerpostcode+'<br/>'+self.customertelephone+'</td></tr></table>';
astr=astr+'</div>';

var d = new Date();

astr=astr+'Date : '+d+'<br/>';
astr = astr + 'Please Supply The following Items ';
astr = astr + '<table><tr><td>Code</td><td>Description</td><td>Price</td><td>Quantity</td><td>Total</td><td>Delivery</td><td>Tax</td><tr>';

gtot = 0;
gtax = 0;
gdel = 0;

for ( var i = 1; i <= presentdetailline-1 ; i++) {

 if (self.orderlines[i].prdcde > ' ') {
    pri = Math.round(self.orderlines[i].prdpri * 100)/100;

    tot = Math.round(self.orderlines[i].prdqty  *pri*100)/100;

//    tdel = Math.round((self.orderlines[i].prdidl*
//                                        self.orderlines[i].prdqty*delamt*100)/100);

               if (self.orderlines[i].prdidl!=0) {
                   tdel = Math.round(self.orderlines[i].prdidl*
                               self.orderlines[i].prdqty*delamt*100)/100;
                   } else {
                                tmpdel = eval( calcdelivery(pri) );
                                 tdel = Math.round(tmpdel *  self.orderlines[i].prdqty*delamt*100)/100;
                   }   
                if (delamt==99999) {
                     tdel=0;
               }




// VAT of GROSS price
if (GrossPrice==true) {
     ttax = (  (eval(tot)+eval(tdel))-( (eval(tot)+eval(tdel))/(1+(self.orderlines[i].prditx/100)) ) ); 
} else {
// VAT on NETT price
       ttax = ( eval(tot) /100)*eval(self.orderlines[i].prditx);                        
}
       ttax = Math.round( ttax*100)/100;


      gtot = Math.round((eval(gtot) + eval(tot))*100)/100;
      gtax = Math.round((eval(gtax) + eval(ttax))*100)/100;
      gdel = Math.round((eval(gdel)+  eval(tdel))*100)/100;

if (novat==true) {
 gtax = 0;
}
    if (delamt==99999) {
            gdel = eval( calcdelivery( gtot ) )
     }

//      tot = ' '+tot+' ';
tmpstr=self.orderlines[i].prddsc;
if (self.orderlines[i].prddsc.indexOf('|')>0) {
  tmpstr=self.orderlines[i].prddsc.substring(0,self.orderlines[i].prddsc.indexOf('|'))
}
    astr = astr +'<tr><td>'+self.orderlines[i].prdcde + '<br/><a href=javascript:remline("'+self.orderlines[i].prdcde+'");document.close();showlist();>Remove Item</a></td><td> '+tmpstr  + '</td><td>'+pri+'</td><td>'+self.orderlines[i].prdqty+'</td><td class="ralign">'+stdf(tot) +'</td><td class="ralign">'+stdf(tdel)+'</td><td class="ralign">'+stdf(ttax)+'</td><tr>'+'<br/>' ;
 }
}
astr = astr+'<tr><td></td><td></td><td></td><td></td><td class="ralign">'+stdf(gtot)+'</td><td class="ralign">'+stdf(gdel)+'</td><td class="ralign">'+stdf(gtax)+'</td></tr><br/>';

// VAT of GROSS price
if (GrossPrice==true) {
   astr = astr+'<tr><td></td><td></td><td></td><td></td><td>Total</td><td class="ralign">'+stdf(Math.round((eval(gtot)+eval(gdel) )*100)/100)+'</td><td></td></tr>';
} else {
  // VAT on NETT price
  astr = astr+'<tr><td></td><td></td><td></td><td></td><td>Total</td><td class="ralign">'+stdf(Math.round((eval(gtot)+eval(gdel)+eval(gtax))*100)/100)+'</td><td></td></tr>';
}

astr = astr+'</table>';

astr = astr+'<div style="text-align:center;" ><br/>If you do not wish to use Card Payment or this site does not allow Card Payment <br/>';
astr = astr+'then Please print this form. You should then post it to is with your cheque enclosed <br/>';
astr = astr+' Thankyou. <br/><br/>';
astr = astr+'<input type="button" value="Continue Shopping" onClick="javascript:history.go(-1)" />';


astr = astr+'<form name="order" id="order" action="<#FORMACTION>" >';
astr = astr+'<input type="hidden" name="recipient" value="<#ORDEREMAIL>" />';
astr = astr+'<input type="hidden" name="cname"   />';
astr = astr+' <input type="hidden" name="email"  />';
astr = astr+'<input type="hidden" name="telephone"  />';
astr = astr+'<input type="hidden" name="addr1"  />';    
astr = astr+' <input type="hidden" name="Detail" value="" />';
astr = astr+'<input type="hidden" name="addr2"  />';
astr = astr+'<input type="hidden" name="town"  />';
astr = astr+'<input type="hidden" name="county"  />';
astr = astr+'<input type="hidden" name="country"  />';
astr = astr+'<input type="hidden" name="postcode"  />';
astr = astr+'<input type="hidden" name="orderinfo"  id="orderinfo" value="" />';
astr = astr+'<input type="hidden" name="amount" id="amount" value="0" />';
astr = astr+'<input type="hidden" name="notes"  />';
astr = astr+'<input type="button" name="checkout"  value="Proceed To Checkout" onClick="javascript:setdetailline();"  /> ';
astr = astr+'<input type="hidden" name="sales"  /> ';
astr = astr+'<input type="hidden" name="del"  /> ';
astr = astr+'<input type="hidden" name="tax"  /> ';
astr = astr+'<input type="hidden" name="tot" size="6" />';
astr = astr+'</form>';



astr = astr+'</div></div>';
astr= astr+'</body></html>'

self.salamt=gtot;
self.dela=gdel;
self.taxamt=Math.round(gtax*100)/100;

d=document;
n=navigator;
nav=n.appVersion;
nan=n.appName;
nua=n.userAgent;

// alert(nav);

document.clear;
document.write(astr);

document.close();



}

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
    ono= formatnumber('#####.00',ino);
    return ( ono )
}

// Example:
// alert( readCookie("myCookie") );
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



// Global variables
var count = 0;
var noofdetaillines = 35;
var presentdetailline = 1;
// Initialise Main form variables here
var noofdetaillines = 35;
var rate = 1;
var count = 0;
var noofdetaillines = 35;




