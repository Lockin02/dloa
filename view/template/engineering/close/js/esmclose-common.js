/**
 * Created by show on 2015/2/9.
 */

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

// 表单验证
function checkForm() {
	var objGrid = $("#closeRules");

	// 从表验证
	var isAllDeal = true;
	objGrid.yxeditgrid("getCmpByCol", "isCustom").each(function() {
		var rowNum = $(this).data("rowNum");
		if ($(this).val() == "0") {
			var status = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "status").val();
			var ruleIdObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "ruleId");
			var msgObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "val");
			if (status == "0") {
				var replyObj = objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "reply");
				if (replyObj.length > 0) {
					if (strTrim(replyObj.val()) == "") {
						alert('请在右侧的文本框中填写相关书面描述');
						isAllDeal = false;
						return false;
					} else {
						objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "status").val(1);
					}
				} else {
					console.log(rowNum);
					alert('含有未处理完成的关闭规则，请先执行相关操作');
					isAllDeal = false;
					return false;
				}
			}else {
				if(ruleIdObj.val() == 7 && msgObj.text() != '已完成'){
					var confirmResult = confirm(msgObj.text()+" 点击【取消】返回或【确定】继续提交。");
					isAllDeal = confirmResult;
					return isAllDeal;
				}
			}
		}
	});
	return isAllDeal;
}