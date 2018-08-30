
var show_page = function(page) {
	$("#linstincustomer").yxgrid("reload");
};
$(function() {
	$("#linstincustomer").yxgrid_linkman({
       action : 'linsinCustomerPageJson'
	});
});