var show_page = function(page) {
	$("#returnDisGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnDisGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '������黹ȷ�ϵ�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowId',
			display : '���õ�ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '�������',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '���õ����',
			sortable : true
		}, {
			name : 'borrowLimit',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'applyTypeName',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'chargerName',
			display : '�豸������',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '�����˲���',
			sortable : true,
			width : 80
		}, {
			name : 'state',
			display : '�黹״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "���黹";
				} else if (v == '1') {
					return "���ֹ黹";
				} else if (v == '2') {
					return "�ѹ黹";
				} else if (v == '3') {
					return "�⳥��ȷ��";
				} else if (v == '4') {
					return "ȷ�����";
				} else{
				    return "--";
				}
			},
			width : 70
		}, {
			name : 'disposeState',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "�������";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "�ѳ���";
				} else{
				    return "--";
				}
			},
			width : 70
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			width : 90
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 140
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'disposeId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				display : '���ϱ��',
				name : 'productNo'
			},{
				display : '��������',
				name : 'productName',
				width : 160
			}, {
				display : '���黹����',
				name : 'disposeNum',
				width : 80
			}, {
				display : '�ѹ黹����',
				name : 'backNum',
				width : 80
			}, {
				display : '����������',
				name : 'outNum',
				width : 80
			}, {
				display : '�ѳ�������',
				name : 'executedNum',
				width : 80
			}, {
				name : 'serialName',
				display : '���к�',
				width : 140
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
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.disposeState == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid,g) {
				if (row) {
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKOTHERGH"
							+ "&relDocType=DBDYDLXGH");
				}
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'disposeState',
			value : '1',
			data : [{
				text : '�������',
				value : '0'
			}, {
				text : '������',
				value : '1'
			}, {
				text : '�ѳ���',
				value : '2'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�������',
			name : 'Code'
		}, {
			display : '���õ����',
			name : 'borrowCode'
		}]
	});
});