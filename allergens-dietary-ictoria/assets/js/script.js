console.log("new js");

var button = document.getElementById("dropdown-ictoria");

window.onload = function() {
    document.getElementById("allergens-ictoria").style.display = "none";
}

function dropdown_form(){
    if ( document.getElementById("allergens-ictoria").style.display == "none"){
        document.getElementById("allergens-ictoria").style.display = "block";
    } else{
        document.getElementById("allergens-ictoria").style.display = "none";
    }
}

button.addEventListener("click", dropdown_form);