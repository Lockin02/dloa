$(document).ready(function() {
			// Tab表头
			//topTabShow(arrayTop, "pjTeam", "{rdproject[id]}");
			// 添加鼠标经过行颜色改变
			rowsColorChange();
			newRoleTreeGrid();
		});

/**
 * 保存表单后执行事件
 *
 * @param {}
 *            page
 */
function show_page(page) {
	myTree._reload();
}
/*
 * 搜索
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
 * 添加角色窗口
 */
function addRole() {
	var projectId = $("#projectId").val();
	var proCenter=$('#proCenter').val();
	if (projectId == '') {
		alert('请先选择项目类型！');
		return;
	}
	var url = '?model=rdproject_role_rdrole&action=toAdd&projectId='
			+ projectId
			+ "&proCenter="
			+ proCenter
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650';
	showThickboxWin(url);
}