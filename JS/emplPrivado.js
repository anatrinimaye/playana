

(()=>{
    let empleados=document.getElementById("infor");

    const objeto=new XMLHttpRequest;

    objeto.onreadystatechange=function(){
        if (objeto.readyState === 4 && objeto.status === 200){

            let datos=JSON.parse(objeto.responseText);
            datos.forEach((ele)=>{
               const tr=document.createElement("tr");
               tr.classList.add("camp");
               empleados.appendChild(tr);
            
               // Creacion del td para la imagen del empleado
               let tdImg=document.createElement("td");
               tr.appendChild(tdImg);
               let foto=document.createElement("img");
               foto.setAttribute("src", ele.foto);
               tdImg.appendChild(foto);
               tr.appendChild(tdImg)
                // creacion del td para el nombre del empleado
                let tdNom=document.createElement("td");
                tdNom.innerHTML=ele.nombre;
                tr.appendChild(tdNom);
                //Creacion del td para la especialidad
                let tdESp=document.createElement("td");
                tdESp.innerHTML=ele.especialidad;
                tr.appendChild(tdESp);

            });
        }
        else{
            document.write="Ruta no encontrada"
        }
    }
    objeto.open("GET", "./JSON/empleados.json");
    objeto.send();

})()