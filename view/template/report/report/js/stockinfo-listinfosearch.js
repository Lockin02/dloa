function toSupport() {
    var budgetTypeName=$("#budgetTypeName").val();
	var budgetTypeId=$("#budgetTypeId").val();
	var brand=$("#brand").val();
	var equName=$("#equName").val();
	var equId=$("#equId").val();
//	var netWork=$("#netWork").attr("checked");
//	var software=$("#software").attr("checked");
	var isStop=$("#isStop").val();
	var dataType = $("#dataType").val();
	var netWork="";
	var software="";
	$("input[name='newWorkStr']:checkbox").each(function(){
        if($(this).attr("checked")){
        	netWork += $(this).val()+",";
        }
    });
	$("input[name='softWareStr']:checkbox").each(function(){
        if($(this).attr("checked")){
        	software += $(this).val()+",";
        }
    });
	this.opener.location = "?model=report_report_stockinfo&action=reportView"
		+"&budgetTypeName="+budgetTypeName
		+"&budgetTypeId="+budgetTypeId
		+"&brand="+brand
		+"&equName="+equName
		+"&equId="+equId
		+"&netWork="+netWork
		+"&software="+software
		+"&isStop="+isStop
		+"&isSearch=1"
		+"&dataType="+dataType
	this.close();
}

function refresh(){
	$('.toClear').val("");
	$("input[name='netWork']:checkbox").each(function(){
		$(this).attr("checked",false);
	});
	$("input[name='software']:checkbox").each(function(){
		$(this).attr("checked",false);
	});
}

$(function(){
	$("#budgetTypeName").yxcombotree({
		hiddenId : 'budgetTypeId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=equipment_budget_budgetType&action=getTreeData"
		}
	});
   $('select[name="isStop"] option').each(function() {
				if( $(this).val() == $("#isStopTemp").val() ){
					$(this).attr("selected","selected'");
				}
			});
});