// Variables

let date = new Date;
const charg_bef = date.getTime();

let firstIMG= document.querySelector(".search-box-pic");

// Fonctions
function delayPageLoad()
{
    let date = new Date;
    let charg_aft = date.getTime();
    let ch = "La page a été chargée en " + (charg_aft - charg_bef) / 1000 + " secondes 🚀";
    console.log(ch);
}

window.addEventListener("DOMContentLoaded", function (){
    delayPageLoad();
})
