(()=>{
    let pacienteOrto=document.getElementById("infor1");

    const obj1=new XMLHttpRequest();

    obj1.onreadystatechange=function(){
        if(obj1.readyState=== 4 && obj1.status=== 200){
            let datJSON=JSON.parse(obj1.responseText);
            datJSON.forEach(element => {
                const cajaGeneral = document.createElement("tr");
                pacienteOrto.appendChild(cajaGeneral);
                //Creacion de los numeros
                let tdNum=document.createElement("td");
                tdNum.classList.add("sem");
                tdNum.innerHTML= element.numero;
                cajaGeneral.appendChild(tdNum);
                //Creacion de los nombres
                let tdNom=document.createElement("td");
                let pNom=document.createElement("p");
                pNom.innerHTML= element.nombre;
                tdNom.appendChild(pNom);
                cajaGeneral.appendChild(tdNom);
                //Creacion del motivo
                let tdMot=document.createElement("td");
                tdMot.classList.add("p");
                let pMot=document.createElement("p");
                pMot.innerHTML=element.motivo;
                tdMot.appendChild(pMot);
                cajaGeneral.appendChild(tdMot);
                //Creacion de la fecha
                let tdFech=document.createElement("td");
                let pFech=document.createElement("p");
                pFech.innerHTML=element.fecha;
                tdFech.appendChild(pFech);
                cajaGeneral.appendChild(tdFech);
                //Creacion de la Proxima cita
                let tdProx=document.createElement("td");
                let pProx=document.createElement("p");
                pProx.innerHTML=element.proximacita;
                tdProx.appendChild(pProx);
                cajaGeneral.appendChild(tdProx);
                //Creacion del td para acceder al historial
                let tdHisto=document.createElement("td");
                tdHisto.classList.add("p");
                let aHisto=document.createElement("a");
                aHisto.innerHTML=`
                    <a href="./citOnto2.html">VER...</a>`
                tdHisto.appendChild(aHisto);
                cajaGeneral.appendChild(tdHisto);
                //Creacion del nombre del doctor
                let tdDoc=document.createElement("td");
                let pDoc=document.createElement("p");
                pDoc.innerHTML=element.doctor;
                tdDoc.appendChild(pDoc);
                cajaGeneral.appendChild(tdDoc);


                
            });
        }
        else{
            document.write="RUTA NO ENCONTRADA"
        }
    }

    obj1.open("GET", "./JSON/Ortopedia.json")
    obj1.send();




})()