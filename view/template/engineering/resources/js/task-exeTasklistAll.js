var show_page = function(page) {
	$("#exetaskGrid").yxgrid("reload");
};
$(function() {
	$("#exetaskGrid").yxgrid({
		model : 'engineering_resources_task',
		action : 'exetaskJson',
//		param : {"areaPrincipalId" : $("#userId").val()},
		title : '详细任务单',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=engineering_resources_task&action=toTaskView&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		comboEx : [{
			text : '任务类型',
			key : 'taskType',
			data : [{
				text : '在库类',
				value : 'ZK'
			}, {
				text : '待申购/生产类',
				value : 'DSG'
			}, {
				text : '无法调配类',
				value : 'WFDP'
			}]
		},{
			text : '是否接收',
			key : 'isRe',
			data : [{
				text : '已接收',
				value : '1'
			}, {
				text : '未接受',
				value : '0'
			}]
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'projectId',
			display : '项目id',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 120
		}, {
			name : 'resourceTypeName',
			display : '设备类型',
			sortable : true
		}, {
			name : 'resourceName',
			display : '设备名称',
			sortable : true
		}, {
			name : 'number',
			display : '数量',
			sortable : true
		}, {
			name : 'planBeginDate',
			display : '预计借用日期',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '预计归还日期',
			sortable : true
		}, {
			name : 'useDays',
			display : '试用天数',
			sortable : true
		}, {
			name : 'price',
			display : '但设备折旧',
			sortable : true
		}, {
			name : 'amount',
			display : '使用成本',
			sortable : true
		}, {
			name : 'makeProgress',
			display : '进展',
			sortable : true,
			hide : true
		}, {
			name : 'area',
			display : '库存地',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '负责人',
			sortable : true
		}, {
			name : 'isRe',
			display : '是否接收',
			sortable : true,
			process : function(v){
				switch(v){
					case '0' : return '×';
					case '1' : return '√';
					default : return v;
				}
			}
		}],


		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "设备名称",
			name : 'resourceName'
		}]
	});
});