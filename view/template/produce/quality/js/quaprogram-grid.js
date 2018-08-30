var show_page = function(page) {
	$("#quaprogramGrid").yxgrid("reload");
};
$(function() {
	$("#quaprogramGrid").yxgrid({
		model : 'produce_quality_quaprogram',
		title : '质检方案',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'programName',
				display : '方案名称',
				sortable : true,
				width : 150,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_quaprogram&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'standardName',
				display : '质量标准',
				sortable : true,
				width : 150
			}, {
				name : 'isClose',
				display : '是否关闭',
				sortable : true,
				process : function(v){
					if(v == "1"){
						return '是';
					}else{
						return '否';
					}
				},
				width : 80
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				width : 200
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建日期',
				sortable : true,
				width : 130
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改日期',
				sortable : true,
				width : 130,
				hide : true
			}], // 主从表格设置

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "模板名称",
			name : 'programNameSearch'
		}
	});
});