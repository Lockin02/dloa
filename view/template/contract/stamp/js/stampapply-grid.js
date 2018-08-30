var show_page = function(page) {
	$("#stampapplyGrid").yxgrid("reload");
};
$(function() {
	$("#stampapplyGrid").yxgrid({
		model : 'contract_stamp_stampapply',
		title : '��������',
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '����',
			sortable : true,
			width : 50,
			align : 'center',
			process : function(v,row){
				if(v=="1"){
					return '<img title="�Ѹ���" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}else if(v=='2'){
					return "�ѹر�";
				}else{
					return "δ����";
				}
			}
		}, {
			name : 'applyUserId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'applyUserName',
			display : '������',
			sortable : true,
        	width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
        	width : 75
		}, {
			name : 'contractType',
			display : '�ļ�����',
			sortable : true,
        	width : 70,
        	datacode : 'HTGZYD'
		}, {
			name : 'fileName',
			display : '�ļ���',
			sortable : true
		}, {
			name : 'signCompanyName',
			display : '�ļ�������λ',
			sortable : true,
        	width : 130
		}, {
			name : 'contractMoney',
			display : '��ͬ���',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'stampType',
			display : '��������',
			sortable : true
		}, {
			name : 'stampCompanyId',
			display : '��˾ID',
			sortable : true,
			hide : true
		}, {
			name : 'stampCompany',
			display : '��˾��',
			sortable : true
		},{
			name : 'useMatters',
			display : 'ʹ������',
			sortable : true	
		}, {
			name : 'useMattersId',
			display : 'ʹ������id',
			sortable : true,
			hide : true
		}, {
			name : 'attn',
			display : 'ҵ�񾭰���',
			sortable : true,
			width : 80
		}, {
			name : 'attnId',
			display : 'ҵ�񾭰���Id',
			sortable : true,
			hide : true
		}, {
			name : 'attnDept',
			display : 'ҵ�񾭰��˲���',
			sortable : true,
			hide : true
		}, {
			name : 'attnDeptId',
			display : 'ҵ�񾭰��˲���Id',
			sortable : true,
			hide : true
		}, {
			name : 'isNeedAudit',
			display : '�Ƿ���Ҫ����',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120,
			sortable : true,
			hide : true
		}, {
			name : 'batchNo',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'contractId',
			display : '��ͬid',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
        	width : 130,
			sortable : true
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
        	width : 130
		}, {
			name : 'remark',
			display : '��ע˵��',
			sortable : true
		}],
		toAddConfig : {
			action : 'toAdd',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "��ͬ����",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "����״̬",
			key: 'status',
			value :'0',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѹ���',
				value : '1'
			}, {
				text : '�ѹر�',
				value : '2'
			}]
		}]
	});
});