var show_page = function(page) {
	$("#innoticeGrid").yxsubgrid("reload");
};
$(function() {
	$("#innoticeGrid").yxsubgrid({
		model : 'stock_withdraw_innotice',
		param : {docType : 'oa_produce_plan'},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : true,
		isDelAction : false,
		title : '���֪ͨ��',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'noticeCode',
			display : '���֪ͨ����',
			sortable : true
		}, {
//			name : 'drawId',
//			display : '�ջ�֪ͨId',
//			sortable : true,
//			hide : true
//		}, {
//			name : 'drawCode',
//			display : '�ջ�֪ͨ���',
//			width : 110,
//			sortable : true
//		}, {
			name : 'rObjCode',
			display : 'Դ��ҵ�����',
			width : 110,
			sortable : true,
			hide : true
		}, {
			name : 'docType',
			display : 'Դ������',
			process : function (v){
				if( v == 'oa_produce_plan' ){
					return '�����ƻ���'
				}
			},
			width : 70,
			sortable : true
		}, {
			name : 'docId',
			display : 'Դ����Id',
			sortable : true,
			hide : true
		}, {
			name : 'docCode',
			display : 'Դ����',
			width : 110,
			sortable : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			width : 70,
			sortable : true,
			process : function(v){
				if(v == "YRK"){
					return '�����';
				}else{
					return 'δ���';
				}
			}
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 120,
			sortable : true
		}, {
			name : 'consignee',
			display : '�ջ���',
			width : 70,
			sortable : true
		}, {
			name : 'auditman',
			display : '�����',
			width : 70,
			sortable : true
		}, {
			name : 'receiveDate',
			display : '�ջ�����',
			width : 70,
			sortable : true
		}, {
			name : 'isSign',
			display : '�Ƿ�ǩ��',
			width : 70,
			sortable : true
		}, {
			name : 'signman',
			display : 'ǩ����',
			width : 70,
			sortable : true
		}, {
			name : 'signDate',
			display : 'ǩ������',
			width : 70,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_withdraw_noticeequ&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				width : 100,
				display : '���ϱ��'
			}, {
				name : 'productName',
				width : 170,
				display : '��������'
			}, {
				name : 'productModel',
				width : 170,
				display : '�ͺ�/�汾'
			}, {
				name : 'unitName',
				display : '��λ',
				width : 70
			}, {
				name : 'number',
				display : '����',
				width : 70
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		menusEx : [{
			name : 'bluepush',
			text : '������ⵥ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "WRK") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (row) {
					// alert()
					showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPRODUCT&relDocType=RSCJHD&relDocId="
						+ row.id + "&relDocCode=" + row.noticeCode + "&docId=" + row.docId + "&rObjCode=" + row.rObjCode,1,row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		//��������
		comboEx : [{
			text : '����״̬',
			key : 'docStatus',
			value : 'WRK',
			data : [{
				text : 'δ���',
				value : 'WRK'
			}, {
				text : '�����',
				value : 'YRK'
			}]
		}],
		searchitems : [{
			display : "���֪ͨ����",
			name : 'noticeCodeSearch'
		}]
	});
});