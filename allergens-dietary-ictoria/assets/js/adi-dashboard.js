jQuery(document).ready(function () {
	//WordPress does not allow the use of $ in js files without changes to some settings. Instead of doing that we add the jQuery command to a local variable.
	let $j = jQuery.noConflict()

	console.log("hello from dashboard")
})
