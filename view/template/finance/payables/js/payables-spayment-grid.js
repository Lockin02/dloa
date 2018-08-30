/** �����б�* */
var show_page = function(page) {
	$("#payablesGrid").yxgrid("reload");
};

$(function() {
	$("#payablesGrid").yxgrid({
		model : 'finance_payables_payables',
		param : {"formType" : $("#formType").val()},
		title : 'Ӧ���������',
		isToolBar : true,
		showcheckbox : true,
		customCode : 'payablesGrid',
//		isDelAction : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ݱ��',
			name : 'formNo',
			sortable : true,
			width : 140,
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
			display : '��Ӧ��id',
			name : 'supplierId',
			hide : true
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
			display : 'ҵ��Ա',
			name : 'salesman',
			width : 90
		}, {
			display : '¼����',
			name : 'createName',
			width : 90
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			width : 140,
			hide : true
		}, {
			display : '״̬',
			name : 'status',
			width : 70,
			process : function(v){
				if(v == '1'){
					return '���˿�';
				}else{
					return '����';
				}
			}
		}, {
			display : '���������',
			name : 'payApplyNo',
			width : 150,
			hide : true
		}, {
			display : '��������id',
			name : 'payApplyId',
			width : 60
		}, {
			display : '���д���',
			name : 'isEntrust',
			width : 50,
			process :function(v){
				if(v == '1'){
					return "<span class='red'>��</span>";
				}else{
					return '��';
				}
			}
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
						alert('�����������Ƶĸ�����ܽ��б༭����');
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

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�����˿',
			icon : 'edit',
			action : function(row, rows, grid) {
		   		if(row.status == 1){
		   			alert('�Ѿ�ȫ�����ƣ����ܼ������иò���');
		   			return false;
		   	    }else{
					showThickboxWin("?model=finance_payables_payables"
						+ "&action=toAddRefund"
						+ "&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
						+ "&width=900");
		   	    }
			}
		}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500,
			plusUrl : '&formType=' + $("#formType").val()
		},
		toDelConfig : {
			text : 'ɾ��',
			/**
			 * Ĭ�ϵ��ɾ����ť�����¼�
			 */
			toDelFn : function(p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getFirstSelectedRow();
				var key = "";
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData['skey_']) {
						key = rowData['skey_'];
					}
				}
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds()
										.toString(),
								skey : key
							},
							success : function(msg) {
								if(msg == 1){
									alert('ɾ���ɹ�');
									show_page();
								}else if(msg == 0){
									alert('ɾ��ʧ��');
								}else{
									alert('���ݣ�' + msg + '�����������˿������ɾ��');
									show_page();
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			},
			action : 'ajaxDelForPayment'
		},
		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName'
		},{
			display : '�������뵥��',
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