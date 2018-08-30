var show_page = function(page) {
	$("#awaitborrowGrid").yxsubgrid("reload");
};
$(function() {
	$("#awaitborrowGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'awaitJsonBorrow',
		param : {
			'docStatusArr' : ['WFH', 'BFFH'],
			'docTypeArr' : ['oa_borrow_borrow']
		},
		title : '�����÷����ƻ�',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'planCode',
			display : '�ƻ����',
			sortable : true
		}, {
			name : 'rObjCode',
			display : '����ҵ����',
			width : 120,
			sortable : true
		}, {
			name : 'docId',
			display : '��ͬId',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : '�����ñ��',
			href : '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
			hrefCol : 'docId',
			width : 160,
			sortable : true
		}, {
//			name : 'docName',
//			display : '����������',
//			width : 160,
//			sortable : true
//		}, {
			name : 'createName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'createId',
			display : '������Id',
			hide : true,
			sortable : true
		}, {
			name : 'createSection',
			display : '���벿��',
			sortable : true
		}, {
			name : 'createSectionId',
			display : '���벿��Id',
			hide : true,
			sortable : true
		}, {
			name : 'week',
			display : '�ܴ�',
			width : 40,
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '�´�����',
			width : 70,
			sortable : true
		}, {
			name : 'docType',
			display : '��������',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "���۷���";
				} else if (v == 'oa_sale_lease') {
					return "���޷���";
				} else if (v == 'oa_sale_service') {
					return "���񷢻�";
				} else if (v == 'oa_sale_rdproject') {
					return "�з�����";
				} else if (v == 'oa_borrow_borrow') {
					return "���÷���";
				}
			}
		}, {
			name : 'stockName',
			display : '�����ֿ�',
			sortable : true
		}, {
			name : 'type',
			display : '����',
			width : 40,
			datacode : 'FHXZ',
			sortable : true
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
			name : 'issuedStatus',
			display : '�´�״̬',
			hide : true,
			process : function(v) {
				(v == '1') ? (v = '��') : (v = '��');
				return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '״̬',
			width : 50,
			process : function(v) {
				if (v == 'YWC') {
					return "�����";
				} else if (v == 'WFH') {
					return "δ����";
				} else
					return "���ַ���";
			},
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '�ƻ���������',
			width : 70,
			sortable : true
		}, {
			name : 'isOnTime',
			display : '��ʱ����',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '��') : (v = '��');
				return v;
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
		//���ӱ��м��˸��ֶ�   ����ͺ�   2013.7.5
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productNo',
				width : 100,
				display : '��Ʒ���'
			}, {
				name : 'productName',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'productModel',
				width : 200,
				display : '����ͺ�'
			},{
				name : 'number',
				display : '����',
				width : 50
			}, {
				name : 'unitName',
				display : '��λ',
				width : 50
			}, {
				name : 'executedNum',
				display : '�ѷ�������',
				width : 60
			}]
		},

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			name : 'business',
			text : "���Ƶ�����",
			icon : 'business',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_allocation_allocation&action=toBluePush&relDocId="
						+ row.id + "&relDocType=DBDYDLXFH")
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�ƻ����',
			name : 'planCode'
		}, {
			display : '����ҵ�񵥱��',
			name : 'rObjCode'
		}, {
			display : 'Դ����',
			name : 'docCode'
		}]
	});
});