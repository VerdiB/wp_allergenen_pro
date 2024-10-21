console.log("new js");

var button = document.getElementById("dropdown-ictoria");
var quickEdit = document.querySelectorAll(".quick_edit");

window.onload = function() {
    if (checkElementExists('dropdown-ictoria') == true ){
    document.getElementById("allergens-ictoria").style.display = "none";
    }
}

function checkElementExists(id) {
    var element = document.getElementById(id);
    if (element) {
        console.log("Het element met ID '" + id + "' bestaat.");
        return true; 
    } else {
        console.log("Het element met ID '" + id + "' bestaat niet.");
        return false; 
    }
}

function dropdown_form(){
    if ( document.getElementById("allergens-ictoria").style.display == "none"){
        document.getElementById("allergens-ictoria").style.display = "block";
    } else{
        document.getElementById("allergens-ictoria").style.display = "none";
    }
}

function quick_edit(form){
    var field = form.querySelector('.update_form');
    var selectdropdown = field.querySelector('.type');
    var allselectdropdownoptions = selectdropdown.querySelectorAll('.option');
    var allinputs = field.querySelectorAll('.update_');

    if (form.style.display == "none"){
    allinputs.forEach(input => {
        input.disabled = false;
    });

   allselectdropdownoptions.forEach(option => {
        option.disabled = false;
    });

    form.style.display = 'block';
}else{
    allinputs.forEach(input => {
        input.disabled = true;
    });

   allselectdropdownoptions.forEach(option => {
        option.disabled = true;
    });

    form.style.display = 'none';
}
}

document.getElementById('the-list').addEventListener('click', function(event) {
    if (event.target.classList.contains('quick_edit')) {
        var quick_edit = event.target.classList.contains('quick_edit')
        
        if (quickEdit.disabled !== true){
            var form = event.target.id + "_form";
            var get_form = document.getElementById(form);
            quick_edit(get_form);
        }
    }
});

if (checkElementExists('dropdown-ictoria') == true ){
    button.addEventListener("click", dropdown_form);
}