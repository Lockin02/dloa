var show_page = function(page) {
	$("#pickingGrid").yxgrid("reload");
};

$(function() {
	$("#pickingGrid").yxgrid({
		model : 'produce_plan_picking',
		param : {
			planId : $("#planId").val()
		},
		title : '生产领料申请单',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'relDocCode',
			display: '源单编号',
			sortable: true
		},{
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "未提交";
					break;
				case '1':
					return "审批中";
					break;
				case '2':
					return "审批完成";
					break;
				case '3':
					return "打回";
					break;
				case '4':
					return "申请出库";
					break;
				case '5':
					return '出库完成';
					break;
				default:
					return "--";
				}
			}
		},{
			name: 'docDate',
			display: '单据日期',
			sortable: true
		},{
			name: 'relDocName',
			display: '源单名称',
			sortable: true,
			width : 200
		},{
			name: 'relDocType',
			display: '源单类型',
			sortable: true
		},{
			name: 'createName',
			display: '申请人',
			sortable: true
		},{
			name: 'remark',
			display: '备注',
			sortable: true,
			width : 250
		}],

		//扩张右键菜单
		menusEx : [{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: '单据状态',
			key: 'docStatus',
			data: [{
				text: '未提交',
				value: '0'
			}, {
				text: '审批中',
				value: '1'
			}, {
				text: '审批完成',
				value: '2'
			}, {
				text: '打回',
				value: '3'
			}, {
				text: '申请出库',
				value: '4'
			}, {
				text: '出库完成',
				value: '5'
			}]
		}],

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		},{
			name: 'docDate',
			display: '单据日期'
		},{
			name: 'relDocCode',
			display: '源单编号'
		},{
			name: 'relDocName',
			display: '源单名称'
		},{
			name: 'relDocType',
			display: '源单类型'
		},{
			name: 'createName',
			display: '申请人'
		}]
	});
});