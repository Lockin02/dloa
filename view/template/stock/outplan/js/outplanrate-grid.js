var show_page = function(page) {
	$("#outplanrateGrid").yxgrid("reload");
};
$(function() {
	$("#outplanrateGrid").yxgrid({
		model : 'stock_outplan_outplanrate',
		title : '发货计划进度备注',
		param : { planId : $('#planId').val() },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		height : 400,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'planId',
			display : '发货计划Id',
			sortable : true,
			hide : true
		}, {
			name : 'keyword',
			display : '关键字',
			width : 150,
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			width : 80,
			sortable : true
		}, {
			name : 'createId',
			display : '创建人Id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			width : 120,
			sortable : true
		}, {
			name : 'remark',
			display : '进度描述',
			width : 400,
			sortable : true
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplanrate&action=toAdd&planId='
				+ $('#planId').val()
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}],
		menusEx : [{
			name : 'view',
			// hide : true,
			text : "查看",
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplanrate&action=init&id='
				+ row.id
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}]
	});
});