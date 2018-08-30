var show_page = function(page) {
	$("#viewlistGrid").yxgrid("reload");
};
$(function() {
	$("#viewlistGrid").yxgrid({
		model : 'projectmanagent_trialproject_extension',
		action : "extPageJson",
		param : {'trialprojectId' : $("#proId").val()},
		title : '��������',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trialprojectCode',
			display : '������Ŀ���',
			width : 130,
			sortable : true
		}, {
			name : 'affirmMoneyOld',
			display : 'ԭȷ�Ͻ��',
			sortable : true
		}, {
			name : 'endDateOld',
			display : 'ԭ����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'extensionDate',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'newProjectDays',
			display : '�����ӳ���',
			sortable : true
		}, {
			name : 'budgetAll',
			display : 'Ԥ��',
			sortable : true
		}, {
			name : 'feeAllCount',
			display : '����',
			sortable : true
		}, {
			name : 'affirmMoney',
			display : '����',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			width : 130,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : '',
			display : '�̻�',
			sortable : true
		}, {
			name : 'extensionTime',
			display : '���ڴ���',
			sortable : true,
			process : function(v,row){
							return "<span>��"+v+"��</span>";
					}
		}, {
			name : 'costReason',
			display : '����',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
        // ��չ�Ҽ��˵�
        menusEx : [{
            text : '�������',
            icon : 'view',
            showMenuFn : function(row) {
                if (row.ExaStatus == '��������') {
                    return true;
                }
                return false;
            },
            action : function(row) {

                showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_extension&pid='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
            }
        }],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '������Ŀ���',
			name : 'trialprojectCode'
		}, {
			display : '������',
			name : 'createName'
		}]
	});
});