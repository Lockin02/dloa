var show_page = function(page) {
	$("#investigationGrid").yxgrid("reload");
};

$(function() {
	$("#investigationGrid").yxgrid({
		model : 'hr_recruitment_investigation',
		title : '���������¼��',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		param : {
			InvestigationManId : $("#userAccount").val(),
		},

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			process : function (v, row) {
				return "<a href='#' onclick='location=\"?model=hr_recruitment_investigation&action=toView&id=" + row.id +"\"'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '��������',
			sortable : true
		},{
			name : 'state',
			display : '״̬',
			hide : true,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '���״̬',
			hide : true,
			sortable : true
		},{
			name : 'interviewType',
			display : '��������',
			hide : true,
			sortable : true
		},{
			name : 'userName',
			display : '����',
			sortable : true
		},{
			name : 'sex',
			display : '�Ա�',
			width : 40,
			sortable : true
		},{
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		},{
			name : 'deptName',
			display : '���˲���',
			sortable : true
		},{
			name : 'consultationName',
			display : '��ѯ������',
			sortable : true
		},{
			name : 'consultationCompanyName',
			display : '��ѯ�˹�˾����',
			sortable : true
		},{
			name : 'consultationPostiton',
			display : '��ѯ��ְλ',
			sortable : true
		},{
			name : 'consultationTel',
			display : '��ѯ�˵绰',
			sortable : true
		},{
			name : 'consultationEmail',
			display : '��ѯ������',
			sortable : true
		},{
			name : 'workBeginDate',
			display : '������ʼʱ��',
			sortable : true
		},{
			name : 'workEndDate',
			display : '��������ʱ��',
			sortable : true
		},{
			name : 'userCompany',
			display : '��ѡ�˹�˾����',
			sortable : true
		},{
			name : 'userPosition',
			display : '��ѡ��ְλ����',
			sortable : true
		},{
			name : 'relationshipName',
			display : '����ѯ�˹�ϵ',
			sortable : true
		},{
			name : 'InvestigationMan',
			display : '������',
			sortable : true
		},{
			name : 'InvestigationDate',
			display : '����ʱ��',
			sortable : true
		},{
			name : 'ExaDT',
			display : '�������',
			hide : true,
			sortable : true
		}],

		lockCol:['formCode','formDate','userName'],//����������

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		menusEx:[{
			text:'�鿴',
			icon:'view',
			action:function(row ,rows ,grid) {
				if(row) {
					location = "?model=hr_recruitment_investigation&action=toView&id=" + row.id;
				}
			}
		}],

		buttonsEx : [{
			text : '����',
			icon : 'add',
			action : function (row) {
				location = "?model=hr_recruitment_investigation&action=toAdd";
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			action : function (row) {
				if(row) {
					if(window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=hr_recruitment_investigation&action=ajaxdeletes",
							data : {
								id:row.id
							},
							success:function(msg) {
								if(msg == 1) {
									alert('ɾ���ɹ�!');
									show_page();
								} else {
									alert('ɾ��ʧ��!');
									show_page();
								}
							}
						});
					}
				}
			}
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode_d'
		},{
			display : "��������",
			name : 'formDate'
		},{
			display : "����",
			name : 'userName'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "ӦƸ��λ",
			name : 'positionsName'
		},{
			display : "���˲���",
			name : 'deptName'
		},{
			display : "��ѯ������",
			name : 'consultationName'
		},{
			display : "��ѯ�˹�˾����",
			name : 'consultationCompanyName'
		},{
			display : "��ѯ��ְλ",
			name : 'consultationPostiton'
		},{
			display : "��ѯ�˵绰",
			name : 'consultationTel'
		},{
			display : "��ѯ������",
			name : 'consultationEmail'
		},{
			display : "������ʼʱ��",
			name : 'workBeginDate'
		},{
			display : "��������ʱ��",
			name : 'workEndDate'
		},{
			display : "��ѡ�˹�˾����",
			name : 'userCompany'
		},{
			display : "��ѡ��ְλ����",
			name : 'userPosition'
		},{
			display : "����ѯ�˹�ϵ",
			name : 'relationshipName'
		},{
			display : "������",
			name : 'InvestigationMan'
		},{
			display : "����ʱ��",
			name : 'InvestigationDate'
		}]
	});
 });