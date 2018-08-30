$(function(){
	$("#userName").yxselect_user({
		hiddenId : 'userCode',
		mode : "check",
		event : {
			"select" : function(obj, row) {
				authorizeList();
			}
		}
	});
	validate({
		"userName" : {
			required : true
		}
	})
});

/**
 * 动态配置合同权限配置列表
 */
function authorizeList() {
	var userCode = $("#userCode").val();
	var userName = $("#userName").val();
	$.ajax({
		type : 'POST',
		url : '?model=contractTool_contractTool_authorize&action=checkUser',
		data : {
			userCode : userCode,
			userName : userName
		},
		async : false,
		success : function(data) {
			if(data == "0"){
				alert("该用户已经赋予权限");
			}
		}
	});
}



function check(){
	if($('#userName').val()==''){
		alert('请指定用户名！');
		return false;
	}
}

//判断是否赋权
function sub(){
	$("form").bind("submit", function() {
		var cl = $("input:checkbox:checked").length;
		if(cl == 0){
			alert("请选择需要赋予的权限");
			return false;
		}
	});
}