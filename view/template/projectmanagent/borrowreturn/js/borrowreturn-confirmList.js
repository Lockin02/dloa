var show_page = function() {
	$("#myreturnGrid").yxsubgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '��ȷ�Ϲ黹����',
		param : {'disposeStateNot' : '8','ExaStatusArr' : '���,����'},
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
			name : 'disposeState',
			display : '��ʾ��',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_yellow.png" title="������"/>';
					case '1' : return '<img src="images/icon/cicle_blue.png" title="�ʼ���"/>';
					case '2' : return '<img src="images/icon/cicle_grey.png" title="�Ѵ���"/>';
                    case '3' : return '<img src="images/icon/cicle_green.png" title="�ʼ����"/>';
//                    case '9' : return '<img src="images/icon/cicle_black.png" title="�ȴ�����ȷ��"/>';
				}
			},
			width : 50
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
//                    case '9' : return '�ȴ�����ȷ��';
                    default : return v;
                }
            },
            width : 80
        }, {
            name : 'receiveStatus',
            display : '����ȷ��',
            sortable : true,
            process : function(v) {
                switch(v){
	            	case '0' : return '��';
	                case '1' : return '��';
                }
            },
            width : 60
        }, {
			name : 'receiveId',
			display : '����ȷ����id',
			sortable : true,
			hide : true
		}, {
			name : 'receiveName',
			display : '����ȷ����',
			sortable : true,
			width : 90
		}, {
			name : 'receiveTime',
			display : '����ȷ��ʱ��',
			sortable : true,
			width : 130
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
			text : '�ύ�ʼ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "0" && row.applyType == 'JYGHSQLX-01') {//�黹�Ĳ��д˲���
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
						+ row.id
						+ "&relDocType=ZJSQYDGH"
						+ "&relDocCode=" + row.Code
						,1,500,1000,row.id
					);
				}
			}
		},{
			text : '�´�黹ȷ�ϵ�',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.disposeState == "3" && row.receiveStatus == '1') || (row.disposeState != "2" && row.applyType == 'JYGHSQLX-02')) {//������豸��ʧ��ֱ��ȷ��
					return true;
				}
				return false;
			},
			action : function(row,rows,grid,g) {
				if (row) {
                    showModalWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=toAdd&borrowreturnId="
						+ row.id + "&skey=" + row['skey_'] + "&borrowreturnCode=" + row.Code
						,1,row.id);
				}
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.disposeState == '0' && row.applyType == 'JYGHSQLX-01') {
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
		},{
			text : '����ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.disposeState == "3" && row.receiveStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(confirm("ȷ��Ҫ����ȷ�ϣ�")){
						$.ajax({
							url:'?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxReceive&id=' + row.id,
							type:'get',
							dataType:'json',
							success:function(msg){
								if(msg==1){
									show_page();
									alert('�����ɹ�');
								}else
									alert('����ʧ��');
							},
							error:function(){
								alert('����������ʧ��');
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		//��������
		comboEx : [{
			text : '����״̬',
			key : 'disposeStates',
			value : '0,1,3',
			data : [{
				text : '������',
				value : '0'
			}, {
				text : '�ʼ���',
				value : '1'
			}, {
                text : '�ʼ����',
                value : '3'
            }, {
				text : '�Ѵ���',
				value : '2'
			}, {
//				text : '�ȴ�����ȷ��',
//				value : '9'
//			}, {
				text : '�������ʼ��С��ʼ����',
				value : '0,1,3'
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
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : '���к�',
			name : 'serialName'
		}, {
            display : '��ע',
            name : 'remark'
        }]
	});
});