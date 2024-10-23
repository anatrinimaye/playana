 
 
 
 
 let body=document.getElementById("body");
 let odonLs=localStorage.getItem("odon");
 let ortoLs=localStorage.getItem("orto");
 let obteLs=localStorage.getItem("obste");
 

 const odontologia=`
 <div class=" header">
        <!--Logo PLAyANA-->
        <div class="logo">
            <img src="./IMG/logoOro.jpg" alt="">
        </div>
        
        <div class="enlaces">
            <a href="./formservis.html"><i class="fa-solid fa-circle-arrow-left"></i></a>
            <a href="./index.html"><i class="fa-solid fa-house"></i>Inicio</a>
            <a href="./citOnto.html"><i class="fa-solid fa-calendar-check"></i> Citas</a>
            <a href="./newCita.html"><i class="fa-solid fa-file-pen"></i> Nueva cita</a>
        </div>
        
        <div class="buscador">
            <div>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search">
            </div>
        </div>
       
        <div class="cont">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-linkedin-in"></i></a>
            <a href=""><i class="fa-brands fa-tiktok"></i></a>
            <a href=""><i class="fa-brands fa-square-instagram"></i></a>    
        </div>
        
            
       
        
    </div>

 
 <h1>ODONTOLOGIA</h1>
 <div class="defin">
     <div class="foto">
         <img src="./IMG/tarj3.jpg" alt="">
     </div>
     <div class="texto">
         <p>
         Lorem ipsum dolor sit amet consectetur adipisicing elit.
          Commodi, ad minima? Culpa quidem, quos, libero sapiente 
          autem provident quo magnam, adipisci officiis sunt inventore 
         perspiciatis! Dolores dicta pariatur assumenda cupiditate?
         Lorem ipsum dolor sit amet consectetur adipisicing elit.
          Commodi, ad minima? Culpa quidem, quos, libero sapiente 
          autem provident quo magnam, adipisci officiis sunt inventore 
         perspiciatis! Dolores dicta pariatur assumenda cupiditate?
         </p>
         
     </div>
 </div>
 
 <div class="empleados">
     <div id="ont"><h1>DOCTORES</h1></div>
     
     <table border="1">
         <thead>
             <tr>
                 <th class="sem">SEMBLANTE</th>
                 <th>NOMBRE</th>
                 <th>CITAS</th>    
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td><img src="./IMG/doc3.jpg" alt=""></td>
                 <td><p>Anastasia Trinidad</p></td>
                 <td class="p"><a href="./citOnto.html">ENTRAR...</a></td>
                 
             </tr>
             <tr>
                 <td><img src="./IMG/doc4.jpg" alt=""></td>
                 <td> <p> Plastico Pablo</p></td>
                 <td class="p"><a href="./citOntohtml">ENTRAR...</a></td>
                 
             </tr>
         </tbody>
     </table>
 </div> 
 `;

const ortopedia=`
<div class=" header">
        <!--Logo PLAyANA-->
        <div class="logo">
            <img src="./IMG/logoOro.jpg" alt="">
        </div>
        <!--Enlaces laterales izquierdos-->
        <div class="enlaces">
            <a href="./formservis.html"><i class="fa-solid fa-circle-arrow-left"></i></a>
            <a href="./index.html"><i class="fa-solid fa-house"></i> Inicio</a>
            <a href="./citOrto.html"><i class="fa-solid fa-calendar-check"></i> Citas</a>
            <a href="./newCita.html"><i class="fa-solid fa-file-pen"></i> Nueva cita</a>
            
        </div>
        <!--Buscador-->
        <div class="buscador">
            <div>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search">
            </div>
        </div>
        <!--Iconos de contactos-->
        <div class="cont">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-linkedin-in"></i></a>
            <a href=""><i class="fa-brands fa-tiktok"></i></a>
            <a href=""><i class="fa-brands fa-square-instagram"></i></a>    
        </div>
        
    </div>

    <h1>ORTOPEDIA</h1>
     <div class="ortopedia">  
     <div class="defin">
         <div class="foto">
             <img src="./IMG/tarj1.jpg" alt="">
         </div>
         <div class="texto">
             <p>
             Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Commodi, ad minima? Culpa quidem, quos, libero sapiente 
              autem provident quo magnam, adipisci officiis sunt inventore 
             perspiciatis! Dolores dicta pariatur assumenda cupiditate?
             Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Commodi, ad minima? Culpa quidem, quos, libero sapiente 
              autem provident quo magnam, adipisci officiis sunt inventore 
             perspiciatis! Dolores dicta pariatur assumenda cupiditate?
             </p>
             
         </div>
     </div>
   
     <div class="empleados">
         <h1>DOCTORES</h1>
         <table border="1">
             <thead>
                 <tr>
                     <th class="sem">SEMBLANTE</th>
                     <th>NOMBRE</th>
                     <th>CITAS</th>    
                 </tr>
             </thead>
             <tbody id="infor">
 
             </tbody>
             
             <tr>
                 <td><img src="./IMG/doc1.jpg" alt=""></td>
                 <td><p>Trinidad Maye Bokuy</p></td>
                 <td class="p"><a href="./citOrto.html">ENTRAR...</a></td>
                 
             </tr>
             <tr>
                 <td><img src="./IMG/doc2.jpg" alt=""></td>
                 <td> <p>Pablo Ndong</p></td>
                 <td class="p"><a href="./citOrto.html">ENTRAR...</a></td>
                 
             </tr>
         </table>
     </div>
     </div>
`;
const obstetria=`
 <div class=" header">
        <!--Logo PLAyANA-->
        <div class="logo">
            <img src="./IMG/logoOro.jpg" alt="">
        </div>
        
        <div class="enlaces">
            <a href="./formservis.html"><i class="fa-solid fa-circle-arrow-left"></i></a>
            <a href="./index.html"><i class="fa-solid fa-house"></i> Inicio</a>
            <a href="./citObste.html"><i class="fa-solid fa-calendar-check"></i> Citas</a>
            <a href="./newCita.html"><i class="fa-solid fa-file-pen"></i> Nueva cita</a>
        </div>
        
        
        <div class="buscador">
            <div>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search">
            </div>
        </div>
        
        <div class="cont">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-linkedin-in"></i></a>
            <a href=""><i class="fa-brands fa-tiktok"></i></a>
            <a href=""><i class="fa-brands fa-square-instagram"></i></a>    
        </div>
       
        
    </div>


    <!--CUERPO-->
    
        <h1>OBSTETRIA</h1>
        <div class="defin">
            <div class="foto">
                <img src="./IMG/tarj2.jpg" alt="">
            </div>
            <div class="texto">
                <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Commodi, ad minima? Culpa quidem, quos, libero sapiente 
                 autem provident quo magnam, adipisci officiis sunt inventore 
                perspiciatis! Dolores dicta pariatur assumenda cupiditate?
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                 Commodi, ad minima? Culpa quidem, quos, libero sapiente 
                 autem provident quo magnam, adipisci officiis sunt inventore 
                perspiciatis! Dolores dicta pariatur assumenda cupiditate?
                </p>
                
            </div>
        </div>
        <!--Tabla con los doctores de Obstetria-->
        <div class="empleados">
            <div id="ont"><h1>DOCTORES</h1></div>
            
            <table border="1">
                <thead>
                    <tr>
                        <th class="sem">SEMBLANTE</th>
                        <th>NOMBRE</th>
                        <th>CITAS</th>    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="./IMG/farma2.jpg" alt=""></td>
                        <td><p>Anastasia Mangue Bokuy</p></td>
                        <td class="p"><a href="./citObste.html">ENTRAR...</a></td>
                        
                    </tr>
                    <tr>
                        <td><img src="./IMG/doc4.jpg" alt=""></td>
                        <td> <p> Plastico Nken Mba</p></td>
                        <td class="p"><a href="./citObste.html">ENTRAR...</a></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        
`;

 if(odonLs === "odontologia"){
    body.innerHTML= odontologia;
 }
 else if(ortoLs === "ortopedia"){
    body.innerHTML= ortopedia;
 }
 else if(obteLs === "obstetria"){
    body.innerHTML= obstetria;;
 }




 
 

 