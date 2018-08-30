$(document).ready(function() {

	$(".menu_menus li").each(function() {

		$(this).bind("mouseover", function() {
			if ($(this).attr("class") != "selected") {
				$(this).attr("class", "over");
			}
		});

		$(this).bind("mouseout", function() {
			if ($(this).attr("class") != "selected") {
				$(this).attr("class", " ");
			}
		});

		$(this).bind("click", function() {
			$(this).parent().find(".selected").attr("class", "");
			$(this).attr("class", "selected");
		});
	});

});