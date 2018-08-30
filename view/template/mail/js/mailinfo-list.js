// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#mailinfoGrid").yxgrid("reload");
};
$(function() {
	$("#mailinfoGrid").yxgrid({
		model : 'mail_mailinfo',
		title : "邮寄信息",
		isToolBar:false,
		showcheckbox:false,
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
			display : '收件人',
			name : 'receiver',
			sortable : true,
			width : 80
		}, {
			display : '收件人电话',
			name : 'tel',
			sortable : true
		}, {
			display : '业务员',
			name : 'salesman',
			sortable : true
		}, {
			display : '邮寄人',
			name : 'mailMan',
			sortable : true
		}, {
			display : '邮寄日期',
			name : 'mailTime',
			sortable : true,
			width : 80
		}, {
			display : '邮寄方式',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : 80,
			hide : true
		}, {
			display : '邮寄类型',
			name : 'docType',
			sortable : true,
			width : 80,
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
			display : '关联业务编号',
			name : 'docCode',
			sortable : true,
			hide : true
		}, {
			display : '客户名称',
			name : 'customerName',
			sortable : true,
			width : 130,
			hide : true
		}, {
			display : '物流公司',
			name : 'logisticsName',
			sortable : true
		}, {
			display : '邮寄费用',
			name : 'mailMoney',
			sortable : true,
			width : 60,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '状态',
			name : 'mailStatus',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "已确认";
				} else {
					return "未确认";
				}
			},
			width : 80
		}, {
			display : '签收状态',
			name : 'status',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "已签收";
				} else {
					return "未签收";
				}
			},
			width : 80
		}, {
			display : '签收日期',
			name : 'signDate',
			sortable : true,
			width : 80
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
				if(row.docType == 'YJSQDLX-FPYJ'){
					showThickboxWin("?model=mail_mailinfo&action=invoiceInit&perm=view&id="
						+ row.id
						+ '&docType=' + row.docType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=mail_mailinfo&action=shipInit&perm=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
					}
				}
			}, {
			name : 'view',
			text : "签收记录",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == 1 && row.docType == 'YJSQDLX-FPYJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=read&id="
					+ "&docId=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}, {
			name : 'edit',
			text : "修改",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == 0 && row.status == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(row.docType == 'YJSQDLX-FPYJ'){
					showThickboxWin("?model=mail_mailinfo&action=invoiceInit&id="
						+ row.id
						+ '&docType=' + row.docType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=mail_mailinfo&action=shipInit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
					}
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
					if(confirm('确认单据正确么?')){
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
								}else{
									alert('确认失败!');
								}
							}
						});
					}
				}
			}, {
			name : 'sign',
			text : "签收",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != 1 && row.docType == 'YJSQDLX-FPYJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=toAdd&id="
					+ row.id + "&signMan=" + row.receiver
					+ "&docId=" + row.docId
					+ "&docCode=" + row.docCode
					+ "&docType=" + row.docType
					+ "&mailNo=" + row.mailNo
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		],
		// 快速搜索
		searchitems : [{
			display : '邮寄单号',
			name : 'mailNo'
		}, {
			display : '邮寄发票号码',
			name : 'docCodeSearch'
		}, {
			display : '收件人',
			name : 'receiver'
		}, {
			display : '邮寄人',
			name : 'mailMan'
		}],// 过滤数据
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
		}, {
			text : '签收状态',
			key : 'status',
			value : '0',
			data : [{
				text : '未签收',
				value : '0'
			}, {
				text : '已签收',
				value : '1'
			}]
		}],
		// 默认搜索顺序
		sortorder : "DESC"

	});
});