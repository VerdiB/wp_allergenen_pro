var button = document.getElementById("ictoria-filter-dropdown-button");

jQuery(document).ready(function ($) {
  $(document).on("change", ".allergen_icon_file_input", function () {
    // Identify the closest '.item' container to get the corresponding image
    const itemContainer = $(this).closest(".item"); // Adjust to match the row/container class
    const imgElement = itemContainer.find(".allergen_icon_img");

    if (imgElement.length === 0) {
      return;
    }
    readURL(this, imgElement);
  });

  $(document).on("change", ".allergen_icon_file_input", function () {
    const itemRow = $(this).closest('.item-row');
    const itemHeader = itemRow.find('.item-header');
    const addImgElement = itemHeader.find('.add_allergen_icon_img');

    if (addImgElement.length === 0) {
      return;
    }
    readURL(this, addImgElement);
  });
});
