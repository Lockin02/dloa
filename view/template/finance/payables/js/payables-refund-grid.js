/** �����б�* */
var show_page = function(page) {
	$("#payablesGrid").yxgrid("reload");
};
$(function() {
	$("#payablesGrid").yxgrid({
		model : 'finance_payables_payables',
		param : {"formType" : $("#formType").val()},
		title : 'Ӧ���˿����',
		isToolBar : true,
		showcheckbox : true,
		isDelAction : true,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ݱ��',
			name : 'formNo',
			sortable : true,
			width : 120,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=finance_payables_payables&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			display : '��������',
			name : 'formDate',
			width : 80
		}, {
			display : '��Ӧ������',
			name : 'supplierName',
			width : 160
		}, {
			display : '���ݽ��',
			name : 'amount',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '��λ�ҽ��',
			name : 'amountCur',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
            name: 'currency',
            display: '�������',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '����',
            sortable: true,
            width: 60
        }, {
			display : '���㷽ʽ',
			name : 'payType',
			datacode : 'CWFKFS',
			width : 70
		}, {
			display : '¼����',
			name : 'createName',
			width : 80
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			width : 140
		}, {
			display : '״̬',
			name : 'status',
			datacode : 'YFDZT',
			width : 90,
			hide : true
		}, {
			display : '�������������',
			name : 'payApplyNo',
			width : 140
		}, {
			display : '������˾',
			name : 'businessBelongName',
			width : 80
		}],
		toViewConfig : {
			formWidth : 900,
			formHeight : 500
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 500,
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//��ӱ༭����
					if(rowData['payApplyNo'] != ''){
						alert('�����������Ƶ��˿���ܽ��б༭����');
						return false;
					}
					if(rowData['belongId'] != ''){
						alert('������Ƶ��˿���ܽ��б༭����');
						return false;
					}
					//�༭����
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&id="
							+ rowData[p.keyField]
							+ keyUrl
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toAddConfig : {
			formWidth : 900,
			formHeight : 500,
			plusUrl : '&formType=' + $("#formType").val()
		},
		toDelConfig : {
			action : 'ajaxDelForPayment'
		},
		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName'
		},{
			display : '�˿����뵥��',
			name : 'payApplyNoSearch'
		},{
			display : '���ݱ��',
			name : 'formNoSearch'
		},{
			display : '��������',
			name : 'formDateSearch'
		},{
			display : 'Դ�����',
			name : 'objCodeSearchDetail'
		},{
			display : '���ݽ��',
			name : 'amount'
		}],
		sortname : 'updateTime'
	});
});