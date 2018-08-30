$(function(){
	// 返回按钮处理	
    $('.ui-icon-return').click(function () {
		window.location.href = $("#backUrl").val();
    });

    $("#form").workflow({
        type: "view",
        pid: $("#pid").val(),
        itemType: $("#itemType").val()
    });
});