


    //FUNCION PARA EL FORMULARIO DEL SISTEMA

    function entrar(){
        let nombre=document.getElementById("nombre").value;
        let contra=document.getElementById("contra").value;

    if((nombre == " " || nombre.length==0) || (contra=="" || contra.length==0)){
        alert("NO PUEDES DEJAR NINGUN CAMPO VACIO");
    }
    //Credenciales del admin
    else if(nombre === "admin" && contra ==="admin1234"){
        window.location="./Admin/indexAdmin.html";
    }
    //Credenciales de la recepcion
    else if(nombre === "recepcion" && contra ==="recep1234"){
        window.location="./Admin/recepcion.html";
    }
    else if((nombre !=="recepcion" && contra !=="recepcion1234")||(nombre !=="admin" && contra !=="admin1234")){
        alert("DATOS INCORRECTOS");
    }
}
   //DOCTORES
    function acceder(){
        let nombre=document.getElementById("nombre").value;
        let contra=document.getElementById("contra").value;
        let radOdon=document.getElementById("radOdon").checked;
        let radObste=document.getElementById("radObs").checked;
        let radOrto=document.getElementById("radOrt").checked;
    
        //No se puede dejar campos vacios
    if((nombre== " " || nombre.length == 0) || (contra == " " || contra.length == 0)){
        document.getElementById("campVacio").classList.add("Vacio");
        //alert("DEBES PROPORCIONAR TODOS LOS DATOS");
        
    }
    else if( (radOdon === false && radObste === false && radOrto === false)){
        
   }
    
    //Credenciales del servicio de odontologia
    else if(nombre === "doctor" && contra ==="odon1234" && radOdon === true){
        localStorage.setItem("odon","odontologia");
        localStorage.removeItem("orto");
        localStorage.removeItem("obste");
        window.location="servicios.html";
        
    }
    //Credenciales del servicio ortopedia
    else if(nombre === "doctor" && contra ==="orto1234" && radOrto === true){
        localStorage.setItem("orto","ortopedia");
        localStorage.removeItem("odon");
        localStorage.removeItem("obste");
        window.location="servicios.html";
    }
    //Credenciales del servicio obstetricia
    else if(nombre === "doctor" && contra ==="obste1234" && radObste === true){
        localStorage.setItem("obste","obstetria");
        localStorage.removeItem("orto");
        localStorage.removeItem("odon");
        window.location="servicios.html";
    }
    else if((nombre !== "doctor" || contra !=="onto1234" || radOdon === true)
        ||(nombre !== "doctor" || contra !=="orto1234" || radOrto === true)
        ||(nombre !== "doctor" || contra !=="obste1234" ||  radObste === true)){
        alert("DATOS INCORRECTOS");
    }


    /*let form = document.querySelector('form');

    document.addEventListener('click', e=>{

    if (form.rad) {
        console.log(form.rad)
    }
    })*/
}