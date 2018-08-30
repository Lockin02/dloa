var show_page = function(page) {
	$("#mypresentGrid").yxgrid("reload");
};
$(function() {
	$("#mypresentGrid").yxgrid({
		model : 'projectmanagent_present_present',
		param : {
			'salesNameId' : $("#user").val()
		},
		title : '赠送申请',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',
			action : function(row) {
				showModalWin('?model=projectmanagent_present_present&action=toAdd');
			}
		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '完成',
				value : '完成'
			},
				{
					text: '物料确认',
					value: '物料确认'
				},
				{
					text: '变更审批中',
					value: '变更审批中'
				}]
		}],
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '编号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'salesName',
					display : '申请人',
					sortable : true
				}, {
					name : 'reason',
					display : '申请理由',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 120
				}, {
					name : 'orderCode',
					display : '源单编号',
					width : 120
				}, {
					name : 'orderName',
					display : '源单名称',
					width : 120
				}],
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}

		}, {
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '未审批') || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=init&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}
		}, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '未审批') || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_present_present&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							} else {
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}, {
		// 	text : '提交审核',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
		// 			return true;
		// 		}
		// 		return false;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			showThickboxWin('controller/projectmanagent/present/ewf_present.php?actTo=ewfSelect&billId='
		// 					+ row.id
		// 					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 		}
		// 	}
        //
		// }, {

			text : '变更',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_present_present&action=toChange&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		},{
			display : '申请人',
			name : 'salesName'
		},{
			display : '源单编号',
			name : 'orderCode'
		}, {
			display : '客户名称',
			name : 'customerName'
		}]
	});
});