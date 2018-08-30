$(function(){
	// sid
	var sid = $("#sid").val();
	// formName
	var formName = $("#formName").val();
	
	// 返回按钮处理	
    $('.ui-icon-return').click(function () {
		location.href = $("#backUrl").val();
        // location.href = './w?sid=' + sid + '&cmd=com.youngheart.apps.mobile.workflow_page&formName=' + formName;
    });

	$("#form").workflow({
		type: "audit",
		form: formName,
		spid: $("#spid").val(),
		sid: sid
	});
});