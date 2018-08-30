$(document).ready(function() {

});

//表单验证
function checkform() {
	var projectId = $("#id").val();
	var str = "";
	var i = 0;
	var rtVal = true;
	//关闭验证加载----------------------/

	//项目成员信息
	$.ajax({
		type: "POST",
		url: "?model=engineering_member_esmmember&action=checkMemberAllLeave",
		data: {
			projectId: projectId
		},
		async: false,
		success: function(msg) {
			if (msg == 1) {
				i++;
				str += i + ".存在未录入离开日期的项目成员，点击" +
				"<a href='javascript:void(0)' onclick='showMemberList(" + projectId + ")'>这里</a>录入相关信息<br/>"
				;
				rtVal = false;
			}
		}
	});

	//判断项目文档是否存在必须提交的文档但未提交
	$.ajax({
		type: "POST",
		url: "?model=engineering_file_esmfiletype&action=checkFileSubmit",
		data: {
			projectId: projectId
		},
		async: false,
		success: function(msg) {
			if (msg == 1) {
				i++;
				str += i + ".存在未上传的附件，点击" +
				"<a href='javascript:void(0)' onclick='showFileEdit(" + projectId + ")'>这里</a>录入相关信息<br/>"
				;
				rtVal = false;
			}
		}
	});

	//提示信息插入
	$("#closeInfo").html(str);

	//设备部分

	//最后确认--------------------------/
	return rtVal == true && confirm('项目关闭后不能重新启动,确认关闭项目吗？');
}

//项目成员更新页面
function showMemberList(projectId) {
	var url = "?model=engineering_member_esmmember&action=closeList&projectId=" + projectId;
	showModalWin(url, 1);
}

//项目文档编辑页面
function showFileEdit(projectId) {
	var url = "?model=engineering_file_esmfile&action=pageForProject&projectId=" + projectId;
	showModalWin(url, 1);
}