$(document).ready(function() {
	var ids='';
	$("input[name='leave[idchecked][]']").each(function(){
		if($(this).attr("checked")) {
			ids += $(this).val() + ',';
		}
	});
	ids = ids.substring(0 ,ids.length - 1);
	$("#userName").yxcombogrid_printleave({
		hiddenId : 'ids',
		height : 350,
		width:450,
		isFocusoutCheck : false,
		gridOptions : {
			param:{
				state:'1,2,3',
				spzt :'完成',
				comfirmQuitDateN : '1',
				idNotIn : ids
			},
			showcheckbox : true
		}
	});
	$("#listUserName").yxcombogrid_printleave({
		hiddenId : 'ids',
		height : 350,
		width:450,
		isFocusoutCheck : false,
		gridOptions : {
			param:{
				handoverId : '1',
				state:'1,2,3',
				idNotIn : ids
			},
			showcheckbox : true
		}
	});

});
function sub(){
	var checked='';
	$("input[name='leave[idchecked][]']").each(function() {
		if($(this).attr("checked")) {
			checked += $(this).val() + ',';
		}
	});
	var newadd = $("#ids").val();
	if(checked == '' && newadd == '') {
		alert("请选择打印数据！");
		return false;
	} else {
		self.parent.tb_remove();
	}
}