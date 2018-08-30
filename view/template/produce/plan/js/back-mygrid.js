var show_page = function (page) {
	$("#backGrid").yxsubgrid("reload");
};

$(function () {
	$("#backGrid").yxsubgrid({
		model: 'produce_plan_back',
		param: {
			createId: $("#userId").val()
		},
		title: '我的生产退料申请单',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'pickingCode',
			display: '领料单编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.pickingId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_back&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "未确认";
					break;
				case '1':
					return "已确认";
					break;
				case '2':
					return "打回";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'docDate',
			display: '单据日期',
			sortable: true
		}, {
			name: 'createName',
			display: '申请人',
			sortable: true
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 250
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=produce_plan_backitem&action=pageJson',
			param: [{
				paramId: 'backId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '物料编码',
				sortable: true,
				width: 150
			}, {
				name: 'productName',
				display: '物料名称',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '规格型号',
				sortable: true
			}, {
				name: 'unitName',
				display: '单位',
				sortable: true
			}, {
				name: 'applyNum',
				display: '申请数量',
				sortable: true
			}, {
				name: 'backNum',
				display: '入库数量',
				sortable: true
			}]
		},

		//扩张右键菜单
		menusEx: [{
			text: "编辑",
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.docStatus == '2') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					showModalWin("?model=produce_plan_back&action=toEdit&id=" + row.id, '1');
				}
			}
		},{
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=produce_plan_back&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功!');
								show_page(1);
							} else {
								alert("删除失败!");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text: '单据状态',
			key: 'docStatus',
			data: [{
				text: '未确认',
				value: '0'
			}, {
				text: '已确认',
				value: '1'
			}, {
				text: '打回',
				value: '2'
			}]
		}],
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		}, {
			name: 'docDate',
			display: '单据日期'
		}, {
			name: 'pickingCode',
			display: '领料单编号'
		}]
	});
});