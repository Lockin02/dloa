// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#myMailapplyGrid").yxgrid("reload");
};
var invoiceapplyFn = function(rowId) {
		showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init&id='
				+ rowId
				+ '&perm=view'
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
	};

	var outapplyFn = function(rowId, docType) {
		showThickboxWin('?model=stock_outstock_outapply&action=toView&id='
				+ rowId
				+ '&docType='
				+ docType
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
	};
$(function() {
	$("#myMailapplyGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailapply',
		action : 'myApplyListJson',
		showcheckbox : false,
		isDelAction : false,
		title : "我的邮寄申请",
		// /**
		// * 默认高度
		// */
		// height : 280,
		// /**
		// * 是否显示工具栏
		// *
		// * @type Boolean
		// */
		// isToolBar : false,
		/**
		 * 表单默认宽度
		 */
		formWidth : 900,
		/**
		 * 表单默认宽度
		 */
		formHeight : 550,

		/**
		 * 是否显示添加按钮/菜单
		 *
		 * @type Boolean
		 */
		isAddAction : true,
		/**
		 * 是否显示查看按钮/菜单
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 *
		 * @type Boolean
		 */
		isEditAction : false,

		// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
				text : '未处理',
				value : '1'
			}, {
				text : '已处理',
				value : '2'
			}]
		}],

		menusEx : [{
			name : 'edit',
			text : "修改",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' && row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailapply&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'read',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailapply&action=readInfo&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			}
		}, {
			name : 'delete',
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' && row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=mail_mailapply&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									// grid.reload();
									alert('删除成功！');
									$("#myMailapplyGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交'&& row.applyId == "" || row.ExaStatus == '打回'&& row.applyId == ""
						) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = 'controller/mail/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_mail_apply&formName=邮寄申请审批';
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成'
						&& row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_mail_apply&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		}, {
			name : 'readMailMessage',
			text : "邮寄记录",
			icon : 'view',
			 showMenuFn : function(row) {
			 if (row.status == '2') {
			 return true;
			 }
			 return false;
			 },
			action : function(row, rows, grid) {
				location = "?model=mail_mailinfo&action=toMailInfo&mailApplyId="
						+ row.id;
			}
		}],

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '关联编号',
			name : 'applyNo',
			sortable : true,
			process : function(v, row) {
				if (row.applyNo == "") {
					return row.applyNo = "无关联编号";
				}
				if (row.applyType == "invoiceapply") {

					return "<a href=\"javascript:invoiceapplyFn(" + row.applyId
							+ ")\">" + v + "</a>";
				}
				if (row.applyType == "outapply") {

					return "<a href=\"javascript:outapplyFn(" + row.applyId
							+ "," + "'" + row.docType + "'" + ")\">" + v
							+ "</a>";
				} else {
					return row.applyNo;
				}
			}

		}, {
			display : '邮寄编号',
			name : 'zipCode',
			sortable : true,
			align : 'center'
		}, {
			display : '通知日期',
			name : 'mailDate',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '收件单位全称',
			name : 'customerName',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '收件人',
			name : 'linkman',
			sortable : true,
			width : '80',
			align : 'center'
		}, {
			display : '收件人电话',
			name : 'tel',
			sortable : true,
			width : '100',
			align : 'center'
		}, {
			display : '邮寄方式',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : '60',
			align : 'center'
		}, {
			display : '邮寄状态',
			name : 'status',
			sortable : true,
			process : function(v) {
				if (v == "1") {
					return "未处理";
				} else {
					return "已处理";
				}
			},
			width : '60',
			align : 'center'
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true,
			width : '60',
			align : 'center'
		}],
		// 快速搜索
		searchitems : [{
			display : '邮寄方式',
			name : 'mailType'
		}],
		// 默认搜索顺序
		sortorder : "desc"

	});
});