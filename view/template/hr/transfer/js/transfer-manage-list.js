var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'ExaStatus' : "���",
			'deptId' : $("#deptId").val()
		},
		title : '��������',
		isAddAction:false,
		isEditAction:false,
		isViewAction:false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},  {
				name : 'formCode',
				display : '���ݱ��',
				sortable : true,
				width:120,
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
			},{
				name : 'stateC',
				display : '����״̬',
				width : 70
			},  {
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
				name : 'preJobName',
				display : '����ǰ��λ',
				sortable : true,
				hide : true
			}, {
				name : 'preUnitName',
				display : '����ǰ��˾',
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
				name : 'preJobName',
				display : '����ǰְλ',
				sortable : true
			}, {
				name : 'afterJobName',
				display : '������λ����',
				sortable : true,
				hide : true
			}, {
				name : 'afterUnitName',
				display : '������˾',
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
				name : 'afterJobName',
				display : '������ְλ',
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
			}],
			lockCol:['formCode','userNo','userName'],//����������
			menusEx:[
			{  text:'�鿴',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id="
						 + row.id +
						 "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
			   		}
			   }
			},
			{  text:'��д��������',
			   icon:'edit',
			   showMenuFn : function(row) {
				if (row.employeeOpinion==1 && row.status == 3) {
					return true;
				}
				return false;
				},
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toLeaderView&id="+ row.id;
			   		}
			   }
			}],
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		},{
			display : 'Ա�����',
			name : 'userNoSearch'
		},{
			display : 'Ա������',
			name : 'userNameSearch'
		},{
			display : '��ְ����',
			name : 'entryDate'
		},{
			display : '��������',
			name : 'applyDate'
		},  {
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