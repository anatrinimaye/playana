

(()=>{
    let cajaGenral=document.getElementById("doc");

    const obj=new XMLHttpRequest;

    obj.onreadystatechange=function(){
        if(obj.readyState === 4 && obj.status === 200){
            let datJSON=JSON.parse(obj.responseText);
            datJSON.forEach(el => {
                
                const cajaPadre=document.createElement("div");
                cajaPadre.classList.add("doc");
                cajaGenral.appendChild(cajaPadre);

                //Creando la Imagen con su Caja y Etiqueta
                let cajaImg=document.createElement("div");
                cajaImg.classList.add("foto");
                cajaPadre.appendChild(cajaImg);
                let etqImg=document.createElement("img");
                etqImg.setAttribute("src",el.foto);
                cajaImg.appendChild(etqImg);

                //Creando la Caja  y las Etiquetas del Nombre y la Especialidad
                let cajaNombre=document.createElement("div");
                cajaNombre.setAttribute("class","nom");
                cajaPadre.appendChild(cajaNombre);
                let etqNom=document.createElement("p");
                etqNom.innerHTML=el.nombre+"<br>"+`<span>${el.especialidad}</span>`
               ;
                cajaNombre.appendChild(etqNom);
                
                //Creando la Caja y las Etiquetas de los Iconos
                let cajaIconos=document.createElement("div");
                cajaIconos.classList.add("iconos");
                cajaIconos.innerHTML=`
                <a href=""><i class="fa-brands fa-facebook"></i></a>
                <a href=""><i class="fa-brands fa-linkedin-in"></i></a>
                <a href=""><i class="fa-brands fa-tiktok"></i></a>
                <a href=""><i class="fa-brands fa-square-instagram"></i></a>
                `
                cajaPadre.appendChild(cajaIconos);
                
            });
        }
        else{
            document.write=(" Ruta no encontrada ")
        }
    }
    obj.open("GET", "./JSON/emplPublico.json");
    obj.send();





})()