var show_page = function(page) {
	$("#auditnoGrid").yxgrid("reload");
};

$(function() {
	$("#auditnoGrid").yxgrid({

		model : 'projectmanagent_present_present',
		action : 'pageJsonAuditNo',
		// title:'待审批销售订单',
		isToolBar : false,
		showcheckbox : false,
		isViewAction : false,
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
					name : 'orderCode',
					display : '源单编号',
					sortable : true
				}, {
					name : 'orderName',
					display : '源单名称',
					sortable : true
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
					name : 'rObjCode',
					display : '源单业务编号',
					width : 120
				}],

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=projectmanagent_present_present&action=init&perm=view&id="
						+ row.presentId
						+ "&skey="
						+ row['skey_']
						+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}, {
			text : '审批',
			icon : 'edit',
			action : function(row) {
				location = 'controller/projectmanagent/present/ewf_present.php?taskId='
						+ row.task
						+ '&spid='
						+ row.id
						+ '&billId='
						+ row.presentId
						+ '&actTo=ewfExam'
						+ "&skey="
						+ row['skey_'];
			}
		}],
		searchitems : [{
					display : '编号',
					name : 'Code'
				}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});