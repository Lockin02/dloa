// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#confirmGrid").yxsubgrid("reload");
};
$(function() {
	$("#confirmGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'asset_purchase_apply_apply',
		action : 'myConfirmListPageJson',
		title : '������ȷ�ϵĲɹ������б�',
		isToolBar : false,
		showcheckbox : false,
		//		param : {
		//			'productSureStatus' : '0'
		//		},

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
			display : 'ȷ��״̬',
			name : 'productSureStatus',
			process : function(v, data) {
				if (v == 0) {
					return "δȷ��";
				} else if (v == 1) {
					return "��ȷ��";
				} else {
					return "����ȷ��";
				}
			}
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
		}],
		comboEx : [{
			text : 'ȷ��״̬',
			key : 'productSureStatusArr',
			data : [{
				text : 'δȷ��',
				value : '0,2'
			}, {
//				text : '����ȷ��',
//				value : 2
//			}, {
				text : '��ȷ��',
				value : '1'
			}],
			value : '0,2'
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCategoryName',
				display : '�������',
				width : 50
			}, {
				name : 'productCode',
				display : '���ϱ��'
			}, {
				name : 'productName',
				width : 200,
				display : '��������',
				process : function(v, data) {
					if (v == "") {
						return data.inputProductName;
					} else {
						return v;
					}
				}
			}, {
				display : '���',
				name : 'pattem',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '��λ',
				name : 'unitName',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '��������',
				name : 'purchAmount',
				tclass : 'txtshort'
			}, {
				display : '�´���������',
				name : 'issuedAmount',
				tclass : 'txtshort'
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
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=asset_purchase_apply_apply&action=toRead&id="
							+ row.id;
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '����ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == 1) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=asset_purchase_apply_apply&action=toConfirmProduct&id="
							+ row.id
							+ "&purchType="
							+ row.purchType
							+ "&skey="
							+ row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		}],
		// ��������
		searchitems : [{
			display : '�ɹ�������',
			name : 'seachPlanNumb'
		}, {
			display : '���ϱ��',
			name : 'productNumb'
		}, {
			display : '��������',
			name : 'productName'
		}, {
			display : '����Դ���ݺ�',
			name : 'sourceNumb'
		}, {
			display : '���κ�',
			name : 'batchNumb'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});