var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};
$(function() {
	$("#basicGrid").yxgrid({
		model : 'outsourcing_approval_basic',
		title : '�������',
        isViewAction:false,
        isAddAction:false,
        isDelAction:false,
        isEditAction:false,
        isOpAction:false,
        showcheckbox:false,
		bodyAlign:'center',
        param:{ExaStatusArr:'��������,���,���'},
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
			name : 'isAllAddContract',
			display : '���ɺ�ͬ',
            width:65,
			sortable : true,
			process : function(v){
				if(v == 1){
					return '��';
				}else{
					return '��';
				}
			}
		},	{
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
            width:80,
			sortable : true
		}],
				//��������
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
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
		toViewConfig : {
			action : 'toView'
		},

    menusEx:[{
            text : "�鿴",
            icon : 'view',
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id="+row.id,'1');
             }
        },{
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
		},{
            text: '���ɺ�ͬ',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.isAllAddContract != 0) {
                    return false;
                }else
                	return true;
            },
            action: function(row) {
            	if(row.outsourcingName == '����' || row.outsourcingName == 'HTWBFS-03'){
                        showModalWin("?model=contract_outsourcing_outsourcing&action=toAddForApproval&projectId="
                        	+row.id
                        	+"&projectCode="
                        	+row.projectCode
                        	+"&projectName="
                        	+row.projectName
                        	+"&orderMoney="
                        	+row.outSuppMoney
                        	+"&signCompanyName="
                        	+row.suppName
                        	+"&projectType="
                        	+row.projectType
                        	+"&outsourcing="
                        	+row.outsourcing
                        	+"&outsourceType=HTWB03"
                        	+"&payType="
                        	+row.payType
                    	);
            	}else{
            		showModalWin("?model=contract_outsourcing_outsourcing&action=toChooseAdd&projectId="
            				+row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false");
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