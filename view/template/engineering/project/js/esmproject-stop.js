$(document).ready(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
});

//����֤
function checkform(){
    if($("#description").val() == ""){
        alert('����д��ͣ˵��');
        return false;
    }
	//���ȷ��--------------------------/
	return confirm('ȷ��Ҫ��ͣ��Ŀ��');
}