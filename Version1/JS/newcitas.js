
let ortopedi=document.getElementById('orto1');
let odonLs=localStorage.getItem("odon");
 let ortoLs=localStorage.getItem("orto");
 let obteLs=localStorage.getItem("obste");
 if(odonLs==="odontologia"){
    ortopedi.textContent="NUEVA CITA ODONTOLOGIA";
 }
 else if(ortoLs==="ortopedia"){
    ortopedi.textContent="NUEVA CITA ORTOPEDIA";
 }else if(obteLs==="obstetria"){
    ortopedi.textContent="NUEVA CITA OBSTETRIA";
 }

