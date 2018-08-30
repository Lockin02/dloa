
var show_page = function(page) {
	$("#customerGrid").yxgrid_customer("reload");
};
$(function() {
	$("#customerGrid").yxgrid_customer({
		customCode:'allCustomer'
	});
});