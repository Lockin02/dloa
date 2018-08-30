var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};
$(function() {
	$("#basicGrid").yxgrid({
		model : 'outsourcing_approval_basic',
		title : '�������',
        isAddAction:false,
        isDelAction:false,
        isEditAction:false,
        isViewAction:false,
        isOpAction:false,
        showcheckbox:false,
		bodyAlign:'center',
		param:{"createId":$("#createId").val()},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
            width:155,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_approval_basic&action=toViewTab&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'applyCode',
			display : '���������',
            width:155,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.applyId +"\",1)'>" + v + "</a>";
			}
		},  {
			name : 'projectCode',
			display : '��Ŀ���',
            width:125,
			sortable : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
            width:125,
			sortable : true
		},{
			name : 'outsourcingName',
			display : '�����ʽ',
            width:65,
			sortable : true
		},{
			name : 'outContractCode',
			display : '������',
            width:125,
			sortable : true
		},  {
			name : 'suppName',
			display : '�����Ӧ��',
            width:125,
			sortable : true
		},{
			name : 'projectTypeName',
			display : '��Ŀ����',
            width:55,
			sortable : true
		},  {
			name : 'saleManangerName',
			display : '���۸�����',
            width:105,
			sortable : true
		}, {
			name : 'projectManangerName',
			display : '��Ŀ����',
            width:105,
			sortable : true
		},  {
			name : 'payTypeName',
			display : '���ʽ',
            width:55,
			sortable : true
		}, {
			name : 'taxPoint',
			display : '��ֵ˰ר�÷�Ʊ˰��',
            width:105,
			sortable : true
		},  {
			name : 'ExaStatus',
			display : '����״̬',
            width:65,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
            width:55,
			sortable : true
		}, {
			name : 'createTime',
			display : '¼������',
            width:120,
			sortable : true
		}],
				//��������
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
					text : 'δ����',
					value : 'δ����'
				},{
					text : '��������',
					value : '��������'
				},{
					text : '���',
					value : '���'
				},{
					text : '���',
					value : '���'
				}]
			},{
			text : '�����ʽ',
			key : 'outsourcing',
			datacode : 'HTWBFS'
			}
		],

    menusEx:[{
            text : "�鿴",
            icon : 'view',
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id="+row.id,'1');
             }
        },
			{
            text : "�༭",
            icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus =='δ����'||row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toEdit&id="+row.id,'1');
           }
        },{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus =='δ����'||row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_approval_basic&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#basicGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
            text : "�ύ����",
            icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus =='δ����'||row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
            action : function(row) {
                showThickboxWin('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId='+ row.id+ '&flowMoney=0&billDept='+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
            }
        },
		        	{
		            text : "������",
		            icon : 'delete',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���') {
							return true;
						}
						return false;
					},
		            action : function(row) {
		                    showModalWin("?model=outsourcing_approval_basic&action=toChange&id="+row.id,'1');
		             }
		        },
		        	{
		            text : "����Y��",
		            icon : 'add',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���') {
							return true;
						}
						return false;
					},
		            action : function(row) {
		                    showModalWin("?model=outsourcing_account_basic&action=toAdd&appId="+row.id,'1');
		             }
		        },
		        	{
					name : 'aduit',
					text : '�������',
					icon : 'view',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_approval&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				}
	],
		searchitems : [{
							display : "���ݱ��",
							name : 'formCode'
						},{
							display : "���������",
							name : 'applyCode'
						},{
							display : "��Ŀ���",
							name : 'projectCode'
						},{
							display : "��Ŀ����",
							name : 'projectName'
						},{
							display : "������",
							name : 'outContractCode'
						},{
							display : "�����Ӧ��",
							name : 'suppName'
						},{
							display : "��Ŀ����",
							name : 'projectTypeName'
						},{
							display : "���۸�����",
							name : 'saleManangerName'
						},{
							display : "��Ŀ����",
							name : 'projectManangerName'
						}]
					});
});