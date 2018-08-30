$(function() {	
//初始化，默认显示未报销，status为1
	init(1);
});
//刷新
var show_page = function(page) {
	init(1);
	self.opener.show_page(1);
};
//初始化函数
function init(status){
	var projectId = $("#projectId").val();
	//实例化详细费用报销
    var costDetailObj = $("#costDetail");
    $.ajax({
    	type : "POST",
    	url : "?model=engineering_cost_esmcostdetail&action=listHtml",
    	data: {"projectId" : projectId,"status" : status},
    	success : function(data) {
			if (data) {
				costDetailObj.empty().append(data);
				$("#status").val(status);
				//初始化金额
				formateMoney();
			}
		}
    });
}
//checkbox全选
function selectAllCheck(){
	$("input.subCheck").each(function(){
		this.checked=$("#mainCheck").attr("checked");
	}); 
 }
//生成报销单
function toEsmExpenseAdd(){
	var dateArr = [];//选择的报销单日期
	$("input.subCheck").each(function(){
		 if(this.checked){
			 dateArr.push($(this).parents(".tr_even").find(".executionDate").html());
		 }
	 }); 
	//报销单生成页面
	if(dateArr.length > 0){
		showModalWin("?model=finance_expense_expense&action=toEsmExpenseAdd&days="
			+ dateArr.toString()
			+ "&projectId="
			+ $("#projectId").val()
		);
	}else{
		alert('请先选择记录');
	}
}
//改变报销状态
function changeStatus(){
	init($("#status").val());
}