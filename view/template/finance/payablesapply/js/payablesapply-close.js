$(function() {
	//�ʼ�֪ͨ������
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID'
	});
});


//����֤
function checkform(){
	if(strTrim($("#closeReason").val()) == ""){
		alert('�ر�ԭ����Ϊ�գ�');
		return false;
	}
	if(confirm("ȷ��Ҫ�رո���������?")){
		return true;
	}else{
		return false;
	}
}