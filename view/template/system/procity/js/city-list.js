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
			display : '��������',
			name : 'cityName',
			sortable : true,
			width : 200
		},{
			display : '���б��',
			name : 'cityCode',
			width : 200,
			sortable : true
		},{
			display : 'ʡ������	',
			name : 'provinceName',
			sortable : true,
			width : 200
		}, {
			display : 'ʡ�ݱ��	',
			name : 'provinceCode',
			sortable : true,
			width : 200
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��������',
			name : 'cityName'
		}],
		sortorder : "ASC",
		title : '��������'
	});
});