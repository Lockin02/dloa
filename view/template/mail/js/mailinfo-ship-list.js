// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".mailshipGrid").yxgrid("reload");
};
$(function() {
	$(".mailshipGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'shipJson',
		param : {
			docType : $('#docType').val()
		},
		title : "邮寄信息",
		isToolBar : false,
		showcheckbox : false,
		// isAddAction : false,
		// isViewAction : false,
		// isEditAction : false,
		// isDelAction:false,

		// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'mailStatus',
			data : [{
				text : '未确认',
				value : '0'
			}, {
				text : '已确认',
				value : '1'
			}]
		}],
//		 扩展按钮
		 buttonsEx : [{
			 name : 'import',
			 text : '邮寄费用信息导入',
			 icon : 'excel',
			 action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=toFareImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500");
			 }
		 }],

		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=shipInit&perm=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'edit',
			text : "修改",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=shipInit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'edit',
			text : "确认",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (confirm('确认单据正确么?')) {
					$.ajax({
						type : "POST",
						url : "?model=mail_mailinfo&action=confirm",
						data : {
							"id" : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('确认成功！');
								show_page(1);
							} else {
								alert('确认失败!');
							}
						}
					});
				}
			}
				// }, {
				// name : 'readMailMessage',
				// text : "客户签收",
				// icon : 'view',
				// showMenuFn : function(row) {
				// if (row.mailStatus == '1') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row, rows, grid) {
				// showThickboxWin("?model=mail_mailsign&action=toAdd&mailInfoId="
				// + row.id
				// +
				// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				// }
				}],

		// toAddConfig : {
		// text : '新增',
		// /**
		// * 默认点击新增按钮触发事件
		// */
		// toAddFn : function(p) {
		// var c = p.toAddConfig;
		// var w = c.formWidth ? c.formWidth : p.formWidth;
		// var h = c.formHeight ? c.formHeight : p.formHeight;
		// showThickboxWin("?model=mail_mailinfo&action=toAdd&mailApplyId="
		// + $('#mailApplyId').val()
		// +
		// "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
		// }
		// },

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '邮寄类型',
			name : 'docType',
			sortable : true,
			process : function(v){
				if(v == 'YJSQDLX-FPYJ'){
					return '发票邮寄';
				}else if(v == 'YJSQDLX-FHYJ'){
					return '发货邮寄';
				}else{
					return v;
				}
			}
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
			display : '物流公司',
			name : 'logisticsName',
			sortable : true,
			width : '100'
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
				if (v == "0") {
					return "未确认";
				} else {
					return "已确认";
				}
			},
			width : '60'
				// }, {
				// display : '客户签收人',
				// name : 'signMan',
				// sortable : true
				// }, {
				// display : '签收时间',
				// name : 'signDate',
				// sortable : true,
				// width : '100'
				}],
		// 快速搜索
		searchitems : [{
			display : '邮寄单号',
			name : 'mailNo'
		}, {
			display : '收件人',
			name : 'receiver'
		}, {
			display : '寄件人',
			name : 'mailMan'
		}],
		// 默认搜索顺序
		sortorder : "DESC"

	});
});