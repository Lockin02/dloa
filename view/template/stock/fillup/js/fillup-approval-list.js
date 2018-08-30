var show_page = function(page) {
	$("#fillupapproval").yxgrid("reload");
};
$(function() {
	$("#fillupapproval").yxgrid({
		model : 'stock_fillup_fillup',
		action : 'myApprovalPJ',
		title : '补库计划审核',
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isDelAction : false,
		isToolBar : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'fillupCode',
					display : '补库计划单号',
					sortable : true
				}, {
					name : 'stockId',
					display : '仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true,
					width:200
				}, {
					name : 'stockCode',
					display : '仓库代码',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'auditStatus',
					display : '提交状态',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '修改人id',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '创建日期',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '录入人',
					sortable : true
				}, {
					name : 'createId',
					display : '创建人id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}],
		toAddConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		toEditConfig : {
			action : 'toEdit',
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		menusEx : [{
			name : 'audit',
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				location = "controller/stock/fillup/ewf_index.php?actTo=ewfExam&taskId="
						+ row.taskId
						+ "&spid="
						+ row.spid
						+ "&billId="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&examCode=oa_stock_fillup";
			}
		}, {
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_fillup_fillup&action=init&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}]
	});
});