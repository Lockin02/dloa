var show_page = function(page) {
	$("#deprMethodGrid").yxgrid("reload");
};
$(function() {
	$("#deprMethodGrid").yxgrid({
		model : 'asset_basic_deprMethod',
		title : '折旧方式',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'code',
			display : '编码',
			width : 110,
			sortable : true
		}, {
			name : 'name',
			display : '折旧名称',
			width : 110,
			sortable : true
		}, {
			name : 'describes',
			display : '公式文字描述',
			sortable : true,
			width : 500
//		}, {
//			name : 'expression',
//			display : '公式字段描述',
//			sortable : true,
//			width : 300
//		}, {
//			name : 'remark',
//			display : '说明',
//			sortable : true,
//			width : 200
		}],
//		//		 扩展按钮
//		buttonsEx : [{
//			name : 'import',
//			text : '导入',
//			icon : 'excel',
//			action : function(row, rows, grid) {
//				showThickboxWin("?model=asset_basic_deprMethod&action=toImport"
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
//			}
//		}],
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 700,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 300
		}
	});
});