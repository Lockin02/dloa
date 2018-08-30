var show_page = function(page) {
	$("#processGrid").yxgrid("reload");
};

$(function() {
	$("#processGrid").yxgrid({
		model: 'manufacture_basic_process',
		title: '������Ϣ-����ģ��',
		bodyAlign : 'center',
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'templateName',
			display : 'ģ������',
			sortable : true,
			width : 200,
			align : 'left'
		},{
			name : 'isEnable',
			display : '�Ƿ�����',
			sortable : true,
			width : 50
		},{
			name : 'createName',
			display : '¼����',
			sortable : true
		},{
			name : 'createTime',
			display : '¼��ʱ��',
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 350,
			align : 'left'
		},{
			name : 'createId',
			display : '¼����ID',
			sortable : true,
			hide : true
		},{
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		},{
			name : 'updateId',
			display : '�޸���ID',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		}],

		//��չ�˵�
		buttonsEx : [{
			name : 'start',
			text : "����",
			icon : 'add',
			action : function(row ,rows ,rowIds) {
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫ����?")) {
						$.ajax({
							type : "POST",
							url : "?model=manufacture_basic_process&action=ajaxEnable",
							data : {
								ids : rowIds.join(','),
								isEnable : '��'
							},
							success : function(msg) {
								if (msg == 1) {
									alert('���óɹ���');
									show_page();
								} else {
									alert('����ʧ�ܣ�');
								}
							}
						});
					}
				}
			}
		},{
			name : 'close',
			text : "����",
			icon : 'delete',
			action : function(row ,rows ,rowIds) {
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫ����?")) {
						$.ajax({
							type : "POST",
							url : "?model=manufacture_basic_process&action=ajaxEnable",
							data : {
								ids : rowIds.join(','),
								isEnable : '��'
							},
							success : function(msg) {
								if (msg == 1) {
									alert('���óɹ���');
									show_page();
								} else {
									alert('����ʧ�ܣ�');
								}
							}
						});
					}
				}
			}
		}],
		//��չ�Ҽ��˵�
		menusEx : [],

		//��������
		comboEx : [{
			text: "�Ƿ�����",
			key: 'isEnable',
			data : [{
				text : '��',
				value : '��'
			},{
				text : '��',
				value : '��'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p ,g) {
				showModalWin("?model=manufacture_basic_process&action=toAdd" ,'1');
			}
		},
		toEditConfig: {
			toEditFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_process&action=toEdit&id=" + get[p.keyField] ,'1');
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_process&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			display: "ģ������",
			name: 'templateName'
		},{
			display: "¼����",
			name: 'createName'
		},{
			display: "¼��ʱ��",
			name: 'createTime'
		},{
			display: "��ע",
			name: 'remark'
		}]
	});
});