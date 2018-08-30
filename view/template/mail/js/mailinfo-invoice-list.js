// ��������/�޸ĺ�ص�ˢ�±��
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
		title : "��Ʊ�ʼ���Ϣ",
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ʼĵ���',
			name : 'mailNo',
			sortable : true
		}, {
			display : '�ռ���',
			name : 'receiver',
			sortable : true,
			width : 80
		}, {
			display : '�ռ��˵绰',
			name : 'tel',
			sortable : true
		}, {
			display : 'ҵ��Ա',
			name : 'salesman',
			sortable : true
		}, {
			display : '�ʼ���',
			name : 'mailMan',
			sortable : true
		}, {
			display : '�ʼ�����',
			name : 'mailTime',
			sortable : true,
			width : 80
		}, {
			display : '�ʼķ�ʽ',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : 80,
			hide : true
		}, {
			display : '��Ʊ��',
			name : 'docCode',
			sortable : true
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true,
			width : 130,
			hide : true
		}, {
			display : '������˾',
			name : 'logisticsName',
			sortable : true
		}, {
			display : '�ʼķ���',
			name : 'mailMoney',
			sortable : true,
			width : 60,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '״̬',
			name : 'mailStatus',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			},
			width : 80
		}, {
			display : 'ǩ��״̬',
			name : 'status',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "��ǩ��";
				} else {
					return "δǩ��";
				}
			},
			width : 80
		}, {
			display : 'ǩ������',
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
			text: "����",
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
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=invoiceInit&perm=view&id="
					+ row.id
					+ '&docType=' + $("#docType").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}, {
			name : 'view',
			text : "ǩ�ռ�¼",
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
			text : "�޸�",
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
			text : "ǩ��",
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
		],// ��������
		comboEx : [{
			text : '״̬',
			key : 'mailStatus',
			data : [{
				text : 'δȷ��',
				value : '0'
			}, {
				text : '��ȷ��',
				value : '1'
			}]
		}, {
			text : 'ǩ��״̬',
			key : 'status',
			data : [{
				text : 'δǩ��',
				value : '0'
			}, {
				text : '��ǩ��',
				value : '1'
			}]
		}],

		// ��������
		searchitems : [{
			display : '�ʼĵ���',
			name : 'mailNo'
		}, {
			display : '�ʼķ�Ʊ����',
			name : 'docCodeSearch'
		}, {
			display : '�ռ���',
			name : 'receiver'
		}, {
			display : '�ʼ���',
			name : 'mailMan'
		}],
		// Ĭ������˳��
		sortorder : "DESC"

	});
});