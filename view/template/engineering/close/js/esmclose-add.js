$(document).ready(function() {

	// 加载从表
	loadGrid();

	// 绑定提交
	$("#sub").click(function() {
		if (checkForm() == true && confirm('确认提交项目关闭申请吗？')) {
			$("form").submit();
		}
	});
});

/**
 * 加载从表
 */
function loadGrid() {
	// 规则
	var objGrid = $("#closeRules");
	if (objGrid.html() != "") {
		objGrid.yxeditgrid('reload');
	} else {
		objGrid.yxeditgrid({
			objName: 'esmclose[esmclosedetail]',
			url: '?model=engineering_close_esmclosedetail&action=listConfirm',
			param: {projectId: $("#projectId").val(), projectCode: $("#projectCode").val()},
			tableClass: 'form_in_table',
			title: '关闭规则',
			isAddOneRow: false,
			isAddAndDel: false,
			colModel: [{
				display: 'id',
				name: 'id',
				type: 'hidden'
			}, {
				display: 'ruleId',
				name: 'ruleId',
				type: 'hidden'
			}, {
				display: 'status',
				name: 'status',
				type: 'hidden'
			}, {
				display: 'isCustom',
				name: 'isCustom',
				type: 'hidden'
			}, {
				display: 'val',
				name: 'val',
				type: 'hidden'
			}, {
				display: '确认清单',
				name: 'ruleName',
				wdith: 100,
				type: 'statictext',
				process: function(v, row) {
					if (parseInt(row.ruleId) <= 5) {
						return '<span class="red">' + v + '</span>';
					} else {
						return v;
					}
				}
			}, {
				display: '当前值',
				name: 'val',
				wdith: 100,
				type: 'statictext'
			}, {
				display: '所需操作',
				name: 'content',
				wdith: 400,
				type: 'statictext',
				align: 'left'
			}, {
				display: '操作',
				name: 'act',
				type: 'statictext'
			}]
		});
	}
}