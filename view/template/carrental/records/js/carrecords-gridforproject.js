var show_page = function(page) {
	$("#carrecordsGrid").yxsubgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxsubgrid({
		model : 'carrental_records_carrecords',
		title : '用车记录',
		param : {'projectId' : $("#projectId").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 160
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 200
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
				name : 'createId',
				display : '创建人Id',
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
				width : 130
			}
		],
		subGridOptions : {
			url : '?model=carrental_records_carrecordsdetail&action=pageJson',
			param : [{
					paramId : 'recordsId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'useDate',
					display : '使用日期',
					width : 80
				}, {
					name : 'beginNum',
					display : '起始公里数',
					width : 80
				}, {
					name : 'endNum',
					display : '结束公里数',
					width : 80
				}, {
					name : 'mileage',
					display : '里程数',
					width : 80
				}, {
					name : 'useHours',
					display : '使用时长',
					width : 80
				}, {
					name : 'useReson',
					display : '用途'
				}, {
					name : 'travelFee',
					display : '乘车费',
					width : 80
				}, {
					name : 'fuelFee',
					display : '油费',
					width : 80
				}, {
					name : 'roadFee',
					display : '路桥费',
					width : 80
				}, {
					name : 'effectiveLog',
					display : '有效LOG'
				}
			]
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toView&id=" + row.id , 1);
				}
			}
		}],
		searchitems : {
			display : "搜索字段",
			name : 'XXX'
		}
	});
});