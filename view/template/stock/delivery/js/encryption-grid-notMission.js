var show_page = function(page) {
	$("#encryptionGrid").yxgrid("reload");
};

$(function() {
	$("#encryptionGrid").yxgrid({
		model : 'stock_delivery_encryption',
		param : {
			'stateArr' : '0,1,2'
		},
		title : '未完成加密锁任务',
		bodyAlign : 'center',
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'sourceDocCode',
			display : '源单编号',
			sortable : true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=stock_delivery_encryption&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		},{
			name : 'sourceDocType',
			display : '源单类型',
			sortable : true,
			width : 70
		},{
			name : 'headMan',
			display : '合同负责人',
			sortable : true,
			width : 70
		},{
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 200
		},{
			name : 'issueName',
			display : '下达人名称',
			sortable : true,
			width : 70
		},{
			name : 'issueDate',
			display : '下达日期',
			sortable : true,
			width : 80
		},{
			name : 'state',
			display : '状态',
			sortable : true,
			width : 60,
			process : function (v) {
				if (v == 0) {
					return '未下达';
				} else if (v == 1) {
					return '已下达';
				} else if (v == 2) {
					return '已接收';
				}
			}
		},{
			name : 'receiveDate',
			display : '接收日期',
			sortable : true,
			width : 80
		},{
		// 	name : 'finshDate',
		// 	display : '完成日期',
		// 	sortable : true
		// },{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 250,
			align : 'left'
		}],

		//扩展右键菜单
		menusEx : [{
			text : "下达任务",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=stock_delivery_encryption&action=assignMission',
					data : {
						id : row.id
					},
					async: false,
					success : function(data) {
						if (data == 1) {
							alert("下达成功");
							show_page();
						} else {
							alert("下达失败，有无法下达的单据！");
						}
					}
				});
			}
		},{
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=stock_delivery_encryption&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page();
							}
						}
					});
				}
			}
		}],

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=stock_delivery_encryption&action=toEdit&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=stock_delivery_encryption&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},

		comboEx : [{
			text : '状态',
			key : 'state',
			data : [{
				text : '未下达',
				value : '0'
			},{
				text : '已下达',
				value : '1'
			},{
				text : '已接收',
				value : '2'
			}]
		}],

		searchitems : [{
			display : "源单编号",
			name : 'sourceDocCode'
		},{
			display : "源单类型",
			name : 'sourceDocType'
		},{
			display : "负责人",
			name : 'headMan'
		},{
			display : "客户名称",
			name : 'customerName'
		},{
			display : "下达人名称",
			name : 'issueName'
		},{
			display : "下达日期",
			name : 'issueDate'
		},{
			display : "接收日期",
			name : 'receiveDate'
		}]
	});
});