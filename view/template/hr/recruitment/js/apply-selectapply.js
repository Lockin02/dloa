var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};
$(function () {
	var showcheckbox = $("#showcheckbox").val();
	var showButton = $("#showButton").val();

	var textArr = [];
	var valArr = [];
	var indexArr = [];
	var combogrid = window.dialogArguments[0];
	var opener = window.dialogArguments[1];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);

	if (eventStr.row_dblclick) {
		var dbclickFunLast = eventStr.row_dblclick;
		eventStr.row_dblclick = function (e, row, data) {
			dbclickFunLast(e, row, data);
			window.returnValue = row.data('data');
			window.close();
		};
	} else {
		eventStr.row_dblclick = function (e, row, data) {
			window.returnValue = row.data('data');
			window.close();
		};
	}

	var gridOptions = combogrid.options.gridOptions;
	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		action: gridOptions.action,
		title: '��Ա����',
		isToolBar: true,
		isViewAction: false,
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: showcheckbox,
		param: {
			moreThanNeednum: 1,
			ExaStatus: '���',
			state: 2
		},
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '���ݱ��',
			width: 130,
			sortable: true,
			process: function (v, row) {
				return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_apply&action=toView&id=' + row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ '<font color = "#4169E1">' + v + '</font></a>';
			}
		}, {
			name: 'deptName',
			display: '������',
			width: 100,
			sortable: true
		}, {
			name: 'positionName',
			display: '����ְλ'
		}, {
			name: 'addType',
			display: '��Ա����'
		}, {
			name: 'workPlace',
			display: '�����ص�'
		}, {
			name: 'resumeToName',
			display: '�ӿ���'
		}, {
			name: 'needNum',
			display: '��������',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'entryNum',
			display: '����ְ����',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'beEntryNum',
			display: '����ְ����',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'stopCancelNum',
			display: '��ͣ/ȡ������',
			sortable: true,
			width: 90
		}, {
			name: 'positionNote',
			display: 'ְλ��ע',
			width: 150,
			process: function (v, row) {
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '��';
				}
				if (row.network) {
					tmp += row.network + '��';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		}],

		searchitems: [{
			display: "���ݱ��",
			name: 'formCode'
		}, {
			display: "������",
			name: 'deptName'
		}, {
			display: "����ְλ",
			name: 'positionName'
		}, {
			display: '��Ա����',
			name: 'addType'
		}, {
			display: '�����ص�',
			name: 'workPlaceSearch'
		}, {
			display: '�ӿ���',
			name: 'resumeToNameSearch'
		}],

		sortname: gridOptions.sortname,
		sortorder: gridOptions.sortorder,
		// ���¼����ƹ���
		event: eventStr
	});
});