var show_page = function(page) {
	$("#resourceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#resourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		title : '��Ŀ�豸�����',
		param : {confirmStatusArr : '3,4,5,7'},
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '��ȷ��',
				sortable : true,
				align : 'center',
				process : function(v) {
					switch(v){
						case '3' : return '<img src="images/icon/cicle_yellow.png" title="��ȷ��"/>';break;
						default : return '';break;
					}
				},
				width : 50
			}, {
				name : 'formNo',
				display : '���뵥���',
				sortable : true,
				width : 120,
				process : function(v, row) {
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
							+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
				}
			}, {
				name : 'applyUser',
				display : '������',
				sortable : true,
				width : 70
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '�����˲���',
				sortable : true,
				width : 70
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 70
			}, {
				name : 'applyTypeName',
				display : '��������',
				sortable : true,
				width : 70
			}, {
				name : 'getTypeName',
				display : '���÷�ʽ',
				sortable : true,
				width : 70
			}, {
				name : 'place',
				display : '�豸ʹ�õ�',
				sortable : true,
				width : 70
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 120
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 120
			}, {
				name : 'managerName',
				display : '��Ŀ����',
				sortable : true,
				width : 80
			}, {
				name : 'managerId',
				display : '��Ŀ����id',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '��ע��Ϣ',
				sortable : true,
				width : 130,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 75
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'confirmStatus',
				display : '����״̬',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case '3' : return '�ȴ�����';break;
						case '4' : return '������';break;
						case '7' : return '���ش�ȷ��';break;
						case '5' : return '���';break;
					}
				}
			}, {
				name : 'status',
				display : '�´�״̬',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case '0' : return 'δ�´�';break;
						case '1' : return '�����´�';break;
						case '2' : return '���´�';break;
						case '3' : return '��ȷ��';break;
					}
				}
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�´﷢������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != '2' && row.ExaStatus == '���' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_task&action=toAdd&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1100,row.id);
				}
			}
//		},{
//			text : 'ȷ�����豸',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status != '2' && row.ExaStatus == '���' ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (row) {
//					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmDetail&id="
//						+ + row.id + "&skey="+ row['skey_'] ,1,600,1100,row.id);
//				}
//			}
		},{
			text : '�����޸�ȷ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmTaskNum&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1200,row.id);
				}
			}
		},{
			text : '����ȷ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.confirmStatus == '7') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmBack&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1200,row.id);
				}
			}
		}],
		comboEx : [{
			text : '�´�״̬',
			key : 'statusArr',
			value : '0,1,3',
			data : [{
				text : 'δ�´�',
				value : '0'
			}, {
				text : '�����´�',
				value : '1'
			}, {
				text : '���´�',
				value : '2'
			}, {
				text : '��ȷ��',
				value : '3'
			}, {
				text : 'δ���',
				value : '0,1,3'
			}]
		},{
			text : '����״̬',
			key : 'confirmStatus',
			data : [{
					text : '�ȴ�����',
					value : '3'
				},{
					text : '������',
					value : '4'
				},{
					text : '���ش�ȷ��',
					value : '7'
				},{
					text : '���',
					value : '5'
				}]
			},{
		     text:'���״̬',
		     key:'ExaStatus',
		     type : 'workFlow',
		     value : "���"
		}],
		searchitems : [{
			display : "���뵥��",
			name : 'formNoSch'
		},{
			display : "��Ŀ���",
			name : 'projectCodeSch'
		},{
			display : "��Ŀ����",
			name : 'projectNameSch'
		}]
	});
});