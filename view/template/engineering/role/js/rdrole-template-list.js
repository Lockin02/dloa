$(document).ready(function() {
			// Tab表头
			topTabShow(arrayTop, "rdItemRole");

			// 添加鼠标经过行颜色改变
			rowsColorChange();
			newRoleTreeGrid();
		});

/**
 * 选择项目类型促发事件
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
	if ($("#projectType").val() != '') {
		param['projectType'] = $("#projectType").val();
	}
	myTree._searchGrid(param);
}

/**
 * 添加角色窗口
 */
function addRole() {
	var projectType = $("#projectType").val();
	if (projectType == '') {
		alert('请先选择项目类型！');
		return;
	}
	var url = '?model=engineering_role_rdrole&action=toAdd&projectType='
			+ projectType
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650';
	showThickboxWin(url);
}