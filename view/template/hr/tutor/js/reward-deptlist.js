var show_page = function(page) {
	$("#rewardGrid").yxgrid("reload");
};
$(function() {
	$("#rewardGrid").yxgrid({
		model : 'hr_tutor_reward',
		title : '��ʦ��������',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_tutor_reward&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
//		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		bodyAlign:'center',
		customCode : 'hr_reward_deptlist',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'code',
			display : '���',
			sortable : true,
			width : 100,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=hr_tutor_reward&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'name',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 80,
			sortable : true
		}, {
			name : 'isGrant',
			display : '��������״̬',
			width : 80,
			sortable : true,
			process: function (v,row){
			   if(v=='0'){
			      return "δ����";
			   }else if(v==1){
			      return "�ѷ���";
			   }
			}
		}, {
			name : 'isPublish',
			display : '������Ϣ�Ƿ񷢲�',
			sortable : true,
			width : 80,
			process: function (v){
			   if(v=='0'){
			      return "��";
			   }else if(v==1){
			      return "��";
			   }
			},
			width :120
		}, {
			name : 'createName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			width : 100,
			sortable : true
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toViewByDept'
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
               showModalWin("?model=hr_tutor_reward&action=toEdit&id="+row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}

		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_reward&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#rewardGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		searchitems : [{
			display : "���",
			name : 'code'
		},{
			display : "��������",
			name : 'name'
		}]
	});
});