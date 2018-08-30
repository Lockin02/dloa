var show_page = function(page) {
	$("#handoverGrid").yxgrid("reload");
};

$(function() {
	$("#handoverGrid").yxgrid({
		model : 'hr_leave_handover',
		title : '��ְ�����嵥',
		action : 'handoverAffirmJson&affirmUserId=' + $("#userId").val(),
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isOpButton : false,
		pageSize : 20, // ÿҳĬ�ϵĽ����
		bodyAlign:'center',

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȷ����ְ�嵥',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isDone == '1' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=affirmList&handoverId=' + row.id);
			}
		},{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toViewAffirmList&handoverId=' + row.id);
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			process : function(v ,row) {
				if(row.isDone == "1") {
					return "<font color=blue>" + v + "</font>";
				} else {
					return  v ;
				}
			},
			width:70
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width:70
		},{
			name : 'companyName',
			display : '��˾',
			sortable : true,
			width:80
		},{
			name : 'regionName',
			display : '����',
			sortable : true,
			width:60
		},{
			name : 'deptName',
			display : '����',
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ',
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			sortable : true
		},{
			name : 'quitDate',
			display : '��ְ����',
			sortable : true
		},{
			name : 'salaryEndDate',
			display : '���ʽ����ֹ����',
			width : 100,
			sortable : true
		},{
			name : 'isDone',
			display : '״̬',
			process : function(v,row){
				if(v == "0" ) {
					return "���"
				} else {
					return  "δ���" ;
				}
			},
			width:70
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		comboEx : [{
			text : '״̬',
			key : 'isDone',
			data : [{
				text : '���',
				value : '0'
			},{
				text : 'δ���',
				value : '1'
			}]
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "Ա������",
			name : 'userName'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "ְλ",
			name : 'jobName'
		}],

		sortorder : "DESC",
		sortname : "id"
	});
});