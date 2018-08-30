// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".myMailinfoGrid").yxgrid("reload");
};
$(function() {
	$(".myMailinfoGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'mylogpageJson&mailApplyId=' + $('#mailApplyId').val(),
		title : "邮寄信息",
		isToolBar:false,
		showcheckbox:false,
		isRightMenu : false,
		//扩展按钮
		buttonsEx : [{
			name : 'return',
			text : '返回',
			icon : 'view',
			action : function(row, rows, grid) {
				location = "?model=mail_mailapply&action=toMyApplyList";

			}
		}],
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
			sortable : true
		}, {
			display : '寄件人',
			name : 'mailMan',
			sortable : true
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