$(document).ready(function() {
	//�ʼ���������Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'task'
	});

	// ʵ�����ʼĹ�˾
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

//�ύʱ��֤��������
function checkForm(){
	for(var i=1;i<=$("#itemCount").val();i++){
		var amount = $("#amount"+i).val();
		var canBorrow = $("#canBorrow"+i).val();
		if(canBorrow > 1 && (!isNum(amount) || accSub(amount,canBorrow) > 0)){
			alert("����������������������1~"+canBorrow+"��������");
			$("#amount"+i).focus();
			return false;
		}
	}
}