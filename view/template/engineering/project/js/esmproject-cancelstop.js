$(document).ready(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
});

//����֤
function checkform(){
    if($("#description").val() == ""){
        alert('����дȡ��˵��');
        return false;
    }
	//���ȷ��--------------------------/
	return confirm('ȷ��Ҫȡ����ͣ��');
}