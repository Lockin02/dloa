var show_page = function(page) {
	$("#encryptionGrid").yxgrid("reload");
};

$(function() {
	$("#encryptionGrid").yxgrid({
		model : 'stock_delivery_encryption',
		param : {
			'stateArr' : '1,2'
		},
		title : '未完成加密锁任务',
		bodyAlign : 'center',
		isAddAction : false,
		isEditAction : false,
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
				if (v == 1) {
					return '未接收';
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
			text : "接收任务",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=stock_delivery_encryption&action=receiveMission',
					data : {
						id : row.id
					},
					async: false,
					success : function(data) {
						if (data == 1) {
							alert("接收成功");
							show_page();
						} else {
							alert("接收失败");
						}
					}
				});
			}
		},{
			text : "完成",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=stock_delivery_encryption&action=toFinish&id=" + row.id ,'1');
			}
		}],

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
				text : '未接收',
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