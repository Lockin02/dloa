$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJson',
		title : '项目分布  ' + $('#provinceName').val() ,
//		param : {'provinceId' : $('#provinceId').val()},
		param : {'provinceName' : $('#provinceName').val(),'provinceId' : $('#provinceId').val()},
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "初始化",
			icon : 'edit',
			/**
			 * row 最后一条选中的行 rows 选中的行（多选） rowIds
			 * 选中的行id数组 grid 当前表格实例
			 */
			action : function(row) {
				location='?model=engineering_project_esmproject&action=rightList'
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});

});