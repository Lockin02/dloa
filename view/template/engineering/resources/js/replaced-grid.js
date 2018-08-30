var show_page = function(page) {
	$("#replacedGrid").yxsubgrid("reload");
};
$(function() {
	$("#replacedGrid").yxsubgrid({
		model : 'engineering_resources_replaced',
		title : '设备管理-可替换设备管理',
//		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'deviceName',
			display : '设备名称',
			sortable : true,
			width : 180
		}, {
			name : 'deviceId',
			display : '设备ID',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 180
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=engineering_resources_replacedinfo&action=pageJson',
			param : [{
				paramId : 'replacedId',
				colId : 'id'
			}],
			colModel : [{
				name : 'deviceName',
				display : '设备名称',
				width : 100
			},{
				name : 'remark',
				display : '备注',
				width : 200
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "设备名称",
			name : 'deviceName'
		}]
	});
});