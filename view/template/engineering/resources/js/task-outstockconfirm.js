$(document).ready(function() {
	//邮件接收人渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'task'
	});

	// 实例化邮寄公司
	$("#expressName").yxcombogrid_logistics({
		hiddenId : 'expressId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});
});

//提交时验证借用数量
function checkForm(){
	for(var i=1;i<=$("#itemCount").val();i++){
		var amount = $("#amount"+i).val();
		var canBorrow = $("#canBorrow"+i).val();
		if(canBorrow > 1 && (!isNum(amount) || accSub(amount,canBorrow) > 0)){
			alert("借用数量输入有误：请输入1~"+canBorrow+"的正整数");
			$("#amount"+i).focus();
			return false;
		}
	}
}