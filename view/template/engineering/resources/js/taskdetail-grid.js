var show_page = function(page) {
	$("#taskdetailGrid").yxgrid("reload");
};
$(function() {
	$("#taskdetailGrid").yxgrid({
		model : 'engineering_resources_taskdetail',
		title : '项目设备任务单',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '任务类型',
			sortable : true
		}, {
			name : 'resourceCode',
			display : '资源编码',
			sortable : true
		}, {
			name : 'resourceName',
			display : '资源名称',
			sortable : true
		}, {
			name : 'resourceTypeCode',
			display : '资源类型',
			sortable : true
		}, {
			name : 'resourceTypeName',
			display : '资源类型名称',
			sortable : true
		}, {
			name : 'number',
			display : '数量',
			sortable : true
		}, {
			name : 'unit',
			display : '单位',
			sortable : true
		}, {
			name : 'price',
			display : '单价',
			sortable : true
		}, {
			name : 'amount',
			display : '成本金额',
			sortable : true
		}, {
			name : 'planBeginDate',
			display : '预计借出日期',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '预计归还日期',
			sortable : true
		}, {
			name : 'beignTime',
			display : '开始使用时间',
			sortable : true
		}, {
			name : 'endTime',
			display : '结束使用时间',
			sortable : true
		}, {
			name : 'useDays',
			display : '使用天数',
			sortable : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true
		}, {
			name : 'activityCode',
			display : '活动编号',
			sortable : true
		}, {
			name : 'activityName',
			display : '活动名称',
			sortable : true
		}, {
			name : 'workContent',
			display : '工作内容',
			sortable : true
		}, {
			name : 'remark',
			display : '备注说明',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人Id',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true
		}, {
			name : 'updateId',
			display : '修改人Id',
			sortable : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			sortable : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true
		}, {
			name : 'area',
			display : '库存地',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '库存地负责人',
			sortable : true
		}, {
			name : 'areaPrincipalId',
			display : '是否接收任务',
			sortable : true
		}, {
			name : 'isMeet',
			display : '满足情况',
			sortable : true
		}, {
			name : 'makeProgress',
			display : '进展',
			sortable : true
		}, {
			name : 'forDays',
			display : '预计筹备天数',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=engineering_resources_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '从表字段'
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});