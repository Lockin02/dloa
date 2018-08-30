var show_page = function(page) {
	$("#classifyGrid").yxgrid("reload");
};

$(function() {
	$("#classifyGrid").yxgrid({
		model: 'manufacture_basic_classify',
		title: '基础信息-分类管理',
		bodyAlign : 'center',
		isOpButton : false,
		//列信息
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'classifyName',
			display : '分类名称',
			sortable : true,
			width : 200,
			align : 'left'
		},{
			name : 'parentName',
			display : '父级',
			sortable : true,
			width : 200
		},{
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'createName',
			display : '录入人',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		},{
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		},{
			name : 'remark',
			display : '备注',
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
			display: "分类名称",
			name: 'classifyName'
		},{
			display: "备注",
			name: 'remark'
		}]
	});
});