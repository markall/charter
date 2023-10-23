// JavaScript Document
function isdatevalid(dob) {

        // dte in dd/mm/yyyy or dd/mm/yyyy hh:mm format
        

        valid=true;
        dob = dob.replace('.','/');
        doba = dob.split('/');

        if (doba.length=3) {
           if ( (isNaN(doba[0])) || (isNaN(doba[1])) || (isNaN(doba[2])) ) {
                 valid=false;

           } else {
                maxdte =28;
                ly =Math.round(doba[2]/4);

                if (ly==doba[2]) {
                   maxdte=29;
                }

                if (doba[1]=='01' ||
                        doba[1]=='03' ||
                        doba[1]=='05' ||
                        doba[1]=='07' ||
                        doba[1]=='08' ||
                        doba[1]=='10' ||
                        doba[1]=='12' ) {
                   maxdte = 31;
                }

                if (    doba[1]=='04' ||
                        doba[1]=='06' ||
                        doba[1]=='09' ||
                        doba[1]=='11' ) {
                   maxdte=30;
                }



                d = parseInt(doba[0]);

                if ( (d<1) || (d>maxdte) ) {

                  valid=false;
                }
           }
        } else {
         valid=false;

        }

        if (valid) {
                r=doba[2]+'/'+doba[1]+'/'+doba[0];
        } else {
                r='';
        }
        return r;
}
