var button = document.getElementById("dropdown-ictoria");
var quickEdit = document.querySelectorAll(".quick_edit");

window.onload = function () {
  if (checkElementExists("dropdown-ictoria") == "dropdown-ictoria") {
    document.getElementById("allergens-ictoria").style.display = "none";
  }
};

function checkElementExists(id) {
  var element = document.getElementById(id);
  if (element) {
    return element;
  }
}

function dropdown_form() {
  if (document.getElementById("allergens-ictoria").style.display == "none") {
    document.getElementById("allergens-ictoria").style.display = "block";
  } else {
    document.getElementById("allergens-ictoria").style.display = "none";
  }
}

function quickedit(form) {
  var field = form.querySelector(".update_form");
  var tehrightelement1 = document.getElementById("the-list");
  var tehrightelement = tehrightelement1.querySelectorAll(".inline-edit-row");
  var element = document.getElementById("allergy_name");
  var element5 = document.getElementById("allergy_description");
  var element6 = document.getElementById("is_allergy");
  var element7 = document.getElementById("is_active");
  tehrightelement.forEach((input) => {
  var element2 = input.querySelector(".allergy_description");
  var element3 = input.querySelector(".is_allergy");
  var element4 = input.querySelector(".is_active");
  element2.style.display = "none";
  element3.style.display = "none";
  element4.style.display = "none";
});
  element.style.width = "100%";
  element5.style.display = "none";
  element6.style.display = "none";
  element7.style.display = "none";
  var selectdropdown = field.querySelector(".type");
  var allselectdropdownoptions = selectdropdown.querySelectorAll(".option");
  var allinputs = field.querySelectorAll(".update_");
  var counter = 0;
  var thelist = document.getElementById("the-list");
  var manage = document.querySelectorAll(".manage-column");
  manage.forEach((managecolumn) => {
  console.log(managecolumn.parentElement.parentElement);
  if (managecolumn.tagName !== "TD"){
    if (managecolumn.textContent !== "Allergy name" && managecolumn.parentElement.parentElement.tagName == "TFOOT"){
      managecolumn.style.display = "none";
    }
  }
  });

  if (form.style.display == "none") {
    if (thelist) {
      var trs = thelist.querySelectorAll("tr");

      trs.forEach((tr) => {
        var closeforms = tr.querySelector(".allergens_table_form");

        if (closeforms) {
          closeforms.style.display = "none";
          var closefield = closeforms.querySelector(".update_form");
          var closeselectdropdown = closefield.querySelector(".type");
          var closeallselectdropdownoptions =
            closeselectdropdown.querySelectorAll(".option");
          var closeallinputs = closefield.querySelectorAll(".update_");

          if (closeforms.style.display == "none") {
            closeallinputs.forEach((closeinput) => {
              closeinput.disabled = true;
            });

            closeallselectdropdownoptions.forEach((closeoption) => {
              closeoption.disabled = true;
            });
          }
        }
      });
    }

    if (counter > 0) {
      closeforms.forEach((form) => {
        form.style.display = false;
      });
    }

    allinputs.forEach((input) => {
      input.disabled = false;
    });

    allselectdropdownoptions.forEach((option) => {
      option.disabled = false;
    });

    form.style.display = "block";
  } else {
    var tehrightelement1 = document.getElementById("the-list");
    var tehrightelement = tehrightelement1.querySelectorAll(".inline-edit-row");
    var element = document.getElementById("allergy_name");
    var element5 = document.getElementById("allergy_description");
    var element6 = document.getElementById("is_allergy");
    var element7 = document.getElementById("is_active");

    element.style.width = "";
    element5.style.display = "table-cell";
    element6.style.display = "table-cell";
    element7.style.display = "table-cell";
    tehrightelement.forEach((input) => {
    var element2 = input.querySelector(".allergy_description");
    var element3 = input.querySelector(".is_allergy");
    var element4 = input.querySelector(".is_active");
    element2.style.display = "table-cell";
    element3.style.display = "table-cell";
    element4.style.display = "table-cell";
  });

  var manage = document.querySelectorAll(".manage-column");
  manage.forEach((managecolumn) => {
  console.log(managecolumn.parentElement.parentElement);
  if (managecolumn.tagName !== "TD"){
    if (managecolumn.textContent !== "Allergy name" && managecolumn.parentElement.parentElement.tagName == "TFOOT"){
      managecolumn.style.display = "table-cell";
    }
  }
  });

    allinputs.forEach((input) => {
      input.disabled = true;
    });

    allselectdropdownoptions.forEach((option) => {
      option.disabled = true;
    });

    form.style.display = "none";
  }
}

document.getElementById("the-list").addEventListener("click", function (event) {
  if (event.target.classList.contains("quick_edit")) {
    var quickEditing = event.target;
    if (quickEditing.style.pointerEvents == "auto") {
      var form = event.target.id + "_form";
      var get_form = document.getElementById(form);
      quickedit(get_form);
    }
  }
});

if (checkElementExists("dropdown-ictoria") == true) {
  button.addEventListener("click", dropdown_form);
}

jQuery(document).ready(function ($) {
  function readURL(input, imgElement) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(imgElement).attr("src", e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  // Listen for changes on any file input with the class 'allergen_icon_file_input'
  $(document).on("change", ".allergen_icon_file_input", function () {
    // Identify the closest '.item' container to get the corresponding image
    const itemContainer = $(this).closest('.item'); // Adjust to match the row/container class
    const imgElement = itemContainer.find('.allergen_icon_img');

    if (imgElement.length === 0) {
      // console.log("No corresponding image found in the same container.");
      return;
    }
    readURL(this, imgElement);
  });
});


