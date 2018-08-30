var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};
$(function() {
	if($("#all").val()!="all"){
		$("#suppName").val($("#suppNameA").val());
		$("#suppName").attr("readonly","readonly"); 
		$("#suppName").attr("class","readOnlyTxtNormal"); 
	}
 });