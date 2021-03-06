var show_page = function(page) {
	$("#tstaskGrid").yxgrid("reload");
};
$(function() {
	$("#tstaskGrid").yxgrid({
		model : 'techsupport_tstask_tstask',
		param : {
			"statusIn" : 'XMZT-02,XMZT-03'
		},
		title : '售前支持',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'formNo',
					display : '单据编号',
					sortable : true,
					width : 120
				}, {
					name : 'objName',
					display : '关联项目名称',
					sortable : true
				}, {
					name : 'salesman',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'trainDate',
					display : '交流时间',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 150
				}, {
					name : 'cusLinkman',
					display : '客户联系人',
					sortable : true
				}, {
					name : 'cusLinkPhone',
					display : '客户联系电话',
					sortable : true
				}, {
					name : 'technicians',
					display : '技术人员',
					sortable : true
				}, {
					name : 'status',
					display : '当前状态',
					sortable : true,
					datacode : 'XMZT'
				}, {
					name : 'createTime',
					display : '申请时间',
					sortable : true,
					width : 120
				}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500,
			action : 'toSelect'
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 500
		},
		menusEx : [{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-01') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=techsupport_tstask_tstask&action=init&id='
							+ row.id
							+ '&skey='
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		}, {
			text : '填写服务记录',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-03') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=techsupport_tstask_tstask&action=handup&id='
							+ row.id
							+ '&skey='
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-01') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=techsupport_tstask_tstask&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page(1);
								} else {
									alert('删除失败');
									show_page(1);
								}
							}
						});
					}
				}
			}
		}],
		searchitems : [{
					display : '单据编号',
					name : 'formNoSearch'
				}],
		// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
					text : '已提交',
					value : 'XMZT-03'
				}, {
					text : '完成',
					value : 'XMZT-02'
				}]
			}
		]
	});
});