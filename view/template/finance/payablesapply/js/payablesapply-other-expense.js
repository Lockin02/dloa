$(function(){
	var data=$.ajax({
		url : '?model=finance_expense_expense&action=getCostType',
		type : 'get',
		async : false
	}).responseText;
	
	$("#expenseTree").html("<div id='costTypeInner2'>" + data + "</div>");
	var feeTypeId=$("#feeTypeId").val();
	if(feeTypeId!='')
		$("#chk"+feeTypeId).attr("checked","checked").parent().css('color','blue');
	//��ʱ�������򷽷�
	setTimeout(function(){
		initMasonry();
		if(checkExplorer() == 1){
			$("#costTypeInner2").height(580).css("overflow-y","scroll");
		}
	},200);
	initMasonry();

});
//�ص������ڷ���
function setCustomCostType(id,obj){
	window.parent.fillFeeType(id,$(obj).attr('name'));
	$(':checkbox').each(function(){
		   $(this).attr("checked",false);
		   $("#chk"+$(this).val()).parent().css('color','#4C4C4C');
		  });  
	$(obj).attr("checked",'checked').parent().css('color','blue');
	$("#chk"+$("#feeTypeId").val()).parent().css('color','blue');
}
