var show_page = function(page) {
	$("#rdGrid").yxsubgrid("reload");
};
$(function() {
	$("#rdGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		param : {
			"ExaStatus" : '���',
			"purchType" : 'rdproject'
		},
		title : '�з��豸�ɹ�����',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true
		}, {
			name : 'applyTime',
			display : '��������',
			sortable : true,
			width : 120
		}, {
			name : 'applicantName',
			display : '����������',
			sortable : true,
			width : 120
		}, {
			name : 'rdProjectCode',
			display : '�з�ר����',
			sortable : true,
			width : 120
		}, {
			name : 'rdProject',
			display : '�з�ר����Ŀ',
			sortable : true,
			width : 120
		}, {
			name : 'assetUse',
			display : '�ʲ���;',
			sortable : true,
			width : 120
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				display : '�豸����',
				name : 'productCode'
			}, {
				display : '�豸����',
				name : 'productName'
			}, {
				display : '����ͺ�',
				name : 'pattem'
			}, {
				display : '��Ӧ��',
				name : 'supplierName'
			}, {
				display : '��λ',
				name : 'unitName'
			}, {
				display : '����',
				name : 'applyAmount'
			}, {
				display : 'ϣ����������',
				name : 'dateHope'
			}, {
				display : '�豸ʹ������',
				name : 'life'
			}, {
				display : 'Ԥ�ƹ��뵥��',
				name : 'exPrice',
				tclass : 'txtshort',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '�Ƿ�����̶��ʲ�',
				name : 'isAsset'
			}, {
				display : '��ע',
				name : 'remark',
				tclass : 'txt'
			}]
		},
//			buttonsEx : [{
//                name : 'Add',
//                text : "���",
//                icon : 'add',
//                action : function() {
//					showThickboxWin('?model=asset_purchase_apply_apply&action=toRDAdd&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//                }
//
//			}],
			menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=RDinit&id='
						+ row.id
						+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		,{
//			text : '�༭',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=asset_purchase_apply_apply&action=RDinit&id='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//			}
//		}
		],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		}, {
			display : '��������',
			name : 'applyTime'
		}, {
			display : '������',
			name : 'applicantName'
		}, {
			display : '�豸����',
			name : 'productName'
		}]
	});
});