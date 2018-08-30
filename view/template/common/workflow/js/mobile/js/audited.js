$(function(){
	// 返回按钮处理	
    $('.ui-icon-return').click(function () {
		window.location.href = $("#backUrl").val();
    });
    $('#returnBtn').click(function () {
        window.location.href = $("#backUrl").val();
    });
});