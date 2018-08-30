var show_page = function(page) {
	$("#listbyorderGrid").yxsubgrid("reload");
};
$(function() {
	$("#listbyorderGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'pageByOrderId',
		param : {
			docId : $('#docId').val(),
			docType : $('#docType').val()
		},
		title : '�����ƻ�',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'listbyorderGrid',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'week',
			display : '�ܴ�',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 150,
			sortable : true
		}, {
			name : 'planCode',
			display : '�ƻ����',
			width : 90,
			hide : true,
			sortable : true
		}, {
			name : 'docId',
			display : 'Դ��Id',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : 'Դ����',
			width : 180,
			sortable : true
		}, {
			name : 'docName',
			display : 'Դ������',
			width : 180,
			hide : true,
			sortable : true
		}, {
			name : 'docType',
			display : '��������',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_contract_contract') {
					return "��ͬ����";
				}else if (v == 'oa_contract_exchangeapply'){
				    return "��������";
				}else if (v == 'oa_borrow_borrow'){
				    return "���÷���";
				}else if (v == 'oa_present_present'){
				    return "���ͷ���";
				}
			}
		}, {
			name : 'isTemp',
			display : '�Ƿ���',
			width : 60,
			process : function(v){
					(v == '1')? (v = '��'):(v = '��');
					return v;
			},
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '�´�����',
			width : 75,
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '�����ֿ�',
			sortable : true,
			hide : true
		}, {
			name : 'type',
			display : '����',
			datacode : 'FHXZ',
			width : 70,
			sortable : true,
			hide : true
		}, {
			name : 'purConcern',
			display : '�ɹ���Ա��ע�ص�',
			hide : true,
			sortable : true
		}, {
			name : 'shipConcern',
			display : '������Ա��ע',
			hide : true,
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 75,
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '�ƻ���������',
			width : 75,
			sortable : true
		}, {
			name : 'status',
			display : '����״̬',
			width : 70,
			process : function(v){
				if( v == 'YZX' ){
					return "��ִ��";
				}else if( v == 'BFZX' ){
					return "����ִ��";
				}else if( v == 'WZX' ){
					return "δִ��";
				}else{
					return "δִ��";
				}
			},
			sortable : true
		}, {
			name : 'isOnTime',
			display : '�Ƿ�ʱ����',
			width : 80,
			process : function(v){
					(v == '1')? (v = '��'):(v = '��');
					return v;
			},
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			width : 60,
			process : function(v){
					(v == '1')? (v = '���´�'):(v = 'δ�´�');
					return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			width : 70,
			process : function(v){
				if( v == 'YWC' ){
					return "�ѷ���";
				}else if( v == 'BFFH' ){
					return "���ַ���";
				}else if( v == 'YGB' ){
					return "ֹͣ����";
				}else
					return "δ����";
			},
			sortable : true
		}, {
			name : 'delayType',
			display : '����ԭ�����',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : 'δ������ԭ��',
			hide : true,
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
						paramId : 'mainId',// ���ݸ���̨�Ĳ�������
						colId : 'id'// ��ȡ���������ݵ�������
					}],
			// ��ʾ����
			colModel : [{
						name : 'productNo',
						width : 150,
						display : '��Ʒ���'
					}, {
						name : 'productName',
						width : 200,
						display : '��Ʒ����',
						process : function(v, data, rowData,$row) {
							if (data.BToOTips == 1) {
								$row.attr("title", "������Ϊ������ת���۵�����");
								return "<img src='images/icon/icon147.gif' />"+v;
							}
							return v;
						}
					}, {
					    name : 'number',
					    display : '����',
						width : 50
					},{
					    name : 'unitName',
					    display : '��λ',
						width : 50
					},{
					    name : 'executedNum',
					    display : '�ѷ�������',
						width : 60
					}]
		},


		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�ƻ����',
			name : 'planCode'
		}]
	});
});