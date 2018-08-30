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
				showModalWin('?model=engineering_resources_task&action=toTaskView&id='
						+ row.taskId
						+ "&taskType="
						+ row.taskType
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : "��������",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isReAll == 'WJS') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ�Ͻ�����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_task&action=ajaxHandle",
						data : {
							id : row.taskId,
							taskType : row.taskType
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ȷ�ϳɹ���');
								show_page(1);
							} else {
								alert("ȷ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '��������',
			key : 'taskType_My',
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
			key : 'isRe_t',
			value : '0',
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
		},{
			display : 'taskId',
			name : 'taskId',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '���񵥺�',
			sortable : true,
			width : 180
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 200
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 200
		}, {
			name : 'managerName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'taskType',
			display : '��������',
			sortable : true,
			process : function(v){
				switch(v){
					case 'ZK' : return '�ڿ���';
					case 'DSG' : return '���깺/������';
					case 'WFDP' : return '�޷�������';
					default : return v;
				}
			}
		}, {
			name : 'isReAll',
			display : '�Ƿ����',
			sortable : true,
			process : function(v){
				switch(v){
					case 'WJS' : return 'δ����';
					case 'YJS' : return '�ѽ���';
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