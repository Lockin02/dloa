$(function() {

});

//单点登录测试
function testSignIn(){
	showOpenWin($("#urlTxt").val());
};

//webservice测试
function testWebService(){
	//显示
	showLoading();
	$.ajax({
		type : "POST",
		url : "?model=common_otherdatas&action=linkWebService",
		success : function(msg) {
			if (msg == 'success') {
				$("#webShow").html('更新成功');
			}else{
				$("#webShow").html(msg);
			}
			//隐藏
			hideLoading();
		}
	});
};

//报销数据同步
function esmproject(thisVal){
	//显示
	showLoading();
	$.ajax({
		type : "POST",
		url : "?model=common_otherdatas&action=updateesmproject",
		data : {'thisVal' : thisVal},
		success : function(msg) {
			if (msg == 'success') {
				$("#esmprojectShow" + thisVal).html('更新成功');
			}else{
				$("#esmprojectShow" + thisVal).html(msg);
			}
			//隐藏
			hideLoading();
			$("#esmproject"+thisVal).hide();
		}
	});
}

//显示
function showLoading(){
	$("#loading").show();
	$(".txt_btn_a").attr("disable",true);
}

//隐藏
function hideLoading(){
	$("#loading").hide();
	$(".txt_btn_a").attr("disable",false);
}