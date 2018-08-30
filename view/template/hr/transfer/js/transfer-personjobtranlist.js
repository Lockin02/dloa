var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'userNo' : $("#userNo").val(),
			'ExaStatus' : "���",
			'status' : '2,3,4'
		},
		title : '��Ա���ڼ�¼',
		isAddAction:false,
		isEditAction:false,
		isViewAction:false,
		isDelAction : false,
		showcheckbox:false,
		isOpButton:false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			width:120,
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width : 80
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width : 80
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'transferTypeName',
			display : '��������',
			sortable : true,
			width : 200
		}, {
			name : 'preUnitTypeName',
			display : '����ǰ��λ',
			sortable : true,
			hide : true
		}, {
			name : 'preUnitName',
			display : '����ǰ��˾',
			sortable : true
		}, {
			name : 'afterUnitTypeName',
			display : '������λ����',
			sortable : true,
			hide : true
		}, {
			name : 'afterUnitName',
			display : '������˾',
			sortable : true
		}, {
			name : 'preBelongDeptName',
			display : '����ǰ��������',
			sortable : true
		}, {
			name : 'afterBelongDeptName',
			display : '��������������',
			sortable : true
		}, {
			name : 'preDeptNameS',
			display : '����ǰ��������',
			sortable : true
		}, {
			name : 'preDeptNameT',
			display : '����ǰ��������',
			sortable : true
		}, {
			name : 'afterDeptNameS',
			display : '�������������',
			sortable : true
		}, {
			name : 'afterDeptNameT',
			display : '��������������',
			sortable : true
		}, {
			name : 'preJobName',
			display : '����ǰְλ',
			sortable : true
		}, {
			name : 'afterJobName',
			display : '������ְλ',
			sortable : true
		}, {
			name : 'preUseAreaName',
			display : '����ǰ��������',
			sortable : true
		}, {
			name : 'afterUseAreaName',
			display : '�������������',
			sortable : true
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			hide : true,
			width : 130
		}, {
			name : 'remark',
			display : '��ע˵��',
			sortable : true,
			hide : true,
			width : 130
		}, {
			name : 'managerName',
			display : '������',
			sortable : true
		}, {
			name : 'employeeOpinion',
			display : 'Ա���Ƿ�ͬ��',
			sortable : true,
			hide : true
		}],
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		//��չ�Ҽ��˵�
		menusEx:[{  text:'�鿴',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toViewJobTran&id="+ row.id;
			   		}
			   }
			},
			{  text:'Ա�����',
			   icon:'edit',
			   showMenuFn : function(row) {
					if (row.employeeOpinion!=1) {
						return true;
				}
				return false;
				},
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toOpinionView&id="+ row.id ;
			   		}
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		}, {
			display : '����ǰ��˾',
			name : 'preUnitName'
		}, {
			display : '����ǰ��������',
			name : 'preBelongDeptName'
		},{
			display : '����ǰ��������',
			name : 'preDeptNameS'
		},{
			display : '����ǰ��������',
			name : 'preDeptNameT'
		}, {
			display : '������˾',
			name : 'afterUnitName'
		}, {
			display : '��������������',
			name : 'afterBelongDeptName'
		}, {
			display : '�������������',
			name : 'afterDeptNameS'
		}, {
			display : '��������������',
			name : 'afterDeptNameT'
		}, {
			display : '����ǰְλ',
			name : 'preJobName'
		}, {
			display : '������ְλ',
			name : 'afterJobName'
		},  {
			display : '����ǰ��������',
			name : 'preUseAreaName'
		}, {
			display : '�������������',
			name : 'afterUseAreaName'
		},{
			display : '������',
			name : 'managerName'
		}]
	});
});