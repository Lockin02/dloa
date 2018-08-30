var show_page = function(page) {
	$("#carrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxgrid({
		model : 'carrental_records_carrecords',
		title : '用车记录',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		customeCode : 'carrecordsGrid',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 200
			}, {
				name : 'projectCode',
				display : '项目编号',
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
			}
		],
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=carrental_records_carrecords&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750");
			}
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toView&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toEdit&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (confirm('确定要删除吗？')) {
					$.ajax({
						type : "POST",
						url : "?model=carrental_records_carrecords&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功!');
								show_page();
							} else {
								alert('删除失败!');
							}
						}
					});
				}
			}
		}],
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