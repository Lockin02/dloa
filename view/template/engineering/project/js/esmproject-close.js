$(document).ready(function() {

});

//����֤
function checkform() {
	var projectId = $("#id").val();
	var str = "";
	var i = 0;
	var rtVal = true;
	//�ر���֤����----------------------/

	//��Ŀ��Ա��Ϣ
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
				str += i + ".����δ¼���뿪���ڵ���Ŀ��Ա�����" +
				"<a href='javascript:void(0)' onclick='showMemberList(" + projectId + ")'>����</a>¼�������Ϣ<br/>"
				;
				rtVal = false;
			}
		}
	});

	//�ж���Ŀ�ĵ��Ƿ���ڱ����ύ���ĵ���δ�ύ
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
				str += i + ".����δ�ϴ��ĸ��������" +
				"<a href='javascript:void(0)' onclick='showFileEdit(" + projectId + ")'>����</a>¼�������Ϣ<br/>"
				;
				rtVal = false;
			}
		}
	});

	//��ʾ��Ϣ����
	$("#closeInfo").html(str);

	//�豸����

	//���ȷ��--------------------------/
	return rtVal == true && confirm('��Ŀ�رպ�����������,ȷ�Ϲر���Ŀ��');
}

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