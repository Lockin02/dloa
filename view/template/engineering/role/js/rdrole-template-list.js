$(document).ready(function() {
			// Tab��ͷ
			topTabShow(arrayTop, "rdItemRole");

			// �����꾭������ɫ�ı�
			rowsColorChange();
			newRoleTreeGrid();
		});

/**
 * ѡ����Ŀ���ʹٷ��¼�
 */
function selectType(v) {
	var param = {};
	if (v != '') {
		param = {
			'projectType' : v
		};
	} else {
		delete myTree.tc.param['projectType'];
	}
	myTree._searchGrid(param);

}
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
	if ($("#projectType").val() != '') {
		param['projectType'] = $("#projectType").val();
	}
	myTree._searchGrid(param);
}

/**
 * ��ӽ�ɫ����
 */
function addRole() {
	var projectType = $("#projectType").val();
	if (projectType == '') {
		alert('����ѡ����Ŀ���ͣ�');
		return;
	}
	var url = '?model=engineering_role_rdrole&action=toAdd&projectType='
			+ projectType
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650';
	showThickboxWin(url);
}