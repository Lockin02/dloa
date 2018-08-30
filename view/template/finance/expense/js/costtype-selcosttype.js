$(function(){
	var data=$.ajax({
		url : '?model=finance_expense_expense&action=getCostType',
		type : 'get',
		async : false
	}).responseText;

	$("#expenseTree").html("<div id='costTypeInner2'>" + data + "</div>");
	var costTypeId=$("#costTypeId").val();
	if(costTypeId!='')
		$("#chk"+costTypeId).attr("checked","checked").parent().css('color','blue');
	//延时调用排序方法
	setTimeout(function(){
		initMasonry();
		if(checkExplorer() == 1){
			$("#costTypeInner2").height(580).css("overflow-y","scroll");
		}
	},200);
	initMasonry();
});

//回调父窗口方法
function setCustomCostType(id,obj){
	window.parent.fillFeeType(id,$(obj));
	$(':checkbox').each(function(){
		$(this).attr("checked",false);
		$("#chk"+$(this).val()).parent().css('color','#4C4C4C');
	});
	$(obj).attr("checked",'checked').parent().css('color','blue');
	$("#chk"+$("#costTypeId").val()).parent().css('color','blue');
}
