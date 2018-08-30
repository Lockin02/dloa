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
		title: '增员申请',
		isToolBar: true,
		isViewAction: false,
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: showcheckbox,
		param: {
			moreThanNeednum: 1,
			ExaStatus: '完成',
			state: 2
		},
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '单据编号',
			width: 130,
			sortable: true,
			process: function (v, row) {
				return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_apply&action=toView&id=' + row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ '<font color = "#4169E1">' + v + '</font></a>';
			}
		}, {
			name: 'deptName',
			display: '需求部门',
			width: 100,
			sortable: true
		}, {
			name: 'positionName',
			display: '需求职位'
		}, {
			name: 'addType',
			display: '增员类型'
		}, {
			name: 'workPlace',
			display: '工作地点'
		}, {
			name: 'resumeToName',
			display: '接口人'
		}, {
			name: 'needNum',
			display: '需求人数',
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
			display: '已入职人数',
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
			display: '待入职人数',
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
			display: '暂停/取消人数',
			sortable: true,
			width: 90
		}, {
			name: 'positionNote',
			display: '职位备注',
			width: 150,
			process: function (v, row) {
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '，';
				}
				if (row.network) {
					tmp += row.network + '，';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		}],

		searchitems: [{
			display: "单据编号",
			name: 'formCode'
		}, {
			display: "需求部门",
			name: 'deptName'
		}, {
			display: "需求职位",
			name: 'positionName'
		}, {
			display: '增员类型',
			name: 'addType'
		}, {
			display: '工作地点',
			name: 'workPlaceSearch'
		}, {
			display: '接口人',
			name: 'resumeToNameSearch'
		}],

		sortname: gridOptions.sortname,
		sortorder: gridOptions.sortorder,
		// 把事件复制过来
		event: eventStr
	});
});