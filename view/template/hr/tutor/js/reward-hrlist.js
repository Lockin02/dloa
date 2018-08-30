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
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_rewardlist',
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
			width : 120,
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
			width : 170
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'isGrant',
			display : '��������״̬',
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
			process: function (v){
			   if(v=='0'){
			      return "��";
			   }else if(v==1){
			      return "��";
			   }
			},
			width :100
		},{
			name : 'createName',
			display : '������',
			sortable : true,
			width : 70
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width :80
		}],
		toAddConfig : {
			formWidth:900
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_tutor_reward&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : 'ȷ�Ϸ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���'&&row.isPublish=='0') {
					return true;
				}
				return false;
			},
			action:function(row){
				if(window.confirm(("ȷ��Ҫ�������������Ϣ?"))){
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_reward&action=publish",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#rewardGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		},{
			text : 'ȷ�Ͻ�������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isGrant == '0'&&row.ExaStatus =="���") {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_tutor_reward&action=toGrant&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}

		}],
				// Ĭ�������ֶ���
				sortname : "code",
				// Ĭ������˳��
				sortorder : "asc",
		searchitems : [{
			display : "���",
			name : 'code'
		},{
			display : "��������",
			name : 'name'
		},{
		    display : "��������",
		    name : 'dept'
		},{
		    display : "������",
		    name : 'createName'
		}]
	});
});