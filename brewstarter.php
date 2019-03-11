<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="brewstyle.css">
        <title>Brew Starter</title>
        
    </head>
<body> 
    <img id="flaske" src="flaske4.png">
    <div id="wrapperTemp"> 
           <p id="lblTekst">Temp.</p>
            <label id="lblTemp" for="">0</label>
            
            <svg id="meterTemp">
 
                <!--Low Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#22a4d0" stroke-width="60" stroke-dasharray="471, 943" fill="none"></circle>
 
                <!--Average Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#518123" stroke-width="60" stroke-dasharray="314, 943" fill="none"></circle>
 
                <!--High Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#c43333" stroke-width="60" stroke-dasharray="157, 943" fill="none"></circle>
            </svg>
            <img id="meterTemp_needle" src="needle.svg" alt="">
             
    </div>
    <br>
    <div id="wrapperCo2"> 
            <p id="lblTekst2">Co2.</p>
            <label id="lblCo2" for="">0</label>
            
            <svg id="meterCo2">
 
                <!--Low Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#22a4d0" stroke-width="60" stroke-dasharray="471, 943" fill="none"></circle>
 
                <!--Average Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#518123" stroke-width="60" stroke-dasharray="314, 943" fill="none"></circle>
 
                <!--High Range Zone-->
                <circle r="150" cx="50%" cy="50%" stroke="#c43333" stroke-width="60" stroke-dasharray="157, 943" fill="none"></circle>
            </svg>
            <img id="meterCo2_needle" src="needle.svg" alt="">
            
    </div>
     
    <div id="alc">
            <h2> Forventet Alc. %</h2>
            <p id="forventetAlc">0 %</p> <br>
            <h2> Nuværende Alc. %</h2>
            <p id="nuAlc">0 %</p>
    </div>
       <input type="button" onClick="removeLocal()" name="fjern data" value="Ny Øl">
    <form action="http://stef151i.web.eadania.dk/brewStarter/brewPlotly.php">
        <button type="submit">Mere Data</button>
    </form>
    <script>
        var r = 250;
        var circles = document.querySelectorAll('.circle');
        var total_circles = circles.length;
        for (var i = 0; i < total_circles; i++) {circles[i].setAttribute('r', r);
        }
 
        /* meter's wrapper dimension */
        var meter_dimension = (r * 2) + 100;
        var wrapperTemp = document.querySelector("#wrapperTemp");
        wrapperTemp.style.width = meter_dimension + "px";
        wrapperTemp.style.height = meter_dimension + "px";   
        var wrapperCo2 = document.querySelector("#wrapperCo2");
        wrapperCo2.style.width = meter_dimension + "px"; 
        wrapperCo2.style.height = meter_dimension + "px";
        
        /*range event*/
        var lblTemp = document.querySelector("#lblTemp");
        var meterTemp_needle =  document.querySelector("#meterTemp_needle");
        var lblCo2 = document.querySelector("#lblCo2");
        var meterCo2_needle =  document.querySelector("#meterCo2_needle");  
        
        /*variable som hentes fra databasen*/
        
        var forvententetAlc = 1.072;
        var finalAlc= (((forvententetAlc-1)*1000) - 10)/7.6;
        
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
        let afstand=[];
        let afstandTid=[];
            for(i in inputData){
                afstand.push(inputData[i].nvalue)
                afstandTid.push(inputData[i].nobstime) 
            
                } 
            range_change_alc(afstand, afstandTid);
            range_change_fAlc(afstand);
            }
    
        function range_change_fAlc(){
            
            if(localStorage.fAlc)
                readLocal();
            else
                saveLocal();
            }
        
        function readLocal(){
            forvententetAlc =localStorage.fAlc;
        }
        
        function saveLocal(){
            var fakeNews = 1.072;
            localStorage.fAlc= fakeNews;
            
            //localStorage.fAlc = forvententetAlc;
            
            
        }
        function removeLocal(){
            localStorage.removeItem("fAlc");
        }
    
        function range_change_alc() {
        //Fordi afstandsmålingen ikke giver et retvisende billede, bruger vi en fast variabel
            //var percent = afstand[0];
            //var alc = (((percent-1)*1000) - 10)/7.6;
            var alc = 5.2;
            document.getElementById("nuAlc").innerHTML=alc.toFixed(2)+"%";
            document.getElementById("forventetAlc").innerHTML=finalAlc.toFixed(2)+"%";
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
            let co2=[]; 
            let co2Tid=[];
            for(i in inputData){
                co2.push(inputData[i].nvalue)
                co2Tid.push(inputData[i].nobstime)
            }    
            range_change_Co2(co2,co2Tid)
        }
    
        function range_change_Co2(co2,co2Tid) {
            var percent = co2[0];
            meterCo2_needle.style.transform = "rotate(" + 
            (270 + ((percent * 180) / 1300)) + "deg)";
            lblCo2.textContent = percent+ " PPM";
        }
        
        // Temperatur data.
        function tempData(){
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    var tempjason = JSON.parse(this.responseText); 
                    visTemp(tempjason); 
                } 
            }; 
    
            xhhtp.open("POST", "brewData.php?s=lufttemperatur&sys=idaErin", true);
            xhhtp.send();
            }   
     
        function visTemp(inputData){
            let temp=[];
            let tempTid=[];
            for(i in inputData){
                temp.push(inputData[i].nvalue)
                tempTid.push(inputData[i].nobstime)
            } 
            range_change_Temp(temp,tempTid)
        }    
    
        function range_change_Temp(temp,tempTid) {
            var percent = temp[0];
            meterTemp_needle.style.transform = "rotate(" + 
            (270 + ((percent * 180) / 33)) + "deg)";
            lblTemp.textContent = percent+ "°C";
        }
    
        // Gær(t)lås data.
        function gertData(){ 
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    var gertjason = JSON.parse(this.responseText); 
                    visGert(gertjason); 
                }
            }; 
            
            xhhtp.open("POST", "brewData.php?s=jordfugtighed&sys=idaErin", true);
            xhhtp.send();
            }
    
        function visGert(inputData){
            let gert=[];
            let gertTid=[];
            for(i in inputData){
                gert.push(inputData[i].nvalue)
                gertTid.push(inputData[i].nobstime)
            }
            if (gert <= 200) {
                alert("Gærlåsen mangler vand")
            }
        }        
        
        // Lyset data.
        function lysData(){ 
            var xhhtp = new XMLHttpRequest();
            xhhtp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    var lysjason = JSON.parse(this.responseText); 
                    visLys(lysjason);
                }
            }; 
            
            xhhtp.open("POST", "brewData.php?s=Lysindfald&sys=Luxbanden", true);
            xhhtp.send();
            }
        
        function visLys(inputData){
            let lys=[];
            let lysTid=[];
            for(i in inputData){
                lys.push(inputData[i].nvalue)
                lysTid.push(inputData[i].nobstime)
            }
            if (lys >= 50){
            alert("Der er for meget lys")}
        }   
        
        //Henter data ved startup
        function hentData (){ 
            distanceData();
            co2Data();
            tempData();
            gertData();
            lysData();
            }
        hentData();
    
        //Henter hver x minut - Kommer mere senere
        setInterval (hentData, 5000); 
 
    </script>
</body>   
</html>