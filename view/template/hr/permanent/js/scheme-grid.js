var show_page = function (page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function () {
	$("#schemeGrid").yxgrid({
		model : 'hr_permanent_scheme',
		title : '转正试用考核方案',
		showcheckbox : true,
		isOpButton:false,
		bodyAlign:'center',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '单据编号',
				sortable : true,
				width:'120',
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_scheme&action=toView&id="+row.id+"\")'>"+v+"</a>"
				}
			}, {
				name : 'schemeName',
				display : '方案名称',
				width:'200',
				sortable : true
			}, {
				name : 'schemeCode',
				display : '方案编码',
				width:'100',
				sortable : true
			}, {
				name : 'schemeTypeName',
				display : '考核对象',
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
				display : "方案名称",
				name : 'schemeName'
			},
			{
				display : "考核对象",
				name : 'schemeTypeName'
			},
			{
				display : "方案编码",
				name : 'schemeCodeSearch'
			}
		]
	});
});