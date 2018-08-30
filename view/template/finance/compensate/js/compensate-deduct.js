$(document).ready(function() {
	//表单验证
	validate({
        "deductMoney_v": {
        	money: true
        }
	});
});

//提交时验证
function checkForm(){
	if($("#deductMoney").val()*1 > $("#remainingV").val()*1){
		alert("录入金额不能大于" + $("#remaining").val());
		return false;
	}
}