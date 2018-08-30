$(document).ready(function() {

});

//刷新列表
function show_page(){
	window.opener.show_page();
	window.close();
}

//刷新列表
function show_page2(){
	window.opener.show_page();
	location.reload();
}

//提交审批
function toAudit(){
	var isLate = $("#isLate").val();
	var id = $("#expenseId").val();
	var Amount = $("#Amount").val();
	var CheckAmount = $("#CheckAmount").val();
	var CostBelongDeptId = $("#CostBelongDeptId").val();

	//如果单据的报销金额和检查金额不一致，需要进行确认
	if(CheckAmount*1 != Amount*1){
		if(confirm('单据已经被修改，需要提交到报销人确认，现在就将单据提交确认吗？')){
			$.ajax({
				type : "POST",
				url : "?model=finance_expense_expense&action=handConfirm",
				data : {
					id : id
				},
				success : function(msg) {
					if (msg == '1') {
						alert('提交成功！');
						window.opener.show_page();
						window.close();
					}else{
						alert("提交失败! ");
					}
				}
			});
		}
	}else{
		if(isLate == "1"){
			showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
				+ id + '&flowMoney=' + Amount
				+ '&billDept=' + CostBelongDeptId
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
		}else{
			showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
				+ id + '&flowMoney=' + Amount
				+ '&billDept=' + CostBelongDeptId
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
		}
	}
}

//提交确认
function toHandConfirm(){
	var id = $("#expenseId").val();
	var Amount = $("#Amount").val();
	var CheckAmount = $("#CheckAmount").val();

	//如果单据的报销金额和检查金额不一致，需要进行确认
	if(CheckAmount*1 != Amount*1){
		if(confirm('确认将单据提交确认吗？')){
			$.ajax({
				type : "POST",
				url : "?model=finance_expense_expense&action=handConfirm",
				data : {
					id : id
				},
				success : function(msg) {
					if (msg == '1') {
						alert('提交成功！');
						window.opener.show_page();
						window.close();
					}else{
						alert("提交失败! ");
					}
				}
			});
		}
	}else{
		alert('单据金额没有发生变更，不需要提交确认');
	}
}

//打回
function toBack(){
	if(!confirm('确定要打回单据吗？')) return false;

	var id = $("#expenseId").val();
	$.ajax({
		type : "POST",
		url : "?model=finance_expense_expense&action=ajaxBack",
		data : {
			id : id
		},
		success : function(msg) {
			if (msg == '1') {
				alert('打回成功！');
				show_page();
			}else{
				alert("打回失败! ");
			}
		}
	});
}

//查看日志
function showWorklog(worklogId){
	var costdetailId = $("#costdetailId" + worklogId).val();
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_worklog_esmworklog&action=md5RowAjax",
	    data: { "id" : worklogId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId +"&skey=" + skey + "&costdetailId="+ costdetailId ,1,750,1150);
}

//修改明细
function editWorklog(worklogId){
	var costdetailId = $("#costdetailId" + worklogId).val();
	var expenseId = $("#expenseId").val();
	var allcostdetailId = $("#allcostdetailId").val();
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_worklog_esmworklog&action=md5RowAjax",
	    data: { "id" : worklogId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_worklog_esmworklog&action=toCheckEdit&id=" + worklogId +"&skey=" + skey + "&costdetailId="+ costdetailId + "&expenseId=" + expenseId + "&allcostdetailId=" + allcostdetailId,1,750,1150);
}