var show_page = function(page) {
	$("#myreturnGrid").yxgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '�黹���뵥',
		param : {'borrowId' : $("#borrowId").val()},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'state',
			display : '�⳥״̬',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_grey.png" title="����"/>';break;
					case '1' : return '<img src="images/icon/cicle_yellow.png" title="�������⳥��"/>';break;
					case '2' : return '<img src="images/icon/cicle_green.png" title="�������⳥��"/>';break;
				}
			},
			width : 50
		}, {
			name : 'borrowId',
			display : '���õ�ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '���ݱ��',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '���õ����',
			sortable : true,
			width : 100
		}, {
			name : 'borrowLimit',
			display : '��������',
			width : 60,
			sortable : true
		}, {
			name : 'applyTypeName',
			display : '��������',
			width : 60,
			sortable : true
		}, {
			name : 'disposeState',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				switch(v){
					case '0' : return '������';break;
					case '1' : return '���ڴ���';break;
					case '2' : return '�Ѵ���';break;
					case '3' : return '����';break;
					case '8' : return '���';break;
					default : return '--';
				}
			},
			width : 70
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'deptName',
			display : '�����˲���',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 140
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '�ӱ��ֶ�'
			}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
							+ row.id + "&skey=" + row['skey_'],1);
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
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});