
function suma(x,y){
    alert("resultado de la suma: " + (x+y));
}
function resta(x,y){
return x-y;
}
//crear una funcion que muestre en consola una tabla de multiplicar indicada por el usuario asta el limite indicado por el usuario

function tablaM(x,y){
    console.log("Tabla del "+x)

    for(let i=1; i<=y;i++){
        console.log(i+" x "+x+" = "+(i*x));
          
    }
    
}
//DOM
function cambioTexto(){
    document.getElementById("text").innerHTML= "Magia";
    document.getElementById("caja").value="adios";
}