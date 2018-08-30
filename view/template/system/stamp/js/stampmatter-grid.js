var show_page = function(page) {
	$("#stampmatterGrid").yxgrid("reload");
};
$(function() {
	$("#stampmatterGrid").yxgrid({
		model: 'system_stamp_stampmatter',
		title: '盖章使用事项配置',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'matterName',
			display: '事项名称',
			sortable: true,
			width : 200,
			align : 'center'
		},
			{
				name : 'stamp_cId',
				display : '盖章类别Id',
				sortable : true,
				hide : true
			},{
				name : 'stamp_cName',
				display : '盖章类别',
				sortable : true,
			},
		{
			name: 'directions',
			display: '特别说明',
			sortable: true,
			width : 150,
			align : 'center'
		},
		{
			name: 'needAudit',
			display: '是否需要审批',
			sortable: true,
			process : function(v){
				if(v == 1){
					return '是';
				}
				else{
					return '否';
				}
			},
			width : 100,
			align : 'center'
		},
		{
			name: 'status',
			display: '状态',
			sortable: true,
			process : function(v){
				if(v == 1){
					return '开启';
				}
				else{
					return '关闭';
				}
			},
			width : 100,
			align : 'center'
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		searchitems: [{
			display: "搜索字段",
			name: 'XXX'
		}]
	});
});