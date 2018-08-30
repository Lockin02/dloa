var show_page = function() {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '��ȷ�Ϲ黹����',
		param : {'disposeState' : '9','ExaStatusArr' : '���,����','salesId' : $("#userId").val()},
		isAddAction : false,
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
			name : 'customerName',
			display : '�ͻ�����',
			width : 100,
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
                    case '0' : return '������';
                    case '1' : return '�ʼ���';
                    case '2' : return '�Ѵ���';
                    case '3' : return '�ʼ����';
                    case '8' : return '���';
                    case '9' : return '��ȷ��';
                    default : return v;
                }
            },
            width : 70
        }],
		// ���ӱ������
		subGridOptions : {
//			subgridcheck:true,
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
            showMenuFn : function(row) {
                return row.disposeState == "0" ? true : false;
            },
            toEditFn : function(p,g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toEditManage&id=" + rowData[p.keyField] );
            }
		},
		toViewConfig : {
            toViewFn : function(p,g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id=" + rowData[p.keyField] );
            }
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "9") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toSaleConfirm&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.disposeState == '9') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toDisposeback&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
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
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : '���к�',
			name : 'serialName'
		}]
	});
});