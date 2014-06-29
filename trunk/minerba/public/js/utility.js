    function number_format(num,dig,dec,sep) {
        x=new Array();
        s=(num<0?"-":"");
        num=Math.abs(num).toFixed(dig).split(".");
        r=num[0].split("").reverse();
        for(var i=1;i<=r.length;i++){x.unshift(r[i-1]);if(i%3==0&&i!=r.length)x.unshift(sep);}
        return s+x.join("")+(num[1]?dec+num[1]:"");
      }
      
    formatMoney = function(val,row){
        return number_format(val,0,',','.');
    };
    
    myDateFormatter=function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
    };
    
    myDateParser=function(s){
       if (!s) return new Date();
       var ss = (s.split('-'));
       var y = parseInt(ss[2],10);
       var m = parseInt(ss[1],10);
       var d = parseInt(ss[0],10);
       if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
           return new Date(y,m-1,d);
       } else {
           return new Date();
       }
    }  