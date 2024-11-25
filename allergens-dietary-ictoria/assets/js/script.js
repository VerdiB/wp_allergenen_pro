console.log("connected");
var button = document.getElementById("ictoria-filter-dropdown-button");
var quickEdit = document.querySelectorAll(".quick_edit");

function checkElementExists(id) {
  var element = document.getElementById(id);
  if (element) {
    return element;
  }
}

function quickedit(form) {
  var field = form.querySelector(".update_form");
  var selectdropdown = field.querySelector(".type");
  var allselectdropdownoptions = selectdropdown.querySelectorAll(".option");
  var allinputs = field.querySelectorAll(".update_");
  var counter = 0;
  var thelist = document.getElementById("the-list");

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
    allinputs.forEach((input) => {
      input.disabled = true;
    });

    allselectdropdownoptions.forEach((option) => {
      option.disabled = true;
    });

    form.style.display = "none";
  }
}

if (checkElementExists("the-list")) {
  document
    .getElementById("the-list")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("quick_edit")) {
        var quickEditing = event.target;
        if (quickEditing.style.pointerEvents == "auto") {
          var form = event.target.id + "_form";
          var get_form = document.getElementById(form);
          quickedit(get_form);
        }
      }
    });
}

function confirmResetInput() {
  if (confirm("Are you sure you want to reset the form?")) {
    location.reload(true);
  }
  return;
}

jQuery(document).ready(function ($) {
  if (checkElementExists("ictoria-filter-dropdown")) {
    $(document).on("click", "#ictoria-filter-dropdown-button", dropdown_form);
  }

  function dropdown_form() {
    if (
      document.getElementById("ictoria-filter-dropdown").style.display == "none"
    ) {
      document.getElementById("ictoria-filter-dropdown").style.display =
        "block";
    } else {
      document.getElementById("ictoria-filter-dropdown").style.display = "none";
    }
  }

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
    const itemContainer = $(this).closest(".item"); // Adjust to match the row/container class
    const imgElement = itemContainer.find(".allergen_icon_img");

    if (imgElement.length === 0) {
      // console.log("No corresponding image found in the same container.");
      return;
    }
    readURL(this, imgElement);
  });
});
