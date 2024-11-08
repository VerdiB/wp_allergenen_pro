var actionSchedulerError = document.querySelectorAll(".notice-error");
var actionSchedulerWarning = document.querySelectorAll(".notice-warning");
var actionSchedulerSucces = document.querySelectorAll(".notice-success");

actionSchedulerError.forEach(error => {
    error.style.backgroundColor = "red";
});

actionSchedulerWarning.forEach(warning => {
    warning.style.backgroundColor = "orange";
});

actionSchedulerSucces.forEach(succes => {
    succes.style.backgroundColor = "green";
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

