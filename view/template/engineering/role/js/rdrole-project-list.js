$(document).ready(function() {
			// Tab��ͷ
			//topTabShow(arrayTop, "pjTeam", "{engineering[id]}");
			// �����꾭������ɫ�ı�
			rowsColorChange();
			newRoleTreeGrid();
		});

/**
 * �������ִ���¼�
 * 
 * @param {}
 *            page
 */
function show_page(page) {
	myTree._reload();
}
/*
 * ����
 */
function search() {
	var searchfield = $('#searchfield').val();
	var searchvalue = $('#searchvalue').val();
	var param = {};
	if (searchfield != '')
		param[searchfield] = searchvalue;
	if ($("#projectId").val() != '') {
		param['projectId'] = $("#projectId").val();
	}
	myTree._searchGrid(param);
}

/**
 * ��ӽ�ɫ����
 */
function addRole() {
	var projectId = $("#projectId").val();
	if (projectId == '') {
		alert('����ѡ����Ŀ���ͣ�');
		return;
	}
	var url = '?model=engineering_role_rdrole&action=toAdd&projectId='
			+ projectId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650';
	showThickboxWin(url);
}