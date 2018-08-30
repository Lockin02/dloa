var show_page = function(page) {
	$("#equipmentGrid").yxgrid("reload");
};
$(function() {
	$("#equipmentGrid").yxgrid({
		model : 'stock_extra_equipment',
		title : '常用设备基本信息',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'equipName',
			display : '设备名称',
			sortable : true,
			width : 200
		}, {
			name : 'isProduce',
			display : '是否停产',
			sortable : true,
			process : function(v, row) {
				if (v == "0") {
					return "是";
				} else {
					return "否";
				}
			}
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		} ],
		// // 主从表格设置
		// subGridOptions : {
		// url : '?model=stock_extra_equipmentpro&action=pageItemJson',
		// param : [ {
		// paramId : 'mainId',
		// colId : 'id'
		// } ],
		// colModel : [ {
		// name : 'XXX',
		// display : '从表字段'
		// } ]
		// },

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "设备名称",
			name : 'equipName'
		} ]
	});
});