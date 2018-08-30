var show_page = function(page) {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '��ȷ�Ϲ黹����',
		param : {'disposeStateNot' : '8','ExaStatusArr' : '���,����'},
		isViewAction : false,
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
			display : '�⳥״̬',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '����';break;
					case '1' : return '�������⳥��';break;
					case '2' : return '������⳥��';break;
				}
			}
		}, {
			name : 'borrowId',
			display : '���õ�ID',
			sortable : true,
			hide : true
		}, {
			name : 'applyTypeName',
			display : '��������',
			width : 60,
			sortable : true
		}, {
			name : 'Code',
			display : '���ݱ��',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '���õ����',
			sortable : true,
			width : 100
		}, {
			name : 'borrowLimit',
			display : '��������',
			width : 60,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			width : 90
		}, {
			name : 'salesName',
			display : '�豸������',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '�����˲���',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 130
		}, {
            name : 'disposeState',
            display : '����״̬',
            sortable : true,
            process : function(v) {
                switch(v){
                    case '0' : return '������';break;
                    case '1' : return '���ڴ���';break;
                    case '2' : return '�Ѵ���';break;
                    case '8' : return '���';break;
                    default : return '--';
                }
            },
            width : 70
        }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_borrowreturnequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'returnId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				display : '���ϱ��',
				name : 'productNo',
				width : 80
			},{
				display : '��������',
				name : 'productName',
				width : 130
			}, {
				display : '����黹����',
				name : 'number',
				width : 80
			}, {
				display : '�����ʼ�����',
				name : 'qualityNum',
				width : 80
			}, {
				display : '�ϸ�����',
				name : 'qPassNum',
				width : 60
			}, {
				display : '���ϸ�����',
				name : 'qBackNum',
				width : 80
			}, {
				display : '���´�黹����',
				name : 'disposeNumber',
				width : 90
			}, {
				display : '���´��������',
				name : 'outNum',
				width : 90
			}, {
				display : '���´��⳥����',
				name : 'compensateNum',
				width : 90
			}, {
				name : 'serialName',
				display : '���к�',
				width : 150
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
							+ row.id + "&skey=" + row['skey_']
						,1,500,1000,row.id);
				}
			}
		},{
			text : '�´��⳥��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == "1") {//������豸��ʧ��ֱ��ȷ��
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=finance_compensate_compensate&action=toAdd&relDocId="
						+ row.id + "&skey=" + row['skey_'] + "&relDocType=PCYDLX-01"
						,1,700,1100,row.id);
				}
			}
		},{
			text : '����⳥',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == "1" && row.applyType == 'JYGHSQLX-01') {//�豸��ʧ���ܽ��д˲���
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ���д˲�����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxState",
						data : {
							id : row.id,
							state : 2
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								show_page();
							}else{
							    alert('����ʧ�ܣ�');
							}
						}
					});
				}
			}
		}],
		//��������
		comboEx : [{
			text : '�⳥״̬',
			key : 'states',
            value : '1',
			data : [{
				text : '����',
				value : '0'
			}, {
				text : '�������⳥��',
				value : '1'
			}, {
				text : '�������⳥��',
				value : '2'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�黹�����',
			name : 'Code'
		}, {
			display : '���õ����',
			name : 'borrowCodeSearch'
		}, {
			display : '����������',
			name : 'createName'
		}, {
			display : '����������',
			name : 'salesName'
		}]
	});
});