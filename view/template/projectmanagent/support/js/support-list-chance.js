var show_page = function(page) {
	$("#supportGrid").yxgrid("reload");
};
$(function() {
	$("#supportGrid").yxgrid({
		model : 'projectmanagent_support_support',
		title : '售前支持申请',
		param : {'SingleId' : $("#chanceId").val()},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'supportCode',
			display : '单据编号',
			sortable : true,
			width : 150
		}, {
			name : 'prinvipalName',
			display : '销售经理',
			sortable : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'signSubjectName',
			display : '支持身份',
			sortable : true
		}, {
			name : 'exchangeName',
			display : '建议交流人员',
			sortable : true
		}, {
			name : 'linkman',
			display : '客户联系人',
			sortable : true
		}, {
			name : 'contact',
			display : '联系方式',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}],
        // 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_support_support&action=toView&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_support_support&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.ExaStatus == '部门审批') {
					return true;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_sale_chance_support&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				 if (window.confirm(("确定提交审批吗?"))) {
					showThickboxWin('controller/projectmanagent/support/ewf_support_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}
		],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'supportCode'
		}, {
			display : '项目名称',
			name : 'projectName'
		}, {
			display : '项目编号',
			name : 'projectCode'
		}]
	});
});