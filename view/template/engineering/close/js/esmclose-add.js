$(document).ready(function() {

	// ���شӱ�
	loadGrid();

	// ���ύ
	$("#sub").click(function() {
		if (checkForm() == true && confirm('ȷ���ύ��Ŀ�ر�������')) {
			$("form").submit();
		}
	});
});

/**
 * ���شӱ�
 */
function loadGrid() {
	// ����
	var objGrid = $("#closeRules");
	if (objGrid.html() != "") {
		objGrid.yxeditgrid('reload');
	} else {
		objGrid.yxeditgrid({
			objName: 'esmclose[esmclosedetail]',
			url: '?model=engineering_close_esmclosedetail&action=listConfirm',
			param: {projectId: $("#projectId").val(), projectCode: $("#projectCode").val()},
			tableClass: 'form_in_table',
			title: '�رչ���',
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
				display: 'ȷ���嵥',
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
				display: '��ǰֵ',
				name: 'val',
				wdith: 100,
				type: 'statictext'
			}, {
				display: '�������',
				name: 'content',
				wdith: 400,
				type: 'statictext',
				align: 'left'
			}, {
				display: '����',
				name: 'act',
				type: 'statictext'
			}]
		});
	}
}