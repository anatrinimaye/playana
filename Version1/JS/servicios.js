

(()=>{
    let cajaServicios=document.getElementById("servicios");

    const obj1=new XMLHttpRequest;

    obj1.onreadystatechange = function(){
        if(obj1.readyState=== 4 && obj1.status=== 200){
            let datosJSON=JSON.parse(obj1.responseText);
            datosJSON.forEach(element => {

                //CAJA GENERAL
                const caja=document.createElement("div");
                caja.classList.add("caja");
                cajaServicios.appendChild(caja);

                //NOMBRE
                let nombre=document.createElement("h3");
                nombre.innerHTML=element.nombre;
                caja.appendChild(nombre);
                
                //CAJA DE LA FOTO Y EL TEXTO
                const cajaPadre=document.createElement("div");
                cajaPadre.classList.add("defin");
                caja.appendChild(cajaPadre);
                
                //FOTO
                let cajafoto=document.createElement("div");
                cajafoto.classList.add("foto");
                cajaPadre.appendChild(cajafoto);
                let foto=document.createElement("img");
                foto.setAttribute("src", element.foto);
                cajafoto.appendChild(foto);
                
                //TEXTO
                let cajatexto=document.createElement("div");
                cajatexto.setAttribute("class", "texto");
                cajaPadre.appendChild(cajatexto);
                let texto=document.createElement("p");
                texto.innerHTML=element.descripcion;
                cajatexto.appendChild(texto);
                
            });
        }
    }

obj1.open("GET", "./JSON/servicios.json");
obj1.send();
})()