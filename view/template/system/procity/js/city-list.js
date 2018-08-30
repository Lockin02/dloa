var show_page = function(page) {
	$("#citylist").yxgrid("reload");
}


$(function() {

   	$("#tree").yxtree({
	url : '?model=system_procity_province&action=getChildren',
	event : {
		"node_click" : function(event, treeId, treeNode) {
			var citylist = $("#citylist").data('yxgrid');
			citylist.options.param['provinceId']=treeNode.id;
			citylist.reload();
		}
	}
	});
	$("#citylist").yxgrid({
		model : 'system_procity_city',

		isToolBar : true,
		isViewAction:false,
		isDelAction : true,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '城市名称',
			name : 'cityName',
			sortable : true,
			width : 200
		},{
			display : '城市编号',
			name : 'cityCode',
			width : 200,
			sortable : true
		},{
			display : '省份名称	',
			name : 'provinceName',
			sortable : true,
			width : 200
		}, {
			display : '省份编号	',
			name : 'provinceCode',
			sortable : true,
			width : 200
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '城市名称',
			name : 'cityName'
		}],
		sortorder : "ASC",
		title : '城市名称'
	});
});