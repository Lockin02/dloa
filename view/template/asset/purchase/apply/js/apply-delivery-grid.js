var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		action : 'deliJson',
		param : {
			"ExaStatus" : '���',
			"state" : '���ύ'
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
//					name : 'purchCategory',
//					display : '�ɹ�����',
//					sortable : true,
//					datacode : 'CGZL',
//					width : 120
//				}, {
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
					name : 'assetUse',
					display : '�ʲ���;',
					sortable : true,
					width : 120
				}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			/**
			 * ��ʾ��ѡ������
			 */
			checkShowFn : function(rowData) {
				if (rowData.productName) {
					return true;
				}
				return false;
			},
			url : '?model=asset_purchase_apply_applyItem&action=delPageJson',
			param : [{
						paramId : 'applyId',
						colId : 'id'
					}],
			colModel : [{
						display : '��������',
						name : 'productName',
						tclass : 'readOnlyTxtItem'
						,
						process : function(v,row){
							if( v=='' ){
								return row.inputProductName;
							}else{
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
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initDeliRequire&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000');
			}
		}, {
//			text : '��ֲɹ�',
//			icon : 'add',
//			action : function(row) {
//				showThickboxWin('?model=asset_purchase_apply_apply&action=initDelAssign&id='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100');
//			}
//		}, {
			text : '������Ա',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus != '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var id = $(this).attr('id');
				var skeyVal = $("#check" + id).val();
				location = '?model=asset_purchase_apply_apply&action=toConfirmUser&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
			}
		}, {
			text : '�´�ɹ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				location = '?model=purchase_task_basic&action=toAddTask&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
			}
		}],
		buttonsEx : [{
			text : '�´�ɹ�����',
			icon:'add',
			action : function() {

				var ids = $("#requirementGrid")
						.yxsubgrid("getAllSubSelectRowCheckIds");
				location = '?model=purchase_task_basic&action=toAddTaskByIds&idArr='
						+ ids
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
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