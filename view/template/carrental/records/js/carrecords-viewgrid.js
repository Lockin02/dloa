var show_page = function(page) {
	$("#carrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxgrid({
		model : 'carrental_records_carrecords',
		param : {
			"carId" : $("#carId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		title : '用车记录',
		// 列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 180
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 120
			}, {
				name : 'carNo',
				display : '车牌号',
				sortable : true,
				width : 80
			}, {
				name : 'carType',
				display : '车辆型号',
				sortable : true,
				width : 80
			}, {
				name : 'driver',
				display : '司机',
				sortable : true
			}, {
				name : 'linkPhone',
				display : '联系电话',
				sortable : true,
				width : 100
			}, {
				name : 'useDate',
				display : '用车日期',
				sortable : true
			}, {
				name : 'beginDate',
				display : '开始日期',
				sortable : true
			}, {
				name : 'endDate',
				display : '结束日期',
				sortable : true
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人名称',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems :[
			{
				display : "车牌号",
				name : 'carNoSearch'
			},{
				display : "项目编号",
				name : 'projectCodeSearch'
			}
		]
	});
});