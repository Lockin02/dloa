var show_page = function(page) {
	$("#closeinfoGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = []
	$("#closeinfoGrid").yxgrid({
		model : 'projectmanagent_chance_close',
		title : '销售商机',
		param : {'chanceId' : $("#chanceId").val()},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'handleType',
			display : '操作类型',
			sortable : true
		}, {
			name : 'createTime',
			display : '操作时间',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '操作人',
			sortable : true
		}, {
			name : 'closeInfo',
			display : '备注信息',
			sortable : true,
			width : 200
		}],
		buttonsEx : buttonsArr,

		// 快速搜索
		searchitems : [],
		// 默认搜索顺序
		sortorder : "DSC",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});
