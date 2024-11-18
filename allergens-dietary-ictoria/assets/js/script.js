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
  var selectdropdown = field.querySelector(".type");
  var allselectdropdownoptions = selectdropdown.querySelectorAll(".option");
  var allinputs = field.querySelectorAll(".update_");
  var counter = 0;
  var thelist = document.getElementById("the-list");
  var tehrightelement = field.parentElement.parentElement.parentElement;
  var allergy_name = tehrightelement.querySelector(".allergy_name");
  var allergy_name_text = allergy_name.querySelector(".allergen_name");
  var allergy_description = tehrightelement.querySelector(".allergy_description");
  var is_allergy = tehrightelement.querySelector(".is_allergy");
  var is_active = tehrightelement.querySelector(".is_active");
  allergy_name.colSpan = 3;
  allergy_description.style.display = "none";
  is_allergy.style.display = "none";
  is_active.style.display = "table-cell";
  allergy_name_text.style.display = "none";

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
    allergy_name.colSpan = 1;
    allergy_description.style.display = "table-cell";
    is_allergy.style.display = "table-cell";
    is_active.style.display = "table-cell";
    allergy_name_text.style.display = "block";

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


