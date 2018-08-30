var show_page = function(page) {
	$("#exetaskGrid").yxgrid("reload");
};
$(function() {
	$("#exetaskGrid").yxgrid({
		model : 'engineering_resources_task',
		action : 'exetaskJson',
//		param : {"areaPrincipalId" : $("#userId").val()},
		title : '��ϸ����',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=engineering_resources_task&action=toTaskView&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		comboEx : [{
			text : '��������',
			key : 'taskType',
			data : [{
				text : '�ڿ���',
				value : 'ZK'
			}, {
				text : '���깺/������',
				value : 'DSG'
			}, {
				text : '�޷�������',
				value : 'WFDP'
			}]
		},{
			text : '�Ƿ����',
			key : 'isRe',
			data : [{
				text : '�ѽ���',
				value : '1'
			}, {
				text : 'δ����',
				value : '0'
			}]
		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'projectId',
			display : '��Ŀid',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 120
		}, {
			name : 'resourceTypeName',
			display : '�豸����',
			sortable : true
		}, {
			name : 'resourceName',
			display : '�豸����',
			sortable : true
		}, {
			name : 'number',
			display : '����',
			sortable : true
		}, {
			name : 'planBeginDate',
			display : 'Ԥ�ƽ�������',
			sortable : true
		}, {
			name : 'planEndDate',
			display : 'Ԥ�ƹ黹����',
			sortable : true
		}, {
			name : 'useDays',
			display : '��������',
			sortable : true
		}, {
			name : 'price',
			display : '���豸�۾�',
			sortable : true
		}, {
			name : 'amount',
			display : 'ʹ�óɱ�',
			sortable : true
		}, {
			name : 'makeProgress',
			display : '��չ',
			sortable : true,
			hide : true
		}, {
			name : 'area',
			display : '����',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '������',
			sortable : true
		}, {
			name : 'isRe',
			display : '�Ƿ����',
			sortable : true,
			process : function(v){
				switch(v){
					case '0' : return '��';
					case '1' : return '��';
					default : return v;
				}
			}
		}],


		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�豸����",
			name : 'resourceName'
		}]
	});
});