// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".mailinfoGrid").yxgrid("reload");
};
$(function() {
	$(".mailinfoGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'mylogpageJson&mailApplyId=' + $('#mailApplyId').val(),
		title : "邮寄信息",

		/**
		 * 是否显示批量查看按钮/菜单
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

		//扩展按钮
		buttonsEx : [{
			name : 'return',
			text : '返回',
			icon : 'back',
			action : function(row, rows, grid) {
				location = "?model=mail_mailapply";

			}
		}],

		menusEx : [{
			name : 'edit',
			text : "修改",
			icon : 'edit',
			showMenuFn : function(row) {
				 if (row.mailStatus == '1') {
				 return true;
				 }
				 return false;
			 },
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'readMailMessage',
			text : "客户签收",
			icon : 'view',
			showMenuFn : function(row) {
				 if (row.mailStatus == '1') {
				 return true;
				 }
				 return false;
			 },
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=toAdd&mailInfoId="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model=mail_mailinfo&action=toAdd&mailApplyId="
						+ $('#mailApplyId').val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '邮寄单号',
			name : 'mailNo',
			sortable : true
		}, {
			display : '通知日期',
			name : 'mailTime',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '收件人',
			name : 'receiver',
			sortable : true,
			width : '80'
		}, {
			display : '寄件人',
			name : 'mailMan',
			sortable : true,
			width : '80'
		}, {
			display : '寄件地址',
			name : 'address',
			hide : true,
			width : '200'
		}, {
			display : '收件人电话',
			name : 'tel',
			sortable : true,
			width : '100'
		}, {
			display : '邮寄方式',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : '60'
		}, {
			display : '状态',
			name : 'mailStatus',
			sortable : true,
			process : function(v) {
				if (v == "1") {
					return "未签收";
				} else {
					return "已签收";
				}
			},
			width : '60'
		}, {
			display : '客户签收人',
			name : 'signMan',
			sortable : true
		}, {
			display : '签收时间',
			name : 'signDate',
			sortable : true,
			width : '100'
		}],
		// 快速搜索
		searchitems : [{
			display : '收件人',
			name : 'receiver'
		}, {
			display : '寄件人',
			name : 'mailMan'
		}, {
			display : '邮寄方式',
			name : 'mailType'
		}],
		// 默认搜索顺序
		sortorder : "DESC"

	});
});