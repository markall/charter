// JavaScript Document
<!--//--><![CDATA[//><!--

function checkCC(ccnum){


   
   return true;
}



function help (category, field ) {            
                helperwin.show();
                helperwin.load('iframe', '../help/'+field+'.htm', 'DHelp' );

}

function addtocart( icode,idescription ,iprice,itax,idel, vatexempt ) {

                qty = document.getElementById(icode+'_qty').value;

                ipid = Math.round(Math.random()*99);

                idel = 0;

                additem(cart, ipid, icode ,idescription, iprice, qty, itax, idel, vatexempt );
//              additem(cart, ipid, icode ,'Carriage Extra', delivery_price, qty, itax, idel );         

                displaycart();

        }


function displaycart() {
//              cartwin.load('inline', showcart() , 'Shopping cart' );  
//              cartwin.show();
                totalcart();
                document.location='content.php?template=templates/shop/cart.html';
//              document.write( cartintemplate( 'templates/cart.html' ) );
}

function setprice(self) {




}

function setinfo(h,w,p,rrp) {
  pdetailtext = document.getElementById("pdetail");
  h_ft = Math.round( 0.0032808399 * h *100)/100;
  w_ft = Math.round( 0.0032808399 * w *100) /100;
  
  dotidx = (h_ft+"").indexOf('.');
  if (dotidx>0) {
    meas = (h_ft+"").split('.');
        h_inch = meas[1];
        h_ft = meas[0];
        h_ft=h_ft+"ft "+Math.round((eval("."+h_inch)*12)-0.49)+"in ";
  } else {
    h_ft=h_ft+"ft ";
  }
  dotidx = (w_ft+"").indexOf('.');
  if (dotidx>0) {
    meas = (w_ft+"").split('.');
        w_inch = meas[1];
        w_ft = meas[0];
        w_ft=w_ft+"ft "+Math.round((eval("."+w_inch)*12)-0.49)+"in ";
  } else {
        w_ft=w_ft+"ft ";
  }
  saver = Math.round((eval(rrp)-eval(p))*100)/100;

  pdetailtext.innerHTML='<br/><h3>Current Size '+h_ft+' x '+w_ft+'<br/><br/>'+'<span class="save">You save &pound;'+saver+'</span></h3>';
  

}

//--><!]]>


