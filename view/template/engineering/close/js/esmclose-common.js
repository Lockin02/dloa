/**
 * Created by show on 2015/2/9.
 */

//��Ŀ��Ա����ҳ��
function showMemberList(projectId) {
	var url = "?model=engineering_member_esmmember&action=closeList&projectId=" + projectId;
	showModalWin(url, 1);
}

//��Ŀ�ĵ��༭ҳ��
function showFileEdit(projectId) {
	var url = "?model=engineering_file_esmfile&action=pageForProject&projectId=" + projectId;
	showModalWin(url, 1);
}

// ����֤
function checkForm() {
	var objGrid = $("#closeRules");

	// �ӱ���֤
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
						alert('�����Ҳ���ı�������д�����������');
						isAllDeal = false;
						return false;
					} else {
						objGrid.yxeditgrid("getCmpByRowAndCol", rowNum, "status").val(1);
					}
				} else {
					console.log(rowNum);
					alert('����δ������ɵĹرչ�������ִ����ز���');
					isAllDeal = false;
					return false;
				}
			}else {
				if(ruleIdObj.val() == 7 && msgObj.text() != '�����'){
					var confirmResult = confirm(msgObj.text()+" �����ȡ�������ػ�ȷ���������ύ��");
					isAllDeal = confirmResult;
					return isAllDeal;
				}
			}
		}
	});
	return isAllDeal;
}