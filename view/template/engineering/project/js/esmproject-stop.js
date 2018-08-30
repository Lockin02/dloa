$(document).ready(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
});

//表单验证
function checkform(){
    if($("#description").val() == ""){
        alert('请填写暂停说明');
        return false;
    }
	//最后确认--------------------------/
	return confirm('确定要暂停项目吗？');
}