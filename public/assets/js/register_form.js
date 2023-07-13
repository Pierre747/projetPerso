window.addEventListener("DOMContentLoaded", function (){
    console.log('adffsefs')
    let registerField = document.querySelector("#client_form > div:nth-child(5) input");

    function logueur(){
        console.log("Clicked")
    }
    registerField.addEventListener("click", logueur);

})