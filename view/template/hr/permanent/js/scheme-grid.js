var show_page = function (page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function () {
	$("#schemeGrid").yxgrid({
		model : 'hr_permanent_scheme',
		title : 'ת�����ÿ��˷���',
		showcheckbox : true,
		isOpButton:false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '���ݱ��',
				sortable : true,
				width:'120',
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_scheme&action=toView&id="+row.id+"\")'>"+v+"</a>"
				}
			}, {
				name : 'schemeName',
				display : '��������',
				width:'200',
				sortable : true
			}, {
				name : 'schemeCode',
				display : '��������',
				width:'100',
				sortable : true
			}, {
				name : 'schemeTypeName',
				display : '���˶���',
				width:'100',
				sortable : true
			}
		],

		toEditConfig : {
			toEditFn : function(p,g){
				action : showOpenWin("?model=hr_permanent_scheme&action=toEdit&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toViewConfig : {
			toViewFn : function(p,g){
				action : showOpenWin("?model=hr_permanent_scheme&action=toView&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toAddConfig : {
			toAddFn : function(p,g){
				showOpenWin("?model=hr_permanent_scheme&action=toAdd");
			}
		},
		searchitems : [{
				display : "��������",
				name : 'schemeName'
			},
			{
				display : "���˶���",
				name : 'schemeTypeName'
			},
			{
				display : "��������",
				name : 'schemeCodeSearch'
			}
		]
	});
});