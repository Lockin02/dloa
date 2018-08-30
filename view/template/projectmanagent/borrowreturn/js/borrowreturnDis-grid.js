var show_page = function(page) {
	$("#returnDisGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnDisGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '�������黹ȷ�ϵ�',
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
			name : 'state',
			display : '��ʾ��',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_yellow.png" title="���黹"/>';
					case '1' : return '<img src="images/icon/cicle_blue.png" title="���ֹ黹"/>';
					case '2' : return '<img src="images/icon/cicle_green.png" title="�ѹ黹"/>';
				}
			},
			width : 50
		}, {
			name : 'borrowreturnId',
			display : '�黹��id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowreturnCode',
			display : '�黹�����',
			sortable : true,
			width : 130
		}, {
			name : 'borrowId',
			display : '���õ�ID',
			sortable : true,
			hide : true
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
			name : 'borrowreturnMan',
			display : '������',
			sortable : true,
			width : 60
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 100,
			sortable : true
		}, {
			name : 'chargerName',
			display : '�豸������',
			sortable : true,
			width : 60
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
			width : 60
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 140
		}, {
			name : 'Code',
			display : '�������',
			sortable : true,
			width : 130
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
			text : '�黹����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state != '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid,g) {
				if (row) {
					var idArr = g.getSubSelectRowCheckIds(rows);
					showModalWin("?model=stock_allocation_allocation&action=toPushReturn&relDocId="
							+ row.id +"&equIdArr="+idArr
							+ "&relDocType=DBDYDLXGH");
				}
			}
		}],
		comboEx : [{
			text : '�黹״̬',
			key : 'states',
			value : '0,1,3,4',
			data : [{
				text : 'δ�黹',
				value : '0,1,3,4'
			}, {
				text : '�ѹ黹',
				value : '2'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�黹�����',
			name : 'borrowreturnCode'
		}, {
			display : '���õ����',
			name : 'borrowCode'
		}, {
			display : '�������',
			name : 'Code'
		}, {
			display : '����������',
			name : 'borrowreturnMan'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : '���к�',
			name : 'serialName'
		}]
	});
});