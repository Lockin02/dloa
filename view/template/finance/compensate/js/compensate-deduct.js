$(document).ready(function() {
	//����֤
	validate({
        "deductMoney_v": {
        	money: true
        }
	});
});

//�ύʱ��֤
function checkForm(){
	if($("#deductMoney").val()*1 > $("#remainingV").val()*1){
		alert("¼����ܴ���" + $("#remaining").val());
		return false;
	}
}