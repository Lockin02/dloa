function batchSet() {
	var newData = "";
	thisInvArr = new Array();
	self.parent.clearTable("invTable");
	$.each($(':checkbox[name^="datacb"]'), function() {
		if ($(this).attr('checked') == true) {
			newData = $(this).prev("input").val();
			self.parent.invAdd(newData);
		}
	});
	self.parent.tb_remove();
	self.parent.reload_cardTb();
}

function search(){
	var supplierId=$('#supplierId').val();
	var supplierName=$('#supplierName').val();
	var searchvalue=$('#searchvalue').val();
	var searchfield=$('#searchfield').val();
	var hookMainIds = $("#hookMainIds").val();
	var url = "?model=finance_invpurchase_invpurchase&action=instockHookPage&"
		+ searchfield + "="+searchvalue
		+ "&supplierId=" + supplierId
		+ "&supplierName=" + supplierName
		+ "&hookMainIds=" + hookMainIds
		;
	this.location = url;
}

//清空列表查询
function clearUrl(){
	var supplierId=$('#supplierId').val();
	var supplierName=$('#supplierName').val();
	var hookMainIds = $("#hookMainIds").val();
	var url = "?model=finance_invpurchase_invpurchase&action=instockHookPage"
		+ "&supplierId=" + supplierId
		+ "&supplierName=" + supplierName
		+ "&hookMainIds=" + hookMainIds
		;
	this.location = url;
}



$(function(){
	var hookArr= new Array();
	var hookMainIds = $("#hookMainIds").val();
	if(hookMainIds != ""){
		hookArr = hookMainIds.split(",");
	}
	for(var i = 0;i< hookArr.length ;i++){
		$("#" + hookArr[i]).attr("checked",true);
	}
});