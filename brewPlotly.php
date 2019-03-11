<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
        <title>Brew Starter</title>
    </head>
<body>

        <h1>Dette er BrewStarter - Til dig som ønsker at blive en Øl-Meistro</h1>
    <div id="plotly"></div>
    <form action="http://stef151i.web.eadania.dk/brewStarter/brewStarter.php">
        <button type="submit">Tilbage til forside.</button>
    </form>
        
    <script>
        
        var afstand=[];
        var afstandTid=[];
        var co2=[]; 
        var co2Tid=[];
        var temp=[];
        var tempTid=[];
        
        function distanceData(){
        
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){ 
                if (this.readyState == 4 && this.status == 200){
                    var distancejason = JSON.parse(this.responseText); 
                    visAfstand(distancejason);
                }
            };
            xhhtp.open("POST", "brewData.php?s=distance&sys=teamTrash", true);
            xhhtp.send();
            }  
       
        function visAfstand(inputData){
            for(i in inputData){
                afstand.push(inputData[i].nvalue)
                afstandTid.push(inputData[i].nobstime) 
            
                } 
            }
    
        // CO2 data.
        function co2Data(){
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    var co2jason = JSON.parse(this.responseText); 
                    visCo2(co2jason); 
                }
            }; 
            
            xhhtp.open("POST", "brewData.php?s=CO2&sys=luftkvalitet", true);
            xhhtp.send();
            }
        
        function visCo2(inputData){
            for(i in inputData){
                co2.push(inputData[i].nvalue)
                co2Tid.push(inputData[i].nobstime)
            }
        }
        
        // Temperatur data.
        function tempData(){
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    var tempjason = JSON.parse(this.responseText); 
                    visTemp(tempjason);
                    grafData();
                } 
            }; 
    
            xhhtp.open("POST", "brewData.php?s=lufttemperatur&sys=idaErin", true);
            xhhtp.send();
            }   
     
        function visTemp(inputData){
            for(i in inputData){
                temp.push(inputData[i].nvalue)
                tempTid.push(inputData[i].nobstime)
            }
        }  
        
        function grafData(){
            var afstandGraf={
            x: afstandTid,
            y: afstand,
            mode: 'lines'
        };
            
            var co2Graf={
            x: afstandTid,
            y: co2,
            mode: 'lines'
        };
            var tempGraf={
            x: afstandTid,
            y: temp,
            mode: 'lines'
            };
           
            var data = [afstandGraf, co2Graf, tempGraf];
            
            Plotly.newPlot('plotly', data,  {showSendToCloud:true});
        }

        
        function hentData (){ 
            distanceData();
            co2Data();
            tempData();
            }
        
        
        hentData();
        grafData();
        
       
        //Henter hver x minut - Kommer mere senere
        setInterval (hentData, 5000); 
 
    </script>
    
    
</body>   
</html>