var show_page = function(page) {
	$("#pickingGrid").yxgrid("reload");
};

$(function() {
	$("#pickingGrid").yxgrid({
		model : 'produce_plan_picking',
		param : {
			planId : $("#planId").val()
		},
		title : '�����������뵥',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'relDocCode',
			display: 'Դ�����',
			sortable: true
		},{
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "δ�ύ";
					break;
				case '1':
					return "������";
					break;
				case '2':
					return "�������";
					break;
				case '3':
					return "���";
					break;
				case '4':
					return "�������";
					break;
				case '5':
					return '�������';
					break;
				default:
					return "--";
				}
			}
		},{
			name: 'docDate',
			display: '��������',
			sortable: true
		},{
			name: 'relDocName',
			display: 'Դ������',
			sortable: true,
			width : 200
		},{
			name: 'relDocType',
			display: 'Դ������',
			sortable: true
		},{
			name: 'createName',
			display: '������',
			sortable: true
		},{
			name: 'remark',
			display: '��ע',
			sortable: true,
			width : 250
		}],

		//�����Ҽ��˵�
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
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: 'δ�ύ',
				value: '0'
			}, {
				text: '������',
				value: '1'
			}, {
				text: '�������',
				value: '2'
			}, {
				text: '���',
				value: '3'
			}, {
				text: '�������',
				value: '4'
			}, {
				text: '�������',
				value: '5'
			}]
		}],

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		},{
			name: 'docDate',
			display: '��������'
		},{
			name: 'relDocCode',
			display: 'Դ�����'
		},{
			name: 'relDocName',
			display: 'Դ������'
		},{
			name: 'relDocType',
			display: 'Դ������'
		},{
			name: 'createName',
			display: '������'
		}]
	});
});