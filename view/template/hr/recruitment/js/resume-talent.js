var show_page = function(page) {
	$("#talentGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [];
	$("#talentGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '�˲ſ�',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'resumeGrid',
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
						+ row.id + "&skey=" + row['skey_'],'1');
			}
		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true
		}, {
			name : 'isInform',
			display : '����֪ͨ',
			sortable : true
		}, {
			name : 'post',
			display : 'ӦƸְλ',
			sortable : true,
			datacode : 'YPZW'
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'email',
			display : '��������',
			sortable : true,
			width : 200
		}],
		comboEx : [{
			text : '��������',
			key : 'resumeType',
			data : [{
				text : '��˾����',
				value : '0'
			}, {
				text : 'Ա������',
				value : '1'
			}, {
				text : '������',
				value : '2'
			}, {
				text : '��������',
				value : '3'
			}, {
				text : '��̭����',
				value : '4'
			}, {
				text : '��ְ����',
				value : '5'
			}, {
				text : '��ְ����',
				value : '6'
			}]
		}],
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�������",
			name : 'resumeCode'
		},{
			display : "ӦƸ������",
			name : 'applicantName'
		}]
	});
});