var show_page = function(page) {
	$("#employmentGrid").yxgrid("reload");
};
$(function() {
	$("#employmentGrid").yxgrid({
		model : 'hr_recruitment_employment',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : true,
		isOpButton:false,
		bodyAlign:'center',
		title : 'ְλ�����',

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_employment&action=toView&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '�༭',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_employment&action=toEdit&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_employment&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#employmentGrid").yxgrid("reload");
							}else{
								alert('ɾ��ʧ�ܣ����ڹ������ݣ�');
							}
						}
					});
				}
			}
//		},{
//			text : '�����������',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.resumeType == 2) {
//					return false;
//				}
//				return true;
//			},
//			action : function(row) {
//					showModalWin('?model=hr_recruitment_interview&action=toAddByEmployment&employmentId='
//							+ row.id + "&skey=" + row['skey_']);
//			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'employmentCode',
			display : '���',
			sortable : true,
			width : 130,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'name',
			display : '����',
			width : 60,
			sortable : true
		},{
			name : 'sex',
			display : '�Ա�',
			width : 60,
			sortable : true
		},{
			name : 'nation',
			display : '����',
			width : 60,
			sortable : true
		},{
			name : 'age',
			display : '����',
			width : 60,
			sortable : true
		},{
			name : 'highEducationName',
			display : 'ѧ��',
			width : 80,
			sortable : true
		},{
			name : 'highSchool',
			display : '��ҵѧУ',
			sortable : true
		},{
			name : 'professionalName',
			display : 'רҵ',
			sortable : true
		},{
			name : 'telephone',
			display : '�̶��绰',
			sortable : true
		},{
			name : 'mobile',
			display : '�ƶ��绰',
			sortable : true
		},{
			name : 'personEmail',
			display : '���˵�������',
			sortable : true
		},{
			name : 'QQ',
			display : 'QQ',
			sortable : true
		}],

		lockCol:['employmentCode','name'],//����������

		toAddConfig : {
			formHeight : 500,
			formWidth : 900,
			toAddFn : function(p,g) {
				showModalWin("?model=hr_recruitment_employment&action=toAdd",'1');
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "���",
			name : 'employmentCode'
		},{
			display : "����",
			name : 'name'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "����",
			name : 'nation'
		},{
			display : "����",
			name : 'age'
		},{
			display : "ѧ��",
			name : 'highEducationName'
		},{
			display : "��ҵѧУ",
			name : 'highSchool'
		},{
			display : "רҵ",
			name : 'professionalName'
		},{
			display : "�̶��绰",
			name : 'telephone'
		},{
			display : "�ƶ��绰",
			name : 'mobile'
		},{
			display : "���˵�������",
			name : 'personEmail'
		},{
			display : "QQ",
			name : 'QQ'
		}]
	});
});