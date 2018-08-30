// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#mailshipGrid").yxgrid("reload");
};

$(function() {
	$("#mailshipGrid").yxgrid({
		model : 'mail_mailinfo',
		action : 'shipJson',
		param : {"docType" : $("#docType").val() },
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		title : "发票邮寄信息",
		showcheckbox : false,
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
			display : '发票号',
			name : 'docCode',
			sortable : true
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

		toAddConfig :{
			formWidth: 900,
			formHeight : 500,
			action : 'invoiceAdd',
			plusUrl : '&docType=' + $("#docType").val()
		},
		buttonsEx : [{//toExportExcel
			name: 'export',
			text: "导出",
			icon: 'excel',
			action: function () {
				var searchConditionKey = "";
				var searchConditionVal = "";
				for (var t in $("#mailshipGrid").data('yxgrid').options.searchParam) {
					if (t != "") {
						searchConditionKey += t;
						searchConditionVal += $("#mailshipGrid").data('yxgrid').options.searchParam[t];
					}
				}
				var i = 1;
				var colId = "";
				var colName = "";
				$("#mailshipGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
					if ($(this).css("display") != "none"
						&& $(this).attr("colId") != undefined) {
						colName += $(this).children("div").html() + ",";
						colId += $(this).attr("colId") + ",";
						i++;
					}
				})
				var searchSql = $("#mailshipGrid").data('yxgrid').getAdvSql()
				var searchArr = [];
				searchArr[0] = searchSql;
				searchArr[1] = searchConditionKey;
				searchArr[2] = searchConditionVal;

				showThickboxWin("?model=mail_mailinfo&action=toExportExcel&colId="
					+ colId
					+ "&colName="
					+ colName
					+ "&searchConditionKey="
					+ searchConditionKey
					+ "&searchConditionVal="
					+ searchConditionVal
					+ "&docType="
					+ $("#docType").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
			}
		}],
		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=invoiceInit&perm=view&id="
					+ row.id
					+ '&docType=' + $("#docType").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
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
				showThickboxWin("?model=mail_mailinfo&action=invoiceInit&id="
					+ row.id
					+ '&docType=' + $("#docType").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
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
		],// 过滤数据
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
			data : [{
				text : '未签收',
				value : '0'
			}, {
				text : '已签收',
				value : '1'
			}]
		}],

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
		}],
		// 默认搜索顺序
		sortorder : "DESC"

	});
});