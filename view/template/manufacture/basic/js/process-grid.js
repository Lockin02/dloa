var show_page = function(page) {
	$("#processGrid").yxgrid("reload");
};

$(function() {
	$("#processGrid").yxgrid({
		model: 'manufacture_basic_process',
		title: '基础信息-工序模板',
		bodyAlign : 'center',
		isOpButton : false,
		//列信息
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'templateName',
			display : '模板名称',
			sortable : true,
			width : 200,
			align : 'left'
		},{
			name : 'isEnable',
			display : '是否启用',
			sortable : true,
			width : 50
		},{
			name : 'createName',
			display : '录入人',
			sortable : true
		},{
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 350,
			align : 'left'
		},{
			name : 'createId',
			display : '录入人ID',
			sortable : true,
			hide : true
		},{
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		},{
			name : 'updateId',
			display : '修改人ID',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}],

		//扩展菜单
		buttonsEx : [{
			name : 'start',
			text : "启用",
			icon : 'add',
			action : function(row ,rows ,rowIds) {
				if (rowIds[0]) {
					if (window.confirm("确认要启用?")) {
						$.ajax({
							type : "POST",
							url : "?model=manufacture_basic_process&action=ajaxEnable",
							data : {
								ids : rowIds.join(','),
								isEnable : '是'
							},
							success : function(msg) {
								if (msg == 1) {
									alert('启用成功！');
									show_page();
								} else {
									alert('启用失败！');
								}
							}
						});
					}
				}
			}
		},{
			name : 'close',
			text : "禁用",
			icon : 'delete',
			action : function(row ,rows ,rowIds) {
				if (rowIds[0]) {
					if (window.confirm("确认要禁用?")) {
						$.ajax({
							type : "POST",
							url : "?model=manufacture_basic_process&action=ajaxEnable",
							data : {
								ids : rowIds.join(','),
								isEnable : '否'
							},
							success : function(msg) {
								if (msg == 1) {
									alert('禁用成功！');
									show_page();
								} else {
									alert('禁用失败！');
								}
							}
						});
					}
				}
			}
		}],
		//扩展右键菜单
		menusEx : [],

		//下拉过滤
		comboEx : [{
			text: "是否启用",
			key: 'isEnable',
			data : [{
				text : '是',
				value : '是'
			},{
				text : '否',
				value : '否'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p ,g) {
				showModalWin("?model=manufacture_basic_process&action=toAdd" ,'1');
			}
		},
		toEditConfig: {
			toEditFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_process&action=toEdit&id=" + get[p.keyField] ,'1');
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_process&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			display: "模板名称",
			name: 'templateName'
		},{
			display: "录入人",
			name: 'createName'
		},{
			display: "录入时间",
			name: 'createTime'
		},{
			display: "备注",
			name: 'remark'
		}]
	});
});