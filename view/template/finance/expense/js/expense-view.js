$(function() {
	//费用归属
	$("#costbelong").costbelong({
		objName : 'expense',
		url : '?model=finance_expense_expense&action=ajaxGet',
		data : {"id" : $("#id").val()},
		actionType : 'view'
	});
	//回到顶部
	$.scrolltotop({className: 'totop'});

	//渲染延迟报销申请
	if($("#isLate").val() == "1"){
		$("#mainTitle").css('color','red').html('【费用延期报销】');
	}
});