var show_page = function(page) {
	$("#regionGrid").yxgrid("reload");
};
$(function() {
	$("#regionGrid").yxgrid({
		model : 'system_region_region',
		title : '�����������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display: '�������',
			name: 'module',
			width: 100,
			sortable: true,
			datacode: 'HTBK'
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'areaCode',
			display : '�������',
			sortable : true,
			hide : true
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'areaSalesman',
			display : '����������Ա',
			sortable : true,
			width : 180
		}, {
			name : 'areaPrincipalId',
			display : '��������Id',
			sortable : true,
			hide : true
		}, {
			name : 'province',
			display : 'ʡ��',
			sortable : true,
			width : 150
		}, {
			name : 'provinceManager',
			display : 'ʡ����',
			sortable : true,
			width : 90
		}, {
			name : 'departmentLeader',
			display : '�����쵼',
			sortable : true,
			width : 90
		}, {
			name : 'departmentDirector',
			display : '�����ܼ�',
			sortable : true,
			width : 90
		}, {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true,
			width : 100
		}, {
			name : 'businessBelong',
			display : '������˾����',
			sortable : true,
			hide : true
		}, {
			name : 'formBelongName',
			display : '���ݹ���',
			sortable : true,
			hide : true
		}, {
			name : 'formBelong',
			display : '���ݹ�������',
			sortable : true,
			hide : true
		}, {
			name : 'isStart',
			display : '�Ƿ���',
			sortable : true,
			width : 80,
			process : function(v) {
				if (v == '0') {
					return "����";
				} else {
					return "�ر�";
				}
			}
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}, {
			name : 'expand',
			display : '��չ����',
			sortable : true,
			width : 50,
			process : function(v) {
				if (v == '0') {
					return "��";
				} else {
					return "��";
				}
			}
		}],

		searchitems : [{
			display : '��������',
			name : 'areaName'
		},{
			display : '��������',
			name : 'areaPrincipal'
		},{
			display : 'ʡ��',
			name : 'province'
		}]
	});
});