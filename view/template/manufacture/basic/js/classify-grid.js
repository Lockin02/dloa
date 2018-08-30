var show_page = function(page) {
	$("#classifyGrid").yxgrid("reload");
};

$(function() {
	$("#classifyGrid").yxgrid({
		model: 'manufacture_basic_classify',
		title: '������Ϣ-�������',
		bodyAlign : 'center',
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'classifyName',
			display : '��������',
			sortable : true,
			width : 200,
			align : 'left'
		},{
			name : 'parentName',
			display : '����',
			sortable : true,
			width : 200
		},{
			name : 'createTime',
			display : '¼��ʱ��',
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'createName',
			display : '¼����',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		},{
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 450,
			align : 'left'
		}],

		toAddConfig: {
			toAddFn : function(p ,g) {
				showModalWin("?model=manufacture_basic_classify&action=toAdd" ,'1');
			}
		},
		toEditConfig: {
			toEditFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_classify&action=toEdit&id=" + get[p.keyField] ,'1');
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=manufacture_basic_classify&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			display: "��������",
			name: 'classifyName'
		},{
			display: "��ע",
			name: 'remark'
		}]
	});
});