jQuery(document).ready(function () {
	let $j = jQuery.noConflict()

	/* [P] Allergens & Dietary -> [S] Manage Allergens
	 * Toggle allergen active class
	 */
	$j(".iam-allergens-dietary-manage-allergens-all-allergens-allergen").on(
		"click",
		function (event) {
			$target = $j(event.target)

			// $j.ajax({
			// 	type: "POST",
			// 	url: "submit_data.php",
			// 	data:
			// 		"allergenName=" +
			// 		"peanuts" +
			// 		"&is_allergen_activated=" +
			// 		"is_allergen_activated",
			// 	success: function (html) {
			// 		alert("sucess!  Result is:" + html)
			// 	},
			// })

			if ($target.hasClass("iam-allergens-dietary-allergen-selected")) {
				$target.removeClass("iam-allergens-dietary-allergen-selected")
			} else {
				$target.addClass("iam-allergens-dietary-allergen-selected")
			}
		}
	)
})
