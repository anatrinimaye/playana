


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
    //FUNCION PARA EL FORMULARIO DEL SISTEMA
    function acceder(){
        let nombre=document.getElementById("nombre").value;
        let contra=document.getElementById("contra").value;
        let radOrto=document.getElementById("label input:checked");

    if((nombre== " " || nombre.length == 0) || (contra == " " || contra.length == 0)){
        alert("DEBES RELLENAR AMBOS CAMPOS");
    }
    //Credenciales del servicio de odontologia
    else if(nombre === "doctor" && contra ==="odon1234"){
        localStorage.setItem("odon","odontologia");
        localStorage.removeItem("orto");
        localStorage.removeItem("obste");
        window.location="servicios.html";
    }
    //Credenciales del servicio ortopedia
    else if(nombre === "doctor" && contra ==="orto1234" && radOrto.isCheck){
        localStorage.setItem("orto","ortopedia");
        localStorage.removeItem("odon");
        localStorage.removeItem("obste");
        window.location="servicios.html";
    }
    //Credenciales del servicio obstetria
    else if(nombre === "doctor" && contra ==="obste1234"){
        localStorage.setItem("obste","obstetria");
        localStorage.removeItem("orto");
        localStorage.removeItem("odon");
        window.location="servicios.html";
    }
    else if((nombre !== "doctor" && contra !=="onto1234")||(nombre !== "doctor" && contra !=="orto1234")||(nombre !== "doctor" && contra !=="obste1234")){
        alert("DATOS INCORRECTOS");
    }
}