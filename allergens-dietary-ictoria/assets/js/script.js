
var actionSchedulerError = document.querySelectorAll(".notice-error");
var actionSchedulerWarning = document.querySelectorAll(".notice-warning");
var actionSchedulerSucces = document.querySelectorAll(".notice-success");


actionSchedulerError.forEach(error => {
    error.style.backgroundColor = "grey"; 
    error.style.setProperty("background-color", "white", "important"); 
    error.style.borderLeft = "5px solid red"; 
    error.style.setProperty("border-left", "5px solid red", "important"); 
    error.style.paddingLeft = "10px"; 
    error.style.color = "black"; 
});

actionSchedulerWarning.forEach(warning => {
    warning.style.setProperty("background-color", "white", "important"); 
    warning.style.setProperty("border-left", "5px solid orange", "important"); 
    warning.style.paddingLeft = "10px";
    warning.style.color = "black";
});

actionSchedulerSucces.forEach(succes => {
    succes.style.setProperty("background-color", "white", "important"); 
    succes.style.setProperty("border-left", "5px solid green", "important"); 
    succes.style.paddingLeft = "10px";
    succes.style.color = "black";
});



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

