var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		// action : 'adminJson',
		param : {
			"ExaStatus" : '���'
			,"state" : '���ύ'
		},
		title : '�ʲ��ɹ�����',
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
			sortable : true,
			width : 120
		}, {
			name : 'applyTime',
			display : '��������',
			sortable : true
		}, {
			name : 'applicantName',
			display : '����������',
			sortable : true,
			width : 120
		}, {
			name : 'userName',
			display : 'ʹ��������',
			sortable : true,
			width : 120
		}, {
			name : 'useDetName',
			display : 'ʹ�ò���',
			sortable : true
		}, {
			name : 'purchCategory',
			display : '�ɹ�����',
			sortable : true,
			datacode : 'CGZL',
			width : 120
		}, {
			name : 'assetUse',
			display : '�ʲ���;',
			sortable : true,
			width : 120
		}, {
			name : 'estimatPrice',
			display : 'Ԥ���ܼ�',
			sortable : true,
			width : 150,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'moneyAll',
			display : '�ܽ��',
			sortable : true,
			width : 150,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				display : '��������',
				name : 'productName',
				tclass : 'readOnlyTxtItem',
				process : function(v,row) {
					if( v == '' ){
						return row.inputProductName;
					}
						return v;
				}
			}, {
				display : '���',
				name : 'pattem',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '��������',
				name : 'applyAmount',
				tclass : 'txtshort'
			}, {
				display : '��Ӧ��',
				name : 'supplierName',
				tclass : 'txtmiddle'
			}, {
				display : '��λ',
				name : 'unitName',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '�ɹ�����',
				name : 'purchAmount',
				tclass : 'txtshort'
			}, {
				display : '����',
				name : 'price',
				tclass : 'txtshort',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '���',
				name : 'moneyAll',
				tclass : 'txtshort',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : 'ϣ����������',
				name : 'dateHope',
				type : 'date'
			}, {
				display : '��ע',
				name : 'remark',
				tclass : 'txt'
			}, {
				display : '�ɹ�����',
				name : 'purchDept',
				tclass : 'txt',
				process : function($input, rowData) {
					if (rowData.purchDept == '0') {
						return '������';
					} else if (rowData.purchDept == '1') {
						return '������';
					}
				}
			}]
		},
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initAdminRequire&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000');
			}
		}, {
			text : '��ֲɹ�',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initAssign&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1100');
			}
		}, {
			text : '�´�ɹ�����',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_task_task&action=toAdd&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=1100');
			}
		}],
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
			display : 'ʹ��������',
			name : 'userName'
		}, {
			display : '��������',
			name : 'productName'
		}]
	});
});